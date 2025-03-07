@extends('layouts.admin')
@section('content')
    <a href="{{ Route('tesis.review') }}">Revision de tesis del area</a>
    {{-- @include('home.index') --}}
    <!-- Mis tesis -->
<div>
    @foreach ($tesisUsuario as $tesis)
        <div class="card mb-4 border-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesis->nombre_tesis }}</h2>
                </div>

                <div class="mt-2">
                    @if ($tesis->comites->isNotEmpty())
                        <span class="text-muted small ms-2">Comité: {{ $tesis->comites->first()->nombre_comite }}</span>
                    @else
                        <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                        @if (Auth::user()->esCoordinador == 1)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">Asignar Comite</button> 
                        @endif
                    @endif
                </div>

                <!-- Requerimientos -->
                @foreach ($tesisComites as $tesisComite)
                    @if ($tesisComite->id_tesis == $tesis->id_tesis)
                        @if ($tesisComite->requerimientos->isNotEmpty())
                            <details>
                                <summary class="h6 text-secondary">Requerimientos</summary>
                                <ul class="list-group list-group-flush">
                                    @foreach ($tesisComite->requerimientos as $requerimiento)
                                        <li class="list-group-item px-0">
                                            <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                            
                                            <a href="{{ Route("avance.index", $requerimiento->id_requerimiento) }}">Realizar avance a este requerimiento</a>
                                            
                                            <br>
                                            <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                            <span class="badge 
                                                @if($requerimiento->estado == 'pendiente') bg-warning 
                                                @elseif($requerimiento->estado == 'completado') bg-success 
                                                @elseif($requerimiento->estado == 'en_proceso') bg-info 
                                                @endif">
                                                {{ ucfirst($requerimiento->estado) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @else
                            <p>No hay requerimientos para esta tesis.</p>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- Tesis auditadas por mi comite -->
<h1>Tesis auditadas por mi comite</h1>
<div>
    @foreach ($tesisDeComite as $tesisComiteItem)
        <div class="card mb-4 border-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisComiteItem->nombre_tesis }}</h2>
                </div>

                <div class="mt-2">
                    @if ($tesisComiteItem->comites->isNotEmpty())
                        <span class="text-muted small ms-2">Comité: {{ $tesisComiteItem->comites->first()->nombre_comite }}</span>
                    @else
                        <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                    @endif
                </div>

                <!-- Requerimientos -->
                @foreach ($tesisComites as $tesisComite)
                    @if ($tesisComite->id_tesis == $tesisComiteItem->id_tesis)
                        @if ($tesisComite->requerimientos->isNotEmpty())
                            <details>
                                <summary class="h6 text-secondary">Requerimientos</summary>
                                <ul class="list-group list-group-flush">
                                    @foreach ($tesisComite->requerimientos as $requerimiento)
                                        <li class="list-group-item px-0">
                                            <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                            @if ($requerimiento->avances->isNotEmpty())
                                                <a href="{{ Route("avance.index", $requerimiento->id_requerimiento) }}">Revisar avance de este requerimiento</a>
                                            @endif
                                            {{-- <a href="{{ Route("avance.index", $requerimiento->id_requerimiento) }}">Revisar avance de este requerimiento</a> --}}
                                            <br>
                                            <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                            <span class="badge 
                                                @if($requerimiento->estado == 'pendiente') bg-warning 
                                                @elseif($requerimiento->estado == 'completado') bg-success 
                                                @elseif($requerimiento->estado == 'en_proceso') bg-info 
                                                @endif">
                                                {{ ucfirst($requerimiento->estado) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @else
                            <p>No hay requerimientos para esta tesis.</p>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection