<x-admin-layout>
<style>
  .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;}
  .stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;}
  .stat-card .num{font-size:1.5rem;font-weight:700;color:#111827;}
  .stat-card .lbl{font-size:.78rem;color:#6b7280;margin-top:2px;}
  .table-wrap{overflow-x:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;}
  table{width:100%;border-collapse:collapse;font-size:.875rem;}
  th{text-align:left;padding:12px 14px;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;background:#f9fafb;border-bottom:1px solid #e5e7eb;}
  td{padding:12px 14px;border-bottom:1px solid #f3f4f6;color:#374151;}
  tr:last-child td{border-bottom:none;}
  .badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;}
  .badge-pendiente{background:#fef3c7;color:#92400e;}
  .badge-aprobado{background:#d1fae5;color:#065f46;}
  .badge-rechazado{background:#fee2e2;color:#991b1b;}
  .btn{display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:.78rem;font-weight:500;text-decoration:none;transition:all .2s;border:none;cursor:pointer;}
  .btn-primary{background:#2563eb;color:#fff;}
  .btn-primary:hover{background:#1d4ed8;}
  .paginacion{margin-top:16px;}
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <h1 style="font-size:1.4rem;font-weight:700;color:#111827;">Pagos</h1>
  <span style="font-size:.85rem;color:#6b7280;background:#fef3c7;padding:6px 14px;border-radius:20px;">
    {{ $pendientes }} pendiente(s)
  </span>
</div>

<div class="stats">
  <div class="stat-card"><div class="num">{{ $pagos->total() }}</div><div class="lbl">Total pagos</div></div>
  <div class="stat-card"><div class="num" style="color:#065f46;">{{ $pagos->where('estado_pago','aprobado')->count() }}</div><div class="lbl">Aprobados</div></div>
  <div class="stat-card"><div class="num" style="color:#991b1b;">{{ $pagos->where('estado_pago','rechazado')->count() }}</div><div class="lbl">Rechazados</div></div>
  <div class="stat-card"><div class="num" style="color:#92400e;">{{ $pendientes }}</div><div class="lbl">Pendientes</div></div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Pedido</th>
        <th>Cliente</th>
        <th>Monto</th>
        <th>Nro. Operación</th>
        <th>Comprobante</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($pagos as $pago)
        <tr>
          <td style="font-weight:600;">{{ $pago->pedido->numero_pedido ?? '#' . $pago->pedido_id }}</td>
          <td>{{ $pago->pedido->cliente_nombre }}</td>
          <td>S/ {{ number_format($pago->monto, 2) }}</td>
          <td>{{ $pago->numero_operacion ?? '—' }}</td>
          <td>
            @if ($pago->comprobante_pago)
              <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-primary" style="font-size:.72rem;">Ver</a>
            @else
              —
            @endif
          </td>
          <td><span class="badge badge-{{ $pago->estado_pago }}">{{ ucfirst($pago->estado_pago) }}</span></td>
          <td style="font-size:.8rem;color:#6b7280;">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
          <td><a href="{{ route('admin.pagos.show', $pago) }}" class="btn btn-primary" style="font-size:.72rem;">Revisar</a></td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:#9ca3af;">No hay pagos registrados.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="paginacion">{{ $pagos->links() }}</div>
</x-admin-layout>
