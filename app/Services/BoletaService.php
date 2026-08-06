<?php

namespace App\Services;

use App\Models\Boleta;
use App\Models\BoletaDetalle;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoletaService
{
    public function generar(Pedido $pedido, Pago $pago): ?Boleta
    {
        if ($pago->estado_pago !== 'aprobado') {
            Log::warning('BoletaService: pago no aprobado, no se genera boleta', [
                'pedido_id'    => $pedido->id,
                'pago_id'      => $pago->id,
                'estado_pago'  => $pago->estado_pago,
            ]);
            return null;
        }

        try {
            $montoPagado = (float) ($pago->monto ?? $pedido->total);
            $totalPedido = (float) $pedido->total;

            $boleta = DB::transaction(function () use ($pedido, $pago, $montoPagado) {
                // Bloquear el pedido para serializar la generación y evitar duplicados
                $pedidoBloqueado = Pedido::whereKey($pedido->id)->lockForUpdate()->first();

                if (! $pedidoBloqueado) {
                    throw new \RuntimeException('Pedido no encontrado al generar la boleta.');
                }

                // Anti-duplicado: dentro de la transacción y con el pedido bloqueado
                $existing = $pedidoBloqueado->boletas()->lockForUpdate()->first();
                if ($existing) {
                    Log::info('BoletaService: boleta ya existe, retornando existente', [
                        'pedido_id' => $pedido->id,
                        'boleta_id' => $existing->id,
                        'numero'    => $existing->numero_boleta,
                    ]);
                    return $existing;
                }

                $serie = config('boleta.serie', 'B001');
                $correlativo = $this->siguienteCorrelativo($serie);
                $numeroBoleta = sprintf('%s-%06d', $serie, $correlativo);

                $igvPorcentaje = (float) config('boleta.igv_porcentaje', 0.18);
                $subtotal = round($montoPagado / (1 + $igvPorcentaje), 2);
                $igv = round($montoPagado - $subtotal, 2);

                $boleta = Boleta::create([
                    'pedido_id'      => $pedido->id,
                    'user_id'        => $pedido->user_id,
                    'numero_boleta'  => $numeroBoleta,
                    'serie'          => $serie,
                    'correlativo'    => $correlativo,
                    'fecha_emision'  => now(),
                    'subtotal'       => $subtotal,
                    'igv'            => $igv,
                    'total'          => $montoPagado,
                    'estado'         => 'emitida',
                    'ruc'            => config('boleta.empresa_ruc'),
                    'cliente_nombre' => $pedido->cliente_nombre ?? $pedido->user?->name,
                    'cliente_email'  => $pedido->cliente_email ?? $pedido->user?->email,
                ]);

                BoletaDetalle::create([
                    'boleta_id'       => $boleta->id,
                    'producto_id'     => $pedido->producto_id,
                    'descripcion'     => $pedido->producto?->nombre ?? 'Producto',
                    'cantidad'        => 1,
                    'precio_unitario' => $montoPagado,
                    'subtotal'        => $montoPagado,
                ]);

                return $boleta;
            });

            Log::info('Boleta generada', [
                'boleta_id'     => $boleta->id,
                'numero'        => $boleta->numero_boleta,
                'pedido_id'     => $pedido->id,
                'pago_id'       => $pago->id,
                'total_pedido'  => $totalPedido,
                'monto_pagado'  => $boleta->total,
            ]);

            return $boleta;
        } catch (\Throwable $e) {
            Log::error('Error al generar boleta', [
                'pedido_id' => $pedido->id,
                'pago_id'   => $pago->id,
                'error'     => $e->getMessage(),
                'class'     => get_class($e),
            ]);
            return null;
        }
    }

    public function anular(Boleta $boleta, string $motivo = ''): void
    {
        $boleta->update([
            'estado'      => 'anulada',
            'motivo_baja' => $motivo,
            'sunat_baja_at' => now(),
        ]);

        Log::info('Boleta anulada', [
            'boleta_id' => $boleta->id,
            'numero'    => $boleta->numero_boleta,
            'motivo'    => $motivo,
        ]);
    }

    private function siguienteCorrelativo(string $serie): int
    {
        $ultimo = Boleta::where('serie', $serie)
            ->whereYear('created_at', now()->year)
            ->lockForUpdate()
            ->max('correlativo');

        return ($ultimo ?? 0) + 1;
    }
}
