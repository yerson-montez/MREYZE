@extends('welcome')

@section('title', 'MREYZE | Soluciones tecnológicas')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@500;700;800&display=swap');

    .mreyze-home {
        position: relative;
        background:
            radial-gradient(circle at top left, rgba(67, 159, 255, 0.20), transparent 32%),
            radial-gradient(circle at bottom right, rgba(31, 100, 210, 0.12), transparent 28%),
            #071420;
        color: #eaf6ff;
        overflow: hidden;
    }

    .mreyze-home * { box-sizing: border-box; }

    .mreyze-shell {
        width: min(1180px, calc(100% - 1.5rem));
        margin: 0 auto;
    }

    .mreyze-kicker {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        padding: .5rem .8rem;
        border-radius: 999px;
        border: 1px solid rgba(130, 185, 255, 0.18);
        background: rgba(12, 25, 38, 0.8);
        color: #8fddff;
        font-size: .7rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        font-weight: 700;
    }

    .mreyze-kicker::before {
        content: "";
        width: .5rem;
        height: .5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #8ad7ff, #5da5ff);
        box-shadow: 0 0 14px rgba(138, 215, 255, .9);
    }

    .mreyze-section {
        position: relative;
        padding: 5.25rem 0;
    }

    .mreyze-section + .mreyze-section {
        border-top: 1px solid rgba(148, 194, 255, 0.08);
    }

    .mreyze-section__header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .mreyze-heading {
        margin: .9rem 0 0;
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        line-height: 1.02;
        letter-spacing: -.06em;
        color: #f3fbff;
        font-family: 'Manrope', sans-serif;
    }

    .mreyze-heading .highlight {
        background: linear-gradient(135deg, #c1ebff, #7ad6ff 32%, #4c9bff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .mreyze-copy {
        color: rgba(218, 232, 245, 0.76);
        line-height: 1.75;
        font-size: 1.04rem;
        max-width: 44rem;
    }

    .mreyze-link {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: #7ad9ff;
        text-decoration: none;
        font-weight: 700;
    }

    .mreyze-hero {
        padding-top: 4.5rem;
        padding-bottom: 3rem;
    }

    .mreyze-hero__grid {
        display: grid;
        grid-template-columns: 1.06fr 0.94fr;
        gap: 2.5rem;
        align-items: center;
        min-height: 42rem;
    }

    .mreyze-hero__title {
        margin: 1rem 0 1.2rem;
        font-size: clamp(2.9rem, 5vw, 5.1rem);
        line-height: .94;
        letter-spacing: -.08em;
        font-family: 'Manrope', sans-serif;
        color: #f2fbff;
    }

    .mreyze-hero__title .block {
        display: block;
    }

    .mreyze-hero__title .gradient {
        background: linear-gradient(135deg, #c5ecff 0%, #7ad6ff 36%, #63a6ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .mreyze-hero__text {
        max-width: 38rem;
        color: rgba(221, 233, 245, 0.8);
        line-height: 1.78;
        font-size: 1.04rem;
        margin: 0;
    }

    .mreyze-hero__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
    }

    .mreyze-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 3.3rem;
        padding: .9rem 1.5rem;
        border-radius: .9rem;
        text-decoration: none;
        font-weight: 700;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .mreyze-btn:hover {
        transform: translateY(-2px);
    }

    .mreyze-btn--primary {
        background: linear-gradient(135deg, #2b77ff, #74dcff);
        color: #061720;
        box-shadow: 0 20px 38px rgba(50, 123, 255, 0.38);
    }

    .mreyze-btn--secondary {
        background: rgba(8, 21, 31, 0.7);
        border: 1px solid rgba(133, 184, 255, 0.18);
        color: #edf9ff;
    }

    .mreyze-hero__stats {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-top: 2.2rem;
        padding-top: 1.6rem;
        border-top: 1px solid rgba(148, 194, 255, 0.1);
    }

    .mreyze-stat {
        min-width: 7.2rem;
    }

    .mreyze-stat__value {
        display: block;
        font-size: clamp(1.6rem, 2vw, 2.3rem);
        font-weight: 800;
        letter-spacing: -.05em;
        color: #f4fbff;
        font-family: 'Manrope', sans-serif;
    }

    .mreyze-stat__label {
        display: block;
        margin-top: .28rem;
        font-size: .63rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(179, 206, 230, 0.76);
    }

    .mreyze-hero__visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 34rem;
    }

    .mreyze-panel {
        position: relative;
        width: min(31rem, 100%);
        background: rgba(9, 21, 32, 0.76);
        border: 1px solid rgba(136, 188, 255, 0.18);
        border-radius: 1.6rem;
        padding: 1.15rem 1rem 1rem;
        box-shadow: 0 30px 80px rgba(1, 8, 16, 0.44);
        backdrop-filter: blur(12px);
        overflow: hidden;
    }

    .mreyze-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(94, 202, 255, 0.08), transparent 28%, rgba(54, 95, 185, 0.12));
        pointer-events: none;
    }

    .mreyze-panel__top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .8rem;
        padding-bottom: .9rem;
        border-bottom: 1px solid rgba(148, 194, 255, 0.1);
    }

    .mreyze-panel__dots {
        display: flex;
        gap: .52rem;
    }

    .mreyze-panel__dots span {
        width: .72rem;
        height: .72rem;
        border-radius: 50%;
        display: block;
    }

    .mreyze-panel__dots span:nth-child(1) { background: #ff655d; }
    .mreyze-panel__dots span:nth-child(2) { background: #f3c854; }
    .mreyze-panel__dots span:nth-child(3) { background: #3dd77d; }

    .mreyze-panel__title {
        position: relative;
        z-index: 1;
        color: rgba(187, 214, 237, 0.8);
        font-size: .72rem;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .mreyze-panel__status {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: .42rem;
        font-size: .65rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #7af0b2;
    }

    .mreyze-panel__status::before {
        content: "";
        width: .5rem;
        height: .5rem;
        border-radius: 50%;
        background: #7af0b2;
        box-shadow: 0 0 14px rgba(122, 240, 178, .8);
    }

    .mreyze-panel__canvas {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 8rem;
        margin-top: 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(122, 168, 226, 0.18);
        background: rgba(5, 17, 27, 0.9);
    }

    .mreyze-panel__metrics {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .8rem;
        margin-top: 1rem;
    }

    .mreyze-mini-card {
        padding: .85rem;
        border-radius: 1rem;
        border: 1px solid rgba(129, 176, 229, 0.1);
        background: rgba(15, 29, 44, 0.8);
    }

    .mreyze-mini-card__label {
        display: block;
        margin-bottom: .42rem;
        font-size: .65rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(186, 214, 236, 0.74);
    }

    .mreyze-mini-card__value {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        line-height: 1.1;
        letter-spacing: -.04em;
        color: #effbff;
    }

    .mreyze-mini-card__value small {
        font-size: .7rem;
        color: #7ad9ff;
    }

    .mreyze-panel__bars {
        position: relative;
        z-index: 1;
        display: grid;
        gap: .7rem;
        margin-top: 1rem;
    }

    .mreyze-bar-row {
        display: grid;
        gap: .28rem;
    }

    .mreyze-bar-row__top {
        display: flex;
        justify-content: space-between;
        gap: .5rem;
        font-size: .64rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(200, 220, 238, 0.78);
    }

    .mreyze-bar-row__track {
        height: .45rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(124, 153, 199, 0.14);
    }

    .mreyze-bar-row__fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #2f74ff, #7fe2ff);
        box-shadow: 0 0 12px rgba(112, 224, 255, .4);
    }

    .mreyze-bar-row__fill--92 { width: 92%; }
    .mreyze-bar-row__fill--87 { width: 87%; }
    .mreyze-bar-row__fill--95 { width: 95%; }

    .mreyze-float-chip {
        position: absolute;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem .8rem;
        border-radius: .9rem;
        color: #eaf7ff;
        border: 1px solid rgba(126, 176, 235, 0.18);
        background: rgba(6, 17, 26, 0.8);
        box-shadow: 0 20px 40px rgba(1, 6, 12, 0.34);
        font-size: .67rem;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .mreyze-float-chip--top { top: .6rem; right: -1.1rem; }
    .mreyze-float-chip--bottom { bottom: .8rem; left: -1.3rem; }

    .mreyze-trust {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .mreyze-trust__grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .mreyze-trust__item {
        padding: 1.1rem 1rem;
        border-radius: 1.2rem;
        border: 1px solid rgba(129, 176, 229, 0.1);
        background: rgba(8, 22, 34, 0.62);
    }

    .mreyze-trust__label {
        display: block;
        margin-bottom: .6rem;
        color: rgba(183, 211, 234, 0.72);
        letter-spacing: .12em;
        text-transform: uppercase;
        font-size: .66rem;
    }

    .mreyze-trust__value {
        display: block;
        color: #f3fbff;
        font-size: clamp(1.5rem, 2vw, 2rem);
        font-weight: 800;
        letter-spacing: -.05em;
        font-family: 'Manrope', sans-serif;
    }

    .mreyze-need__grid,
    .mreyze-solution__grid,
    .mreyze-project__grid,
    .mreyze-process__grid,
    .mreyze-difference__grid {
        display: grid;
        gap: 1.2rem;
    }

    .mreyze-need__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .mreyze-solution__grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .mreyze-project__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .mreyze-process__grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .mreyze-difference__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }

    .mreyze-need-card,
    .mreyze-solution,
    .mreyze-step,
    .mreyze-project,
    .mreyze-difference-card {
        background: rgba(8, 22, 34, 0.62);
        border: 1px solid rgba(131, 174, 229, 0.12);
        border-radius: 1.3rem;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .mreyze-need-card:hover,
    .mreyze-solution:hover,
    .mreyze-step:hover,
    .mreyze-project:hover,
    .mreyze-difference-card:hover {
        transform: translateY(-4px);
        border-color: rgba(122, 215, 255, 0.32);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
    }

    .mreyze-need-card {
        display: block;
        padding: 1.35rem 1.2rem;
        text-decoration: none;
        color: inherit;
    }

    .mreyze-need-card__icon,
    .mreyze-solution__icon {
        display: inline-grid;
        place-items: center;
        width: 3rem;
        height: 3rem;
        border-radius: .8rem;
        background: rgba(92, 181, 255, 0.09);
        border: 1px solid rgba(109, 189, 255, 0.18);
        color: #8ad7ff;
        margin-bottom: 1rem;
    }

    .mreyze-need-card__title,
    .mreyze-solution__title,
    .mreyze-step__title,
    .mreyze-project__title {
        margin: 0 0 .55rem;
        color: #f3fbff;
        letter-spacing: -.03em;
    }

    .mreyze-need-card__title { font-size: 1.2rem; }
    .mreyze-solution__title { font-size: 1.2rem; }
    .mreyze-step__title { font-size: 1.1rem; }
    .mreyze-project__title { font-size: 1.45rem; }

    .mreyze-need-card p,
    .mreyze-solution__desc,
    .mreyze-step p,
    .mreyze-project__desc,
    .mreyze-difference-card p,
    .mreyze-contact-panel p {
        margin: 0;
        line-height: 1.75;
        color: rgba(222, 233, 244, 0.76);
    }

    .mreyze-solution {
        display: block;
        padding: 1.5rem 1.2rem;
        text-decoration: none;
        color: inherit;
    }

    .mreyze-solution__tags {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-top: 1rem;
    }

    .mreyze-solution__tags span {
        display: inline-flex;
        padding: .33rem .7rem;
        border-radius: 999px;
        background: rgba(14, 28, 39, 0.82);
        border: 1px solid rgba(122, 176, 240, 0.1);
        color: #dceeff;
        font-size: .62rem;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .mreyze-project {
        display: block;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
    }

    .mreyze-project__media {
        position: relative;
        display: grid;
        place-items: center;
        min-height: 14rem;
        background: linear-gradient(135deg, rgba(34, 87, 175, 0.45), rgba(8, 19, 31, 0.82));
    }

    .mreyze-project__media::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(122, 214, 255, 0.2), transparent 38%);
    }

    .mreyze-project__icon {
        position: relative;
        z-index: 1;
        display: grid;
        place-items: center;
        width: 4.5rem;
        height: 4.5rem;
        border-radius: 1rem;
        background: rgba(8, 20, 30, 0.7);
        border: 1px solid rgba(146, 201, 255, 0.2);
        color: #8ad7ff;
        font-size: 1.8rem;
    }

    .mreyze-project__body {
        padding: 1.15rem 1.1rem 1.3rem;
    }

    .mreyze-project__eyebrow {
        display: inline-block;
        margin-bottom: .8rem;
        color: #7ad9ff;
        font-size: .66rem;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .mreyze-project__meta {
        display: flex;
        flex-wrap: wrap;
        gap: .45rem;
        margin: 1rem 0 .9rem;
    }

    .mreyze-project__meta span {
        padding: .38rem .7rem;
        border-radius: 999px;
        background: rgba(13, 27, 39, 0.85);
        border: 1px solid rgba(127, 169, 225, 0.12);
        color: #dfeeff;
        font-size: .62rem;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .mreyze-project__link {
        color: #89e1ff;
        font-weight: 700;
        text-decoration: none;
    }

    .mreyze-step {
        padding: 1.4rem 1rem 1.2rem;
    }

    .mreyze-step__index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.4rem;
        height: 2.4rem;
        margin-bottom: 1rem;
        border-radius: .8rem;
        color: #8ad7ff;
        border: 1px solid rgba(126, 191, 255, 0.18);
        background: rgba(99, 175, 255, 0.08);
        font-weight: 800;
    }

    .mreyze-difference-card {
        padding: 1.4rem 1.2rem;
    }

    .mreyze-difference-card__number {
        display: block;
        font-size: 2.1rem;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -.06em;
        color: #f3fbff;
        font-family: 'Manrope', sans-serif;
        margin-bottom: .5rem;
    }

    .mreyze-contact {
        padding-top: 3.5rem;
        padding-bottom: 5rem;
    }

    .mreyze-contact__wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        padding: clamp(1.5rem, 3vw, 2.4rem);
        border-radius: 1.6rem;
        background: linear-gradient(135deg, rgba(17, 36, 54, 0.95), rgba(8, 19, 30, 0.98));
        border: 1px solid rgba(130, 184, 255, 0.12);
        box-shadow: 0 28px 60px rgba(0, 0, 0, 0.18);
    }

    .mreyze-contact__wrap h3 {
        margin: 0 0 .45rem;
        font-size: clamp(2rem, 3vw, 2.8rem);
        line-height: 1.08;
        letter-spacing: -.06em;
        color: #f4fbff;
        font-family: 'Manrope', sans-serif;
    }

    .mreyze-contact__wrap p {
        margin: 0;
        max-width: 42rem;
        color: rgba(220, 232, 244, 0.8);
        line-height: 1.7;
    }

    @media (max-width: 1024px) {
        .mreyze-hero__grid {
            min-height: auto;
        }

        .mreyze-hero__visual {
            min-height: clamp(24rem, 70vw, 34rem);
        }

        .mreyze-hero__grid,
        .mreyze-difference__grid {
            grid-template-columns: 1fr;
        }

        .mreyze-solution__grid,
        .mreyze-process__grid,
        .mreyze-project__grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .mreyze-section { padding: 4.2rem 0; }
        .mreyze-hero__visual { min-height: 0; padding-block: 1.5rem; }

        .mreyze-hero__actions,
        .mreyze-hero__stats {
            flex-direction: column;
            align-items: flex-start;
        }

        .mreyze-btn {
            width: 100%;
        }

        .mreyze-need__grid,
        .mreyze-solution__grid,
        .mreyze-project__grid,
        .mreyze-process__grid,
        .mreyze-difference__grid,
        .mreyze-trust__grid {
            grid-template-columns: 1fr;
        }

        .mreyze-panel__metrics {
            grid-template-columns: 1fr 1fr;
        }

        .mreyze-contact__wrap {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<style>
    /* Mobile app composition: intentionally different from desktop. */
    .mreyze-hero {
        isolation: isolate;
        position: relative;
    }
    .mreyze-hero::before,
    .mreyze-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
    }
    .mreyze-hero::before {
        z-index: 0;
        opacity: .48;
        background-image: url('/img/fondo1.png');
        background-size: cover;
        background-position: 68% center;
        filter: saturate(.88) brightness(.7);
    }
    .mreyze-hero::after {
        z-index: 1;
        background: linear-gradient(90deg, rgba(7, 21, 34, .98) 0%, rgba(7, 21, 34, .9) 40%, rgba(7, 21, 34, .52) 78%, rgba(7, 21, 34, .78) 100%);
    }
    .mreyze-hero__grid { position: relative; z-index: 2; }

    @media (max-width: 767px) {
        .mreyze-home { padding-bottom: 1rem; }
        .mreyze-hero::before {
            z-index: 0;
            opacity: .78;
            background-image: url('/img/fondo1.png');
            background-size: cover;
            background-position: 62% bottom;
            filter: saturate(.92) brightness(.82);
        }
        .mreyze-hero::after {
            z-index: 1;
            background:
                linear-gradient(180deg, #071522 0%, rgba(7, 21, 34, .98) 23%, rgba(7, 21, 34, .78) 48%, rgba(7, 21, 34, .32) 73%, #071522 100%),
                radial-gradient(circle at 70% 65%, rgba(20, 216, 255, .12), transparent 40%);
        }
        .mreyze-shell { width: min(100% - 2rem, 36rem); }
        .mreyze-section { padding: 3.5rem 0; }

        .mreyze-hero { padding: 2rem 0 1.25rem; }
        .mreyze-hero__grid {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 0;
            min-height: 0;
        }
        .mreyze-hero__grid > div:first-child { display: contents; }
        .mreyze-hero .mreyze-kicker { order: 1; align-self: flex-start; margin-bottom: 1rem; font-size: .62rem; }
        .mreyze-hero__title { order: 2; margin: 0; font-size: clamp(2.1rem, 9vw, 3.2rem); line-height: .96; letter-spacing: -.07em; }
        .mreyze-hero__text { order: 3; margin-top: 1rem; font-size: .94rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
        .mreyze-hero__actions { order: 4; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .65rem; width: min(100%, 22rem); margin: 1.25rem auto 0; }
        .mreyze-hero__actions .mreyze-btn { width: 100%; min-width: 0; min-height: 46px; padding: .65rem .45rem; font-size: .72rem; }
        .mreyze-hero__visual { order: 5; min-height: 0; padding: 1.5rem 0 .8rem; }
        .mreyze-panel { width: min(100%, 22rem); padding: .8rem; border-radius: 1.1rem; }
        .mreyze-panel__title { font-size: .6rem; }
        .mreyze-panel__status { font-size: .57rem; }
        .mreyze-panel__canvas { height: 5.4rem; margin-top: .7rem; }
        .mreyze-panel__metrics { gap: .5rem; margin-top: .65rem; }
        .mreyze-mini-card { padding: .65rem; border-radius: .75rem; }
        .mreyze-mini-card__label { font-size: .56rem; }
        .mreyze-mini-card__value { font-size: 1.15rem; }
        .mreyze-panel__bars { gap: .5rem; margin-top: .7rem; }
        .mreyze-bar-row__top { font-size: .55rem; }
        .mreyze-bar-row__track { height: .35rem; }
        .mreyze-float-chip { display: none; }

        .mreyze-hero__stats {
            order: 6;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .45rem;
            margin-top: .75rem;
            padding: .65rem;
            border: 1px solid rgba(100, 180, 255, .14);
            border-radius: 1rem;
            background: rgba(13, 31, 49, .78);
        }
        .mreyze-stat { min-width: 0; text-align: center; }
        .mreyze-stat__value { font-size: 1.15rem; }
        .mreyze-stat__label { font-size: .5rem; letter-spacing: .06em; }

        .mreyze-trust { padding: 1rem 0 0; }
        .mreyze-trust__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .55rem; }
        .mreyze-trust__item { padding: .75rem; border-radius: .85rem; }
        .mreyze-trust__label { font-size: .55rem; margin-bottom: .3rem; }
        .mreyze-trust__value { font-size: 1.05rem; }

        .mreyze-section__header { align-items: flex-start; margin-bottom: 1.25rem; }
        .mreyze-heading { margin-top: .65rem; font-size: clamp(1.6rem, 6vw, 2.2rem); line-height: 1.05; }
        .mreyze-section__header .mreyze-link { font-size: .75rem; }

        .mreyze-need__grid { grid-template-columns: 1fr; gap: .6rem; }
        .mreyze-need-card { min-height: 5rem; display: grid; grid-template-columns: 2.5rem 1fr auto; gap: .7rem; align-items: center; padding: .75rem; border-radius: .85rem; }
        .mreyze-need-card__icon { width: 2.5rem; height: 2.5rem; margin: 0; font-size: 0; }
        .mreyze-need-card__icon::after { content: '→'; font-size: 1.2rem; color: #72dcff; }
        .mreyze-need-card__title { margin: 0; font-size: .86rem; }
        .mreyze-need-card p { grid-column: 2 / -1; margin-top: -.35rem; font-size: .7rem; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        .mreyze-solution__grid { grid-template-columns: 1fr; gap: .75rem; }
        .mreyze-solution { min-height: 0; display: grid; grid-template-columns: 3rem 1fr auto; gap: .75rem; align-items: center; padding: .9rem; border-radius: .9rem; }
        .mreyze-solution__icon { width: 3rem; height: 3rem; margin: 0; grid-row: span 2; }
        .mreyze-solution__title { margin: 0; font-size: .95rem; }
        .mreyze-solution__desc { font-size: .72rem; line-height: 1.4; margin: 0; }
        .mreyze-solution__tags { grid-column: 2 / -1; margin: 0; }
        .mreyze-solution__tags span { font-size: .52rem; padding: .25rem .45rem; }

        .mreyze-project__grid, .mreyze-process__grid, .mreyze-difference__grid { grid-template-columns: 1fr; gap: .75rem; }
        .mreyze-project__media { min-height: 9rem; }
        .mreyze-project__body { padding: .85rem; }
        .mreyze-project__desc { font-size: .75rem; }
        .mreyze-step, .mreyze-difference-card { padding: 1rem; }
        .mreyze-contact__wrap { padding: 1.1rem; border-radius: 1rem; }
        .mreyze-contact__wrap h3 { font-size: 1.65rem; }
        .mreyze-contact__wrap .mreyze-btn { width: 100%; }
    }

    @media (max-width: 360px) {
        .mreyze-shell { width: min(100% - 1.5rem, 36rem); }
        .mreyze-hero__stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .mreyze-stat:last-child { grid-column: 1 / -1; }
        .mreyze-panel__bars { display: none; }
    }
</style>

<main class="mreyze-home">
    <section class="mreyze-section mreyze-hero">
        <div class="mreyze-shell mreyze-hero__grid">
            <div>
                <span class="mreyze-kicker">Ingeniería · Automatización · Software</span>

                <h1 class="mreyze-hero__title">
                    <span class="block">Tecnología que</span>
                    <span class="block gradient">transforma</span>
                    <span class="block">operaciones reales.</span>
                </h1>

                <p class="mreyze-hero__text">
                    MREYZE crea soluciones integradas de automatización, IoT, software y estrategia para empresas que necesitan crecer con procesos más inteligentes, medibles y confiables.
                </p>

                <div class="mreyze-hero__actions">
                    <a href="{{ route('contacto') }}" class="mreyze-btn mreyze-btn--primary">Solicitar cotización</a>
                    <a href="{{ route('servicios') }}" class="mreyze-btn mreyze-btn--secondary">Ver soluciones</a>
                </div>

                <div class="mreyze-hero__stats">
                    <div class="mreyze-stat">
                        <span class="mreyze-stat__value">+50%</span>
                        <span class="mreyze-stat__label">eficiencia</span>
                    </div>
                    <div class="mreyze-stat">
                        <span class="mreyze-stat__value">24/7</span>
                        <span class="mreyze-stat__label">monitoreo</span>
                    </div>
                    <div class="mreyze-stat">
                        <span class="mreyze-stat__value">100%</span>
                        <span class="mreyze-stat__label">a medida</span>
                    </div>
                </div>
            </div>

            <div class="mreyze-hero__visual">
                <div class="mreyze-float-chip mreyze-float-chip--top">Industria 4.0</div>

                <div class="mreyze-panel">
                    <div class="mreyze-panel__top">
                        <div class="mreyze-panel__dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="mreyze-panel__title">mreyze_flow_v2</div>
                        <div class="mreyze-panel__status">Online</div>
                    </div>

                    <canvas class="mreyze-panel__canvas" id="mreyze-graph-canvas"></canvas>

                    <div class="mreyze-panel__metrics">
                        <div class="mreyze-mini-card">
                            <span class="mreyze-mini-card__label">PLC</span>
                            <div class="mreyze-mini-card__value">92<small>%</small></div>
                        </div>
                        <div class="mreyze-mini-card">
                            <span class="mreyze-mini-card__label">IoT</span>
                            <div class="mreyze-mini-card__value">4<small>ms</small></div>
                        </div>
                    </div>

                    <div class="mreyze-panel__bars">
                        <div class="mreyze-bar-row">
                            <div class="mreyze-bar-row__top"><span>Automatización</span><span>92%</span></div>
                            <div class="mreyze-bar-row__track"><div class="mreyze-bar-row__fill mreyze-bar-row__fill--92"></div></div>
                        </div>
                        <div class="mreyze-bar-row">
                            <div class="mreyze-bar-row__top"><span>IoT / sensores</span><span>87%</span></div>
                            <div class="mreyze-bar-row__track"><div class="mreyze-bar-row__fill mreyze-bar-row__fill--87"></div></div>
                        </div>
                        <div class="mreyze-bar-row">
                            <div class="mreyze-bar-row__top"><span>Software / cloud</span><span>95%</span></div>
                            <div class="mreyze-bar-row__track"><div class="mreyze-bar-row__fill mreyze-bar-row__fill--95"></div></div>
                        </div>
                    </div>
                </div>

                <div class="mreyze-float-chip mreyze-float-chip--bottom">Cloud + API</div>
            </div>
        </div>
    </section>

    <section class="mreyze-section mreyze-trust">
        <div class="mreyze-shell">
            <div class="mreyze-trust__grid">
                <div class="mreyze-trust__item">
                    <span class="mreyze-trust__label">Soluciones</span>
                    <span class="mreyze-trust__value">Industrial</span>
                </div>
                <div class="mreyze-trust__item">
                    <span class="mreyze-trust__label">Tecnología</span>
                    <span class="mreyze-trust__value">IoT</span>
                </div>
                <div class="mreyze-trust__item">
                    <span class="mreyze-trust__label">Sistemas</span>
                    <span class="mreyze-trust__value">Embebidos</span>
                </div>
                <div class="mreyze-trust__item">
                    <span class="mreyze-trust__label">Integración</span>
                    <span class="mreyze-trust__value">Cloud</span>
                </div>
            </div>
        </div>
    </section>

    <section class="mreyze-section">
        <div class="mreyze-shell">
            <div class="mreyze-section__header">
                <div>
                    <span class="mreyze-kicker">Necesidades reales</span>
                    <h2 class="mreyze-heading">La tecnología que tu operación necesita <span class="highlight">hoy</span></h2>
                </div>
            </div>

            <div class="mreyze-need__grid">
                <a href="{{ route('servicios.automatizacion') }}" class="mreyze-need-card">
                    <div class="mreyze-need-card__icon"><x-ui.icon><path d="M12 3v3m0 12v3M3 12h3m12 0h3m-4.64-6.36-2.12 2.12m-4.48 4.48-2.12 2.12m0-8.72 2.12 2.12m4.48 4.48 2.12 2.12M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></x-ui.icon></div>
                    <h3 class="mreyze-need-card__title">Automatización industrial</h3>
                    <p>Reduce tiempos, mejora control y optimiza procesos críticos con soluciones integradas y fácilmente escalables.</p>
                </a>

                <a href="{{ route('servicios.iot') }}" class="mreyze-need-card">
                    <div class="mreyze-need-card__icon"><x-ui.icon><path d="M5 12a7 7 0 0 1 14 0M8 12a4 4 0 0 1 8 0M11 12a1 1 0 0 1 2 0m-1 4v5" /></x-ui.icon></div>
                    <h3 class="mreyze-need-card__title">Monitoreo inteligente</h3>
                    <p>Recolecta y analiza indicadores en tiempo real para responder antes, con mayor precisión y menos riesgo operativo.</p>
                </a>

                <a href="{{ route('servicios.desarrollo') }}" class="mreyze-need-card">
                    <div class="mreyze-need-card__icon"><x-ui.icon><rect x="3" y="4" width="18" height="13" rx="2" /><path d="M8 21h8m-4-4v4" /></x-ui.icon></div>
                    <h3 class="mreyze-need-card__title">Software a medida</h3>
                    <p>Diseñamos plataformas, dashboards y herramientas digitales que acompañan decisiones, procesos y crecimiento.</p>
                </a>
            </div>
        </div>
    </section>

    <section class="mreyze-section">
        <div class="mreyze-shell">
            <div class="mreyze-section__header">
                <div>
                    <span class="mreyze-kicker">Soluciones</span>
                    <h2 class="mreyze-heading">Todo para una empresa <span class="highlight">más eficiente</span></h2>
                </div>
                <a href="{{ route('servicios') }}" class="mreyze-link">Ver todas las soluciones →</a>
            </div>

            <div class="mreyze-solution__grid">
                <a href="{{ route('servicios.automatizacion') }}" class="mreyze-solution">
                    <div class="mreyze-solution__icon"><x-ui.icon><path d="M12 3v3m0 12v3M3 12h3m12 0h3m-4.64-6.36-2.12 2.12m-4.48 4.48-2.12 2.12m0-8.72 2.12 2.12m4.48 4.48 2.12 2.12M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></x-ui.icon></div>
                    <h3 class="mreyze-solution__title">Automatización</h3>
                    <p class="mreyze-solution__desc">Integración de procesos, control y optimización operativa con enfoque industrial.</p>
                    <div class="mreyze-solution__tags"><span>PLC</span><span>SCADA</span><span>Control</span></div>
                </a>

                <a href="{{ route('servicios.iot') }}" class="mreyze-solution">
                    <div class="mreyze-solution__icon"><x-ui.icon><path d="M5 12a7 7 0 0 1 14 0M8 12a4 4 0 0 1 8 0M11 12a1 1 0 0 1 2 0m-1 4v5" /></x-ui.icon></div>
                    <h3 class="mreyze-solution__title">IoT &amp; sensores</h3>
                    <p class="mreyze-solution__desc">Conectividad, telemetría y monitoreo de activos con datos accionables en tiempo real.</p>
                    <div class="mreyze-solution__tags"><span>ESP32</span><span>MQTT</span><span>IoT</span></div>
                </a>

                <a href="{{ route('servicios.desarrollo') }}" class="mreyze-solution">
                    <div class="mreyze-solution__icon"><x-ui.icon><path d="M9 3h6v4h4v6h-4v4H9v-4H5V7h4V3Z" /></x-ui.icon></div>
                    <h3 class="mreyze-solution__title">Software</h3>
                    <p class="mreyze-solution__desc">Sistemas web, APIs y dashboards que conectan equipos, datos y decisiones.</p>
                    <div class="mreyze-solution__tags"><span>Web</span><span>API</span><span>Dashboard</span></div>
                </a>

                <a href="{{ route('servicios.consultoria') }}" class="mreyze-solution">
                    <div class="mreyze-solution__icon"><x-ui.icon><path d="M9.5 4a3.5 3.5 0 0 0-3.4 4.35A3.5 3.5 0 0 0 7 15.2V18a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2.8a3.5 3.5 0 0 0 .9-6.85A3.5 3.5 0 0 0 14.5 4h-5Z" /><path d="M9 20h6" /></x-ui.icon></div>
                    <h3 class="mreyze-solution__title">Consultoría</h3>
                    <p class="mreyze-solution__desc">Arquitectura, estrategia y acompañamiento para transformar ideas en soluciones viables.</p>
                    <div class="mreyze-solution__tags"><span>Arquitectura</span><span>Infra</span><span>Soporte</span></div>
                </a>
            </div>
        </div>
    </section>

    <section class="mreyze-section">
        <div class="mreyze-shell">
            <div class="mreyze-section__header">
                <div>
                    <span class="mreyze-kicker">Proyecto</span>
                    <h2 class="mreyze-heading">Casos de éxito con <span class="highlight">impacto real</span></h2>
                </div>
                <a href="{{ route('proyectos') }}" class="mreyze-link">Ver todos los proyectos →</a>
            </div>

            <div class="mreyze-project__grid">
                <a href="{{ route('proyectos') }}" class="mreyze-project">
                    <div class="mreyze-project__media"><div class="mreyze-project__icon"><x-ui.icon><path d="M12 3v3m0 12v3M3 12h3m12 0h3m-4.64-6.36-2.12 2.12m-4.48 4.48-2.12 2.12m0-8.72 2.12 2.12m4.48 4.48 2.12 2.12M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></x-ui.icon></div></div>
                    <div class="mreyze-project__body">
                        <span class="mreyze-project__eyebrow">Automatización</span>
                        <h3 class="mreyze-project__title">Control industrial</h3>
                        <p class="mreyze-project__desc">Optimización de procesos con trazabilidad, control y mejor estabilidad operativa.</p>
                        <div class="mreyze-project__meta"><span>PLC</span><span>SCADA</span></div>
                        <span class="mreyze-project__link">Explorar proyecto →</span>
                    </div>
                </a>

                <a href="{{ route('proyectos') }}" class="mreyze-project">
                    <div class="mreyze-project__media"><div class="mreyze-project__icon"><x-ui.icon><path d="M5 12a7 7 0 0 1 14 0M8 12a4 4 0 0 1 8 0M11 12a1 1 0 0 1 2 0m-1 4v5" /></x-ui.icon></div></div>
                    <div class="mreyze-project__body">
                        <span class="mreyze-project__eyebrow">IoT</span>
                        <h3 class="mreyze-project__title">Telemetría conectada</h3>
                        <p class="mreyze-project__desc">Sistema de monitoreo en tiempo real para análisis, mantenimiento y toma de decisiones.</p>
                        <div class="mreyze-project__meta"><span>MQTT</span><span>ESP32</span></div>
                        <span class="mreyze-project__link">Explorar proyecto →</span>
                    </div>
                </a>

                <a href="{{ route('proyectos') }}" class="mreyze-project">
                    <div class="mreyze-project__media"><div class="mreyze-project__icon"><x-ui.icon><rect x="3" y="6" width="18" height="13" rx="2" /><path d="M8 6V4h8v2m-13 5h18" /></x-ui.icon></div></div>
                    <div class="mreyze-project__body">
                        <span class="mreyze-project__eyebrow">Software</span>
                        <h3 class="mreyze-project__title">Plataforma digital</h3>
                        <p class="mreyze-project__desc">Solución web para integración, gestión y mayor visibilidad de procesos operativos.</p>
                        <div class="mreyze-project__meta"><span>Laravel</span><span>API</span></div>
                        <span class="mreyze-project__link">Explorar proyecto →</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="mreyze-section">
        <div class="mreyze-shell">
            <div class="mreyze-section__header">
                <div>
                    <span class="mreyze-kicker">Proceso</span>
                    <h2 class="mreyze-heading">Cómo entregamos valor <span class="highlight">sin fricción</span></h2>
                </div>
            </div>

            <div class="mreyze-process__grid">
                <div class="mreyze-step">
                    <div class="mreyze-step__index">01</div>
                    <h3 class="mreyze-step__title">Diagnóstico</h3>
                    <p>Analizamos objetivos, operación, limitaciones y oportunidades para crear la base correcta.</p>
                </div>

                <div class="mreyze-step">
                    <div class="mreyze-step__index">02</div>
                    <h3 class="mreyze-step__title">Diseño</h3>
                    <p>Definimos arquitectura, flujo de información y solución técnica orientada a resultados medibles.</p>
                </div>

                <div class="mreyze-step">
                    <div class="mreyze-step__index">03</div>
                    <h3 class="mreyze-step__title">Desarrollo</h3>
                    <p>Construimos sistemas con tecnologías modernas, pensando en escalabilidad y mantenimiento.</p>
                </div>

                <div class="mreyze-step">
                    <div class="mreyze-step__index">04</div>
                    <h3 class="mreyze-step__title">Implementación</h3>
                    <p>Validamos, desplegamos y acompañamos la ejecución para asegurar una integración real y estable.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mreyze-section">
        <div class="mreyze-shell">
            <div class="mreyze-section__header">
                <div>
                    <span class="mreyze-kicker">Nuestra diferencia</span>
                    <h2 class="mreyze-heading">Más que tecnología: <span class="highlight">solución estratégica</span></h2>
                </div>
            </div>

            <div class="mreyze-difference__grid">
                <div class="mreyze-difference-card">
                    <span class="mreyze-difference-card__number">01</span>
                    <h3 class="mreyze-step__title">Enfoque en resultados</h3>
                    <p>Cada decisión técnica se vincula a un objetivo empresarial claro, medible y relevante.</p>
                </div>

                <div class="mreyze-difference-card">
                    <span class="mreyze-difference-card__number">02</span>
                    <h3 class="mreyze-step__title">Integración completa</h3>
                    <p>Unimos automatización, electrónica, software y datos para trabajar con un mismo sistema.</p>
                </div>

                <div class="mreyze-difference-card">
                    <span class="mreyze-difference-card__number">03</span>
                    <h3 class="mreyze-step__title">Ejecución real</h3>
                    <p>Implementamos soluciones revisadas, probadas y diseñadas para sostenerse en la práctica diaria.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mreyze-section mreyze-contact">
        <div class="mreyze-shell">
            <div class="mreyze-contact__wrap">
                <div>
                    <h3>¿Listo para transformar tu operación?</h3>
                    <p>Hablemos sobre tu proceso, tus objetivos y la mejor forma de llevarlo a una solución tecnológica útil y escalable.</p>
                </div>
                <a href="{{ route('contacto') }}" class="mreyze-btn mreyze-btn--primary">Hablar con MREYZE</a>
            </div>
        </div>
    </section>

    <section class="mreyze-section" style="padding-top:0;">
        <div class="mreyze-shell">
            @include('sections.partials._productos-destacados')
        </div>
    </section>
</main>

<script>
    const graphCanvas = document.getElementById('mreyze-graph-canvas');

    if (graphCanvas) {
        const ctx = graphCanvas.getContext('2d');

        function renderGraph() {
            const rect = graphCanvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;
            graphCanvas.width = rect.width * ratio;
            graphCanvas.height = rect.height * ratio;
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            const w = rect.width;
            const h = rect.height;

            ctx.clearRect(0, 0, w, h);

            const grad = ctx.createLinearGradient(0, 0, w, 0);
            grad.addColorStop(0, 'rgba(91, 185, 255, 0.12)');
            grad.addColorStop(1, 'rgba(123, 227, 255, 0.9)');

            ctx.beginPath();
            ctx.moveTo(8, h - 22);
            ctx.lineTo(52, h - 46);
            ctx.lineTo(108, h - 60);
            ctx.lineTo(168, h - 36);
            ctx.lineTo(220, h - 52);
            ctx.lineTo(278, h - 24);
            ctx.lineTo(w - 10, h - 18);
            ctx.lineTo(w - 10, h - 8);
            ctx.lineTo(8, h - 8);
            ctx.closePath();
            ctx.fillStyle = 'rgba(98, 210, 255, 0.1)';
            ctx.fill();

            ctx.beginPath();
            ctx.moveTo(8, h - 22);
            ctx.lineTo(52, h - 46);
            ctx.lineTo(108, h - 60);
            ctx.lineTo(168, h - 36);
            ctx.lineTo(220, h - 52);
            ctx.lineTo(278, h - 24);
            ctx.lineTo(w - 10, h - 18);
            ctx.strokeStyle = grad;
            ctx.lineWidth = 2.2;
            ctx.stroke();

            for (let x = 0; x < w; x += 20) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, h);
                ctx.strokeStyle = 'rgba(136, 184, 255, 0.08)';
                ctx.stroke();
            }
        }

        renderGraph();
        window.addEventListener('resize', renderGraph);
    }
</script>

@endsection