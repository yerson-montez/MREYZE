@extends('layouts.app')

@section('content')
<style>
  .boleta-wrapper{max-width:800px;margin:40px auto;padding:0 16px}
  .boleta-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
  .boleta-header{background:#1e293b;color:#fff;padding:28px 32px}
  .boleta-header h1{font-size:1.05rem;font-weight:700;margin:0 0 4px}
  .boleta-header p{margin:0;font-size:.8rem;opacity:.8}
  .boleta-body{padding:28px 32px}
  .boleta-meta{display:flex;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f1f5f9}
  .boleta-meta-right{text-align:right}
  .boleta-meta-right .numero{font-size:1.1rem;font-weight:700;color:#1e293b}
  .boleta-meta-right .fecha{font-size:.8rem;color:#64748b;margin-top:4px}
  .boleta-meta-right .estado{display:inline-block;margin-top:6px;padding:2px 10px;border-radius:20px;font-size:.7rem;font-weight:600}
  .estado-emitida{background:#d1fae5;color:#065f46}
  .estado-anulada{background:#fee2e2;color:#991b1b}
  .section-title{font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:6px}
  .cliente-info{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-bottom:20px}
  .cliente-info p{margin:0;font-size:.85rem;color:#1e293b;line-height:1.6}
  .cliente-info .name{font-weight:600}
  .cliente-info .doc{font-size:.76rem;color:#64748b}
  table.detalles{width:100%;border-collapse:collapse;margin-bottom:20px}
  table.detalles th{padding:8px 10px;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#64748b;text-align:left;border-bottom:2px solid #e2e8f0}
  table.detalles td{padding:10px;font-size:.85rem;color:#1e293b;border-bottom:1px solid #f1f5f9}
  table.detalles td:last-child,table.detalles th:last-child{text-align:right}
  table.detalles td:nth-child(2),table.detalles th:nth-child(2){text-align:center}
    .totales{margin-left:auto;width:260px}
    .total-row{display:flex;justify-content:space-between;padding:5px 0;font-size:.85rem;color:#475569}
    .total-row.total{border-top:2px solid #e2e8f0;margin-top:4px;padding-top:8px;font-size:1rem;font-weight:700;color:#1e293b}
    .total-row.saldo{color:#dc2626;font-weight:600}
    .total-row.cancel{color:#059669;font-weight:600}
    .boleta-footer{text-align:center;padding:16px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:.76rem;color:#94a3b8}
    .boleta-logo{display:flex;align-items:center;gap:12px}
    .boleta-logo img{width:42px;height:42px;object-fit:contain;border-radius:6px}
  .acciones{margin-top:20px;display:flex;gap:12px}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:8px;font-size:.85rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
  .btn-pdf{background:#dc2626;color:#fff}
  .btn-pdf:hover{background:#b91c1c}
  .btn-back{background:#e5e7eb;color:#374151}
  .btn-back:hover{background:#d1d5db}
</style>

<div class="boleta-wrapper">
  <div class="boleta-card">
    <div class="boleta-header">
      <div class="boleta-logo">
        <img src="{{ asset('img/LOGOMREYZE1.png') }}" alt="MREYZE Logo">
        <div>
          <h1>MREYZE Automatización y Sistemas SAC</h1>
          <p>RUC: {{ config('boleta.empresa_ruc') }} &mdash; {{ config('boleta.empresa_direccion') }}</p>
        </div>
      </div>
    </div>

    <div class="boleta-body">
      <div class="boleta-meta">
        <div class="boleta-meta-left">
          <div class="section-title">Cliente</div>
          <div class="cliente-info">
            <p class="name">{{ $boleta->cliente_nombre }}</p>
            @if ($boleta->cliente_documento)
              <p class="doc">{{ $boleta->cliente_tipo_documento ?? 'DNI' }}: {{ $boleta->cliente_documento }}</p>
            @endif
            @if ($boleta->cliente_email)
              <p class="doc">{{ $boleta->cliente_email }}</p>
            @endif
          </div>
        </div>
        <div class="boleta-meta-right">
          <div class="numero">{{ $boleta->numero_boleta }}</div>
          <div class="fecha">{{ $boleta->fecha_emision->format('d/m/Y H:i') }}</div>
          <span class="estado estado-{{ $boleta->estado }}">{{ ucfirst($boleta->estado) }}</span>
        </div>
      </div>

      <div class="section-title">Detalle</div>
      <table class="detalles">
        <thead>
          <tr>
            <th>Producto</th>
            <th style="text-align:center">Cant.</th>
            <th style="text-align:right">Precio</th>
            <th style="text-align:right">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($boleta->detalles as $detalle)
            <tr>
              <td>{{ $detalle->descripcion }}</td>
              <td style="text-align:center">{{ $detalle->cantidad }}</td>
              <td style="text-align:right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
              <td style="text-align:right">S/ {{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      @php
        $totalPedido    = (float) $boleta->pedido->total;
        $montoPagado    = (float) $boleta->total;
        $totalPagado    = (float) $boleta->pedido->total_pagado;
        $saldoPendiente = round($totalPedido - $totalPagado, 2);
      @endphp

      <div class="totales">
        <div class="total-row">
          <span>Subtotal</span>
          <span>S/ {{ number_format($boleta->subtotal, 2) }}</span>
        </div>
        <div class="total-row">
          <span>IGV (18%)</span>
          <span>S/ {{ number_format($boleta->igv, 2) }}</span>
        </div>
        <div class="total-row total">
          <span>Total comprobante</span>
          <span>S/ {{ number_format($montoPagado, 2) }}</span>
        </div>
        <div style="border-top:1px dashed #d1d5db;margin-top:12px;padding-top:12px">
          <div class="total-row">
            <span>Total del pedido</span>
            <span>S/ {{ number_format($totalPedido, 2) }}</span>
          </div>
          <div class="total-row {{ $saldoPendiente > 0 ? 'saldo' : 'cancel' }}">
            @if ($saldoPendiente > 0)
              <span>Saldo pendiente</span>
              <span>S/ {{ number_format($saldoPendiente, 2) }}</span>
            @else
              <span>Total pagado</span>
              <span>S/ {{ number_format($totalPagado, 2) }}</span>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="boleta-footer">
      Esta boleta es un comprobante interno de pago — No tiene validez tributaria ante SUNAT
    </div>
  </div>

  <div class="acciones">
    <a href="{{ route('pedidos.boleta.pdf', $pedido) }}" class="btn btn-pdf" target="_blank">
      <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
      Descargar PDF
    </a>
    @if ((float) $pedido->total_pagado >= (float) $pedido->total)
      <a href="{{ route('pedidos.mis') }}" class="btn btn-back">← Volver a mis pedidos</a>
    @else
      <a href="{{ route('pedidos.pago', $pedido) }}" class="btn btn-back">← Volver al pago</a>
    @endif
  </div>
</div>
@endsection