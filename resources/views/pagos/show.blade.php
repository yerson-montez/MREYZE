@extends('layouts.app')

@section('content')
<style>
:root {
  --ice-900:#071a2e;--ice-800:#0d1f3c;--ice-700:#112952;--ice-600:#1a3a6e;
  --ice-500:#1e4d99;--ice-400:#2563c7;--ice-300:#4a90d9;--ice-200:#7ab8f5;
  --ice-100:#b8d9f9;
  --accent:#00d4ff;--success:#00d4aa;--danger:#ff6b6b;--warning:#f59e0b;
}
.pago-page{max-width:680px;margin:0 auto;padding:48px 1rem 80px;}
.pago-card{
  background:linear-gradient(135deg,rgba(13,31,60,.95),rgba(17,41,82,.8));
  border:1px solid rgba(74,144,217,.25);border-radius:16px;padding:24px;
  margin-bottom:20px;position:relative;overflow:hidden;
}
.pago-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--ice-500),var(--accent));
}
.pago-card h2{
  font-family:'Exo 2',sans-serif;font-size:.85rem;font-weight:600;
  color:var(--ice-200);letter-spacing:.06em;text-transform:uppercase;
  margin-bottom:16px;
}
.info-row{display:flex;justify-content:space-between;padding:8px 0;font-size:.9rem;border-bottom:1px solid rgba(74,144,217,.08);}
.info-row:last-child{border-bottom:none;}
.info-label{color:var(--ice-300);}
.info-value{color:#fff;font-weight:600;}

/* QR centrado: contenedor en columna, todo centrado horizontalmente */
.qr-wrap{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
  padding:20px 0;
}
.qr-wrap img{
  display:block;
  width:200px;
  height:200px;
  border-radius:16px;
  border:2px solid rgba(74,144,217,.2);
  margin:0 auto 12px;
}
.qr-num{font-size:1.5rem;font-weight:700;color:#fff;letter-spacing:.03em;text-align:center;}
.qr-label{font-size:.78rem;color:var(--ice-300);margin-top:4px;text-align:center;font-weight:600;}

.form-group{margin-bottom:16px;}
.form-group label{display:block;font-size:.72rem;font-weight:600;color:var(--ice-200);margin-bottom:6px;letter-spacing:.06em;text-transform:uppercase;}
.form-group input{width:100%;background:rgba(7,26,46,.7);border:1px solid rgba(74,144,217,.2);border-radius:8px;padding:11px 12px;font-size:.875rem;color:#e2ecff;outline:none;transition:border .2s;}
.form-group input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,212,255,.12);}
.form-group input[type=file]{padding:8px;}
.btn-yape{
  width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);
  color:#fff;font-size:1rem;font-weight:700;padding:14px 24px;
  border:none;border-radius:12px;cursor:pointer;transition:all .3s;
  display:flex;align-items:center;justify-content:center;gap:10px;
}
.btn-yape:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(124,58,237,.4);}
.btn-yape:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.success-box{background:rgba(0,212,170,.08);border:1px solid rgba(0,212,170,.3);border-radius:12px;padding:16px;text-align:center;}
.success-box p{color:var(--success);font-size:.9rem;}
.estado-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:.78rem;font-weight:600;}
.estado-badge.pendiente{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning);}
.estado-badge.aprobado{background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success);}
.estado-badge.rechazado{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger);}
.alert{background:rgba(255,107,107,.08);border:1px solid rgba(255,107,107,.3);border-left:4px solid var(--danger);border-radius:12px;padding:14px 18px;margin-bottom:16px;}
.alert p{color:var(--danger);font-size:.82rem;padding:3px 0;}
.alert.success{background:rgba(0,212,170,.08);border-color:rgba(0,212,170,.3);border-left-color:var(--success);}
.alert.success p{color:var(--success);}
</style>

