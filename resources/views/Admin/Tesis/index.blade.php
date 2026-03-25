@extends('layouts.base')
@section('content')

@php
    $agrupadasPorTesis = $datosTesis->groupBy('id_tesis');
@endphp

<div class="container">
    <h1>Todas las Tesis y su estructura</h1>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="tesisTabs" role="tablist">
          <!-- Ta1 -->
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="todas-tab" data-bs-toggle="tab" data-bs-target="#todas" type="button" role="tab">
                Todas las Tesis
            </button>
        </li>
          <!-- Ta2 -->
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="plan-tab" data-bs-toggle="tab" data-bs-target="#plan" type="button" role="tab">
                Plan de trabajo
            </button>
        </li>
          <!-- Ta3 -->
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="estructura-tab" data-bs-toggle="tab" data-bs-target="#estructura" type="button" role="tab">
                Estructura de tesis
            </button>
        </li>
          <!-- T4 -->
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ver-tab" data-bs-toggle="tab" data-bs-target="#ver" type="button" role="tab">
                Ver tesis
            </button>
        </li>
    </ul>

    <!-- T5 -->
    <div class="tab-content mt-3" id="tesisTabsContent">
        <!-- Pestaña Todas las Tesis -->
        <div class="tab-pane fade show active" id="todas" role="tabpanel">
            @if (Auth::user()->esCoordinador == 1)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tesisModal">
                    Crear Título de la Tesis
                </button>
            @endif

            @if ($agrupadasPorTesis->isNotEmpty())
                <div class="container mt-4">
                    @foreach ($agrupadasPorTesis as $idTesis => $itemsTesis)
                        @php
                            $primero = $itemsTesis->first();
                            $tieneRequerimientos = $itemsTesis->contains(fn($item) => !empty($item->nombre_requerimiento));
                        @endphp
                        <div class="card mb-4 border-secondary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $primero->nombre_tesis }}</h2>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('plan.historial',$primero->id_comite) }}">Generar plan de trabajo</a>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    @if (!empty($primero->id_comite))
                                        <span class="text-muted small ms-2">Comité: {{ $primero->nombre_comite }}</span>
                                    @else
                                        <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                                    @endif
                                </div>

                                @if ($tieneRequerimientos)
                                    <details>
                                        <summary class="h6 text-secondary">Estructura de la tesis</summary>
                                        <ul class="list-group list-group-flush">
                                            @foreach ($itemsTesis as $fila)
                                                @if (!empty($fila->nombre_requerimiento))
                                                    <li class="list-group-item px-0">
                                                        <strong>{{ $fila->nombre_requerimiento }}</strong><br>
                                                        <span>Descripción:</span> {{ $fila->desc }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </details>
                                @else
                                    <p class="text-muted">No hay requerimientos para esta tesis.</p>
                                @endif

                                @if ($primero->nombre_rol === 'administrador' && !$primero->id_requerimiento)
                                    <a href="{{ route('tesis.requerimientos', $primero->id_tc) }}">
                                        Tiene permitido crear la estructura para esta tesis
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <h1>No se encuentra ninguna tesis por el momento</h1>
            @endif
        </div>





        <!-- Pestaña Plan de trabajo -->
        <div class="tab-pane fade" id="plan" role="tabpanel">
        @if ($agrupadasPorTesis->isNotEmpty())
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($agrupadasPorTesis as $idTesis => $itemsTesis)
                @php
                    $primero = $itemsTesis->first();
                @endphp
                <div class="col">
                    <div class="card shadow-sm border-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <!-- Título de la tesis -->
                            <h5 class="card-title text-dark fw-bold mb-3">
                                {{ $primero->nombre_tesis }}
                            </h5>

                            <!-- Botón para generar plan -->
                            <a href="{{ route('plan.historial', $primero->id_comite) }}" 
                               class="btn btn-primary mt-auto">
                                Generar plan de trabajo
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <h1>No se encuentra ninguna tesis por el momento</h1>
@endif


        </div>

        <!-- Pestaña Estructura de tesis -->
        <div class="tab-pane fade" id="estructura" role="tabpanel">
            @if ($agrupadasPorTesis->isNotEmpty())
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($agrupadasPorTesis as $idTesis => $itemsTesis)
                @php
                    $primero = $itemsTesis->first();
                @endphp
                <div class="col">
                    <div class="card shadow-sm border-secondary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <!-- Título de la tesis -->
                            <h5 class="card-title text-dark fw-bold mb-3">
                                {{ $primero->nombre_tesis }}
                            </h5>

                            <!-- Botón para gestionar estructura -->
                            <a href="{{ route('tesis.requerimientos', $primero->id_tc) }}" 
                               class="btn btn-secondary mt-auto">
                                Gestionar estructura de tesis
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <h1>No se encuentra ninguna tesis por el momento</h1>
@endif

        </div>

        <!-- Pestaña Ver tesis -->
        <div class="tab-pane fade" id="ver" role="tabpanel">
            <p>Aquí se mostrará la tesis completa...</p>
        </div>
    </div>
</div>

@if (Auth::user()->esCoordinador == 1)
    @include('Admin.Tesis.Modals.TesisModal')
@endif

@endsection
