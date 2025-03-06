@extends('layouts.base')
@section('content')

<nav class="navbar  border-bottom border-dark">
    <div class="container-fluid">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-10">
                <p class="fs-1 ms-3 display-4 text-light">Sistesis</p>
            </div>
            <div class="col-2 d-flex justify-content-end align-items-center gap-3">
               <p class="text-light mt-3">{{ Auth::user()->correo_electronico }}</p>
                <form action="{{ Route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger text-nowrap" type="submit">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="container-flex">
    <div class="row">
        <div class="col-3 ms-0">
            <nav class="nav flex-column gap-5 bg-body-tertiary pt-5 pb-5 fs-4 text-center vh-100">
                @if (Auth::user()->tipos->contains("nombre_tipo", "coordinador"))
                    <div class="option my-1 py-3 ">
                        <img src="{{ asset('images/people-fill.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold  ">Gestionar comités</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/person-arms-up.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold">Gestionar usuarios</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/i.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold">Gestionar información académica</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/T.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold diva">Gestionar tesis</a>
                    </div>
                @else
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/person.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold diva">Mi perfil</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/people.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold diva">Mi comité</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/archive.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold diva">Mis tesis</a>
                    </div>
                    <div class="option my-1 py-3">
                        <img src="{{ asset('images/backpack.svg') }}" style="height: 25px;">
                        <a href="" class="text-decoration-none text-dark fw-semibold diva">Mi unidad</a>
                    </div>
                @endif
            </nav>
        </div>

        <div class="col-9">
            @foreach ($tesisUsuario as $tesis)
                <div class="card border-0 mb-4  mt-5 mx-5 ">
                    <div class="card-body row container-pers border-secondary border-2 rounded-4">
                        <div class="col-12 d-flex justify-content-between align-items-center text-light">
                            <h2 class="card-title h4 font-weight-bold text-light flex-grow-1 ">{{ $tesis->nombre_tesis }}</h2>
                        </div>

                        <div class="col-12">
                            @if ($tesis->comites->isNotEmpty())
                                <span class=" small ms-2 text-light">Comité: <b>{{ $tesis->comites->first()->nombre_comite }}</b></span>
                            @else
                                <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                                @if (Auth::user()->esCoordinador == 1)
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">
                                        Asignar Comité
                                    </button>
                                @endif
                            @endif
                        </div>

                        <div class="col-12 text-center ">
                            @foreach ($tesisComites as $tesisComite)
                                @if ($tesisComite->id_tesis == $tesis->id_tesis)
                                    @if ($tesisComite->requerimientos->isNotEmpty())
                                        <details>
                                            <summary class="h5 text-light ">Requerimientos</summary>
                                            <ul class="list-group list-group-flush ">
                                                @foreach ($tesisComite->requerimientos as $requerimiento)
                                                    <li class="list-group-item px-0 rounded-4 ps-5 ">
                                                        <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                        <a class="text-decoration-none text-primary fw-semibold" href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Realizar avance</a>
                                                        <br>
                                                        <span class="fw-semibold">Descripción:</span> {{ $requerimiento->descripcion }}
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
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection


<script>

</script>