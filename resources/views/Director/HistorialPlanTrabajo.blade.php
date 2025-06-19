@extends('layouts.base')

@section('content')
<div class="container">
    <h1 class="mb-4">Historial de Planes de Trabajo</h1>

    <div class="mb-3">
        <a href="{{ route('plan.index', $id) }}" class="btn btn-success">Crear nuevo plan de trabajo</a>
    </div>

    @forelse ($planMes as $mes => $items)
        <div class="mb-5">
            <h4 class="text-primary border-bottom pb-2">
                {{ \Carbon\Carbon::parse($mes)->locale('es')->translatedFormat('F Y') }}
            </h4>

            <ul class="list-group shadow-sm">
                @foreach ($items as $historial)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <div class="flex-grow-1 me-3">
                            <h5 class="mb-1">
                                <strong>{{ ucfirst(str_replace('_', ' ', $historial->tipo_accion ?? 'Actualización')) }}</strong>
                            </h5>
                            <p class="mb-1">
                                <small>
                                    <strong>Fecha de creación:</strong> {{ $historial->fecha_creacion }}<br>
                                    <strong>Estado:</strong> {{ $historial->estado }}<br>
                                    {{-- <strong>ID del Plan:</strong> {{ $historial->id_plan }} --}}
                                </small>
                            </p>
                        </div>

                        <div class="btn-group mt-2 mt-md-0" role="group">
                            <a href="{{ Route('plan.edit', ['id_comite' => $id, 'id_plan' => $historial->id_plan]) }}" class="btn btn-primary btn-sm">Editar</a>
                            <a href="{{ Route('plan.print',$historial->id_plan) }}" class="btn btn-outline-secondary btn-sm">Imprimir</a>
                        </div>

                        <span class="badge bg-dark mt-2 mt-md-0 ms-md-3">
                            {{ \Carbon\Carbon::parse($historial->fecha_creacion)->format('d/m/Y H:i') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-muted">No hay planes de trabajo registrados aún.</p>
    @endforelse
</div>
@endsection
