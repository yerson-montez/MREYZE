@extends('welcome')

@section('title', 'MREYZE | Automatizamos, innovamos y construimos')
@section('meta_description', 'MReyze: automatizamos, innovamos y construimos.')

@section('content')
<style>
    .inicio-mreyze {
        --inicio-bg: #07151b;
        --inicio-accent: #62d5ee;
        --inicio-text: #f2f8fa;
        min-height: calc(100vh - 70px);
        padding: clamp(3rem, 8vw, 7rem) 1.25rem 5rem;
        color: var(--inicio-text);
        background: radial-gradient(ellipse at 18% 8%, rgba(28, 113, 133, .24), transparent 42%), var(--inicio-bg);
    }
    .inicio-mreyze *, .inicio-mreyze *::before, .inicio-mreyze *::after { box-sizing: border-box; }
    .inicio-wrap { width: min(1120px, 100%); margin: 0 auto; }
    .inicio-hero {
        min-height: clamp(27rem, 56vh, 38rem);
        display: grid;
        align-content: center;
        justify-items: start;
        padding: clamp(2.5rem, 6vw, 4rem);
        border: 1px solid rgba(145, 205, 220, .2);
        border-radius: 1.1rem;
        background-image: linear-gradient(90deg, rgba(3, 14, 24, .84), rgba(3, 14, 24, .63) 56%, rgba(3, 14, 24, .32)), linear-gradient(0deg, rgba(3, 14, 24, .25), rgba(3, 14, 24, .08)), url('{{ asset('img/fondo-inicio.png') }}');
        background-position: center 32%;
        background-size: cover;
        box-shadow: 0 22px 56px rgba(0, 0, 0, .24);
    }
    .inicio-title { max-width: 900px; margin: 0; font: 800 clamp(2.35rem, 6vw, 4.8rem)/.98 'Manrope', 'Inter', sans-serif; letter-spacing: -.065em; }
    .inicio-title span { color: var(--inicio-accent); }
    .inicio-actions { display: flex; flex-wrap: wrap; gap: .85rem; margin-top: 2.2rem; }
    .inicio-btn { display: inline-flex; min-height: 3.25rem; min-width: 9rem; align-items: center; justify-content: center; padding: .85rem 1.4rem; border: 1px solid rgba(145, 205, 220, .4); border-radius: .65rem; color: var(--inicio-text); background: rgba(255,255,255,.04); text-decoration: none; font: 700 .88rem/1 'Inter', sans-serif; transition: transform .2s ease, background .2s ease, box-shadow .2s ease; }
    .inicio-btn:hover { transform: translateY(-2px); background: rgba(98,213,238,.12); box-shadow: 0 10px 28px rgba(0,0,0,.22); }
    .inicio-btn--primary { border-color: var(--inicio-accent); color: #062027; background: var(--inicio-accent); }
    .inicio-btn--primary:hover { background: #8be7f7; }
    .inicio-projects { padding-top: 1.5rem; }
    .inicio-section-title { margin: 0 0 1.5rem; font: 700 clamp(1.3rem, 2.6vw, 1.8rem)/1.15 'Manrope', 'Inter', sans-serif; letter-spacing: -.035em; }
    .inicio-project-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; }
    .inicio-project { position: relative; isolation: isolate; display: flex; min-height: 290px; align-items: center; justify-content: center; text-align: center; overflow: hidden; border: 1px solid rgba(145,205,220,.22); border-radius: 1rem; color: inherit; background: #0b2028; text-decoration: none; transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease; }
    .inicio-project:hover { transform: translateY(-4px); border-color: rgba(98,213,238,.62); box-shadow: 0 18px 42px rgba(0,0,0,.3); }
    .inicio-project-bg { position: absolute; z-index: -2; inset: 0; background-position: center; background-size: cover; transform: scale(1); animation: inicio-fade-first 14s ease-in-out infinite; transition: transform .7s ease; }
    .inicio-project-bg--first { opacity: 1; }
    .inicio-project-bg--second { opacity: 0; animation-name: inicio-fade-second; }
    .inicio-project:hover .inicio-project-bg { transform: scale(1.04); }
    .inicio-project-shade { position: absolute; z-index: -1; inset: 0; background: linear-gradient(0deg, rgba(3,12,17,.88), rgba(3,12,17,.12) 78%), linear-gradient(90deg, rgba(3,12,17,.2), transparent); }
    .inicio-project-name { display: block; width: 100%; padding: 1.3rem 1.4rem; text-align: center; font: 700 clamp(.95rem, 1.8vw, 1.2rem)/1.25 'Manrope', 'Inter', sans-serif; text-shadow: 0 2px 14px rgba(0,0,0,.75); }
    @keyframes inicio-fade-first {
        0%, 38% { opacity: 1; }
        50%, 88% { opacity: 0; }
        100% { opacity: 1; }
    }
    @keyframes inicio-fade-second {
        0%, 38% { opacity: 0; }
        50%, 88% { opacity: 1; }
        100% { opacity: 0; }
    }
    .inicio-store { padding-top: 4rem; }
    .inicio-store .section-title { font-size: clamp(1.45rem, 3vw, 2rem); }
    .inicio-store .section-desc { font-size: .92rem; }
    .inicio-store .destacados-ver-mas { font-size: .82rem; }
    .inicio-store .destacado-nombre { font-size: .86rem; }
    .inicio-store .destacado-precio-actual { font-size: .98rem; }
    .inicio-store .destacado-btn { font-size: .76rem; }
    @media (max-width: 680px) {
        .inicio-mreyze { padding: 3rem 1rem 3.5rem; }
        .inicio-hero { min-height: clamp(27rem, 64vh, 36rem); padding-bottom: 3.5rem; background-position: 55% center; }
        .inicio-project-grid { grid-template-columns: 1fr; gap: 1rem; }
        .inicio-project { min-height: 250px; }
        .inicio-btn { flex: 1 1 9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .inicio-btn, .inicio-project, .inicio-project-bg { transition: none; animation: none; }
        .inicio-project-bg--first { opacity: 1; }
        .inicio-project-bg--second { opacity: 0; }
        .inicio-btn:hover, .inicio-project:hover { transform: none; }
    }
</style>

<main class="inicio-mreyze">
    <div class="inicio-wrap">
        <section class="inicio-hero" aria-labelledby="inicio-title">
            <h1 class="inicio-title" id="inicio-title">Automatizamos,<br><span>innovamos y construimos.</span></h1>
            <nav class="inicio-actions" aria-label="Opciones principales">
                <a class="inicio-btn inicio-btn--primary" href="{{ route('contacto') }}">Cotizar</a>
                <a class="inicio-btn" href="{{ route('proyectos') }}">Proyectos</a>
            </nav>
        </section>

        <section class="inicio-projects" aria-labelledby="inicio-projects-title">
            <h2 class="inicio-section-title" id="inicio-projects-title">Proyectos</h2>
            <div class="inicio-project-grid">
                <a class="inicio-project" href="{{ route('proyectos') }}" aria-label="Proyecto Minero: ver proyectos">
                    <span class="inicio-project-bg inicio-project-bg--first" style="background-image: url('{{ asset('img/mreyze-chancado.jpg') }}')" aria-hidden="true"></span>
                    <span class="inicio-project-bg inicio-project-bg--second" style="background-image: url('{{ asset('img/mreyze-tajo-noche.jpg') }}')" aria-hidden="true"></span>
                    <span class="inicio-project-shade" aria-hidden="true"></span>
                    <span class="inicio-project-name">PROYECTO MINERO</span>
                </a>
                <a class="inicio-project" href="{{ route('proyectos') }}" aria-label="Proyecto Telecomunicación: ver proyectos">
                    <span class="inicio-project-bg inicio-project-bg--first" style="background-image: url('{{ asset('img/mreyze-telecomunicacion-noche.jpg') }}')" aria-hidden="true"></span>
                    <span class="inicio-project-bg inicio-project-bg--second" style="background-image: url('{{ asset('img/mreyze-trabajador.jpg') }}')" aria-hidden="true"></span>
                    <span class="inicio-project-shade" aria-hidden="true"></span>
                    <span class="inicio-project-name">PROYECTO TELECOMUNICACIÓN</span>
                </a>
            </div>
        </section>

        <section class="inicio-store" aria-label="Tienda MReyze">
            @include('sections.partials._productos-destacados')
        </section>
    </div>
</main>
@endsection