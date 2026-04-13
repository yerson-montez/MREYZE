{{-- resources/views/sections/proyectos.blade.php --}}
{{-- Uso: @include('sections.proyectos') --}}

<style>
    /* ═══════════════════════════════════════
       PROYECTOS SECTION
    ═══════════════════════════════════════ */
    .proyectos {
        padding: 7rem 2rem;
        background: var(--ice-900);
        position: relative;
        overflow: hidden;
    }

    .proyectos::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .proyectos-bg {
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 50% 100%, rgba(30,77,153,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    .proyectos-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .proyectos-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .proyectos-header-left {}

    .proyectos-ver-todos {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.3rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
        border: 1px solid rgba(0,212,255,0.3);
        text-decoration: none;
        transition: all 0.25s;
        white-space: nowrap;
    }

    .proyectos-ver-todos svg { width: 15px; height: 15px; transition: transform 0.2s; }

    .proyectos-ver-todos:hover {
        background: rgba(0,212,255,0.1);
        border-color: rgba(0,212,255,0.5);
    }

    .proyectos-ver-todos:hover svg { transform: translateX(4px); }

    /* Filtros */
    .proyectos-filtros {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .filtro-btn {
        padding: 0.45rem 1.1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(74,144,217,0.2);
        background: transparent;
        color: var(--ice-300);
        transition: all 0.22s;
        letter-spacing: 0.04em;
    }

    .filtro-btn:hover {
        border-color: rgba(74,144,217,0.5);
        color: var(--ice-100);
    }

    .filtro-btn.active {
        background: rgba(37,99,199,0.3);
        border-color: rgba(0,212,255,0.5);
        color: var(--accent);
    }

    /* Grid de proyectos */
    .proyectos-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    /* Proyecto destacado */
    .proyecto-card.destacado {
        grid-column: span 2;
    }

    /* Tarjeta de proyecto */
    .proyecto-card {
        position: relative;
        background: rgba(13,31,60,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.35s;
        cursor: default;
    }

    .proyecto-card:hover {
        border-color: rgba(0,212,255,0.3);
        transform: translateY(-6px);
        box-shadow: 0 20px 56px rgba(0,30,90,0.45), 0 0 0 1px rgba(0,212,255,0.08);
    }

    /* Imagen / preview del proyecto */
    .proyecto-preview {
        height: 180px;
        background: rgba(10,22,40,0.8);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .proyecto-card.destacado .proyecto-preview {
        height: 220px;
    }

    /* Fondo decorativo del preview */
    .proyecto-preview-bg {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        opacity: 0.06;
        transition: all 0.4s;
    }

    .proyecto-card:hover .proyecto-preview-bg {
        opacity: 0.1;
        transform: scale(1.1);
    }

    .proyecto-preview-icon {
        position: relative;
        z-index: 2;
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .proyecto-preview-icon svg { width: 32px; height: 32px; }

    .proyecto-card:hover .proyecto-preview-icon {
        transform: scale(1.12);
    }

    /* Tags del proyecto */
    .proyecto-tags {
        position: absolute;
        bottom: 12px;
        left: 12px;
        display: flex;
        gap: 6px;
    }

    .proyecto-tag {
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .proyecto-tag.iot     { background: rgba(0,212,255,0.15); color: var(--accent); border: 1px solid rgba(0,212,255,0.3); }
    .proyecto-tag.auto    { background: rgba(37,99,199,0.2);  color: var(--ice-200); border: 1px solid rgba(37,99,199,0.4); }
    .proyecto-tag.web     { background: rgba(20,184,166,0.15); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.3); }
    .proyecto-tag.mobile  { background: rgba(99,102,241,0.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3); }

    /* Status badge */
    .proyecto-status {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .proyecto-status.completado {
        background: rgba(34,197,94,0.15);
        border: 1px solid rgba(34,197,94,0.3);
        color: #4ade80;
    }

    .proyecto-status.en-curso {
        background: rgba(251,191,36,0.15);
        border: 1px solid rgba(251,191,36,0.3);
        color: #fbbf24;
    }

    .proyecto-status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    /* Cuerpo de la tarjeta */
    .proyecto-body {
        padding: 1.5rem;
    }

    .proyecto-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }

    .proyecto-card.destacado .proyecto-nombre { font-size: 1.15rem; }

    .proyecto-desc {
        font-size: 0.83rem;
        color: var(--ice-200);
        line-height: 1.65;
        margin-bottom: 1.2rem;
    }

    /* Métricas */
    .proyecto-metrics {
        display: flex;
        gap: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(74,144,217,0.1);
    }

    .proyecto-metric {}

    .proyecto-metric-val {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }

    .proyecto-metric-val span { color: var(--accent); }

    .proyecto-metric-label {
        font-size: 0.72rem;
        color: var(--ice-300);
        margin-top: 2px;
    }

    /* CTA final */
    .proyectos-bottom {
        text-align: center;
        margin-top: 3.5rem;
        padding: 2.5rem;
        background: rgba(13,31,60,0.4);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 18px;
        position: relative;
        overflow: hidden;
    }

    .proyectos-bottom::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 50% 100%, rgba(0,212,255,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .proyectos-bottom-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.6rem;
        position: relative;
    }

    .proyectos-bottom-desc {
        font-size: 0.9rem;
        color: var(--ice-200);
        margin-bottom: 1.5rem;
        position: relative;
    }

    @media (max-width: 1024px) {
        .proyectos-grid { grid-template-columns: repeat(2, 1fr); }
        .proyecto-card.destacado { grid-column: span 2; }
    }

    @media (max-width: 640px) {
        .proyectos-grid { grid-template-columns: 1fr; }
        .proyecto-card.destacado { grid-column: span 1; }
        .proyectos-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<section class="proyectos" id="proyectos">
    <div class="proyectos-bg"></div>
    <div class="proyectos-container">

        {{-- Header --}}
        <div class="proyectos-header reveal">
            <div class="proyectos-header-left">
                <div class="section-label">Portafolio</div>
                <h2 class="section-title">Proyectos <span>Realizados</span></h2>
            </div>
            <a href="{{ url('/proyectos') }}" class="proyectos-ver-todos">
                Ver todos los proyectos
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
            </a>
        </div>

        {{-- Filtros --}}
        <div class="proyectos-filtros reveal">
            <button class="filtro-btn active" onclick="filtrarProyectos('todos', this)">Todos</button>
            <button class="filtro-btn" onclick="filtrarProyectos('automatizacion', this)">Automatización</button>
            <button class="filtro-btn" onclick="filtrarProyectos('iot', this)">IoT</button>
            <button class="filtro-btn" onclick="filtrarProyectos('web', this)">Software</button>
        </div>

        {{-- Grid de proyectos --}}
        <div class="proyectos-grid" id="proyectosGrid">

            {{-- Proyecto destacado --}}
            <div class="proyecto-card destacado reveal" data-categoria="automatizacion">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">⚙️</div>
                    <div class="proyecto-preview-icon" style="background:rgba(30,77,153,0.3);border:1px solid rgba(37,99,199,0.4)">
                        <svg viewBox="0 0 24 24" fill="rgba(122,184,245,0.9)"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                    </div>
                    <div class="proyecto-tags">
                        <span class="proyecto-tag auto">Automatización</span>
                        <span class="proyecto-tag iot">IoT</span>
                    </div>
                    <div class="proyecto-status completado">
                        <span class="proyecto-status-dot"></span> Completado
                    </div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Sistema SCADA de Monitoreo Industrial</div>
                    <p class="proyecto-desc">
                        Implementación completa de un sistema SCADA para planta de manufactura.
                        Integración de PLCs, sensores de temperatura, presión y flujo con visualización
                        en tiempo real y alertas automáticas vía WhatsApp y correo.
                    </p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">40<span>%</span></div>
                            <div class="proyecto-metric-label">Reducción de paradas</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">12<span> sen.</span></div>
                            <div class="proyecto-metric-label">Sensores integrados</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">24<span>/7</span></div>
                            <div class="proyecto-metric-label">Monitoreo continuo</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proyecto IoT --}}
            <div class="proyecto-card reveal reveal-delay-1" data-categoria="iot">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">📡</div>
                    <div class="proyecto-preview-icon" style="background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                    </div>
                    <div class="proyecto-tags">
                        <span class="proyecto-tag iot">IoT</span>
                    </div>
                    <div class="proyecto-status completado">
                        <span class="proyecto-status-dot"></span> Completado
                    </div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Red de Sensores Agrícola con ESP32</div>
                    <p class="proyecto-desc">
                        Sistema de monitoreo de humedad, temperatura y pH del suelo para invernadero.
                        Dashboard web en tiempo real con historial y alertas.
                    </p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">8<span> nod.</span></div>
                            <div class="proyecto-metric-label">Nodos inalámbricos</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">30<span>%</span></div>
                            <div class="proyecto-metric-label">Ahorro de agua</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proyecto Web --}}
            <div class="proyecto-card reveal reveal-delay-1" data-categoria="web">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">💻</div>
                    <div class="proyecto-preview-icon" style="background:rgba(20,184,166,0.1);border:1px solid rgba(20,184,166,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(45,212,191,0.9)"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    </div>
                    <div class="proyecto-tags">
                        <span class="proyecto-tag web">Software</span>
                    </div>
                    <div class="proyecto-status en-curso">
                        <span class="proyecto-status-dot"></span> En curso
                    </div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Sistema de Gestión Empresarial</div>
                    <p class="proyecto-desc">
                        ERP personalizado con módulos de inventario, ventas, facturación y reportes.
                        Integración con SUNAT para factura electrónica.
                    </p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">5<span> mod.</span></div>
                            <div class="proyecto-metric-label">Módulos integrados</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">60<span>%</span></div>
                            <div class="proyecto-metric-label">Tiempo ahorrado</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proyecto Automatización 2 --}}
            <div class="proyecto-card reveal reveal-delay-2" data-categoria="automatizacion">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">🏭</div>
                    <div class="proyecto-preview-icon" style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(165,180,252,0.9)"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    </div>
                    <div class="proyecto-tags">
                        <span class="proyecto-tag auto">Automatización</span>
                    </div>
                    <div class="proyecto-status completado">
                        <span class="proyecto-status-dot"></span> Completado
                    </div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Control de Línea de Producción PLC</div>
                    <p class="proyecto-desc">
                        Automatización de línea de envasado con PLC Siemens S7-1200.
                        Control de velocidad, conteo de productos y parada de emergencia.
                    </p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">3<span>x</span></div>
                            <div class="proyecto-metric-label">Mayor producción</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">0<span> err.</span></div>
                            <div class="proyecto-metric-label">Errores en 6 meses</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- CTA --}}
        <div class="proyectos-bottom reveal">
            <div class="proyectos-bottom-title">¿Tienes un proyecto en mente?</div>
            <p class="proyectos-bottom-desc">Cuéntanos tu idea y te damos una propuesta sin costo.</p>
            <a href="{{ url('/contacto') }}" class="hero-btn-primary" style="display:inline-flex;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Hablemos de tu proyecto
            </a>
        </div>

    </div>
</section>

<script>
    function filtrarProyectos(categoria, btn) {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.proyecto-card').forEach(card => {
            if (categoria === 'todos' || card.dataset.categoria === categoria) {
                card.style.display = '';
                card.style.animation = 'fadeIn 0.4s ease';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>