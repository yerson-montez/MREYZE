@extends('layouts.milink')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Inter:wght@300;400;500&display=swap');

    :root {
        --bg-deep:     #060c1a;
        --bg-card:     #0a1220;
        --border:      rgba(0, 212, 255, 0.12);
        --border-hot:  rgba(0, 212, 255, 0.32);
        --accent:      #00d4ff;
        --blue-dark:   #185fa5;
        --violet:      #7c6ee0;
        --green:       #1d9e75;
        --yellow:      #d4a017;
        --red:         #f87171;
        --text-1:      #e2ecff;
        --text-2:      #7ab8f5;
        --text-3:      #2b5278;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg-deep);
        color: var(--text-1);
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ── Grid de fondo ── */
    body::before {
        content: '';
        position: fixed; inset: 0;
        background-image:
            linear-gradient(rgba(0,212,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,212,255,0.03) 1px, transparent 1px);
        background-size: 36px 36px;
        pointer-events: none;
        z-index: 0;
    }

    /* ── Glow superior ── */
    body::after {
        content: '';
        position: fixed;
        top: -100px; left: 50%;
        transform: translateX(-50%);
        width: min(600px, calc(100vw - 2rem)); height: clamp(200px, 38vw, 280px);
        background: radial-gradient(ellipse, rgba(0,212,255,0.055) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* ══ WRAPPER ══ */
    .wrapper {
        position: relative;
        z-index: 1;
        max-width: 820px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ══ HEADER ══ */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .avatar {
        width: 50px; height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--blue-dark), var(--accent));
        display: flex; align-items: center; justify-content: center;
        font-family: 'Exo 2', sans-serif;
        font-size: 1.2rem; font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .header-name {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.3rem; font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--text-1);
    }

    .header-sub {
        font-size: 0.65rem; font-weight: 500;
        color: var(--accent);
        letter-spacing: 0.15em;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Exo 2', sans-serif;
        font-size: 0.62rem; font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--green);
        background: rgba(29,158,117,0.1);
        border: 1px solid rgba(29,158,117,0.25);
        border-radius: 100px;
        padding: 5px 13px;
    }

    .badge-status::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--green);
        animation: bpulse 2s ease-in-out infinite;
    }

    @keyframes bpulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(1.4); }
    }

    /* ══ STATS GRID ══ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 9rem), 1fr));
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.1rem 1rem;
        position: relative;
        overflow: hidden;
        transition: border-color 0.25s, transform 0.25s;
        cursor: default;
    }

    .stat-card:hover {
        border-color: var(--border-hot);
        transform: translateY(-2px);
    }

    .stat-glow {
        position: absolute;
        top: -30px; right: -30px;
        width: 90px; height: 90px;
        border-radius: 50%;
        opacity: 0.07;
        transition: opacity 0.3s;
    }

    .stat-card:hover .stat-glow { opacity: 0.14; }

    .glow-blue   { background: var(--accent); }
    .glow-green  { background: var(--green); }
    .glow-violet { background: var(--violet); }

    .stat-label {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.58rem; font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.55rem; font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .v-blue   { color: var(--accent); }
    .v-green  { color: var(--green); }
    .v-violet { color: var(--violet); }

    /* ══ COMISIÓN HIGHLIGHT ══ */
    .comision-highlight {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        background: rgba(0,212,255,0.04);
        border: 1px solid rgba(0,212,255,0.18);
        border-radius: 12px;
        padding: 1.1rem 1.3rem;
        margin-bottom: 1.25rem;
    }

    .comision-circle {
        width: 68px; height: 68px;
        border-radius: 50%;
        border: 2px solid var(--accent);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .comision-num {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.25rem; font-weight: 700;
        color: var(--accent);
        line-height: 1;
    }

    .comision-lbl {
        font-size: 0.5rem;
        color: var(--text-2);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 1px;
    }

    .comision-desc h3 {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.95rem; font-weight: 600;
        color: var(--text-1);
        margin-bottom: 0.25rem;
    }

    .comision-desc p {
        font-size: 0.76rem;
        color: var(--text-2);
        line-height: 1.55;
    }

    /* ══ PANEL ══ */
    .panel {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.25rem;
        transition: border-color 0.25s;
    }

    .panel:hover { border-color: var(--border-hot); }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.2rem;
        border-bottom: 1px solid var(--border);
    }

    .panel-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.62rem; font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-2);
    }

    .panel-line {
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .panel-body { padding: 1.2rem; }

    /* ══ INFO GRID ══ */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-item label {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.58rem; font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        display: block;
        margin-bottom: 0.3rem;
    }

    .mono-value {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.85rem; font-weight: 600;
        color: var(--accent);
    }

    .mono-violet { color: var(--violet); }
    .info-full   { grid-column: 1 / -1; }

    /* ══ LINK ROW ══ */
    .link-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(6,12,26,0.6);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        margin-top: 0.4rem;
    }

    .link-text {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.7rem;
        color: var(--text-2);
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-copy {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.58rem; font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--accent);
        background: rgba(0,212,255,0.08);
        border: 1px solid rgba(0,212,255,0.22);
        border-radius: 6px;
        padding: 4px 11px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-copy:hover {
        background: rgba(0,212,255,0.16);
        border-color: rgba(0,212,255,0.5);
    }

    .btn-copy.copied {
        color: var(--green);
        background: rgba(29,158,117,0.1);
        border-color: rgba(29,158,117,0.3);
    }

    /* ══ TABLE ══ */
    .table-wrap { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    thead th {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.57rem; font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 0.65rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    thead th.right  { text-align: right; }
    thead th.center { text-align: center; }

    tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.03);
        transition: background 0.15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(0,212,255,0.03); }

    tbody td {
        padding: 0.75rem 1rem;
        color: var(--text-1);
        vertical-align: middle;
    }

    .td-id {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.68rem; font-weight: 600;
        color: var(--text-3);
    }

    .td-prod {
        font-size: 0.75rem;
        color: var(--text-2);
    }

    .td-right {
        text-align: right;
        font-family: 'Exo 2', sans-serif;
        font-size: 0.78rem;
        white-space: nowrap;
    }

    .td-comision { color: var(--green); }
    .td-center   { text-align: center; }
    .td-muted    { color: var(--text-3); }

    /* Badges estado */
    .badge {
        display: inline-block;
        font-family: 'Exo 2', sans-serif;
        font-size: 0.58rem; font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 100px;
    }

    .badge-pendiente  { background: rgba(212,160,23,0.1);  color: var(--yellow); border: 1px solid rgba(212,160,23,0.25); }
    .badge-confirmado { background: rgba(0,212,255,0.08);  color: var(--accent); border: 1px solid rgba(0,212,255,0.22); }
    .badge-entregado  { background: rgba(29,158,117,0.1);  color: var(--green);  border: 1px solid rgba(29,158,117,0.25); }
    .badge-cancelado  { background: rgba(248,113,113,0.08);color: var(--red);    border: 1px solid rgba(248,113,113,0.2); }

    .empty-row td {
        text-align: center;
        padding: 2.5rem 1rem;
        font-family: 'Exo 2', sans-serif;
        font-size: 0.72rem;
        color: var(--text-3);
        letter-spacing: 0.08em;
    }

    /* Pagination */
    .pagination-wrap {
        padding: 0.7rem 1rem;
        border-top: 1px solid var(--border);
    }

    .pagination-wrap nav { display: flex; gap: 0.4rem; flex-wrap: wrap; }

    .pagination-wrap a,
    .pagination-wrap span {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.62rem; font-weight: 600;
        padding: 4px 10px;
        border-radius: 7px;
        color: var(--text-2);
        background: transparent;
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-wrap a:hover {
        color: var(--accent);
        border-color: var(--border-hot);
    }

    .pagination-wrap span[aria-current] {
        color: var(--accent);
        border-color: var(--accent);
        background: rgba(0,212,255,0.08);
    }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .stats-grid .stat-card:last-child { grid-column: 1 / -1; }
        .info-grid  { grid-template-columns: 1fr; }
        .info-full  { grid-column: 1; }
        .header     { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="wrapper">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            <div class="avatar">
                {{ strtoupper(substr($colaborador->nombre ?? 'C', 0, 2)) }}
            </div>
            <div>
                <div class="header-name">{{ $colaborador->nombre ?? 'Colaborador' }}</div>
                <div class="header-sub">Panel de colaborador</div>
            </div>
        </div>
        <div class="badge-status">Activo</div>
    </div>

    @if($colaborador)

    {{-- ── STATS ── --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-glow glow-blue"></div>
            <div class="stat-label">Total pedidos</div>
            <div class="stat-value v-blue">{{ $stats['total_pedidos'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-glow glow-green"></div>
            <div class="stat-label">Ventas entregadas</div>
            <div class="stat-value v-green">S/ {{ number_format($stats['total_ventas'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-glow glow-violet"></div>
            <div class="stat-label">Comisión acumulada</div>
            <div class="stat-value v-violet">S/ {{ number_format($colaborador->comision_total_acumulada ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- ── COMISIÓN ── --}}
    <div class="comision-highlight">
        <div class="comision-circle">
            <span class="comision-num">{{ $colaborador->comision_porcentaje ?? 0 }}%</span>
            <span class="comision-lbl">comisión</span>
        </div>
        <div class="comision-desc">
            <h3>Ganas el {{ $colaborador->comision_porcentaje ?? 0 }}% por cada venta completada</h3>
            <p>Cada vez que un cliente compra usando tu enlace y el pedido es completado, recibes automáticamente tu comisión sobre el total de la venta.</p>
        </div>
    </div>

    {{-- ── INFO ── --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Tu información de referido</span>
            <div class="panel-line"></div>
        </div>
        <div class="panel-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Código de referido</label>
                    <span class="mono-value">{{ $colaborador->codigo_ref }}</span>
                </div>
                <div class="info-item">
                    <label>Porcentaje de comisión</label>
                    <span class="mono-value mono-violet">{{ $colaborador->comision_porcentaje }}%</span>
                </div>
                <div class="info-item info-full">
                    <label>Tu enlace de afiliado</label>
                    <div class="link-row">
                        <span class="link-text" id="link-val">{{ $colaborador->link_ref }}</span>
                        <button class="btn-copy" id="btn-copy" onclick="copiarLink()">Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Pedidos generados</span>
            <div class="panel-line"></div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th class="right">Total</th>
                        <th class="right">Comisión</th>
                        <th class="center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                    <tr>
                        <td class="td-id">#{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente_nombre }}</td>
                        <td class="td-prod">{{ $pedido->producto->nombre }}</td>
                        <td class="td-right">S/ {{ number_format($pedido->total, 2) }}</td>
                        <td class="td-right td-comision">
                            @if($pedido->comision_generada > 0)
                                S/ {{ number_format($pedido->comision_generada, 2) }}
                            @else
                                <span class="td-muted">—</span>
                            @endif
                        </td>
                        <td class="td-center">
                            @php
                                $badges = [
                                    'pendiente'      => 'badge-pendiente',
                                    'pago_confirmado' => 'badge-confirmado',
                                    'preparando'     => 'badge-confirmado',
                                    'enviado'        => 'badge-confirmado',
                                    'en_camino'      => 'badge-confirmado',
                                    'listo_recojo'   => 'badge-confirmado',
                                    'completado'     => 'badge-entregado',
                                    'cancelado'      => 'badge-cancelado',
                                ];
                                $badgeClass = $badges[$pedido->estado] ?? 'badge-pendiente';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $pedido->estado_label }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="6">// sin pedidos aún</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $pedidos->links() }}</div>
    </div>

    @else
    <div class="panel" style="text-align:center; padding:3rem;">
        <p style="font-family:'Exo 2',sans-serif; font-size:0.8rem; color:var(--text-3); letter-spacing:0.08em;">
            // acceso denegado — no eres colaborador
        </p>
    </div>
    @endif

</div>

<script>
function copiarLink() {
    const val = document.getElementById('link-val').innerText.trim();
    const btn = document.getElementById('btn-copy');
    const fallback = () => {
        const ta = document.createElement('textarea');
        ta.value = val;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    };
    (navigator.clipboard ? navigator.clipboard.writeText(val) : Promise.reject())
        .catch(fallback)
        .finally(() => {
            btn.textContent = '✓ Copiado';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.textContent = 'Copiar';
                btn.classList.remove('copied');
            }, 2000);
        });
}
</script>

@endsection