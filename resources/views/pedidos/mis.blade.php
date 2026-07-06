@extends('layouts.app')

@section('content')
<style>
.mis-pedidos{max-width:720px;margin:0 auto;padding:48px 1rem 80px;}
.mis-pedidos h1{font-family:'Exo 2',sans-serif;font-size:1.8rem;font-weight:700;color:#fff;margin-bottom:24px;}
.mis-pedidos h1 span{background:linear-gradient(135deg,var(--accent),var(--ice-200));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.pedido-card{
  background:linear-gradient(135deg,rgba(13,31,60,.95),rgba(17,41,82,.8));
  border:1px solid rgba(74,144,217,.2);border-radius:16px;padding:20px;
  margin-bottom:16px;position:relative;overflow:hidden;
}
.pedido-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--ice-500),var(--accent));}
.pedido-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;}
.pedido-num{font-size:.85rem;font-weight:600;color:var(--ice-100);}
.pedido-fecha{font-size:.75rem;color:var(--ice-300);}
.estado-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:600;}
.estado-badge.amber{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning);}
.estado-badge.blue{background:rgba(37,99,199,.1);border:1px solid rgba(37,99,199,.3);color:var(--ice-200);}
.estado-badge.indigo{background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.3);color:#a5b4fc;}
.estado-badge.purple{background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.3);color:#c084fc;}
.estado-badge.cyan{background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.3);color:#67e8f9;}
.estado-badge.green{background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success);}
.estado-badge.red{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger);}
.pedido-producto{font-size:.9rem;color:var(--ice-100);margin-bottom:12px;}
.precios-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px;}
.precio-box{background:rgba(7,26,46,.5);border:1px solid rgba(74,144,217,.12);border-radius:10px;padding:10px;text-align:center;}
.precio-label{font-size:.62rem;color:var(--ice-300);margin-bottom:4px;text-transform:uppercase;letter-spacing:.04em;}
.precio-val{font-size:.95rem;font-weight:700;color:#fff;}
.precio-val.green{color:var(--success);}
.precio-val.amber{color:var(--warning);}
.pedido-actions{display:flex;justify-content:flex-end;}
.pedido-actions a{padding:10px 20px;border-radius:10px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .3s;}
.btn-pagar{background:linear-gradient(135deg,var(--ice-500),var(--ice-400));color:#fff;border:1px solid rgba(74,144,217,.4);}
.btn-pagar:hover{background:linear-gradient(135deg,var(--ice-400),var(--glow));box-shadow:0 6px 24px rgba(59,130,246,.5);transform:translateY(-1px);}
.btn-ver{background:rgba(37,99,199,.15);color:var(--ice-200);border:1px solid rgba(74,144,217,.2);}
.btn-ver:hover{background:rgba(37,99,199,.3);color:#fff;}
.pago-info{font-size:.78rem;color:var(--ice-300);background:rgba(7,26,46,.4);border-radius:8px;padding:10px 12px;margin-bottom:12px;line-height:1.6;}
.pago-info strong{color:var(--ice-100);}
.vacio{text-align:center;padding:60px 20px;}
.vacio p{color:var(--ice-300);margin-bottom:16px;}
.btn-empezar{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:12px;background:linear-gradient(135deg,var(--ice-500),var(--ice-400));color:#fff;text-decoration:none;font-weight:600;transition:all .3s;}
.btn-empezar:hover{background:linear-gradient(135deg,var(--ice-400),var(--glow));transform:translateY(-2px);}
</style>

<div class="mis-pedidos">
  <h1>Mis <span>pedidos</span></h1>

  @forelse ($pedidos as $pedido)
    <div class="pedido-card">
      <div class="pedido-header">
        <div>
          <div class="pedido-num">{{ $pedido->numero_pedido ?? '#' . $pedido->id }}</div>
          <div class="pedido-fecha">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <span class="estado-badge
          {{ $pedido->estado === 'pendiente_pago' ? 'amber' : '' }}
          {{ $pedido->estado === 'pendiente_aprobacion' ? 'blue' : '' }}
          {{ $pedido->estado === 'pagado_parcial' ? 'indigo' : '' }}
          {{ $pedido->estado === 'preparando' ? 'purple' : '' }}
          {{ $pedido->estado === 'enviado' ? 'cyan' : '' }}
          {{ $pedido->estado === 'entregado' ? 'green' : '' }}
          {{ $pedido->estado === 'cancelado' ? 'red' : '' }}">
          {{ $pedido->estado_label }}
        </span>
      </div>

      <div class="pedido-producto">{{ $pedido->producto->nombre }}</div>

      <div class="precios-grid">
        <div class="precio-box">
          <div class="precio-label">Total</div>
          <div class="precio-val">S/ {{ number_format($pedido->total, 2) }}</div>
        </div>
        <div class="precio-box">
          <div class="precio-label">Adelanto</div>
          <div class="precio-val green">S/ {{ number_format($pedido->adelanto, 2) }}</div>
        </div>
        <div class="precio-box">
          <div class="precio-label">Saldo</div>
          <div class="precio-val amber">S/ {{ number_format($pedido->saldo, 2) }}</div>
        </div>
      </div>

      @if ($pago = $pedido->ultimoPago)
        <div class="pago-info">
          @if ($pago->numero_operacion)
            Nro. operación: <strong>{{ $pago->numero_operacion }}</strong><br>
          @endif
          @if ($pago->comprobante_pago)
            <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" style="color:var(--accent);">Ver comprobante</a><br>
          @endif
          Estado pago: <strong>{{ ucfirst($pago->estado_pago) }}</strong>
          @if ($pago->observaciones_admin)
            <br>Observaciones: {{ $pago->observaciones_admin }}
          @endif
        </div>
      @endif

      <div class="pedido-actions">
        @if ($pedido->estado === 'pendiente_pago')
          <a href="{{ route('pedidos.pago', $pedido) }}" class="btn-pagar">
            Pagar ahora — S/ {{ number_format($pedido->adelanto, 2) }}
          </a>
        @else
          <a href="{{ route('pedidos.show', $pedido) }}" class="btn-ver">Ver detalle</a>
        @endif
      </div>
    </div>
  @empty
    <div class="vacio">
      <p>Todavía no tienes pedidos.</p>
      <a href="{{ route('tienda.index') }}" class="btn-empezar">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/></svg>
        Ir a la tienda
      </a>
    </div>
  @endforelse
</div>
@endsection
