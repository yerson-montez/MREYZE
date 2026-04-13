{{-- resources/views/admin/productos/index.blade.php --}}
@extends('admin.layout')

@section('title', 'Productos — Admin')

@section('content')
<div class="admin-page">

    {{-- Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-title">🛒 Productos</h1>
            <p class="admin-sub">{{ $productos->total() }} productos en catálogo</p>
        </div>
        <a href="{{ route('admin.productos.create') }}" class="btn-primary">
            + Nuevo producto
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabla --}}
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Producto</th>
                    <th>Cat.</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="sortable-productos">
                @forelse($productos as $p)
                <tr data-id="{{ $p->id }}">
                    <td class="drag-handle" title="Arrastrar para reordenar">⠿</td>
                    <td>
                        <span style="font-size:1.3rem">{{ $p->emoji }}</span>
                        <strong>{{ $p->nombre }}</strong>
                    </td>
                    <td><span class="badge badge-{{ $p->categoria }}">{{ ucfirst($p->categoria) }}</span></td>
                    <td>
                        S/ {{ number_format($p->precio, 2) }}
                        @if($p->precio_anterior)
                            <br><small style="text-decoration:line-through;color:#888">S/ {{ number_format($p->precio_anterior,2) }}</small>
                        @endif
                    </td>
                    <td><span class="badge badge-estado-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
                    <td>
                        <span class="badge {{ $p->activo ? 'badge-green' : 'badge-gray' }}">
                            {{ $p->activo ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td class="acciones">
                        <a href="{{ route('admin.productos.edit', $p) }}" class="btn-edit">Editar</a>
                        <form method="POST" action="{{ route('admin.productos.destroy', $p) }}"
                              onsubmit="return confirm('¿Eliminar «{{ $p->nombre }}»?')">
                            @csrf @method('DELETE')
                            <button class="btn-delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:2rem;color:#888">No hay productos aún.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem">{{ $productos->links() }}</div>
    </div>

</div>

{{-- Drag & drop reordenamiento --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const tbody = document.getElementById('sortable-productos');
if (tbody) {
    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            const ids = [...tbody.querySelectorAll('tr[data-id]')].map(tr => tr.dataset.id);
            fetch('{{ route('admin.productos.reordenar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orden: ids })
            });
        }
    });
}
</script>
@endsection