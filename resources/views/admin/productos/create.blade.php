{{-- resources/views/admin/productos/create.blade.php --}}
@extends('admin.layout')
@section('title', 'Nuevo Producto — Admin')
@section('content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1 class="admin-title">➕ Nuevo Producto</h1>
            <a href="{{ route('admin.productos.index') }}" class="back-link">← Volver al listado</a>
        </div>
    </div>

    <div class="admin-card" style="max-width:720px">
        <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.productos.form', ['producto' => null])
            <div class="form-actions">
                <a href="{{ route('admin.productos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar producto</button>
            </div>
        </form>
    </div>
</div>
@endsection