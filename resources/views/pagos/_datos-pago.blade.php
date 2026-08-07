@php
  $pagoInfo = config('empresa.pago');
@endphp
<div class="pago-card">
  <h2>Paga con {{ $pagoInfo['medios'] }}</h2>
  <div class="qr-wrap">
    <img src="{{ asset($pagoInfo['qr']) }}" alt="Código QR {{ $pagoInfo['medios'] }}">
  </div>
  <div class="info-row">
    <span class="info-label">Banco</span>
    <span class="info-value">{{ $pagoInfo['banco'] }}</span>
  </div>
  <div class="info-row" style="justify-content:center;">
    <span class="info-value" style="color:var(--accent);font-weight:700;">{{ $pagoInfo['medios'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">CCI</span>
    <span class="info-value">{{ $pagoInfo['cci'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Número de cuenta</span>
    <span class="info-value">{{ $pagoInfo['cuenta'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Titular</span>
    <span class="info-value">{{ $pagoInfo['titular'] }}</span>
  </div>
</div>