<div class="pago-page">
  <div style="text-align:center;margin-bottom:24px;">
    <h1 style="font-family:'Exo 2',sans-serif;font-size:1.6rem;font-weight:700;color:#fff;margin-bottom:4px;">
      Pago con <span style="background:linear-gradient(135deg,var(--accent),var(--ice-200));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Yape</span>
    </h1>
    <p style="color:var(--ice-300);font-size:.85rem;">Pedido {{ $pedido->numero_pedido }}</p>
  </div>

  @if (session('success'))
    <div class="alert success"><p>{{ session('success') }}</p></div>
  @endif
  @if ($errors->any())
    <div class="alert">@foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach</div>
  @endif

  {{-- Resumen del pedido --}}
  <div class="pago-card">
    <h2>Resumen del pedido</h2>
    <div class="info-row">
      <span class="info-label">Producto</span>
      <span class="info-value">{{ $pedido->producto->nombre }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Total</span>
      <span class="info-value">S/ {{ number_format($pedido->total, 2) }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Adelanto (50%)</span>
      <span class="info-value" style="color:var(--success);">S/ {{ number_format($pedido->adelanto, 2) }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Saldo pendiente</span>
      <span class="info-value" style="color:var(--warning);">S/ {{ number_format($pedido->saldo, 2) }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Estado</span>
      <span class="estado-badge {{ $pedido->estado === 'pendiente_pago' ? 'pendiente' : ($pedido->estado === 'pagado_parcial' ? 'aprobado' : '') }}">
        {{ $pedido->estado_label }}
      </span>
    </div>
  </div>

  @if ($pedido->estado === 'pendiente_pago')
    {{-- QR Yape --}}
    @php
      $datosYape = $yape?->datos ?? [];
      // Datos fijos de Yape (usados como respaldo si no vienen configurados en BD)
      $numeroYape  = $datosYape['numero']  ?? '930910949';
      $titularYape = $datosYape['titular'] ?? 'MREYZE AUOTOMATISACION Y SISTEMAS SAC';
    @endphp
    <div class="pago-card">
      <h2>Paga con Yape</h2>
      <div class="qr-wrap">
        <img src="{{ asset('img/Empresa.png') }}" alt="Código QR Yape">
        <div class="qr-num">{{ $numeroYape }}</div>
        <div class="qr-label">{{ $titularYape }}</div>
      </div>
    </div>

    {{-- Formulario comprobante --}}
    <div class="pago-card">
      <h2>Sube tu comprobante</h2>
      <form action="{{ route('pedidos.comprobante', $pedido) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label>Número de operación <span style="color:var(--danger);">*</span></label>
          <input type="text" name="numero_operacion" placeholder="Ej: 123456789" value="{{ old('numero_operacion') }}" required>
        </div>
        <div class="form-group">
          <label>Comprobante (JPG, PNG o PDF — máx 5 MB)</label>
          <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <button type="submit" class="btn-yape">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          Enviar comprobante
        </button>
      </form>
    </div>
  @elseif ($pedido->estado === 'pendiente_aprobacion')
    <div class="pago-card">
      <div class="success-box">
        <p style="font-size:1.2rem;font-weight:700;">Comprobante recibido</p>
        <p style="margin-top:8px;">Estamos revisando tu pago. Te notificaremos cuando sea aprobado.</p>
        @if ($pago)
          <p style="margin-top:12px;font-size:.82rem;color:var(--ice-200);">
            Nro. operación: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong>
          </p>
          <p style="font-size:.82rem;color:var(--ice-200);">
            Enviado: <strong style="color:#fff;">{{ $pago->created_at->format('d/m/Y H:i') }}</strong>
          </p>
        @endif
      </div>
    </div>
  @elseif ($pedido->estado === 'pagado_parcial')
    <div class="pago-card">
      <div class="success-box">
        <p style="font-size:1.2rem;font-weight:700;">Pago aprobado</p>
        <p style="margin-top:8px;">Gracias por tu pago. Estamos preparando tu pedido.</p>
      </div>
    </div>
  @endif

  <div style="text-align:center;margin-top:8px;">
    <a href="{{ route('pedidos.mis') }}" style="color:var(--ice-300);font-size:.85rem;text-decoration:none;">← Volver a mis pedidos</a>
  </div>
</div>
@endsection