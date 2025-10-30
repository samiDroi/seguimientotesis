@extends('layouts.admin')
@section('content')

<div class="container">
    <br>
    
    @include('Admin.Tesis.Modals.TesisModal')
   
    <x-Titulos text="Evaluar tesis"/>
    <x-boton-modal clases="crear-tesis" text="Crear titulo de la tesis" target="tesisModal" icon=plus/>
   
    <div class="text-end mt-4 enlace-a">
        <a class="text-decoration-none" href="{{ route('tesis.admin') }}">
            Acceder a mis tesis asignadas <i class="fa-solid fa-arrow-right-long"></i>
        </a>
    </div>
 
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
                        $comitesTesis = $tesisComites->where('id_tesis', $tesisItem->id_tesis);
                        $alumnosMostrados = collect();
                    @endphp

                    <div class="card mb-4 border-secondary Tesis {{ str_replace(' ', '_', strtolower($tesisItem->estado)) }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
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

                            @if ($tesisItem->comites->first() && $tesisItem->comites->first()->usuarios->isEmpty())
                                @php
                                    $comite = $tesisItem->comites->first();
                                    $alumno = $tesisItem->usuarios->first();
                                @endphp

                                <div class="mt-3">
                                    <a href="{{ route('comites.members', [$comite->id_comite, 'idAlumno' => $alumno->id_user]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                        Gestionar comité
                                    </a>
                                </div>
                            @endif

                            @if ($comitesTesis->isNotEmpty())
                                <details>
                                    <summary>Estructura</summary>
                                    @foreach ($comitesTesis as $tesisComite)
                                        @foreach ($tesisComite->requerimientos as $requerimiento)
                                            <li class="list-group-item px-0">
                                                <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                <br>
                                                <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                            </li>
                                        @endforeach
                                    @endforeach
                                </details>
                            @else
                                <p>Esta tesis aun no tiene estructura.</p>
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
        $(".select2").each(function(){
            $(this).select2({
                theme: "bootstrap-5",
                dropdownParent: $("#tesisModal")
            });
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
