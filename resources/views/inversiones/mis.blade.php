@extends('layouts.profile')

@section('title', 'Mis inversiones — MREYZE')

@section('private-content')
<style>
:root{--success:#00d4aa;--danger:#ff6b6b;--warning:#f59e0b;}
.mis-inv{max-width:860px;margin:0 auto;padding:0;}
.mis-inv h1{font-family:'Exo 2',sans-serif;font-size:1.8rem;font-weight:700;color:#fff;margin-bottom:24px;}
.mis-inv h1 span{background:linear-gradient(135deg,var(--accent),var(--ice-200));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.inv-card{
  background:linear-gradient(135deg,rgba(13,31,60,.95),rgba(17,41,82,.8));
  border:1px solid rgba(74,144,217,.2);border-radius:16px;padding:20px;
  margin-bottom:16px;position:relative;overflow:hidden;
  transition:transform .3s ease,box-shadow .3s ease,border-color .3s ease;
}
.inv-card:hover{transform:translateY(-3px);border-color:rgba(0,212,255,.4);box-shadow:0 12px 40px rgba(0,60,160,.35);}
.inv-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--ice-500),var(--accent));}
.inv-header{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:12px;flex-wrap:wrap;}
.inv-titulo{font-size:1rem;font-weight:700;color:#fff;}
.inv-fecha{font-size:.75rem;color:var(--ice-300);margin-top:2px;}
.estado-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:600;white-space:nowrap;}
.estado-badge.pendiente{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--warning);}
.estado-badge.aprobado{background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);color:var(--success);}
.estado-badge.rechazado{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger);}
.estado-badge.cancelado{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:var(--danger);}
.inv-datos{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,12rem),1fr));gap:8px;margin-bottom:14px;}
.inv-dato{background:rgba(7,26,46,.5);border:1px solid rgba(74,144,217,.12);border-radius:10px;padding:10px;text-align:center;}
.inv-dato-label{font-size:.62rem;color:var(--ice-300);margin-bottom:4px;text-transform:uppercase;letter-spacing:.04em;}
.inv-dato-val{font-size:.95rem;font-weight:700;color:#fff;}
.inv-dato-val.accent{color:var(--accent);}
.inv-dato-val.green{color:var(--success);}
.inv-crono{margin-bottom:14px;}
.inv-crono-titulo{font-size:.68rem;font-weight:700;color:var(--ice-300);text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;}
.inv-crono-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,10rem),1fr));gap:8px;}
.inv-crono-paso{background:rgba(7,26,46,.4);border:1px solid rgba(74,144,217,.12);border-radius:10px;padding:10px;text-align:center;position:relative;}
.inv-crono-paso.activo{border-color:rgba(0,212,255,.5);box-shadow:0 0 14px rgba(0,212,255,.15);}
.inv-crono-paso.futuro{opacity:.45;}
.inv-crono-fecha{font-size:.68rem;color:var(--accent);font-weight:700;margin-bottom:3px;}
.inv-crono-paso.activo .inv-crono-fecha{color:#4ade80;}
.inv-crono-nombre{font-size:.7rem;color:var(--ice-100);}
.inv-pago-info{font-size:.78rem;color:var(--ice-300);background:rgba(7,26,46,.4);border-radius:8px;padding:10px 12px;margin-bottom:12px;line-height:1.7;}
.inv-pago-info strong{color:var(--ice-100);}
.inv-actions{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:8px;}
.inv-actions a{padding:10px 20px;border-radius:10px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .3s;}
.btn-ver{background:rgba(37,99,199,.15);color:var(--ice-200);border:1px solid rgba(74,144,217,.2);}
.btn-ver:hover{background:rgba(37,99,199,.3);color:#fff;}
.vacio{text-align:center;padding:60px 20px;}
.vacio-icon{font-size:3.6rem;margin-bottom:16px;filter:drop-shadow(0 8px 22px rgba(0,212,255,.3));animation:flotar 3s ease-in-out infinite;}
@keyframes flotar{0%,100%{transform:translateY(0);}50%{transform:translateY(-9px);}}
.vacio p{color:var(--ice-300);margin-bottom:18px;font-size:1rem;}
.btn-empezar{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:12px;background:linear-gradient(135deg,var(--ice-500),var(--ice-400));color:#fff;text-decoration:none;font-weight:600;transition:all .3s;}
.btn-empezar:hover{background:linear-gradient(135deg,var(--ice-400),var(--glow));transform:translateY(-2px);box-shadow:0 8px 28px rgba(59,130,246,.5);}
@media (max-width:560px){
  .inv-datos{grid-template-columns:1fr;}
  .inv-crono-grid{grid-template-columns:repeat(2,1fr);}
  .inv-actions{justify-content:stretch;}
  .inv-actions a{text-align:center;flex:1;}
}
</style>

<div class="mis-inv">
  <h1>Mis <span>inversiones</span></h1>

    @forelse ($inversiones as $inversion)
    @php
      $proyecto = $inversion->proyecto;
      $tiempo = (int) $proyecto->inversion_tiempo_estimado ?: 12;
      $inicio = $proyecto->fecha_inicio?->copy() ?? now();
      $pago = $inversion->ultimoPago;
      $pagoAprobado = $pago && $pago->estado_pago === 'aprobado';
      $pagoRechazado = $pago && $pago->estado_pago === 'rechazado';
      $pasos = [
        ['fecha' => $inicio->format('M Y'), 'nombre' => 'Aporte confirmado'],
        ['fecha' => $inicio->copy()->addMonths(ceil($tiempo / 3))->format('M Y'), 'nombre' => 'Avance 1'],
        ['fecha' => $inicio->copy()->addMonths(ceil($tiempo * 2 / 3))->format('M Y'), 'nombre' => 'Avance 2'],
        ['fecha' => $inicio->copy()->addMonths($tiempo)->format('M Y'), 'nombre' => 'Retorno estimado'],
      ];
      $indicePaso = $inversion->estado === 'aprobado' ? 0 : -1;
    @endphp
    <div class="inv-card">
      <div class="inv-header">
        <div>
          <div class="inv-titulo">{{ $proyecto->titulo }}</div>
          <div class="inv-fecha">Solicitada el {{ $inversion->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <span class="estado-badge {{ $inversion->estado }}">{{ $inversion->estado_label }}</span>
          @if($pago)
            <span class="estado-badge {{ $pago->estado_pago }}">{{ $inversion->estado_pago_label }}</span>
          @endif
        </div>
      </div>

      <div class="inv-datos">
        <div class="inv-dato">
          <div class="inv-dato-label">Monto invertido</div>
          <div class="inv-dato-val accent">S/ {{ number_format($inversion->monto, 2) }}</div>
        </div>
        <div class="inv-dato">
          <div class="inv-dato-label">ROI estimado</div>
          <div class="inv-dato-val green">{{ $proyecto->inversion_roi_label }}</div>
        </div>
        <div class="inv-dato">
          <div class="inv-dato-label">Tiempo de ejecución</div>
          <div class="inv-dato-val">{{ $tiempo }} meses</div>
        </div>
      </div>

      <div class="inv-crono">
        <div class="inv-crono-titulo">Cronograma estimado</div>
        <div class="inv-crono-grid">
          @foreach ($pasos as $idx => $paso)
            <div class="inv-crono-paso {{ $idx === $indicePaso ? 'activo' : ($idx < $indicePaso ? 'activo' : 'futuro') }}">
              <div class="inv-crono-fecha">{{ $paso['fecha'] }}</div>
              <div class="inv-crono-nombre">{{ $paso['nombre'] }}</div>
            </div>
          @endforeach
        </div>
      </div>

      @if($inversion->metodoPago)
        <div class="inv-pago-info">
          Medio de pago: <strong>{{ $inversion->metodoPago->emoji }} {{ $inversion->metodoPago->nombre }}</strong>
          @if($inversion->numero_operacion)
            · N.º operación: <strong>{{ $inversion->numero_operacion }}</strong>
          @endif
        </div>
      @endif

      @if($pagoAprobado)
        <div class="inv-pago-info">
          <strong style="color:var(--success);">✅ Pago confirmado correctamente.</strong><br>
          Hemos recibido y validado tu inversión de <strong style="color:#fff;">S/ {{ number_format($inversion->monto, 2) }}</strong>.
          @if($inversion->estado === 'aprobado')
            Ya eres parte del proyecto. Tu contrato de inversión está disponible en esta sección.
          @else
            Tu inversión está registrada y en proceso de formalización.
          @endif
        </div>
      @elseif($pagoRechazado)
        <div class="inv-pago-info">
          Tu pago fue rechazado. Verifica los datos e inténtalo nuevamente.
          @if($inversion->notas_admin)
            Motivo: <strong>{{ $inversion->notas_admin }}</strong>
          @endif
        </div>
      @elseif($pago)
        <div class="inv-pago-info">
          Tu pago está siendo verificado automáticamente. Te contactaremos en cuanto se confirme tu aporte.
        </div>
      @endif

      @if($inversion->participacion_activa)
        <div class="inv-pago-info" style="border-color:rgba(0,212,170,.3);">
          Tu participación está <strong style="color:var(--success);">activa</strong>. El proyecto avanza según su cronograma y se encuentra {{ strtolower($inversion->estado_proyecto_label) }}.
        </div>
      @endif

      @if($inversion->estado === 'rechazado' && $inversion->notas_admin && !$pagoRechazado)
        <div class="inv-pago-info">
          Motivo: <strong>{{ $inversion->notas_admin }}</strong>
        </div>
      @endif

      @if($inversion->estado === 'pendiente' && !$pago)
        <div class="inv-pago-info">
          Tu solicitud está en revisión. Te contactaremos por correo o WhatsApp para confirmar tu aporte.
        </div>
      @endif

      <div class="inv-actions">
        <a href="{{ route('proyectos.show', $proyecto) }}" class="btn-ver">Ver proyecto</a>
        @if($inversion->contrato_pdf)
          <a href="{{ route('inversiones.contrato', $inversion) }}" class="btn-ver">📄 Descargar contrato</a>
        @endif
        @if($proyecto->whatsapp_numero)
          <a href="{{ $proyecto->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn-ver">Contactar</a>
        @endif
      </div>
    </div>
  @empty
    <div class="vacio">
      <div class="vacio-icon">📈</div>
      <p>Aún no tienes inversiones registradas.</p>
      <a href="{{ route('proyectos') }}" class="btn-empezar">
        Ver proyectos de inversión
      </a>
    </div>
  @endforelse

  @if($inversiones->hasPages())
    <div style="margin-top:1.5rem;">{{ $inversiones->links() }}</div>
  @endif
</div>
@endsection
