@extends('layouts.base')
@section('content')



<div class="container-flex">
    <div class="row">
       

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
