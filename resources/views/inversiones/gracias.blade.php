@extends('welcome')

@section('title', 'Solicitud enviada — MREYZE')

@section('content')

<style>
    .inv-gracias {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6rem 1.5rem 5rem;
    }

    .inv-gracias-card {
        max-width: 620px;
        width: 100%;
        text-align: center;
        background: linear-gradient(135deg, rgba(13,31,60,.95), rgba(17,41,82,.9));
        border: 1px solid rgba(0,212,255,.25);
        border-radius: 22px;
        padding: 3rem 2.2rem;
        position: relative;
        overflow: hidden;
    }

    .inv-gracias-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .inv-gracias-icon {
        width: 84px; height: 84px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(34,197,94,.12);
        border: 1px solid rgba(34,197,94,.4);
        color: #4ade80;
        animation: invPulso 2s ease-in-out infinite;
    }

    @keyframes invPulso {
        0%, 100% { box-shadow: 0 0 14px rgba(74,222,128,.2); transform: scale(1); }
        50% { box-shadow: 0 0 34px rgba(74,222,128,.5); transform: scale(1.05); }
    }

    .inv-gracias h1 {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 800; color: #fff; margin-bottom: .8rem;
    }

    .inv-gracias h1 span {
        background: linear-gradient(135deg, #4ade80, var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .inv-gracias p { color: var(--ice-200); font-size: .95rem; line-height: 1.7; margin-bottom: 1.8rem; }

    .inv-gracias-resumen {
        background: rgba(7,26,46,.55);
        border: 1px solid rgba(74,144,217,.2);
        border-radius: 14px;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.8rem;
        text-align: left;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .8rem;
    }

    .inv-gracias-resumen div { font-size: .8rem; color: var(--ice-300); }
    .inv-gracias-resumen strong { display: block; color: #fff; font-size: .92rem; margin-top: 2px; }

    .inv-gracias-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: .95rem 2rem; border-radius: 12px;
        font-size: .95rem; font-weight: 700; color: #fff; text-decoration: none;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        box-shadow: 0 8px 26px rgba(0,136,204,.4);
        transition: all .25s; margin: 0 4px 10px;
    }
    .inv-gracias-btn:hover { background: linear-gradient(135deg, #0099dd, #00bbff); transform: translateY(-2px); }

    .inv-gracias-btn.whatsapp {
        background: rgba(37,211,102,.15);
        border: 1px solid rgba(37,211,102,.4);
        color: #25D366;
        box-shadow: none;
    }
    .inv-gracias-btn.whatsapp:hover { background: rgba(37,211,102,.25); }

    @media (max-width: 520px) {
        .inv-gracias-resumen { grid-template-columns: 1fr; }
    }
</style>

<div class="inv-gracias">
    <div class="inv-gracias-card reveal visible">
        <div class="inv-gracias-icon">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:40px;height:40px"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>

        <h1>¡Solicitud <span>enviada</span>!</h1>
        <p>
            Gracias por tu interés en invertir en <strong style="color:#fff;">{{ $inversion->proyecto->titulo }}</strong>.
            Hemos recibido tu solicitud y nuestro equipo la revisará para confirmar tu participación.
        </p>

        <div class="inv-gracias-resumen">
            <div>Proyecto<strong>{{ $inversion->proyecto->titulo }}</strong></div>
            <div>Monto invertido<strong>S/ {{ number_format($inversion->monto, 2) }}</strong></div>
            <div>Medio de pago<strong>{{ $inversion->metodoPago->emoji ?? '' }} {{ $inversion->metodoPago->nombre ?? 'Por confirmar' }}</strong></div>
            <div>Estado<strong style="color:#fbbf24;">Pendiente de confirmación</strong></div>
        </div>

        <a href="{{ route('proyectos.show', $inversion->proyecto) }}" class="inv-gracias-btn">
            Ver proyecto
        </a>
        @if($inversion->proyecto->whatsapp_numero)
            <a href="{{ $inversion->proyecto->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inv-gracias-btn whatsapp">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Coordinar por WhatsApp
            </a>
        @endif
    </div>
</div>
@endsection
