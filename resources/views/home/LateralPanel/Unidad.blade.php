@extends('layouts.base')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-primary">Mi Unidad Académica</h2>

            @if($unidadAcademica)
                <div class="alert alert-info text-center">
                    <h3 class="mb-0">{{ $unidadAcademica->nombre }}</h3>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    No tienes una unidad académica asignada.
                </div>
            @endif

            <h3 class="mt-4">Mis Programas Académicos</h3>

            @if($programas->isNotEmpty())
                <ul class="list-group mt-3">
                    @foreach($programas as $programa)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $programa->nombre_programa }}
                            <span class="badge bg-primary">ID: {{ $programa->id_programa }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-danger text-center mt-3">
                    No estás registrado en ningún programa académico.
                </div>
            @endif
        </div>
    </div>
@endsection
