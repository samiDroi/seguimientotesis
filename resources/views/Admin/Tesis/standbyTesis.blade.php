@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Todas las Tesis y sus Requerimientos</h1>
    <div class="container mt-4">
        @foreach ($tesisComites as $tesisComite)
            <div class="card mb-4 border-secondary">
                <div class="card-body">
                    <!-- Contenedor flex para nombre de tesis, botones y comité -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Título de la Tesis -->
                        <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisComite->tesis->nombre_tesis }}</h2>
                        
                        <!-- Botones: Editar y Eliminar -->
                        {{-- <div class="d-flex gap-2">

                            <form action="{{ Route("tesis.delete",$tesisComite->id_tesis_comite) }}" class="delete" method="POST">
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                            </form>
                        </div> --}}

                        <!-- Texto del Comité -->
                        {{-- @if ($tesisComite->comite)
                            <span class="text-muted small ms-2">Comité: {{ $tesisComite->comite->nombre_comite }}</span>
                        @endif --}}
                    </div>

                    <!-- Requerimientos -->
                    <details>
                        <summary class="h6 text-secondary">Requerimientos</summary>
                        <ul class="list-group list-group-flush">
                            @foreach ($tesisComite->requerimientos as $requerimiento)
                                <li class="list-group-item px-0">
                                    <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                    <br>
                                    <span>Descripción:</span> {{ $requerimiento->descripcion }}

                                     <!-- Estado Actual -->
                                     <span class="badge bg-secondary ms-2">{{ ucfirst($requerimiento->estado) }}</span>

                                     <!-- Botones de Aceptar y Rechazar -->
                                     <div class="d-flex gap-2 mt-2">
                                         <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST" class="d-inline">
                                             @csrf
                                             @method('POST') 
                                             <input type="hidden" name="estado" value="ACEPTADO"> <!-- Estado a Aceptado -->
                                             <button type="submit" class="btn btn-sm btn-success">Aceptar</button>
                                         </form>
 
                                         <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST" class="d-inline">
                                             @csrf
                                             @method('POST') 
                                             <input type="hidden" name="estado" value="RECHAZADO"> <!-- Estado a Rechazado -->
                                             <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                         </form>
                                     </div>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                    @if ($tesisComite->requerimientos->every(fn($requerimiento) => $requerimiento->estado == 'ACEPTADO'))
                        <!-- Mostrar el Modal si todos los requerimientos están aceptados -->
                        <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#comiteModal">Seleccionar Comité</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal para seleccionar un comité -->
<div class="modal fade" id="comiteModal" tabindex="-1" aria-labelledby="comiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comiteModalLabel">Seleccionar Comité para la Tesis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tesis.comite.attach') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tesis_id" value="{{ $tesisComite->tesis->id_tesis }}">

                    <div class="mb-3">
                        <label for="comite_id" class="form-label">Seleccione un Comité</label>
                        <select class="form-select" id="comite_id" name="comite_id" required>
                            {{-- @foreach ($comites as $comite)
                                <option value="{{ $comite->id_comite }}">{{ $comite->nombre_comite }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Asignar Comité</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection