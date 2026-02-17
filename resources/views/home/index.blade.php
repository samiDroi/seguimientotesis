@extends('layouts.base')
@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs mb-4" id="tesisTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active pestañas-nav" id="mis-tesis-tab" data-bs-toggle="tab" data-bs-target="#mis-tesis" type="button" role="tab">Mis Tesis</button>
    </li>
    @if(isInAnyComite())
    <li class="nav-item" role="presentation">
        <button class="nav-link pestañas-nav" id="comite-tesis-tab" data-bs-toggle="tab" data-bs-target="#comite-tesis" type="button" role="tab">Tesis Asignadas a mi Comité</button>
    </li>
    @endif
</ul>


<div class="tab-content" id="tesisTabsContent">

    <!-- TAB 1: Mis Tesis -->
    <div class="tab-pane fade show active " id="mis-tesis" role="tabpanel">
        @foreach ($tesisUsuario as $tesis)
            <div class="tesis-item">
                <div class="card-body row  border-secondary border-2 rounded-4">
                    <div class="col-12 targ-tesis " >
                        <h4 class="tesis-item-title  flex-grow-1 titulotesis">{{ $tesis->nombre_tesis }}</h4>
                    </div>

                    <div class="col-12">
                        @if ($tesis->comites->isNotEmpty())
                            
                            
                        @else
                            <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                            @if (Auth::user()->esCoordinador == 1)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">
                                    Asignar Comité
                                </button>
                            @endif
                        @endif
                    </div>

                    <div class="col-12 text-center">

                    @php $tieneRequerimientos = false; @endphp
                     @foreach ($tesisComites as $tesisComite)
                                @if ($tesisComite->id_tesis == $tesis->id_tesis)
                                    @if ($tesisComite->requerimientos->isNotEmpty())
                                     @php $tieneRequerimientos = true; @endphp
                                        <details>
                                            <summary class="h5 "> Estructura de tesis</summary>
                                            <ul class="list-group list-group-flush text-start py-1">
                                                @foreach ($tesisComite->requerimientos as $requerimiento)
                                                {{-- @dd($requerimiento) --}}
                                                    <li class="list-group-item px-0 rounded-4 ps-5 py-2 mx-5 mt-4">
                                                        <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                        <a class="btn-avance " href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Revisar avance  <i class="fa-solid fa-file-word"></i></a>
                                                        <br>
                                                        <span class="fw-semibold descripcion-span">Descripción:</span> {{ $requerimiento->descripcion }}
                                                        @foreach ($requerimiento->avances as $avance)
                                                            <span class="badge 
                                                            @if($avance->estado == '') bg-warning
                                                            @elseif($avance->estado == 'ACEPTADO') bg-success
                                                            @elseif($avance->estado == 'RECHAZADO') bg-info
                                                            @endif">
                                                            {{-- {{ ucfirst($avance->estado ?? 'PENDIENTE') }} --}}
                                                            {{ ucfirst($avance->estado ?: 'PENDIENTE') }}

                                                        </span>    
                                                        @endforeach
                                                        
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    @else
                                        @if (!$tieneRequerimientos)
                                            <p class="text-danger">No hay requerimientos para esta tesis.</p>
 
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                    
                    @if (!$tieneRequerimientos)
                        <p class="text-danger">No hay requerimientos para esta tesis.</p>
                    @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- TAB 2: Tesis del Comité -->
    {{-- @if(isInAnyComite()) --}}
    <div class="tab-pane fade" id="comite-tesis" role="tabpanel">
        @if ($tesisDeComite->isNotEmpty())
            @foreach ($tesisDeComite as $tesis)
                <div class="card border-3 border-success rounded-5 ps-3 mb-4 mt-5 mx-5">
                    <div class="card-body row border-secondary border-2 rounded-4">
                        <div class="col-12 d-flex justify-content-between align-items-center text-light">
                            <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1 titulotesis">{{ $tesis->nombre_tesis }}</h2>
                        </div>

                        <div class="col-12">
                            @if ($tesis->comites->isNotEmpty())
                                {{-- <span class="small ms-2">: <b>{{ $tesis->comites->first()->nombre_comite }}</b></span> --}}
                                @php
                                    $comite = $tesis->comites->first();
                                    $roles = $comite ? getRolComite($comite->id_comite)->pluck('rol_personalizado')->toArray() : [];
                                @endphp

                                <span class="small ms-2">
                                    
                                    @if (!empty($roles))
                                        <span class="badge bg-secondary ms-2">
                                            Tu(s) rol(es): {{ implode(', ', $roles) }}
                                        </span>
                                    @endif
                                </span>
                            @else
                                <span class="text-danger small ms-2">Pendiente de asignación de comité</span>
                                @if (Auth::user()->esCoordinador == 1)
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarComiteModal">
                                        Asignar Comité
                                    </button>
                                @endif
                            @endif
                        </div>
                        @php $tieneRequerimientos = false; @endphp
                        <div class="col-12 text-center">
                            @foreach ($tesisComites as $tesisComite)
                                @if ($tesisComite->id_tesis == $tesis->id_tesis)
                                    @if ($tesisComite->requerimientos->isNotEmpty())
                                    @php $tieneRequerimientos = true; @endphp    
                                    <details>
                                            <summary class="h5 text-primary">Requerimientos</summary>
                                            <ul class="list-group list-group-flush text-start py-1">
                                                @foreach ($tesisComite->requerimientos as $requerimiento)
                                                {{-- @dd($requerimiento) --}}
                                                    <li class="list-group-item px-0 rounded-4 ps-5 py-2 mx-5">
                                                        <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                                        <a class="text-primary fw-semibold" href="{{ route('avance.index', $requerimiento->id_requerimiento) }}">Revisar avance</a>
                                                        <br>
                                                        <span class="fw-semibold">Descripción:</span> {{ $requerimiento->descripcion }}
                                                        @foreach ($requerimiento->avances as $avance)
                                                            <span class="badge 
                                                            @if($avance->estado == '') bg-warning
                                                            @elseif($avance->estado == 'ACEPTADO') bg-success
                                                            @elseif($avance->estado == 'RECHAZADO') bg-info
                                                            @endif">
                                                            {{-- {{ ucfirst($avance->estado ?? 'PENDIENTE') }} --}}
                                                            {{ ucfirst($avance->estado ?: 'PENDIENTE') }}

                                                        </span>    
                                                        @endforeach
                                                        
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    @else
                                        @if (!$tieneRequerimientos)
                                        <p class="text-danger">No hay requerimientos para esta tesis.</p>
                                            
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-danger">No tienes tesis asignadas como parte de un comité.</p>
        @endif
    </div>
    {{-- @endif --}}

</div>

@endsection
