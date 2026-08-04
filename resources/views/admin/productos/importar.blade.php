@extends('layouts.admin')

@section('title','Importar Productos')

@section('content')

<div class="container">
    <h1>Importar Productos</h1>

    @if(session('exito'))
        <div class="alert alert-success">
            {{ session('exito') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST"
          action="{{ route('admin.productos.importar') }}"
          enctype="multipart/form-data">

        @csrf

        <div>
            <label for="archivo">
                Seleccionar archivo Excel
            </label>

            <input 
                type="file"
                id="archivo"
                name="archivo"
                accept=".xlsx,.xls,.csv"
                required
            >
        </div>

        <br>

        <button type="submit">
            Importar Productos
        </button>

    </form>

</div>

@endsection