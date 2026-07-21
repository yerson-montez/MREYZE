<x-admin-layout>
<style>
  .max-w-2xl{max-width:640px;margin:0 auto;padding:24px;}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin-bottom:16px;}
  .card h2{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;margin-bottom:12px;}
  .row{display:flex;justify-content:space-between;padding:6px 0;font-size:.875rem;border-bottom:1px solid #f3f4f6;}
  .row:last-child{border-bottom:none;}
  .label{color:#6b7280;}
  .value{font-weight:600;color:#111827;}
  .badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;}
  .badge-pendiente{background:#fef3c7;color:#92400e;}
  .badge-aprobado{background:#d1fae5;color:#065f46;}
  .badge-rechazado{background:#fee2e2;color:#991b1b;}
  .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:8px;font-size:.85rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;}
  .btn-success{background:#059669;color:#fff;}
  .btn-success:hover{background:#047857;}
  .btn-danger{background:#dc2626;color:#fff;}
  .btn-danger:hover{background:#b91c1c;}
  .btn-secondary{background:#e5e7eb;color:#374151;}
  .btn-secondary:hover{background:#d1d5db;}
  .acciones{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;}
  .form-group{margin-bottom:14px;}
  .form-group label{display:block;font-size:.78rem;font-weight:500;color:#374151;margin-bottom:4px;}
  .form-group textarea{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px;font-size:.875rem;resize:vertical;min-height:80px;}
  .form-group textarea:focus{border-color:#2563eb;outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
  .alert{background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;border-radius:8px;padding:12px;margin-bottom:16px;font-size:.85rem;}
  .img-comprobante{max-width:100%;border-radius:8px;border:1px solid #e5e7eb;}
</style>

<div class="max-w-2xl">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
    <a href="{{ route('admin.pagos.index') }}" style="color:#6b7280;text-decoration:none;font-size:.85rem;">← Volver</a>
    <h1 style="font-size:1.3rem;font-weight:700;color:#111827;">Pago #{{ $pago->id }}</h1>
    <span class="badge badge-{{ $pago->estado_pago }}">{{ ucfirst($pago->estado_pago) }}</span>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:8px;padding:12px;margin-bottom:16px;font-size:.85rem;">{{ session('error') }}</div>
  @endif

  <div class="card">
    <h2>Información del pago</h2>
    <div class="row"><span class="label">Pedido</span><span class="value">{{ $pago->pedido->numero_pedido ?? '#' . $pago->pedido_id }}</span></div>
    <div class="row"><span class="label">Cliente</span><span class="value">{{ $pago->pedido->cliente_nombre }}</span></div>
    <div class="row"><span class="label">Email</span><span class="value">{{ $pago->pedido->cliente_email }}</span></div>
    <div class="row"><span class="label">Teléfono</span><span class="value">{{ $pago->pedido->cliente_telefono }}</span></div>
    <div class="row"><span class="label">Producto</span><span class="value">{{ $pago->pedido->producto?->nombre }}</span></div>
    <div class="row"><span class="label">Monto</span><span class="value">S/ {{ number_format($pago->monto, 2) }}</span></div>
    <div class="row"><span class="label">Método</span><span class="value">{{ ucfirst($pago->metodo_pago) }}</span></div>
    <div class="row"><span class="label">Nro. operación</span><span class="value">{{ $pago->numero_operacion ?? '—' }}</span></div>
    <div class="row"><span class="label">Fecha de envío</span><span class="value">{{ $pago->created_at->format('d/m/Y H:i') }}</span></div>
    @if ($pago->fecha_pago)
      <div class="row"><span class="label">Fecha de pago</span><span class="value">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</span></div>
    @endif
    @if ($pago->aprobador)
      <div class="row"><span class="label">Aprobado por</span><span class="value">{{ $pago->aprobador->name }}</span></div>
    @endif
  </div>

  @if ($pago->comprobante_pago)
    <div class="card">
      <h2>Comprobante</h2>
      @php
        $ext = pathinfo($pago->comprobante_pago, PATHINFO_EXTENSION);
      @endphp
      @if (in_array($ext, ['jpg','jpeg','png']))
        <img src="{{ asset('storage/' . $pago->comprobante_pago) }}" alt="Comprobante" class="img-comprobante">
      @else
        <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
          Descargar PDF
        </a>
      @endif
    </div>
  @endif

  @if ($pago->estado_pago === 'pendiente')
    <div class="card">
      <h2>Revisar pago</h2>
      <form action="{{ route('admin.pagos.aprobar', $pago) }}" method="POST">
        @csrf
        <div class="form-group">
          <label>Observaciones (opcional)</label>
          <textarea name="observaciones_admin" placeholder="Ej: Pago verificado correctamente...">{{ old('observaciones_admin') }}</textarea>
        </div>
        <div class="acciones">
          <button type="submit" name="accion" value="aprobar" class="btn btn-success">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Aprobar pago
          </button>
          <button type="submit" name="accion" value="rechazar" class="btn btn-danger" onclick="return confirm('¿Rechazar este pago?')">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            Rechazar pago
          </button>
        </div>
      </form>
    </div>
  @endif

  @if ($pago->observaciones_admin)
    <div class="card">
      <h2>Observaciones del admin</h2>
      <p style="font-size:.875rem;color:#374151;">{{ $pago->observaciones_admin }}</p>
    </div>
  @endif

  @php $boleta = $pago->pedido->boletas()->first(); @endphp
  @if ($boleta)
    <div class="card">
      <h2>Boleta interna</h2>
      <div class="row">
        <span class="label">Número</span>
        <span class="value">{{ $boleta->numero_boleta }}</span>
      </div>
      <div class="row">
        <span class="label">Total</span>
        <span class="value">S/ {{ number_format($boleta->total, 2) }}</span>
      </div>
      <div class="row">
        <span class="label">Estado</span>
        <span class="value"><span class="badge badge-{{ $boleta->estado === 'emitida' ? 'aprobado' : 'rechazado' }}">{{ ucfirst($boleta->estado) }}</span></span>
      </div>
      <div class="acciones" style="margin-top:12px">
        <a href="{{ route('admin.boletas.show', $boleta) }}" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M12 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
          Ver boleta
        </a>
        <a href="{{ route('admin.boletas.pdf', $boleta) }}" class="btn btn-success" target="_blank">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
          Descargar PDF
        </a>
      </div>
    </div>
  @endif
</div>
</x-admin-layout>
