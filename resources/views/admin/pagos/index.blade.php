<x-admin-layout>

@if(session('success'))
  <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
@endif

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Pagos</h1>
    <p class="adm-page-sub">Revisa y procesa las transferencias de los pedidos.</p>
  </div>
  @if($pendientes > 0)
  <span class="adm-pill adm-pill-pending" style="font-size:.85rem;padding:6px 14px;">
    {{ $pendientes }} pendiente(s)
  </span>
  @endif
</div>

<div class="adm-stats">
  <div class="adm-card" style="text-align: center; padding: 1.1rem 1.3rem;">
    <div style="font-size: 1.6rem; font-weight: 800; color: var(--text);">{{ $pagos->total() }}</div>
    <div style="font-size: .75rem; color: var(--muted); margin-top: 4px;">Total pagos</div>
  </div>
  <div class="adm-card" style="text-align: center; padding: 1.1rem 1.3rem;">
    <div style="font-size: 1.6rem; font-weight: 800; color: #10b981;">{{ $pagos->where('estado_pago','aprobado')->count() }}</div>
    <div style="font-size: .75rem; color: var(--muted); margin-top: 4px;">Aprobados</div>
  </div>
  <div class="adm-card" style="text-align: center; padding: 1.1rem 1.3rem;">
    <div style="font-size: 1.6rem; font-weight: 800; color: #f43f5e;">{{ $pagos->where('estado_pago','rechazado')->count() }}</div>
    <div style="font-size: .75rem; color: var(--muted); margin-top: 4px;">Rechazados</div>
  </div>
  <div class="adm-card" style="text-align: center; padding: 1.1rem 1.3rem;">
    <div style="font-size: 1.6rem; font-weight: 800; color: #f59e0b;">{{ $pendientes }}</div>
    <div style="font-size: .75rem; color: var(--muted); margin-top: 4px;">Pendientes</div>
  </div>
</div>

<div class="adm-table-container">
  <table class="adm-table">
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
          <td style="font-weight:600; color: var(--text);">{{ $pago->pedido->numero_pedido ?? '#' . $pago->pedido_id }}</td>
          <td>{{ $pago->pedido->cliente_nombre }}</td>
          <td style="font-weight:700;">S/ {{ number_format($pago->monto, 2) }}</td>
          <td>{{ $pago->numero_operacion ?? '—' }}</td>
          <td>
            @if ($pago->comprobante_pago)
              <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="adm-btn adm-btn-ghost" style="padding:.35rem .75rem; font-size:.72rem;">Ver</a>
            @else
              —
            @endif
          </td>
          <td>
            @php
              $pillClass = match($pago->estado_pago) {
                'pendiente' => 'adm-pill-pending',
                'aprobado' => 'adm-pill-approved',
                'rechazado' => 'adm-pill-rejected',
                default => ''
              };
            @endphp
            <span class="adm-pill {{ $pillClass }}">{{ ucfirst($pago->estado_pago) }}</span>
          </td>
          <td style="font-size:.8rem;color:var(--muted);">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <a href="{{ route('admin.pagos.show', $pago) }}" class="adm-btn adm-btn-primary" style="padding:.35rem .75rem; font-size:.72rem;">Revisar</a>
            <form method="POST" action="{{ route('admin.pagos.destroy', $pago) }}"
                  onsubmit="return confirm('¿Eliminar este pago? Esta acción no se puede deshacer.');"
                  style="display: inline-block; margin-left: .35rem;">
              @csrf
              @method('DELETE')
              <button type="submit" class="adm-btn adm-btn-danger" style="padding:.35rem .75rem; font-size:.72rem;">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--muted);">No hay pagos registrados.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($pagos->hasPages())
<div class="adm-pagination">
  <span class="adm-pagination-info">
      Mostrando {{ $pagos->firstItem() }}–{{ $pagos->lastItem() }}
      de {{ $pagos->total() }} pagos
  </span>
  {{ $pagos->links() }}
</div>
@endif

</x-admin-layout>
