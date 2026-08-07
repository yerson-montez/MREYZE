@include('pagos._datos-pago')

<div class="pago-card">
  <h2>{{ $retry ?? false ? 'Reintentar' : 'Sube tu comprobante' }}</h2>
  <form action="{{ route('pedidos.comprobante', $pedido) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label>Número de operación <span style="color:var(--danger);">*</span></label>
      <input type="text" name="numero_operacion" placeholder="Ej: 123456789" value="{{ old('numero_operacion', $pago?->numero_operacion) }}" required>
    </div>
    <div class="form-group">
      <label>Comprobante (JPG, PNG o PDF — máx 5 MB)</label>
      <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
    </div>
    <button type="submit" class="btn-yape">
      <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      {{ $retry ?? false ? 'Reintentar' : 'Enviar comprobante' }}
    </button>
  </form>
</div>
