@extends('layouts.milink')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap');

    :root {
        --bg-deep:    #050810;
        --bg-card:    #0b0f1e;
        --bg-glass:   rgba(255,255,255,0.03);
        --border:     rgba(99,210,255,0.12);
        --border-hot: rgba(99,210,255,0.35);
        --cyan:       #63d2ff;
        --violet:     #a78bfa;
        --green:      #34d399;
        --yellow:     #fbbf24;
        --red:        #f87171;
        --text-1:     #e8f4ff;
        --text-2:     #7a9ab5;
        --text-3:     #3d5a72;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg-deep);
        color: var(--text-1);
        font-family: 'Syne', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ── grid background ── */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(99,210,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99,210,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
    }

    body::after {
        content: '';
        position: fixed;
        top: -200px; left: 50%;
        transform: translateX(-50%);
        width: 900px; height: 500px;
        background: radial-gradient(ellipse, rgba(99,210,255,0.06) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .wrapper {
        position: relative;
        z-index: 1;
        max-width: 820px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem 4rem;
        animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── HEADER ── */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.5rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--cyan), var(--violet));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 800;
        color: var(--bg-deep);
        box-shadow: 0 0 20px rgba(99,210,255,0.3);
        flex-shrink: 0;
    }

    .header-name {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text-1);
    }

    .header-sub {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--cyan);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--green);
        background: rgba(52,211,153,0.08);
        border: 1px solid rgba(52,211,153,0.2);
        border-radius: 100px;
        padding: 6px 14px;
    }

    .badge-status::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--green);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(1.4); }
    }

    /* ── CARDS STATS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.4rem 1.2rem;
        position: relative;
        overflow: hidden;
        transition: border-color 0.25s, transform 0.25s;
    }

    .stat-card:hover {
        border-color: var(--border-hot);
        transform: translateY(-3px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 100px; height: 100px;
        border-radius: 50%;
        opacity: 0.08;
        transition: opacity 0.3s;
    }

    .stat-card:hover::before { opacity: 0.16; }

    .stat-card.cyan::before  { background: var(--cyan); }
    .stat-card.green::before { background: var(--green); }
    .stat-card.violet::before{ background: var(--violet); }

    .stat-icon {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
        opacity: 0.7;
    }

    .stat-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 0.4rem;
    }

    .stat-value {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .stat-value.cyan   { color: var(--cyan);   text-shadow: 0 0 20px rgba(99,210,255,0.4); }
    .stat-value.green  { color: var(--green);  text-shadow: 0 0 20px rgba(52,211,153,0.4); }
    .stat-value.violet { color: var(--violet); text-shadow: 0 0 20px rgba(167,139,250,0.4); }

    /* ── GLASS PANEL ── */
    .panel {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: border-color 0.25s;
    }

    .panel:hover { border-color: var(--border-hot); }

    .panel-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .panel-header-title {
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-2);
    }

    .panel-header-line {
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .panel-body {
        padding: 1.4rem;
    }

    /* ── COMISION HIGHLIGHT ── */
    .comision-highlight {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        background: linear-gradient(135deg, rgba(99,210,255,0.06), rgba(167,139,250,0.04));
        border: 1px solid rgba(99,210,255,0.2);
        border-radius: 14px;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.5rem;
    }

    .comision-pct-circle {
        width: 72px; height: 72px;
        border-radius: 50%;
        border: 2px solid var(--cyan);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 0 24px rgba(99,210,255,0.2), inset 0 0 24px rgba(99,210,255,0.05);
    }

    .comision-pct-number {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--cyan);
        line-height: 1;
    }

    .comision-pct-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.5rem;
        color: var(--text-2);
        letter-spacing: 0.08em;
    }

    .comision-desc h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-1);
        margin-bottom: 0.25rem;
    }

    .comision-desc p {
        font-size: 0.78rem;
        color: var(--text-2);
        line-height: 1.5;
    }

    /* ── INFO GRID ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-item label {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-3);
        display: block;
        margin-bottom: 0.3rem;
    }

    .info-item .mono-value {
        font-family: 'Space Mono', monospace;
        font-size: 0.85rem;
        color: var(--cyan);
    }

    .info-item.full { grid-column: 1 / -1; }

    /* ── LINK COPY ROW ── */
    .link-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 0.5rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.5rem 0.7rem;
    }

    .link-text {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--text-2);
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-copy {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--cyan);
        background: rgba(99,210,255,0.08);
        border: 1px solid rgba(99,210,255,0.25);
        border-radius: 7px;
        padding: 5px 12px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-copy:hover {
        background: rgba(99,210,255,0.18);
        border-color: var(--cyan);
        box-shadow: 0 0 10px rgba(99,210,255,0.2);
    }

    .btn-copy.copied {
        color: var(--green);
        background: rgba(52,211,153,0.1);
        border-color: rgba(52,211,153,0.3);
    }

    /* ── TABLE ── */
    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    thead th {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 0.7rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    thead th:last-child { text-align: center; }
    thead th.right      { text-align: right; }

    tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.03);
        transition: background 0.15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(99,210,255,0.03); }

    tbody td {
        padding: 0.8rem 1rem;
        color: var(--text-1);
        vertical-align: middle;
    }

    .td-id {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--text-3);
    }

    .td-total {
        text-align: right;
        font-family: 'Space Mono', monospace;
        font-size: 0.8rem;
        color: var(--text-1);
        white-space: nowrap;
    }

    .td-comision {
        text-align: right;
        font-family: 'Space Mono', monospace;
        font-size: 0.8rem;
        color: var(--green);
        white-space: nowrap;
    }

    .td-estado { text-align: center; }

    .badge {
        display: inline-block;
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 100px;
        font-weight: 400;
    }

    .badge-pendiente  { background: rgba(251,191,36,0.1);  color: var(--yellow); border: 1px solid rgba(251,191,36,0.25); }
    .badge-confirmado { background: rgba(99,210,255,0.1);  color: var(--cyan);   border: 1px solid rgba(99,210,255,0.25); }
    .badge-entregado  { background: rgba(52,211,153,0.1);  color: var(--green);  border: 1px solid rgba(52,211,153,0.25); }
    .badge-cancelado  { background: rgba(248,113,113,0.1); color: var(--red);    border: 1px solid rgba(248,113,113,0.25); }

    .empty-row td {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-3);
        font-family: 'Space Mono', monospace;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
    }

    /* ── PAGINATION ── */
    .pagination-wrap {
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border);
    }

    .pagination-wrap nav { display: flex; gap: 0.4rem; }

    .pagination-wrap a,
    .pagination-wrap span {
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        padding: 5px 10px;
        border-radius: 7px;
        color: var(--text-2);
        background: transparent;
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-wrap a:hover {
        color: var(--cyan);
        border-color: var(--border-hot);
    }

    .pagination-wrap span[aria-current] {
        color: var(--cyan);
        border-color: var(--cyan);
        background: rgba(99,210,255,0.08);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .stats-grid .stat-card:last-child { grid-column: 1 / -1; }
        .info-grid  { grid-template-columns: 1fr; }
        .info-item.full { grid-column: 1; }
        .header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<div class="wrapper">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            <div class="avatar">
                {{ strtoupper(substr($colaborador->nombre ?? 'C', 0, 1)) }}
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
        <div class="stat-card cyan">
            <div class="stat-icon">📦</div>
            <div class="stat-label">Total pedidos</div>
            <div class="stat-value cyan">{{ $stats['total_pedidos'] ?? 0 }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-label">Ventas entregadas</div>
            <div class="stat-value green">S/ {{ number_format($stats['total_ventas'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-card violet">
            <div class="stat-icon">💎</div>
            <div class="stat-label">Comisión acumulada</div>
            <div class="stat-value violet">S/ {{ number_format($colaborador->comision_total_acumulada ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- ── COMISIÓN HIGHLIGHT ── --}}
    <div class="comision-highlight">
        <div class="comision-pct-circle">
            <span class="comision-pct-number">{{ $colaborador->comision_porcentaje ?? 0 }}%</span>
            <span class="comision-pct-label">comisión</span>
        </div>
        <div class="comision-desc">
            <h3>Ganas el {{ $colaborador->comision_porcentaje ?? 0 }}% por cada venta entregada</h3>
            <p>Cada vez que un cliente compra usando tu enlace y el pedido es entregado, recibes automáticamente tu comisión sobre el total de la venta.</p>
        </div>
    </div>

    {{-- ── INFO ── --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-header-title">Tu información de referido</span>
            <div class="panel-header-line"></div>
        </div>
        <div class="panel-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Código de referido</label>
                    <span class="mono-value">{{ $colaborador->codigo_ref }}</span>
                </div>
                <div class="info-item">
                    <label>Porcentaje de comisión</label>
                    <span class="mono-value" style="color:var(--violet)">{{ $colaborador->comision_porcentaje }}%</span>
                </div>
                <div class="info-item full">
                    <label>Tu enlace de afiliado</label>
                    <div class="link-row">
                        <span class="link-text" id="link-val">{{ $colaborador->link_ref }}</span>
                        <button class="btn-copy" id="btn-copy"
                            onclick="copiarLink()">Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TABLA PEDIDOS ── --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-header-title">Pedidos generados</span>
            <div class="panel-header-line"></div>
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
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                    <tr>
                        <td class="td-id">#{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente_nombre }}</td>
                        <td style="color:var(--text-2);font-size:0.78rem;">{{ $pedido->producto->nombre }}</td>
                        <td class="td-total">S/ {{ number_format($pedido->total, 2) }}</td>
                        <td class="td-comision">
                            @if($pedido->comision_generada > 0)
                                S/ {{ number_format($pedido->comision_generada, 2) }}
                            @else
                                <span style="color:var(--text-3)">—</span>
                            @endif
                        </td>
                        <td class="td-estado">
                            @php
                                $badge = [
                                    'pendiente'  => 'badge-pendiente',
                                    'confirmado' => 'badge-confirmado',
                                    'entregado'  => 'badge-entregado',
                                    'cancelado'  => 'badge-cancelado',
                                ][$pedido->estado] ?? '';
                            @endphp
                            <span class="badge {{ $badge }}">{{ ucfirst($pedido->estado) }}</span>
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
    <div class="panel" style="text-align:center;padding:3rem;">
        <p style="font-family:'Space Mono',monospace;font-size:0.8rem;color:var(--text-3);">
            // acceso denegado — no eres colaborador
        </p>
    </div>
    @endif

</div>

<script>
function copiarLink() {
    const val = document.getElementById('link-val').innerText.trim();
    const btn = document.getElementById('btn-copy');
    navigator.clipboard.writeText(val).then(() => {
        btn.textContent = '✓ Copiado';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.textContent = 'Copiar';
            btn.classList.remove('copied');
        }, 2000);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = val;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        btn.textContent = '✓ Copiado';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Copiar'; btn.classList.remove('copied'); }, 2000);
    });
}
</script>

@endsection