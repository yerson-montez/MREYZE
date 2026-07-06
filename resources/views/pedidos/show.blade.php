@extends('layouts.app')

@section('content')
<style>
.pd-container{max-width:820px;margin:0 auto;padding:48px 1rem 80px;}
.pd-header{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:32px;}
.pd-title{font-family:'Exo 2',sans-serif;font-size:1.5rem;font-weight:700;color:#fff;margin:0;}
.pd-title small{display:block;font-size:.78rem;font-weight:400;color:var(--ice-300);margin-top:4px;}
.pd-num{font-size:.85rem;font-weight:600;color:var(--ice-100);}
.pd-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:20px;font-size:.75rem;font-weight:600;}
.pd-badge.amber{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#fbbf24;}
.pd-badge.blue{background:rgba(37,99,199,.12);border:1px solid rgba(37,99,199,.3);color:var(--ice-200);}
.pd-badge.indigo{background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.3);color:#a5b4fc;}
.pd-badge.purple{background:rgba(168,85,247,.12);border:1px solid rgba(168,85,247,.3);color:#c084fc;}
.pd-badge.orange{background:rgba(251,146,60,.12);border:1px solid rgba(251,146,60,.3);color:#fb923c;}
.pd-badge.cyan{background:rgba(6,182,212,.12);border:1px solid rgba(6,182,212,.3);color:#67e8f9;}
.pd-badge.green{background:rgba(0,212,170,.12);border:1px solid rgba(0,212,170,.3);color:var(--success);}
.pd-badge.red{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.3);color:var(--danger);}

/* Card */
.pd-card{background:linear-gradient(135deg,rgba(13,31,60,.95),rgba(17,41,82,.8));border:1px solid rgba(74,144,217,.18);border-radius:16px;padding:24px;margin-bottom:20px;position:relative;overflow:hidden;}
.pd-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--ice-500),var(--accent));}
.pd-card-title{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ice-300);margin:0 0 16px;}
.pd-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.pd-field{}
.pd-label{font-size:.72rem;color:var(--ice-300);margin-bottom:2px;}
.pd-value{font-size:.88rem;font-weight:500;color:#fff;}
.pd-value a{color:var(--accent);text-decoration:none;}
.pd-value a:hover{text-decoration:underline;}
.pd-full{grid-column:1/-1;}

/* Precios */
.pd-precios{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:0;}
.pd-precio-box{background:rgba(7,26,46,.5);border:1px solid rgba(74,144,217,.12);border-radius:12px;padding:14px;text-align:center;}
.pd-precio-label{font-size:.65rem;color:var(--ice-300);margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;}
.pd-precio-val{font-size:1.1rem;font-weight:700;color:#fff;}
.pd-precio-val.green{color:var(--success);}
.pd-precio-val.amber{color:var(--warning);}

/* Timeline */
.pd-timeline{padding:8px 0;}
.tl-step{display:flex;gap:16px;position:relative;padding-bottom:28px;}
.tl-step:last-child{padding-bottom:0;}
.tl-line{position:absolute;left:17px;top:36px;bottom:0;width:2px;background:rgba(74,144,217,.15);}
.tl-step.completed .tl-line{background:var(--success);}
.tl-step.canceled .tl-line{background:var(--danger);}
.tl-step.active .tl-line{background:linear-gradient(180deg,var(--accent),rgba(0,212,255,.3));}
.tl-icon{position:relative;z-index:2;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;transition:all .3s;}
.tl-step.completed .tl-icon{background:rgba(0,212,170,.15);border:2px solid var(--success);color:var(--success);}
.tl-step.active .tl-icon{background:rgba(0,212,255,.12);border:2px solid var(--accent);color:var(--accent);box-shadow:0 0 0 4px rgba(0,212,255,.08);}
.tl-step.pending .tl-icon{background:rgba(74,144,217,.06);border:2px solid rgba(74,144,217,.2);color:var(--ice-300);}
.tl-step.canceled .tl-icon{background:rgba(255,107,107,.12);border:2px solid var(--danger);color:var(--danger);}
.tl-content{flex:1;padding-top:4px;}
.tl-title{font-size:.88rem;font-weight:600;color:#fff;margin-bottom:2px;}
.tl-step.completed .tl-title{color:var(--success);}
.tl-step.active .tl-title{color:var(--accent);}
.tl-step.canceled .tl-title{color:var(--danger);}
.tl-date{font-size:.72rem;color:var(--ice-300);margin-bottom:4px;}
.tl-desc{font-size:.78rem;color:var(--ice-200);line-height:1.5;max-width:480px;}
.tl-cancel-box{margin-top:16px;padding:14px 18px;background:rgba(255,107,107,.06);border:1px solid rgba(255,107,107,.2);border-radius:12px;}
.tl-cancel-box .tl-title{color:var(--danger);}
.vacio{padding:60px 20px;text-align:center;}

@media(max-width:600px){
  .pd-grid{grid-template-columns:1fr;}
  .pd-precios{grid-template-columns:1fr;}
  .pd-header{flex-direction:column;}
}
</style>

<div class="pd-container">
  {{-- Header --}}
  <div class="pd-header">
    <div>
      <h1 class="pd-title">
        Pedido {{ $pedido->numero_pedido ?? '#' . $pedido->id }}
        <small>{{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</small>
      </h1>
    </div>
    <span class="pd-badge
      {{ $pedido->estado === 'pendiente_pago' ? 'amber' : '' }}
      {{ $pedido->estado === 'pendiente_aprobacion' ? 'blue' : '' }}
      {{ $pedido->estado === 'pagado_parcial' ? 'indigo' : '' }}
      {{ $pedido->estado === 'preparando' ? 'purple' : '' }}
      {{ $pedido->estado === 'en_proceso_envio' ? 'orange' : '' }}
      {{ $pedido->estado === 'enviado' ? 'cyan' : '' }}
      {{ $pedido->estado === 'entregado' ? 'green' : '' }}
      {{ $pedido->estado === 'cancelado' ? 'red' : '' }}">
      <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px;">
        <circle cx="12" cy="12" r="10"/>
      </svg>
      {{ $pedido->estado_label }}
    </span>
  </div>

  {{-- Timeline --}}
  @php
    $timelineSteps = [
      ['key' => 'recibido',     'label' => 'Pedido recibido',     'desc' => 'Hemos recibido tu pedido y está pendiente de pago.',                    'svg' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>'],
      ['key' => 'pagado',       'label' => 'Pago confirmado',     'desc' => 'Tu pago ha sido confirmado. Estamos revisando los datos.',             'svg' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>'],
      ['key' => 'preparando',   'label' => 'Preparando pedido',   'desc' => 'Estamos preparando tu producto para el envío.',                         'svg' => '<path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>'],
      ['key' => 'proceso_envio','label' => 'En proceso de envío', 'desc' => 'Tu pedido está siendo trasladado para su entrega.',                     'svg' => '<path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>'],
      ['key' => 'enviado',      'label' => 'Enviado',             'desc' => 'Tu pedido ha sido enviado y está en camino.',                           'svg' => '<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/>'],
      ['key' => 'entregado',    'label' => 'Entregado',           'desc' => 'Tu pedido ha sido entregado. ¡Gracias por tu compra!',                  'svg' => '<path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>'],
    ];

    $currentEstado = $pedido->estado;
    $currentIdx = null;
    foreach ($timelineSteps as $i => $step) {
        if (in_array($currentEstado, ['pendiente_pago', 'pendiente_aprobacion']) && $i === 0) { $currentIdx = 0; break; }
        if ($currentEstado === 'pagado_parcial' && $step['key'] === 'pagado') { $currentIdx = $i; break; }
        if ($currentEstado === 'preparando' && $step['key'] === 'preparando') { $currentIdx = $i; break; }
        if ($currentEstado === 'en_proceso_envio' && $step['key'] === 'proceso_envio') { $currentIdx = $i; break; }
        if ($currentEstado === 'enviado' && $step['key'] === 'enviado') { $currentIdx = $i; break; }
        if ($currentEstado === 'entregado' && $step['key'] === 'entregado') { $currentIdx = $i; break; }
    }

    $esCancelado = $currentEstado === 'cancelado';

    // Fecha de cada estado desde el historial
    $estadosMap = [];
    foreach ($pedido->estados as $e) {
        $estadosMap[$e->estado] = $e;
    }
  @endphp

  <div class="pd-card">
    <h2 class="pd-card-title">Estado del pedido</h2>
    <div class="pd-timeline">
      @foreach ($timelineSteps as $i => $step)
        @php
          $isCompleted = !$esCancelado && $currentIdx !== null && $i < $currentIdx;
          $isActive    = !$esCancelado && $currentIdx !== null && $i === $currentIdx;
          $isPending   = !$esCancelado && ($currentIdx === null || $i > $currentIdx);

          // Buscar fecha en historial
          $estadoFecha = null;
          $estadoDesc  = $step['desc'];
          foreach (['pendiente_pago', 'pendiente_aprobacion', 'pagado_parcial', 'preparando', 'en_proceso_envio', 'enviado', 'entregado'] as $eKey) {
              if (isset($estadosMap[$eKey])) {
                  $h = $estadosMap[$eKey];
                  if ($step['key'] === 'recibido' && in_array($eKey, ['pendiente_pago', 'pendiente_aprobacion'])) {
                      $estadoFecha = $h->created_at;
                      if ($h->descripcion) $estadoDesc = $h->descripcion;
                  } elseif ($eKey === 'pagado_parcial' && $step['key'] === 'pagado') {
                      $estadoFecha = $h->created_at;
                      if ($h->descripcion) $estadoDesc = $h->descripcion;
                  } elseif ($eKey === $step['key']) {
                      $estadoFecha = $h->created_at;
                      if ($h->descripcion) $estadoDesc = $h->descripcion;
                  }
              }
          }
          if (!$estadoFecha) {
              $estadoFecha = $pedido->created_at;
          }
        @endphp

        <div class="tl-step {{ $isCompleted ? 'completed' : ($isActive ? 'active' : ($isPending ? 'pending' : '')) }}">
          <div class="tl-line"></div>
          <div class="tl-icon">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;">{!! $step['svg'] !!}</svg>
          </div>
          <div class="tl-content">
            <div class="tl-title">{{ $step['label'] }}</div>
            <div class="tl-date">{{ $estadoFecha->format('d/m/Y H:i') }}</div>
            <div class="tl-desc">{{ $estadoDesc }}</div>
          </div>
        </div>
      @endforeach

      {{-- Cancelado --}}
      @if ($esCancelado)
      <div class="tl-step canceled">
        <div class="tl-line"></div>
        <div class="tl-icon">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;">
            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
          </svg>
        </div>
        <div class="tl-content">
          <div class="tl-title">Cancelado</div>
          <div class="tl-date">
            @if (isset($estadosMap['cancelado']))
              {{ $estadosMap['cancelado']->created_at->format('d/m/Y H:i') }}
            @endif
          </div>
          <div class="tl-desc">Este pedido ha sido cancelado.</div>
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- Cliente y dirección --}}
  <div class="pd-card">
    <h2 class="pd-card-title">Información del cliente</h2>
    <div class="pd-grid">
      <div class="pd-field">
        <div class="pd-label">Nombre</div>
        <div class="pd-value">{{ $pedido->cliente_nombre }}</div>
      </div>
      <div class="pd-field">
        <div class="pd-label">Teléfono</div>
        <div class="pd-value">{{ $pedido->cliente_telefono }}</div>
      </div>
      <div class="pd-field pd-full">
        <div class="pd-label">Correo electrónico</div>
        <div class="pd-value">{{ $pedido->cliente_email }}</div>
      </div>
      <div class="pd-field pd-full">
        <div class="pd-label">Dirección de envío</div>
        <div class="pd-value">{{ $pedido->cliente_direccion }}</div>
      </div>
    </div>
  </div>

  {{-- Producto --}}
  <div class="pd-card">
    <h2 class="pd-card-title">Producto comprado</h2>
    <div class="pd-grid">
      <div class="pd-field pd-full">
        <div class="pd-label">Producto</div>
        <div class="pd-value" style="font-size:1rem;">{{ $pedido->producto->nombre }}</div>
      </div>
      @if ($pedido->producto->descripcion)
      <div class="pd-field pd-full">
        <div class="pd-label">Descripción</div>
        <div class="pd-value" style="font-weight:400;color:var(--ice-200);">{{ $pedido->producto->descripcion }}</div>
      </div>
      @endif
    </div>
  </div>

  {{-- Resumen de pago --}}
  <div class="pd-card">
    <h2 class="pd-card-title">Resumen de compra</h2>
    <div class="pd-precios">
      <div class="pd-precio-box">
        <div class="pd-precio-label">Total</div>
        <div class="pd-precio-val">S/ {{ number_format($pedido->total, 2) }}</div>
      </div>
      <div class="pd-precio-box">
        <div class="pd-precio-label">Adelanto (50 %)</div>
        <div class="pd-precio-val green">S/ {{ number_format($pedido->adelanto, 2) }}</div>
      </div>
      <div class="pd-precio-box">
        <div class="pd-precio-label">Saldo contra entrega</div>
        <div class="pd-precio-val amber">S/ {{ number_format($pedido->saldo, 2) }}</div>
      </div>
    </div>
  </div>

  {{-- Pagos realizados --}}
  @if ($pedido->pagos->count() > 0)
  <div class="pd-card">
    <h2 class="pd-card-title">Pagos realizados</h2>
    @foreach ($pedido->pagos as $pago)
    <div style="background:rgba(7,26,46,.4);border:1px solid rgba(74,144,217,.1);border-radius:10px;padding:14px;margin-bottom:10px;">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <div>
          <div style="font-size:.82rem;font-weight:600;color:#fff;">
            {{ ucfirst($pago->metodo_pago) }}
            @if ($pago->numero_operacion)
              — <span style="color:var(--ice-300);font-weight:400;">Nro. {{ $pago->numero_operacion }}</span>
            @endif
          </div>
          <div style="font-size:.72rem;color:var(--ice-300);margin-top:3px;">
            {{ $pago->created_at->format('d/m/Y H:i') }}
          </div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:1rem;font-weight:700;color:#fff;">S/ {{ number_format($pago->monto, 2) }}</div>
          <span style="display:inline-block;padding:2px 8px;border-radius:10px;font-size:.65rem;font-weight:600;margin-top:3px;
            {{ $pago->estado_pago === 'aprobado' ? 'background:rgba(0,212,170,.12);color:var(--success);' : '' }}
            {{ $pago->estado_pago === 'pendiente' ? 'background:rgba(245,158,11,.12);color:#fbbf24;' : '' }}
            {{ $pago->estado_pago === 'rechazado' ? 'background:rgba(255,107,107,.12);color:var(--danger);' : '' }}">
            {{ ucfirst($pago->estado_pago) }}
          </span>
        </div>
      </div>
      @if ($pago->comprobante_pago)
      <div style="margin-top:8px;">
        <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:5px;font-size:.78rem;color:var(--accent);text-decoration:none;">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;">
            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/>
          </svg>
          Ver comprobante
        </a>
      </div>
      @endif
      @if ($pago->observaciones_admin)
      <div style="margin-top:6px;font-size:.75rem;color:var(--ice-300);">
        Observaciones: {{ $pago->observaciones_admin }}
      </div>
      @endif
    </div>
    @endforeach
  </div>
  @endif

  {{-- Volver --}}
  <div style="text-align:center;margin-top:8px;">
    <a href="{{ route('pedidos.mis') }}" style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;border-radius:10px;font-size:.85rem;font-weight:600;color:var(--ice-200);border:1px solid rgba(74,144,217,.2);text-decoration:none;transition:all .2s;"
       onmouseover="this.style.background='rgba(37,99,199,.15)';this.style.color='#fff';"
       onmouseout="this.style.background='transparent';this.style.color='var(--ice-200)';">
      <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
      </svg>
      Volver a Mis Pedidos
    </a>
  </div>

</div>
@endsection
