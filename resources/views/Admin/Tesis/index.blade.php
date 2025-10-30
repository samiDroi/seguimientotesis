@extends('layouts.base')
@section('content')

@php
    $agrupadasPorTesis = $datosTesis->groupBy('id_tesis');
@endphp

<div class="container">
    <h1>Todas las Tesis y su estructura</h1>

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
                @endphp
                <div class="card mb-4 border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $primero->nombre_tesis }}</h2>

                            <div class="d-flex gap-2">
                                @php
                                    $tieneRequerimientos = $itemsTesis->contains(function($item) {
                                        return !empty($item->nombre_requerimiento);
                                    });
                                @endphp

                                {{-- @if ($tieneRequerimientos)
                                    <a href="{{ route('tesis.requerimientos', $idTesis) }}" class="btn btn-sm btn-warning text-light">Editar requerimientos</a>
                                @endif --}}
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
                                                <strong>{{ $fila->nombre_requerimiento }}</strong>
                                                <br>
                                                <span>Descripción:</span> {{ $fila->desc }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </details>
                        @else
                            <p class="text-muted">No hay requerimientos para esta tesis.</p>
                        @endif

                        {{-- Permiso para crear estructura --}}
                        @if ($primero->nombre_rol === 'administrador' && !$primero->id_requerimiento)
                            <a href="{{ route('tesis.requerimientos', $primero->id_tc) }}">
                                Tiene permitido crear la estructura para esta tesis
                            </a>
                        
                          
                        @endif
                          <a href="{{ route('tesis.requerimientos', $primero->id_tc) }}">
                                Tiene permitido crear la estructura para esta tesis
                            </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <h1>No se encuentra ninguna tesis por el momento</h1>
    @endif
</div>

@if (Auth::user()->esCoordinador == 1)
    @include('Admin.Tesis.Modals.TesisModal')
@endif

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>

<script>
    $("body").on("click",".delete > button",function(event){
        event.preventDefault();
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

    $('#modalTextarea').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var comentario = button.data('comentario')
        var modal = $(this)
        modal.find('.modal-body #comentariosTextarea').val(comentario)
    });
</script>
@endsection
