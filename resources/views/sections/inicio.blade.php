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
    .inicio-hero { min-height: 46vh; display: grid; align-content: center; justify-items: start; padding: 2rem 0 4.5rem; }
    .inicio-title { max-width: 900px; margin: 0; font: 800 clamp(2.8rem, 8vw, 6.4rem)/.98 'Manrope', 'Inter', sans-serif; letter-spacing: -.065em; }
    .inicio-title span { color: var(--inicio-accent); }
    .inicio-actions { display: flex; flex-wrap: wrap; gap: .85rem; margin-top: 2.2rem; }
    .inicio-btn { display: inline-flex; min-height: 3.25rem; min-width: 9rem; align-items: center; justify-content: center; padding: .85rem 1.4rem; border: 1px solid rgba(145, 205, 220, .4); border-radius: .65rem; color: var(--inicio-text); background: rgba(255,255,255,.04); text-decoration: none; font: 700 .95rem/1 'Inter', sans-serif; transition: transform .2s ease, background .2s ease, box-shadow .2s ease; }
    .inicio-btn:hover { transform: translateY(-2px); background: rgba(98,213,238,.12); box-shadow: 0 10px 28px rgba(0,0,0,.22); }
    .inicio-btn--primary { border-color: var(--inicio-accent); color: #062027; background: var(--inicio-accent); }
    .inicio-btn--primary:hover { background: #8be7f7; }
    .inicio-projects { padding-top: 1.5rem; }
    .inicio-section-title { margin: 0 0 1.5rem; font: 700 clamp(1.5rem, 3vw, 2.1rem)/1.15 'Manrope', 'Inter', sans-serif; letter-spacing: -.035em; }
    .inicio-project-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; }
    .inicio-project { display: block; overflow: hidden; border: 1px solid rgba(145,205,220,.18); border-radius: 1rem; color: inherit; background: #0b2028; text-decoration: none; transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease; }
    .inicio-project:hover { transform: translateY(-4px); border-color: rgba(98,213,238,.62); box-shadow: 0 18px 42px rgba(0,0,0,.3); }
    .inicio-project-name { display: block; padding: 1rem 1.1rem .8rem; font: 700 1.12rem/1.25 'Manrope', 'Inter', sans-serif; }
    .inicio-project-images { display: grid; grid-template-columns: 1.35fr 1fr; gap: .55rem; padding: 0 .65rem .65rem; }
    .inicio-project-images img { display: block; width: 100%; height: clamp(150px, 22vw, 245px); border-radius: .55rem; object-fit: cover; transition: transform .35s ease; }
    .inicio-project:hover .inicio-project-images img { transform: scale(1.025); }
    .inicio-project-images img + img { object-position: center; }
    @media (max-width: 680px) {
        .inicio-mreyze { padding: 3rem 1rem 3.5rem; }
        .inicio-hero { min-height: 42vh; padding-bottom: 3.5rem; }
        .inicio-project-grid { grid-template-columns: 1fr; gap: 1rem; }
        .inicio-project-images { grid-template-columns: 1fr 1fr; }
        .inicio-project-images img { height: clamp(145px, 42vw, 220px); }
        .inicio-btn { flex: 1 1 9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .inicio-btn, .inicio-project, .inicio-project-images img { transition: none; }
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
                    <span class="inicio-project-name">Proyecto Minero</span>
                    <span class="inicio-project-images">
                        <img src="{{ asset('img/mreyze-chancado.jpg') }}" alt="Planta de chancado" loading="lazy">
                        <img src="{{ asset('img/mreyze-tajo-noche.jpg') }}" alt="Tajo minero de noche" loading="lazy">
                    </span>
                </a>
                <a class="inicio-project" href="{{ route('proyectos') }}" aria-label="Proyecto Telecomunicación: ver proyectos">
                    <span class="inicio-project-name">Proyecto Telecomunicación</span>
                    <span class="inicio-project-images">
                        <img src="{{ asset('img/mreyze-telecomunicacion-noche.jpg') }}" alt="Antena de telecomunicación al atardecer" loading="lazy">
                        <img src="{{ asset('img/mreyze-trabajador.jpg') }}" alt="Técnico trabajando en una instalación" loading="lazy">
                    </span>
                </a>
            </div>
        </section>
    </div>
</main>
@endsection
