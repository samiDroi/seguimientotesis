@extends('layouts.admin')
@section('content')

<div class="container">
    <h1>Todas las Tesis y sus Requerimientos</h1>
    {{-- <a href="{{ Route('tesis.store',$tesisComite->id_tesis_comite ?? '') }}" class="btn btn-primary">Crear nueva tesis</a> --}}

    <ul class="nav nav-tabs fs-5" id="filterNav">
    <li class="nav-item">
        <a class="nav-link active" data-filter="Todos">Todos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-filter="EN DEFINICION">En definición</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-filter="EN CURSO">En curso</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-filter="POR EVALUAR">Por evaluar</a>
    </li>
</ul>


    <div class="container mt-4 ">
        @if ($tesis->isNotEmpty())
       

        @php 
        $estados=['EN DEFINICION','EN CURSO','POR EVALUAR','RECHAZADA','ACEPTADA'];
        @endphp
        @foreach($estados as $estado)
        @foreach ($tesis->where("estado",$estado) as $tesisItem)

        @php
        $colorEstado = match ($tesisItem->estado) {
            'EN DEFINICION' => 'text-secondary',  // Azul
            'EN CURSO' => 'text-primary',       // Amarillo
            'POR EVALUAR' => 'text-warning',       // Celeste
            'RECHAZADA' => 'text-danger',       // Rojo
            'ACEPTADA' => 'text-success',       // Verde
            default => 'text-secondary',        // Gris por defecto
            };
        @endphp

        <div class="card mb-4 border-secondary Tesis {{ str_replace(' ', '_', strtolower($tesisItem->estado)) }}">

            <div class="card-body">
                <!-- Contenedor flex para nombre de tesis, botones y comité -->
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Título de la Tesis -->
                    <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisItem->nombre_tesis }}</h2>
                    <h3 class=" fs-5 text-end me-5 {{ $colorEstado }} "> {{ $tesisItem->estado }}</h3>

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
                <a href="{{ Route("tesis.requerimientos",$tesisComite->id_tesis_comite)}}" class="">Tiene permitido crear requerimientos para esta tesis</a>
                @endif

            </div>
        </div>

        @endforeach
        @endforeach


        @endif
    </div>




    @include('Admin.Tesis.Modals.ComiteAlumnoModal')




    @endsection

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("#filterNav .nav-link");
        const tesisCards = document.querySelectorAll(".Tesis");

        navLinks.forEach(link => {
            link.addEventListener("click", function () {
                const filter = this.getAttribute("data-filter");

                // Remover clase "active" de todos los enlaces
                navLinks.forEach(nav => nav.classList.remove("active"));
                this.classList.add("active");

                // Mostrar/Ocultar las tesis según el filtro
                tesisCards.forEach(card => {
                    if (filter === "Todos") {
                        card.style.display = "block";
                    } else {
                        card.style.display = card.classList.contains(filter.replace(" ", "_").toLowerCase()) ? "block" : "none";
                    }
                });
            });
        });
    });
</script>