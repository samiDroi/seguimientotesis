@extends('layouts.admin')
@section('content')

<div class="container">
    <h1>Todas las Tesis y su estructura</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tesisModal">
        Crear Título de la Tesis
    </button>

    @if ($tesis->isNotEmpty())
        <div class="container mt-4">
            @foreach ($tesis as $tesisItem)
               
                <div class="card mb-4 border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                            <div class="d-flex gap-2">
                                @php
                                    $tieneRequerimientos = false;
                                    foreach ($tesisComites as $tesisComite) {
                                        if ($tesisComite->id_tesis == $tesisItem->id_tesis && $tesisComite->requerimientos->isNotEmpty()) {
                                            $tieneRequerimientos = true;
                                            break;
                                        }
                                    }
                                @endphp

                                @if ($tieneRequerimientos)
                                    <a href="{{ route('tesis.requerimientos', $tesisItem->id_tesis) }}" class="btn btn-sm btn-warning">Editar requerimientos</a>
                                @endif
                                {{-- <a href="{{ route('tesis.requerimientos', $tesisItem->id_tesis) }}" class="btn btn-sm btn-warning">Editar requerimientos</a> --}}
                                {{-- <form action="{{ route('tesis.delete', $tesisItem->id_tesis) }}" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                                </form> --}}
                            </div>
                        </div>
                        <div class="mt-2">
                            @if ($tesisItem->comites->isNotEmpty())
                                <span class="text-muted small ms-2">Comité: {{ $tesisItem->comites->first()->nombre_comite }}</span>
                            @else
                                <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                            @endif
                        </div>

                        @php $tieneRequerimientos = false; @endphp
                        @foreach ($tesisComites as $tesisComite)
                            @if ($tesisComite->id_tesis == $tesisItem->id_tesis && $tesisComite->requerimientos->isNotEmpty())
                                @php $tieneRequerimientos = true; @endphp
                                <details>
                                    <summary class="h6 text-secondary">Estructura de la tesis</summary>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($tesisComite->requerimientos as $requerimiento)
                                            <li class="list-group-item px-0">
                                                <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                <br>
                                                <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                                <span class="badge
                                                    @if(strtolower($requerimiento->estado) == 'pendiente') bg-warning 
                                                    @elseif(strtolower($requerimiento->estado) == 'aceptado') bg-success 
                                                    @elseif(strtolower($requerimiento->estado) == 'rechazado') bg-info
                                                    @else bg-dark 
                                                    @endif">
                                                    {{ ucfirst($requerimiento->estado) }}
                                                </span>
                                                @if ($requerimiento->motivo_rechazo)
                                                @include('Admin.Tesis.Modals.MotivoRechazoModal')

                                                <button class="btn btn-sm btn-secondary mt-2 ver-comentario" data-bs-toggle="modal" data-bs-target="#modalTextarea" data-comentario="{{ $requerimiento->motivo_rechazo }}">Ver comentario</button>
                                                @endif
                                                
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            @endif
                        @endforeach

                        @if (!$tieneRequerimientos)
                            <p class="text-muted">No hay requerimientos para esta tesis.</p>
                        @endif

                        @if ($tesisItem->comites->isNotEmpty() && isset($tesisComite) && $tesisComite->requerimientos->isEmpty()
                             && $tesisItem->comites->pluck('id_comite')->contains(fn($id) => comprobarRolComite('DIRECTOR', $id)))
                            <a href="{{ Route('tesis.requerimientos', $tesisComite->id_tesis_comite) }}" class="">Tiene permitido crearla estructura para esta tesis</a>
                        @endif
                    </div>
                </div>
                
            @endforeach
        </div>
    @else
        <h1>No se encuentra ninguna tesis por el momento</h1>
    @endif
</div>

@endsection
@include('Admin.Tesis.Modals.TesisModal')



 


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

    <script>
        // Esto captura el comentario del requerimiento y lo coloca en el textarea del modal
        $('#modalTextarea').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Botón que activó el modal
            var comentario = button.data('comentario') // Extrae el comentario

            var modal = $(this)
            modal.find('.modal-body #comentariosTextarea').val(comentario) // Coloca el comentario en el textarea
        })

    </script>
@endsection
