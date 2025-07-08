@extends('layouts.base')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-light fw-semibold py-2 px-2 rounded"" style="background-color:var(--color-azul-obscuro)"><i class="fa-solid fa-school-flag"></i> Mi Unidad Académica</h2>

            @if($unidadAcademica)
                <div class="alert  text-center" >
                    <h3 class="mb-0"> {{ $unidadAcademica->nombre }}</h3>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    No tienes una unidad académica asignada.
                </div>
            @endif
                <div class="mt-4 text-light py-2 px-2 rounded" style="background-color: var(--color-azul-obscuro)">
            <h3 class="ms-4"><i class="fa-solid fa-graduation-cap"></i> Mis Programas Académicos</h3>
            </div>

            @if($programas->isNotEmpty())
                <ul class="list-group mt-3">
                    @foreach($programas as $programa)
                        <li class="list-group-item d-flex justify-content-between align-items-center " >
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
