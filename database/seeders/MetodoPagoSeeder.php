<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            [
                'clave'  => 'yape',
                'nombre' => 'Yape',
                'emoji'  => '📱',
                'activo' => true,
                'orden'  => 1,
                'datos'  => [
                    'numero'  => '999888777',
                    'titular' => 'Tienda Online S.A.C.',
                    'qr'      => null,
                ],
            ],
            [
                'clave'  => 'plin',
                'nombre' => 'Plin',
                'emoji'  => '💜',
                'activo' => true,
                'orden'  => 2,
                'datos'  => [
                    'numero'  => '999888777',
                    'titular' => 'Tienda Online S.A.C.',
                    'qr'      => null,
                ],
            ],
            [
                'clave'  => 'bcp',
                'nombre' => 'BCP',
                'emoji'  => '🏦',
                'activo' => true,
                'orden'  => 3,
                'datos'  => [
                    'numero'  => '191-12345678-0-01',
                    'cci'     => '002-191-001234567801-10',
                    'titular' => 'Tienda Online S.A.C.',
                    'alias'   => 'tiendaonline',
                ],
            ],
            [
                'clave'  => 'interbank',
                'nombre' => 'Interbank',
                'emoji'  => '🏦',
                'activo' => false,
                'orden'  => 4,
                'datos'  => [
                    'numero'  => '200-3012345678',
                    'cci'     => '003-200-003012345678-34',
                    'titular' => 'Tienda Online S.A.C.',
                ],
            ],
            [
                'clave'  => 'culqi',
                'nombre' => 'Tarjeta (Culqi)',
                'emoji'  => '💳',
                'activo' => true,
                'orden'  => 5,
                'datos'  => [],
            ],
        ];

        foreach ($metodos as $metodo) {
            MetodoPago::updateOrCreate(
                ['clave' => $metodo['clave']],
                $metodo
            );
        }
    }
}