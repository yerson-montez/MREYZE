@extends('layouts.app')

@section('content')
<style>
:root{--warning:#f59e0b;--success:#00d4aa;--danger:#ff6b6b;}
.mis-pedidos{max-width:860px;margin:0 auto;padding:48px 1rem 80px;}
.mis-pedidos h1{font-family:'Exo 2',sans-serif;font-size:1.8rem;font-weight:700;color:#fff;margin-bottom:24px;}
.mis-pedidos h1 span{background:linear-gradient(135deg,var(--accent),var(--ice-200));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.pedido-card{
  background:linear-gradient(135deg,rgba(13,31,60,.95),rgba(17,41,82,.8));
  border:1px solid rgba(74,144,217,.2);border-radius:16px;padding:20px;
  margin-bottom:16px;position:relative;overflow:hidden;
  transition:transform .3s ease,box-shadow .3s ease,border-color .3s ease;
}
.pedido-card:hover{transform:translateY(-3px);border-color:rgba(0,212,255,.4);box-shadow:0 12px 40px rgba(0,60,160,.35);}
.pedido-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--ice-500),var(--accent));}
.pedido-header{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:12px;flex-wrap:wrap;}
.pedido-num{font-size:.85rem;font-weight:600;color:var(--ice-100);}
.pedido-fecha{font-size:.75rem;color:var(--ice-300);margin-top:2px;}
.estado-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:600;white-space:nowrap;}
.estado-badge.pendiente{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning);}
.estado-badge.pago_confirmado{background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.3);color:#a5b4fc;}
.estado-badge.preparando{background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.3);color:#c084fc;}
.estado-badge.enviado{background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.3);color:#67e8f9;}
.estado-badge.en_camino{background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);color:#fb923c;}
.estado-badge.listo_recojo{background:rgba(37,99,199,.1);border:1px solid rgba(37,99,199,.3);color:var(--ice-200);}
.estado-badge.completado{background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success);}
.estado-badge.cancelado{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger);}
.proceso-badge{
  display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;
  padding:6px 14px;border-radius:20px;font-size:.72rem;font-weight:600;color:#fff;
  background:linear-gradient(135deg,rgba(0,212,255,.18),rgba(37,99,199,.25));
  border:1px solid rgba(0,212,255,.4);
  animation:procesoPulso 2s ease-in-out infinite;
}
@keyframes procesoPulso{0%,100%{box-shadow:0 0 10px rgba(0,212,255,.18),inset 0 0 6px rgba(0,212,255,.06);}50%{box-shadow:0 0 24px rgba(0,212,255,.5),inset 0 0 10px rgba(0,212,255,.12);}}
.pedido-producto{font-size:.9rem;color:var(--ice-100);margin-bottom:12px;}
.precios-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,12rem),1fr));gap:8px;margin-bottom:14px;}
.precio-box{background:rgba(7,26,46,.5);border:1px solid rgba(74,144,217,.12);border-radius:10px;padding:10px;text-align:center;}
.precio-label{font-size:.62rem;color:var(--ice-300);margin-bottom:4px;text-transform:uppercase;letter-spacing:.04em;}
.precio-val{font-size:.95rem;font-weight:700;color:#fff;}
.precio-val.green{color:var(--success);}
.precio-val.amber{color:var(--warning);}
.pago-info{font-size:.78rem;color:var(--ice-300);background:rgba(7,26,46,.4);border-radius:8px;padding:10px 12px;margin-bottom:12px;line-height:1.7;}
.pago-info strong{color:var(--ice-100);}
.pedido-actions{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:8px;}
.pedido-actions a{padding:10px 20px;border-radius:10px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .3s;}
.btn-pagar{background:linear-gradient(135deg,var(--ice-500),var(--ice-400));color:#fff;border:1px solid rgba(74,144,217,.4);}
.btn-pagar:hover{background:linear-gradient(135deg,var(--ice-400),var(--glow));box-shadow:0 6px 24px rgba(59,130,246,.5);transform:translateY(-1px);}
.btn-ver{background:rgba(37,99,199,.15);color:var(--ice-200);border:1px solid rgba(74,144,217,.2);}
.btn-ver:hover{background:rgba(37,99,199,.3);color:#fff;}
.btn-cancelar{background:rgba(255,107,107,.12);color:var(--danger);border:1px solid rgba(255,107,107,.3);padding:10px 20px;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;transition:all .3s;}
.btn-cancelar:hover{background:rgba(255,107,107,.25);}
.vacio{text-align:center;padding:60px 20px;}
.vacio-icon{font-size:3.6rem;margin-bottom:16px;filter:drop-shadow(0 8px 22px rgba(0,212,255,.3));animation:flotar 3s ease-in-out infinite;}
@keyframes flotar{0%,100%{transform:translateY(0);}50%{transform:translateY(-9px);}}
.vacio p{color:var(--ice-300);margin-bottom:18px;font-size:1rem;}
.btn-empezar{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:12px;background:linear-gradient(135deg,var(--ice-500),var(--ice-400));color:#fff;text-decoration:none;font-weight:600;transition:all .3s;}
.btn-empezar:hover{background:linear-gradient(135deg,var(--ice-400),var(--glow));transform:translateY(-2px);box-shadow:0 8px 28px rgba(59,130,246,.5);}
@media (max-width:560px){
  .precios-grid{grid-template-columns:1fr;}
  .pedido-actions{justify-content:stretch;}
  .pedido-actions a,.pedido-actions button{text-align:center;flex:1;}
}
</style>

<div class="mis-pedidos">
  <h1>Mis <span>pedidos</span></h1>

  @forelse ($pedidos as $pedido)
    @php
      $pagado        = $pedido->total_pagado;
      $saldoPendiente = max($pedido->total - $pagado, 0);
      $pago          = $pedido->pagos->first();
      $boleta        = $pedido->boletas->first();
      $enProceso     = ! in_array($pedido->estado, ['completado', 'cancelado']);
      $puedeCancelar = in_array($pedido->estado, ['pendiente', 'pago_confirmado']);
      $puedePagarSaldo = $puedeCancelar && $pagado > 0 && $saldoPendiente > 0;
    @endphp
    <div class="pedido-card">
      @if ($enProceso)
        <span class="proceso-badge">📍 Pedido en proceso</span>
      @endif

      <div class="pedido-header">
        <div>
          <div class="pedido-num">{{ $pedido->numero_pedido ?? '#' . $pedido->id }}</div>
          <div class="pedido-fecha">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <span class="estado-badge {{ $pedido->estado }}">{{ $pedido->estado_label }}</span>
      </div>

      <div class="pedido-producto">{{ $pedido->producto->nombre }}</div>

      <div class="precios-grid">
        <div class="precio-box">
          <div class="precio-label">Total</div>
          <div class="precio-val">S/ {{ number_format($pedido->total, 2) }}</div>
        </div>
        <div class="precio-box">
          <div class="precio-label">Total pagado</div>
          <div class="precio-val green">S/ {{ number_format($pagado, 2) }}</div>
        </div>
        <div class="precio-box">
          <div class="precio-label">Saldo</div>
          @if ($saldoPendiente <= 0 && $pagado > 0)
            <div class="precio-val green">✓ 100% pagado</div>
          @else
            <div class="precio-val amber">S/ {{ number_format($saldoPendiente, 2) }}</div>
          @endif
        </div>
      </div>

      @if ($pago || $boleta)
        <div class="pago-info">
          @if ($pago)
            Método de pago: <strong>{{ ucfirst($pago->metodo_pago ?? '—') }}</strong>
            @if ($pago->numero_operacion)
              · Nro. operación: <strong>{{ $pago->numero_operacion }}</strong>
            @endif
            <br>
          @endif
          @if ($boleta)
            N.º boleta: <strong>{{ $boleta->numero_boleta }}</strong>
          @endif
        </div>
      @endif

      <div class="pedido-actions">
        @if ($pedido->estado === 'pendiente' && $pagado <= 0)
          <a href="{{ route('pedidos.pago', $pedido) }}" class="btn-pagar">
            Pagar ahora — S/ {{ number_format($pedido->adelanto, 2) }}
          </a>
        @elseif ($puedePagarSaldo)
          <a href="{{ route('pedidos.pago', $pedido) }}" class="btn-pagar">
            Completar pago pendiente — S/ {{ number_format($saldoPendiente, 2) }}
          </a>
        @endif
        <a href="{{ route('pedidos.show', $pedido) }}" class="btn-ver">Ver seguimiento</a>
        @if ($boleta)
          <a href="{{ route('pedidos.boleta', $pedido) }}" class="btn-ver">Ver boleta</a>
          <a href="{{ route('pedidos.boleta.pdf', $pedido) }}" target="_blank" class="btn-ver">Boleta PDF</a>
        @endif
        @if ($puedeCancelar)
          <form action="{{ route('pedidos.cancelar', $pedido) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-cancelar" onclick="return confirm('¿Seguro que deseas cancelar este pedido?')">Cancelar pedido</button>
          </form>
        @endif
      </div>
    </div>
  @empty
    <div class="vacio">
      <div class="vacio-icon">📦</div>
      <p>Aún no tienes pedidos realizados.</p>
      <a href="{{ route('tienda.index') }}" class="btn-empezar">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/></svg>
        Ir a la tienda
      </a>
    </div>
  @endforelse
</div>
@endsection
