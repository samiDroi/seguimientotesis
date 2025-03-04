@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Todas las Tesis y sus Requerimientos</h1>
    {{-- <a href="{{ Route('tesis.store',$tesisComite->id_tesis_comite ?? '') }}" class="btn btn-primary">Crear nueva tesis</a> --}}
    
  <div class="container mt-4">
    @foreach ($tesis as $tesisItem)
    <div class="card mb-4 border-secondary">
        <div class="card-body">
            <!-- Contenedor flex para nombre de tesis, botones y comité -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Título de la Tesis -->
                <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                @if (isset($tesisItem->estado))
                <span class="badge 
                    @if(strtolower($tesisItem->estado) == 'pendiente') bg-warning 
                    @elseif(strtolower($tesisItem->estado) == 'aprobada') bg-success 
                    @elseif(strtolower($tesisItem->estado) == 'rechazada') bg-danger 
                    @else bg-secondary 
                    @endif">
                    {{ ucfirst($tesisItem->estado) }}
                </span>
            @else
                <span class="badge bg-secondary">Estado desconocido</span>
            @endif
                
            </div>
            <!-- Comité -->
            <div class="mt-2">
                @if ($tesisItem->comites->isNotEmpty())
                    <span class="text-muted small ms-2">Comité: {{ $tesisItem->comites->first()->nombre_comite }}</span>
                    @foreach ($directores as $director)
                    @if ($tesisItem->id_tesis == $director->id_tesis)
                        <span class="text-muted small ms-2">Director de tesis: {{ $director->nombre . " ",$director->apellidos }}</span>
                    @endif
                @endforeach
                
                @foreach ($tesisItem->usuarios as $alumno)
                <span class="text-muted small ms-2">Alumno(s): {{ $alumno->nombre . " ",$alumno->apellidos }}</span>
                @endforeach
                
                @else
                   
                    <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                    @if (Auth::user()->esCoordinador == 1)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">
                            Asignar Comite
                        </button> 
                    @endif
                @endif
            </div>

            <!-- Verifica si esta tesis tiene comités asignados y requerimientos asociados -->
            @foreach ($tesisComites as $tesisComite)
            {{-- <h1 class="display-2">{{ $loop->iteration }}</h1>
            <h1 class="display-2">{{ rand(1,1000) }}</h1> --}}
                @if ($tesisComite->id_tesis == $tesisItem->id_tesis) <!-- Compara con la tesis actual -->  
                    @if ($tesisComite->requerimientos->isNotEmpty())
                        <!-- Requerimientos de esta TesisComite -->
                        <details>
                            <summary class="h6 text-secondary">Requerimientos</summary>
                            <ul class="list-group list-group-flush">
                                @foreach ($tesisComite->requerimientos as $requerimiento)
                                    <li class="list-group-item px-0">
                                        <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                        <br>
                                        <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                        
                                        <!-- Estado del requerimiento -->
                                        @if (isset($requerimiento->estado))
                                        
                                            <span class="badge
                                                @if(strtolower($requerimiento->estado) == 'pendiente') bg-warning 
                                                @elseif(strtolower($requerimiento->estado) == 'aceptado') bg-success 
                                                @elseif(strtolower($requerimiento->estado) == 'rechazado') bg-info
                                                @else bg-dark 
                                                @endif">
                                                {{ ucfirst($requerimiento->estado) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Estado desconocido</span>
                                        @endif
                                        <!-- Botones de Aceptar y Rechazar -->
                                        <div class="d-flex gap-2 mt-2">
                                            <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST') 
                                                <input type="hidden" name="estado" value="ACEPTADO"> <!-- Estado a Aceptado -->
                                                <button type="submit" class="btn btn-sm btn-success">Aceptar</button>
                                            </form>

                                            <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST" class="d-inline rechazarForm" >
                                                @csrf
                                                @method('POST') 
                                                <input type="hidden" name="estado" value="RECHAZADO"> <!-- Estado a Rechazado -->
                                                <button type="button" class="btn btn-sm btn-danger btn-modal-rechazo" data-bs-toggle="modal" data-bs-target="#modalTextarea" >Rechazar</button>
                                            </form>
                                        </div>
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

@include('Admin.Tesis.Modals.ComiteAlumnoModal')
@include('Admin.Tesis.Modals.MotivoRechazoModal')
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let referencia;
        $('.btn-modal-rechazo').on('click',function () {
             referencia = $(this).closest('form');
        })
        // Evento al hacer clic en el botón "Rechazar"
        $('#submitRechazoBtn').on('click', function() {
            // Obtener el comentario del textarea
            var comentario = $('#comentariosTextarea').val();

            // Si el comentario está vacío, mostrar una alerta y no enviar el formulario
            if ($.trim(comentario) === '') {
                alert('Por favor, escribe un comentario.');
                return;
            }

            // Crear un input oculto para el comentario
            var inputComentario = $('<input>').attr({
                type: 'hidden',
                name: 'comentario',
                value: comentario
            });

            // Agregar el input oculto al formulario
            $(referencia).append(inputComentario);

            // Enviar el formulario
            $(referencia).submit();
        });
    });
</script>
@endsection
