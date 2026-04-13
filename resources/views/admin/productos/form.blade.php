{{-- resources/views/admin/productos/form.blade.php --}}
{{-- Se usa tanto en create como en edit. $producto viene del padre. --}}

<div class="form-group">
    <label>Nombre <span class="req">*</span></label>
    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}"
           class="form-input" placeholder="Ej. Kit ESP32 Pro" required>
    @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label>Categoría <span class="req">*</span></label>
        <select name="categoria" class="form-input" required>
            <option value="">-- Seleccionar --</option>
            @foreach(['iot' => 'IoT / Electrónica', 'software' => 'Software', 'kits' => 'Kits'] as $val => $label)
                <option value="{{ $val }}" {{ old('categoria', $producto->categoria ?? '') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('categoria')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label>Estado <span class="req">*</span></label>
        <select name="estado" class="form-input" required>
            @foreach(['nuevo' => 'Nuevo', 'disponible' => 'Disponible', 'agotado' => 'Agotado'] as $val => $label)
                <option value="{{ $val }}" {{ old('estado', $producto->estado ?? 'disponible') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('estado')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label>Precio actual (S/) <span class="req">*</span></label>
        <input type="number" name="precio" step="0.01" min="0"
               value="{{ old('precio', $producto->precio ?? '') }}"
               class="form-input" placeholder="85.00" required>
        @error('precio')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label>Precio anterior (S/) <small>— opcional, para mostrar descuento</small></label>
        <input type="number" name="precio_anterior" step="0.01" min="0"
               value="{{ old('precio_anterior', $producto->precio_anterior ?? '') }}"
               class="form-input" placeholder="380.00">
        @error('precio_anterior')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="form-group">
    <label>Descripción</label>
    <textarea name="descripcion" class="form-input" rows="3"
              placeholder="Descripción breve del producto...">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    @error('descripcion')<p class="form-error">{{ $message }}</p>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label>Emoji del producto</label>
        <input type="text" name="emoji" maxlength="10"
               value="{{ old('emoji', $producto->emoji ?? '📦') }}"
               class="form-input" style="font-size:1.5rem;width:80px">
    </div>

    <div class="form-group">
        <label>Orden de aparición</label>
        <input type="number" name="orden" min="0"
               value="{{ old('orden', $producto->orden ?? 0) }}"
               class="form-input" style="width:80px">
    </div>
</div>

<div class="form-group">
    <label>Imagen del producto <small>(jpg/png, máx. 2MB)</small></label>
    @if(!empty($producto->imagen))
        <div style="margin-bottom:.5rem">
            <img src="{{ $producto->imagen_url }}" alt="" style="height:80px;border-radius:8px">
        </div>
    @endif
    <input type="file" name="imagen" class="form-input" accept="image/*">
    @error('imagen')<p class="form-error">{{ $message }}</p>@enderror
</div>

<div class="form-group">
    <label class="checkbox-label">
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" value="1"
               {{ old('activo', $producto->activo ?? true) ? 'checked' : '' }}>
        Producto visible en la tienda
    </label>
</div>