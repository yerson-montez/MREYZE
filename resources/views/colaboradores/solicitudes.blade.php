@extends('layouts.admin')

@section('title', 'Solicitudes de Colaboradores')

@section('content')

<div class="container" style="padding:20px;">

    <h2 style="margin-bottom:20px;">📋 Solicitudes de Colaboradores</h2>

    @if($solicitudes->isEmpty())
        <div style="background:#0d1f33;padding:20px;border-radius:10px;">
            No hay solicitudes registradas.
        </div>
    @else

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;background:#0d1f33;border-radius:10px;overflow:hidden;">
            <thead style="background:#081428;">
                <tr>
                    <th style="padding:12px;">#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($solicitudes as $solicitud)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                    <td style="padding:10px;">{{ $solicitud->id }}</td>
                    <td>{{ $solicitud->nombre }}</td>
                    <td>{{ $solicitud->email }}</td>
                    <td>{{ $solicitud->telefono ?? '-' }}</td>
                    <td>{{ $solicitud->mensaje ?? '-' }}</td>
                    <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>

                    <td style="display:flex;gap:5px;">

                        {{-- Aprobar --}}
                        <form action="{{ route('admin.colaboradores.aprobar', $solicitud->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button style="background:green;color:white;padding:6px 10px;border:none;border-radius:6px;">
                                ✔
                            </button>
                        </form>

                        {{-- Rechazar --}}
                        <form action="{{ route('admin.colaboradores.rechazar', $solicitud->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button style="background:red;color:white;padding:6px 10px;border:none;border-radius:6px;">
                                ✖
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endif

</div>

@endsection