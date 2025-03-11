@extends('layouts.base')
@section('content')
        <div class="">
          
            @foreach ($tesisUsuario as $tesis)
          
                <div class="card border-3 border-primary rounded-5 ps-3     mb-4  mt-5 mx-5 ">
                    <div class="card-body row border-secondary border-2 rounded-4">
                        <div class="col-12 d-flex justify-content-between align-items-center text-light">
                            <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1 titulotesis ">{{ $tesis->nombre_tesis }}</h2>
                        </div>

                        <div class="col-12">
                            @if ($tesis->comites->isNotEmpty())
                                <span class=" small ms-2 ">Comité: <b>{{ $tesis->comites->first()->nombre_comite }}</b></span>
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
                                            <summary class="h5 text-primary">Requerimientos</summary>
                                            <ul class="list-group list-group-flush text-start py-1 ">
                                                @foreach ($tesisComite->requerimientos as $requerimiento)
                                                    <li class="list-group-item px-0 rounded-4 ps-5  py-2 mx-5">
                                                        <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                        <a class=" text-primary fw-semibold" href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Realizar avance</a>
                                                        <br>
                                                        <span class="fw-semibold ">Descripción:</span> {{ $requerimiento->descripcion }}
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
                                        <p class="text-danger">No hay requerimientos para esta tesis.</p>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            
        </div>
        @if ($tesisDeComite->isNotEmpty())
        <h3 class="text-center text-primary mt-5">Tesis Asignadas a mi Comité</h3>
        @foreach ($tesisDeComite as $tesis)
            <div class="card border-3 border-success rounded-5 ps-3 mb-4 mt-5 mx-5">
                <div class="card-body row border-secondary border-2 rounded-4">
                    <div class="col-12 d-flex justify-content-between align-items-center text-light">
                        <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1 titulotesis ">{{ $tesis->nombre_tesis }}</h2>
                    </div>

                    {{-- <div class="col-12">
                        @if ($tesis->comites->isNotEmpty())
                            <span class="small ms-2">Comité: <b>{{ $tesis->comites->first()->nombre_comite }}</b></span>
                        @endif
                    </div> --}}
                    <div class="col-12">
                        @if ($tesis->comites->isNotEmpty())
                            <span class=" small ms-2 ">Comité: <b>{{ $tesis->comites->first()->nombre_comite }}</b></span>
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
                                        <summary class="h5 text-primary">Requerimientos</summary>
                                        <ul class="list-group list-group-flush text-start py-1 ">
                                            @foreach ($tesisComite->requerimientos as $requerimiento)
                                                <li class="list-group-item px-0 rounded-4 ps-5  py-2 mx-5">
                                                    <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                    <a class=" text-primary fw-semibold" href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Realizar avance</a>
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
                                    <p class="text-danger">No hay requerimientos para esta tesis.</p>
                                @endif
                            @endif
                        @endforeach
                    </div>

                    {{-- <div class="col-12 text-center">
                        @if ($tesis->requerimientos->isNotEmpty())
                            <details>
                                <summary class="h5 text-success">Requerimientos</summary>
                                <ul class="list-group list-group-flush text-start py-1">
                                    @foreach ($tesis->requerimientos as $requerimiento)
                                        <li class="list-group-item px-0 rounded-4 ps-5 py-2 mx-5">
                                            <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                            <a class="text-primary fw-semibold" href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Realizar avance</a>
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
                            <p class="text-danger">No hay requerimientos para esta tesis.</p>
                        @endif
                    </div> --}}
                </div>
            </div>
        @endforeach
    @endif
    </div>

@endsection


<script>

</script>
