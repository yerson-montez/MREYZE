<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
  @page{margin:30px 40px}
  body{font-family:DejaVu Sans, sans-serif;font-size:11px;color:#1e293b;margin:0;padding:0}
  .header{text-align:center;padding-bottom:16px;border-bottom:2px solid #1e293b;margin-bottom:20px}
  .header h1{font-size:16px;font-weight:700;margin:0 0 4px;color:#1e293b}
  .header p{margin:0;font-size:10px;color:#64748b}
  .meta{display:flex;justify-content:space-between;margin-bottom:20px}
  .cliente{font-size:11px}
  .cliente .name{font-weight:700}
  .cliente .doc{font-size:10px;color:#64748b}
  .info-derecha{text-align:right}
  .info-derecha .numero{font-size:14px;font-weight:700}
  .info-derecha .fecha{font-size:10px;color:#64748b}
  table{width:100%;border-collapse:collapse;margin-bottom:16px}
  th{background:#f1f5f9;padding:8px 10px;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;text-align:left;border-bottom:2px solid #e2e8f0}
  td{padding:8px 10px;border-bottom:1px solid #f1f5f9}
  td:last-child,th:last-child{text-align:right}
  td:nth-child(2),th:nth-child(2){text-align:center}
  .totales{margin-left:auto;width:240px}
  .total-row{display:flex;justify-content:space-between;padding:4px 0}
  .total-row.total{border-top:2px solid #1e293b;margin-top:4px;padding-top:8px;font-weight:700;font-size:13px}
  .total-row.saldo{color:#dc2626;font-weight:600}
  .total-row.cancel{color:#059669;font-weight:600}
  .footer{text-align:center;padding-top:20px;font-size:9px;color:#94a3b8;border-top:1px solid #e2e8f0;margin-top:30px}
  .badge-estado{display:inline-block;margin-top:6px;padding:2px 10px;border-radius:10px;font-size:9px;font-weight:600}
  .estado-emitida{background:#d1fae5;color:#065f46}
  .logo-header{display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px}
  .logo-header img{width:36px;height:36px;object-fit:contain}
</style>
</head>
<body>

<div class="header">
  <div class="logo-header">
    <img src="{{ public_path('img/LOGOMREYZE1.png') }}" alt="MREYZE Logo">
    <h1>MREYZE Automatización y Sistemas SAC</h1>
  </div>
  <p>RUC: {{ config('boleta.empresa_ruc') }}</p>
  <p>{{ config('boleta.empresa_direccion') }}</p>
</div>

<div class="meta">
  <div class="cliente">
    <p class="name">{{ $boleta->cliente_nombre }}</p>
    @if ($boleta->cliente_documento)
      <p class="doc">{{ $boleta->cliente_tipo_documento ?? 'DNI' }}: {{ $boleta->cliente_documento }}</p>
    @endif
    @if ($boleta->cliente_email)
      <p class="doc">{{ $boleta->cliente_email }}</p>
    @endif
  </div>
  <div class="info-derecha">
    <div class="numero">{{ $boleta->numero_boleta }}</div>
    <div class="fecha">{{ $boleta->fecha_emision->format('d/m/Y H:i') }}</div>
    <span class="badge-estado estado-{{ $boleta->estado }}">{{ ucfirst($boleta->estado) }}</span>
  </div>
</div>

<table>
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
  $totalPedido = (float) $boleta->pedido->total;
  $montoPagado = (float) $boleta->total;
  $saldoPendiente = round($totalPedido - $montoPagado, 2);
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
  <div style="border-top:1px dashed #ccc;margin-top:8px;padding-top:8px">
    <div class="total-row">
      <span>Total del pedido</span>
      <span>S/ {{ number_format($totalPedido, 2) }}</span>
    </div>
    <div class="total-row {{ $saldoPendiente > 0 ? 'saldo' : 'cancel' }}">
      <span>Saldo pendiente</span>
      <span>S/ {{ number_format($saldoPendiente, 2) }}</span>
    </div>
  </div>
</div>

<div class="footer">
  Boleta interna de pago — Sin validez tributaria ante SUNAT<br>
  Generado el {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
