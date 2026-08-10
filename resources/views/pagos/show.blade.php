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
.estado-badge.procesando{background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.3);color:var(--accent);}
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
      <span class="info-label">Total pagado</span>
      <span class="info-value" style="color:var(--success);">S/ {{ number_format($pedido->total_pagado, 2) }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Saldo pendiente</span>
      <span class="info-value" style="{{ $saldoPendiente > 0 ? 'color:var(--warning);' : 'color:var(--success);' }}">S/ {{ number_format($saldoPendiente, 2) }}</span>
    </div>
    @if ($saldoPendiente <= 0 && $pedido->total_pagado > 0)
    <div class="info-row">
      <span class="info-label">Progreso de pago</span>
      <span class="estado-badge aprobado" style="font-size:.95rem;padding:8px 18px;">100% PAGADO</span>
    </div>
    @endif
    <div class="info-row">
      <span class="info-label">Estado</span>
      <span id="pedido-estado-badge" class="estado-badge
        {{ in_array($pedido->estado, ['preparando', 'enviado', 'en_camino', 'listo_recojo']) ? 'procesando' : '' }}
        {{ $pedido->estado === 'pendiente' ? 'pendiente' : '' }}
        {{ $pedido->estado === 'pago_confirmado' || $pedido->estado === 'completado' ? 'aprobado' : '' }}">
        {{ $pedido->estado_label }}
      </span>
    </div>
    @if ($pago)
    <div class="info-row">
      <span class="info-label">Estado OCR</span>
      <span id="ocr-estado-badge" class="estado-badge" style="
        @php
          $c = $pago->ocr_resultado;
          $badgeStyle = match(true) {
            $c === 'validado'       => 'background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success)',
            $c === 'procesando'     => 'background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.3);color:var(--accent)',
            $c === 'pendiente'      => 'background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning)',
            $c === 'error_ocr'      => 'background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger)',
            $c === 'ocr_fallido'    => 'background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger)',
            $c === 'rechazado'      => 'background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger)',
            $c === 'confianza_baja' => 'background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning)',
            $c === 'sospechoso'     => 'background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning)',
            default                 => 'background:rgba(148,163,184,.1);border:1px solid rgba(148,163,184,.3);color:#94a3b8',
          };
        @endphp
        {{ $badgeStyle }}">
        {{ \App\Enums\EstadoValidacionOcr::tryFrom($pago->ocr_resultado)?->label() ?? 'Pendiente' }}
      </span>
    </div>
    @endif
  </div>

  <div id="pago-status-content">
  @php
    $ultimoOcrError = $pago && in_array($pago->ocr_resultado, ['error_ocr', 'ocr_fallido', 'rechazado', 'confianza_baja', 'sospechoso']) ? $pago->ocr_resultado : null;
    $pagoEnRevision = $pago && in_array($pago->ocr_resultado, ['pendiente', 'procesando', 'validado']);
  @endphp

  @if ($pedido->estado === 'pendiente' && ! $puedePagarSaldo)
    @if ($ultimoOcrError)
      @php
        $motivoReintento = match ($ultimoOcrError) {
          'error_ocr' => 'No pudimos validar el comprobante por un problema temporal del servicio.',
          'ocr_fallido' => 'No fue posible leer el comprobante. Asegúrate de que la imagen sea clara y legible.',
          'rechazado' => 'El monto del comprobante no coincide o el comprobante ya fue utilizado.',
          'confianza_baja' => 'No se pudo leer el comprobante con suficiente claridad.',
          'sospechoso' => 'Los datos del comprobante no coinciden con los ingresados.',
          default => 'El comprobante no pudo ser validado automáticamente.',
        };
      @endphp
      <div class="pago-card">
        <div class="alert" style="border-left-color:var(--danger);text-align:center;">
          <p style="font-size:1.1rem;font-weight:700;color:#fff;">
            {{ match($ultimoOcrError) {
              'error_ocr' => 'Error temporal',
              'ocr_fallido' => 'No fue posible leer el comprobante',
              default => 'No fue posible validar automáticamente el comprobante',
            } }}
          </p>
          <p style="margin-top:6px;">{{ $motivoReintento }}</p>
          <p style="margin-top:10px;font-size:.78rem;color:var(--ice-300);">Puedes intentar subir el comprobante nuevamente.</p>
          @if ($pago)
            <p style="font-size:.78rem;color:var(--ice-300);">Nro. operación anterior: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong></p>
          @endif
        </div>
      </div>
      @include('pagos._form-comprobante', ['retry' => true])
    @elseif ($pagoEnRevision)
      @php
        $ocrEstado = $pago?->ocr_resultado ?? 'pendiente';
      @endphp

    <div id="pendiente-aprobacion-section">
      <div id="status-pendiente" class="pago-card" data-ocr-state="pendiente" {{ $ocrEstado !== 'pendiente' ? 'style=display:none' : '' }}>
        <div class="success-box">
          <p style="font-size:1.2rem;font-weight:700;">Comprobante recibido</p>
          <p style="margin-top:8px;">Hemos recibido tu comprobante y está en proceso de validación.</p>
          @if ($pago)
            <p style="margin-top:12px;font-size:.82rem;color:var(--ice-200);">
              Nro. operación: <strong id="ocr-numero-operacion" style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong>
            </p>
            <p style="font-size:.82rem;color:var(--ice-200);">
              Enviado: <strong style="color:#fff;">{{ $pago->created_at->format('d/m/Y H:i') }}</strong>
            </p>
          @endif
        </div>
      </div>

      <div id="status-procesando" class="pago-card" data-ocr-state="procesando" {{ $ocrEstado !== 'procesando' ? 'style=display:none' : '' }}>
        <div class="success-box">
          <p style="font-size:1.2rem;font-weight:700;">Procesando</p>
          <p style="margin-top:8px;">Estamos analizando automáticamente tu comprobante.</p>
          @if ($pago)
            <p style="margin-top:12px;font-size:.82rem;color:var(--ice-200);">
              Nro. operación: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong>
            </p>
          @endif
        </div>
      </div>

      <div id="status-validado" class="pago-card" data-ocr-state="validado" {{ $ocrEstado !== 'validado' ? 'style=display:none' : '' }}>
        <div class="success-box">
          <p style="font-size:1.2rem;font-weight:700;color:var(--success);">✓ Pago validado</p>
          <p style="margin-top:8px;">Tu pago fue validado correctamente.</p>
          @if ($pago && $pago->ocr_datos)
            <div style="margin-top:16px;text-align:left;">
              <div class="info-row">
                <span class="info-label">Número de operación</span>
                <span class="info-value">{{ $pago->ocr_datos['codigo_operacion'] ?? $pago->numero_operacion ?? '—' }}</span>
              </div>
              @if (!empty($pago->ocr_datos['monto']))
              <div class="info-row">
                <span class="info-label">Monto validado</span>
                <span class="info-value" style="color:var(--success);">S/ {{ number_format((float) $pago->ocr_datos['monto'], 2) }}</span>
              </div>
              @endif
              @if (!empty($pago->ocr_datos['fecha']))
              <div class="info-row">
                <span class="info-label">Fecha</span>
                <span class="info-value">{{ $pago->ocr_datos['fecha'] }}</span>
              </div>
              @endif
            </div>
          @endif
        </div>
      </div>

      <div id="status-error-ocr" class="pago-card" data-ocr-state="error_ocr" {{ $ocrEstado !== 'error_ocr' ? 'style=display:none' : '' }}>
        <div class="alert" style="border-left-color:var(--danger);text-align:center;">
          <p style="font-size:1.1rem;font-weight:700;color:#fff;">Error temporal</p>
          <p style="margin-top:6px;">No pudimos validar el comprobante por un error del sistema.</p>
          <p style="margin-top:10px;font-size:.78rem;color:var(--ice-300);">Puedes intentar subir el comprobante nuevamente.</p>
          @if ($pago)
            <p style="font-size:.78rem;color:var(--ice-300);">Nro. operación: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong></p>
          @endif
        </div>
        @include('pagos._form-comprobante', ['retry' => true])
      </div>

      <div id="status-ocr-fallido" class="pago-card" data-ocr-state="ocr_fallido" {{ $ocrEstado !== 'ocr_fallido' ? 'style=display:none' : '' }}>
        <div class="alert" style="border-left-color:var(--danger);text-align:center;">
          <p style="font-size:1.1rem;font-weight:700;color:#fff;">No fue posible leer el comprobante</p>
          <p style="margin-top:6px;">Asegúrate de que la imagen sea clara y legible, luego intenta nuevamente.</p>
          <p style="margin-top:10px;font-size:.78rem;color:var(--ice-300);">Puedes intentar subir el comprobante nuevamente.</p>
          @if ($pago)
            <p style="font-size:.78rem;color:var(--ice-300);">Nro. operación: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong></p>
          @endif
        </div>
        @include('pagos._form-comprobante', ['retry' => true])
      </div>

      <div id="status-rechazado" class="pago-card" data-ocr-state="rechazado" {{ !in_array($ocrEstado, ['rechazado', 'confianza_baja', 'sospechoso']) ? 'style=display:none' : '' }}>
        @php
          $motivo = match ($ocrEstado) {
            'rechazado' => 'El monto del comprobante no coincide o el comprobante ya fue utilizado.',
            'confianza_baja' => 'No se pudo leer el comprobante con suficiente claridad.',
            'sospechoso' => 'Los datos del comprobante no coinciden con los ingresados.',
            default => 'El comprobante no pudo ser validado automáticamente.',
          };
        @endphp
        <div class="alert" style="border-left-color:var(--danger);text-align:center;">
          <p style="font-size:1.1rem;font-weight:700;color:#fff;">No fue posible validar automáticamente el comprobante</p>
          <p style="margin-top:6px;">{{ $motivo }}</p>
          <p style="margin-top:10px;font-size:.78rem;color:var(--ice-300);">Si el problema persiste, puedes intentar subir un comprobante diferente.</p>
          @if ($pago)
            <p style="font-size:.78rem;color:var(--ice-300);">Nro. operación: <strong style="color:#fff;">{{ $pago->numero_operacion ?? '—' }}</strong></p>
          @endif
        </div>
        @include('pagos._form-comprobante', ['retry' => true])
      </div>
    </div>

    @else
      @include('pagos._form-comprobante')
    @endif

  @elseif ($puedePagarSaldo)
    @include('pagos._datos-pago')
    <div id="status-saldo-pendiente" class="pago-card">
      <h2>Completar pago pendiente</h2>
      <div class="info-row">
        <span class="info-label">Total pagado</span>
        <span class="info-value" style="color:var(--success);">S/ {{ number_format($pedido->total_pagado, 2) }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Saldo restante</span>
        <span class="info-value" style="color:var(--warning);">S/ {{ number_format($saldoPendiente, 2) }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Estado</span>
        <span class="estado-badge aprobado">{{ $pedido->estado_label }}</span>
      </div>

      <form action="{{ route('pedidos.comprobante', $pedido) }}" method="POST" enctype="multipart/form-data" style="margin-top:16px;">
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
          Enviar comprobante del saldo
        </button>
      </form>
    </div>

  @elseif (in_array($pedido->estado, ['pago_confirmado', 'preparando', 'enviado', 'en_camino', 'listo_recojo']))
    <div id="status-pagado-parcial" class="pago-card">
      <div class="success-box">
        @if ($saldoPendiente <= 0)
          <p style="font-size:1.2rem;font-weight:700;color:var(--success);">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px;vertical-align:-4px;color:var(--success);"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            ¡Pedido 100% pagado!
          </p>
          <p style="margin-top:8px;">Tu pedido fue pagado en su totalidad. Estamos preparando tu pedido.</p>
        @else
          <p style="font-size:1.2rem;font-weight:700;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px;vertical-align:-4px;color:var(--success);"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            ¡Pedido realizado!
          </p>
          <p style="margin-top:8px;">Tu pago fue confirmado. Estamos preparando tu pedido.</p>
        @endif
        @if ($pagoEnRevision)
          <p style="margin-top:8px;{{ $pago?->ocr_resultado === 'validado' ? 'color:var(--success);' : 'color:var(--warning);' }}font-size:.85rem;">
            {{ $pago?->ocr_resultado === 'validado'
                ? 'El pago del saldo fue validado correctamente.'
                : 'Tu pago del saldo está en proceso de validación.' }}
          </p>
        @endif
        <div style="margin-top:14px;">
          <a href="{{ route('pedidos.show', $pedido) }}" class="btn-yape" style="display:inline-flex;padding:10px 20px;font-size:.85rem;gap:6px;text-decoration:none;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            Ver seguimiento del pedido
          </a>
        </div>
      </div>
    </div>
  @elseif ($pedido->estado === 'completado')
    <div id="status-completado" class="pago-card">
      <div class="success-box">
        <p style="font-size:1.2rem;font-weight:700;">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px;vertical-align:-4px;color:var(--success);"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
          Pedido completado
        </p>
        <p style="margin-top:8px;">Tu pedido fue completado. ¡Gracias por tu compra!</p>
        <div style="margin-top:14px;">
          <a href="{{ route('pedidos.show', $pedido) }}" class="btn-yape" style="display:inline-flex;padding:10px 20px;font-size:.85rem;gap:6px;text-decoration:none;">
            Ver detalle del pedido
          </a>
        </div>
      </div>
    </div>
  @endif
  </div>

  <div id="boleta-section" style="{{ $boleta ? '' : 'display:none;' }}">
    <div class="pago-card" id="boleta-card">
      <h2>Boleta electrónica</h2>
      <div class="info-row">
        <span class="info-label">Serie</span>
        <span class="info-value" id="boleta-serie">{{ $boleta?->serie ?? '' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Número</span>
        <span class="info-value" id="boleta-numero">{{ $boleta?->numero_boleta ?? '' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Fecha de emisión</span>
        <span class="info-value" id="boleta-fecha">{{ $boleta?->fecha_emision?->format('d/m/Y') ?? '' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Total</span>
        <span class="info-value" id="boleta-total" style="color:var(--success);">{{ $boleta ? 'S/ '.number_format($boleta->total, 2) : '' }}</span>
      </div>
      <div style="margin-top:16px;display:flex;gap:10px;">
        <a id="boleta-show-link" href="{{ $boleta ? route('pedidos.boleta', $pedido) : '#' }}" target="_blank" class="btn-yape" style="display:inline-flex;padding:10px 20px;font-size:.85rem;gap:6px;text-decoration:none;">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M12 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
          Ver boleta
        </a>
        <a id="boleta-pdf-link" href="{{ $boleta ? route('pedidos.boleta.pdf', $pedido) : '#' }}" target="_blank" style="display:inline-flex;padding:10px 20px;font-size:.85rem;gap:6px;text-decoration:none;border-radius:8px;background:#dc2626;color:#fff;font-weight:600;align-items:center;">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
          Descargar PDF
        </a>
      </div>
    </div>
  </div>

  <div style="text-align:center;margin-top:8px;">
    <a href="{{ route('pedidos.mis') }}" style="color:var(--ice-300);font-size:.85rem;text-decoration:none;">← Volver a mis pedidos</a>
  </div>
</div>

@php
  $pollUrl = url('/pedido/' . $pedido->id . '/estado');
  $pedidoId = $pedido->id;
@endphp
<script>
(function() {
    var POLL_INTERVAL = 3000;
    var pollUrl = '{{ $pollUrl }}';
    var pedidoId = {{ $pedidoId }};
    var pollTimer = null;
    var currentEstado = @json($currentEstado);
    var currentOcr = @json($currentOcr);

    var badgeStyles = {
        aprobado:   'background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success)',
        completado: 'background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success)',
        pendiente:  'background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning)',
        error:      'background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger)',
        procesando: 'background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.3);color:var(--accent)',
    };

    var badgeLabels = {
        aprobado:   'Aprobado',
        completado: 'Completado',
        pendiente:  'Pendiente',
        error:      'Error',
        procesando: 'Procesando',
    };

    var ocrToDataState = {
        completado: 'validado',
        procesando: 'procesando',
        pendiente:  'pendiente',
        error:      'error_ocr',
    };

    function updateBadges(data) {
        var badge = document.getElementById('pedido-estado-badge');
        if (badge) {
            badge.textContent = badgeLabels[data.estado] || data.estado;
            badge.className = 'estado-badge';
            if (data.estado === 'aprobado' || data.estado === 'completado') {
                badge.classList.add('aprobado');
            } else {
                badge.classList.add('pendiente');
            }
        }

        var ocrBadge = document.getElementById('ocr-estado-badge');
        if (ocrBadge && data.estado_ocr) {
            ocrBadge.textContent = badgeLabels[data.estado_ocr] || data.estado_ocr;
            ocrBadge.setAttribute('style', badgeStyles[data.estado_ocr] || badgeStyles.pendiente);
        }
    }

    function showStatusSection(ocrSimpleState) {
        var dataState = ocrToDataState[ocrSimpleState] || ocrSimpleState;
        document.querySelectorAll('#pendiente-aprobacion-section > [data-ocr-state]').forEach(function(el) {
            el.style.display = 'none';
        });
        var target = document.querySelector('#pendiente-aprobacion-section > [data-ocr-state="' + dataState + '"]');
        if (target) {
            target.style.display = '';
        }
    }

    function showPagadoParcial() {
        var section = document.getElementById('pago-status-content');
        if (section) {
            section.innerHTML =
                '<div class="pago-card"><div class="success-box">' +
                '<p style="font-size:1.2rem;font-weight:700;">¡Pedido realizado!</p>' +
                '<p style="margin-top:8px;">Tu pago fue confirmado. Estamos preparando tu pedido.</p>' +
                '<div style="margin-top:14px;"><a href="{{ route('pedidos.show', $pedido) }}" ' +
                'style="display:inline-flex;padding:10px 20px;font-size:.85rem;gap:6px;border-radius:8px;background:var(--success);color:#062b24;font-weight:700;text-decoration:none;">' +
                'Ver seguimiento del pedido</a></div>' +
                '</div></div>';
        }
    }

    function showBoleta(data) {
        var section = document.getElementById('boleta-section');
        if (!section) return;
        if (data.boleta && data.boleta.existe) {
            section.style.display = '';
            if (data.boleta.serie) document.getElementById('boleta-serie').textContent = data.boleta.serie;
            if (data.boleta.numero) document.getElementById('boleta-numero').textContent = data.boleta.numero;
            if (data.boleta.fecha) document.getElementById('boleta-fecha').textContent = data.boleta.fecha;
            if (data.boleta.total) document.getElementById('boleta-total').textContent = 'S/ ' + parseFloat(data.boleta.total).toFixed(2);
            if (data.boleta.show_url) document.getElementById('boleta-show-link').href = data.boleta.show_url;
            if (data.boleta.pdf_url) document.getElementById('boleta-pdf-link').href = data.boleta.pdf_url;
        } else {
            section.style.display = 'none';
        }
    }

    function poll() {
        fetch(pollUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                updateBadges(data);
                showBoleta(data);

                var newEstado = data.estado;
                var newOcr = data.estado_ocr || 'pendiente';

                if (newEstado !== currentEstado) {
                    currentEstado = newEstado;
                    if (newEstado === 'aprobado' || newEstado === 'completado') {
                        showPagadoParcial();
                    } else if (newEstado !== 'pendiente') {
                        return;
                    }
                }

                if (currentEstado === 'pendiente' && newOcr !== currentOcr) {
                    currentOcr = newOcr;
                    showStatusSection(newOcr);
                }

                if (newEstado === 'aprobado' || newEstado === 'completado' || newOcr === 'completado') {
                    if (pollTimer) {
                        clearTimeout(pollTimer);
                        pollTimer = null;
                    }
                }
            })
            .catch(function() {});
    }

    function startPolling() {
        if (currentEstado === 'aprobado' || currentEstado === 'completado' || currentOcr === 'completado') {
            poll();
            return;
        }
        pollTimer = setInterval(poll, POLL_INTERVAL);
    }

    startPolling();
})();
</script>
@endsection