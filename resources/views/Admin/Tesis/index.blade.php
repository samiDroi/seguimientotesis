@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Todas las Tesis y sus Requerimientos</h1>
    {{-- <a href="{{ Route('tesis.store',$tesisComite->id_tesis_comite ?? '') }}" class="btn btn-primary">Crear nueva tesis</a> --}}
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tesisModal">
        Crear Título de la Tesis
      </button>
  <div class="container mt-4">
    @foreach ($tesis as $tesisItem)
    <div class="card mb-4 border-secondary">
        <div class="card-body">
            <!-- Contenedor flex para nombre de tesis, botones y comité -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Título de la Tesis -->
                <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                
                <!-- Botones: Editar y Eliminar -->
                <div class="d-flex gap-2">
                    <a href="{{ route('tesis.requerimientos', $tesisItem->id_tesis) }}" class="btn btn-sm btn-warning">Editar requerimientos</a>

                    <form action="{{ route('tesis.delete', $tesisItem->id_tesis) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                    </form>
                </div>
            </div>

            <!-- Comité -->
            <div class="mt-2">
                @if ($tesisItem->comites->isNotEmpty())
                    <span class="text-muted small ms-2">Comité: {{ $tesisItem->comites->first()->nombre_comite }}</span>
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
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    @else
                        <p>No hay requerimientos para esta tesis.</p>
                    @endif
                @endif
            @endforeach

            @if ($tesisItem->comites->isNotEmpty() && $tesisComite->requerimientos->isEmpty())
                <a href="{{ Route("tesis.requerimientos",$tesisComite->id_tesis_comite) }}" class="">Tiene permitido crear requerimientos para esta tesis</a>
            @endif
            
        </div>
    </div>
@endforeach

    {{-- @foreach ($tesis as $tesisItem)
        <div class="card mb-4 border-secondary">
            <div class="card-body">
                <!-- Contenedor flex para nombre de tesis, botones y comité -->
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Título de la Tesis -->
                    <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                    
                    <!-- Botones: Editar y Eliminar -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('tesis.store', $tesisItem->id_tesis) }}" class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('tesis.delete', $tesisItem->id_tesis) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                        </form>
                    </div>
                </div>

                <!-- Comité -->
                <div class="mt-2">
                    @if ($tesisItem->comites->isNotEmpty())
                        <!-- Si tiene comité asignado -->
                        <span class="text-muted small ms-2">Comité: {{ $tesisItem->comites->first()->nombre_comite }}</span>
                    @else
                        <!-- Si no tiene comité asignado -->
                        <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                    @endif
                </div>
                
                @foreach ($tesisComites as $tesisComite)
                @if ($tesisComite->requerimientos->isNotEmpty() || $tesisItem->comites->isNotEmpty())
                    <!-- Requerimientos de este TesisComite -->
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
                                            @if($requerimiento->estado == 'pendiente') bg-warning 
                                            @elseif($requerimiento->estado == 'completado') bg-success 
                                            @elseif($requerimiento->estado == 'en_proceso') bg-info 
                                            @endif">
                                            {{ ucfirst($requerimiento->estado) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Estado desconocido</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </details>
                @else
                    @if (Auth::user()->esCoordinador == 1)
                                             
                    @endif

                    @if ($tesisItem->comites->isNotEmpty() && $tesisComite->requerimientos->isEmpty())
                        <a href="">Tiene permitido crear requerimientos para esta tesis</a>
                    @endif
                    <p>No hay requerimientos para esta tesis.</p>
                @endif
            @endforeach
            
            </div>
        </div>
    @endforeach --}}
</div>
@include('Admin.Tesis.Modals.TesisModal')
@include('Admin.Tesis.Modals.ComiteAlumnoModal')



 
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>
    <script>
        
        $("body").on("click",".delete > button",function(){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Tesis",
                text: "Estas a punto de eliminar esta tesis, esto no puede ser reversible ¿Estas seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar"
            }).then((result) => {
            if (result.isConfirmed) {
                $(formulario).submit();
               
            }
        });
    });
    </script>
@endsection
