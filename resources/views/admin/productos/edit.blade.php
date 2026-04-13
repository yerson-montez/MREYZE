{{-- resources/views/admin/productos/edit.blade.php --}}
@extends('admin.layout')
@section('title', 'Editar Producto — Admin')
@section('content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1 class="admin-title">✏️ Editar: {{ $producto->nombre }}</h1>
            <a href="{{ route('admin.productos.index') }}" class="back-link">← Volver al listado</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card" style="max-width:720px">
        <form method="POST" action="{{ route('admin.productos.update', $producto) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.productos.form')
            <div class="form-actions">
                <a href="{{ route('admin.productos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar producto</button>
            </div>
        </form>
    </div>
</div>
@endsection