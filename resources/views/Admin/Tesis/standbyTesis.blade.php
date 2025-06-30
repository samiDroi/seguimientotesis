@extends('layouts.admin')
@section('content')
{{-- @dd($usuarios) --}}
<div class="container">
    <br>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tesisModal">
        Crear Título de la Tesis
    </button>
    @include('Admin.Tesis.Modals.TesisModal')

    <h1>Todas las Tesis y sus Requerimientos</h1>

    <ul class="nav nav-tabs fs-5" id="filterNav">
        <li class="nav-item"><a class="nav-link active" data-filter="Todos">Todos</a></li>
        <li class="nav-item"><a class="nav-link" data-filter="EN DEFINICION">En definición</a></li>
        <li class="nav-item"><a class="nav-link" data-filter="EN CURSO">En curso</a></li>
        <li class="nav-item"><a class="nav-link" data-filter="POR EVALUAR">Por evaluar</a></li>
    </ul>

    <div class="container mt-4">
        @if ($tesis->isNotEmpty())

            @php
            $estados = ['EN DEFINICION', 'EN CURSO', 'POR EVALUAR', 'RECHAZADA', 'ACEPTADA'];
            @endphp

            @foreach($estados as $estado)
                @foreach ($tesis->where("estado", $estado) as $tesisItem)

                    @php
                        $colorEstado = match ($tesisItem->estado) {
                            'EN DEFINICION' => 'text-secondary',
                            'EN CURSO' => 'text-primary',
                            'POR EVALUAR' => 'text-warning',
                            'RECHAZADA' => 'text-danger',
                            'ACEPTADA' => 'text-success',
                            default => 'text-secondary',
                        };

                        $comitesTesis = $tesisComites->where('id_tesis', $tesisItem->id_tesis);
                        $tieneRequerimientos = $comitesTesis->some(function ($comite) {
                            return $comite->requerimientos->isNotEmpty();
                        });

                        $alumnosMostrados = collect();
                    @endphp

                    <div class="card mb-4 border-secondary Tesis {{ str_replace(' ', '_', strtolower($tesisItem->estado)) }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                                <h3 class="fs-5 text-end me-5 {{ $colorEstado }}">{{ $tesisItem->estado }}</h3>
                            </div>

                            <div class="mt-2">
                                @if ($tesisItem->comites->isNotEmpty())
                                    <span class="text-muted small ms-2">Comité: {{ $tesisItem->comites->first()->nombre_comite }}</span>
                                    @php $directorMostrado = false; @endphp
                                    @foreach ($directores as $director)
                                        @if ($tesisItem->id_tesis == $director->id_tesis && !$directorMostrado)
                                            <span class="text-muted small ms-2">Director de tesis: {{ $director->nombre . " " . $director->apellidos }}</span>
                                            @php $directorMostrado = true; @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($tesisItem->usuarios as $alumno)
                                        @if (!$alumnosMostrados->contains($alumno->id))
                                            <span class="text-muted small ms-2">Alumno(s): {{ $alumno->nombre . " " . $alumno->apellidos }}</span>
                                            @php $alumnosMostrados->push($alumno->id); @endphp
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                                    @if (Auth::user()->esCoordinador == 1)
                                        @include('admin.tesis.Modals.ComiteAlumnoModal')
                                        <button type="button" class=" ms-2 btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">
                                            Asignar Comité
                                        </button>
                                    @endif
                                @endif
                            </div>

                            @if ($tieneRequerimientos)
                                @foreach ($comitesTesis as $tesisComite)
                                    @foreach ($tesisComite->requerimientos as $requerimiento)
                                        @include('admin.tesis.Modals.MotivoRechazoModal')
                                        <li class="list-group-item px-0">
                                            <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                            <br>
                                            <span>Descripción:</span> {{ $requerimiento->descripcion }}

                                            @if (isset($requerimiento->estado))
                                                <span class="badge 
                                                    @if(strtolower($requerimiento->estado) == 'pendiente') bg-warning 
                                                    @elseif(strtolower($requerimiento->estado) == 'aceptado') bg-success 
                                                    @elseif(strtolower($requerimiento->estado) == 'rechazado') bg-danger
                                                    @else bg-secondary 
                                                    @endif">
                                                    {{ ucfirst($requerimiento->estado) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Estado desconocido</span>
                                            @endif

                                            <div class="d-flex gap-2 mt-2">
                                                <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="estado" value="ACEPTADO">
                                                    <button type="submit" class="btn btn-sm btn-success">Aceptar</button>
                                                </form>

                                                <form action="{{ route('tesis.review.update', $requerimiento->id_requerimiento) }}" method="POST" class="rechazarForm">
                                                    @csrf
                                                    <input type="hidden" name="estado" value="RECHAZADO">
                                                    <button type="button" class="btn btn-sm btn-danger btn-modal-rechazo" data-bs-toggle="modal" data-bs-target="#modalTextarea">Rechazar</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                @endforeach
                            @else
                                <p>No hay requerimientos para esta tesis.</p>
                            @endif

                            @if ($tesisItem->comites->isNotEmpty() && !$tieneRequerimientos)
                                <a href="{{ route('tesis.requerimientos', optional($comitesTesis->first())->id_tesis_comite) }}">
                                    Tiene permitido crear requerimientos para esta tesis
                                </a>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endforeach

        @else
            <p>No hay tesis registradas.</p>
        @endif
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        let referencia;
        $('.btn-modal-rechazo').on('click', function () {
            referencia = $(this).closest('form');
        });

        $('#submitRechazoBtn').on('click', function() {
            var comentario = $('#comentariosTextarea').val();

            if ($.trim(comentario) === '') {
                alert('Por favor, escribe un comentario.');
                return;
            }

            var inputComentario = $('<input>').attr({
                type: 'hidden',
                name: 'comentario',
                value: comentario
            });

            $(referencia).append(inputComentario);
            $(referencia).submit();
        });

        $(".nav-link").on("click", function () {
            let filtro = $(this).data("filter");

            $(".nav-link").removeClass("active");
            $(this).addClass("active");

            if (filtro === "Todos") {
                $(".Tesis").show();
            } else {
                $(".Tesis").hide();
                $("." + filtro.replace(/ /g, "_").toLowerCase()).show();
            }
        });
    });
</script>
@endsection