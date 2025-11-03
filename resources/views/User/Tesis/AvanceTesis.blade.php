@extends('layouts.base')
@section('css')

<style>
 .comentario-resaltado {
     background-color: rgba(255, 229, 100, 0.5);
    cursor: pointer;
    }
    .comentario-resaltado:hover {
     background-color: rgba(255, 229, 100, 0.9);
    }
</style>
@endsection
@section('content') 
<div  class="container mt-5  ">
    @php
        $rolesUsuario = getUserRolPermiso(Auth::user()->id_user, $comiteTesis->id_comite);
    @endphp
    {{-- el nombre del capitulo --}}
    <h1 class=" fw-semibold" style="color: var(--color-azul-principal)">{{ $requerimiento->nombre_requerimiento }}</h1>
    
    {{-- la cantidad de alumnos que tiene la tesis --}}
    @foreach (getAlumnoAvance($requerimiento->id_requerimiento) as $alumno)
        <h4 class="  fw-semibold border-bottom border-primary p-2 mb-3">Alumno: <span style="color: var(--color-azul-principal)">{{ $alumno->usuario_nombre }}</span></h4>
    @endforeach
    {{-- rutas y datos necesarios para los comentarios --}}
    <div data-route="{{ Route("comentario.create") }}"></div>
    <div data-autor="{{ Auth::user()->id_user }}"></div>
    <div data-requerimiento = "{{ $requerimiento->id_requerimiento }}"></div>
    {{-- rutas y datos necesarios para el avance de tesis --}}
    @if($avanceTesis)
        <div data-avance = "{{ route("helper.fetch.avance",$avanceTesis->id_avance_tesis) }}"></div>
        <div data-get-comment="{{ route("helper.fetch.comentarios",$avanceTesis->id_avance_tesis) }}"></div>
    @endif
    <div data-avance-tesis= "{{ $avanceTesis?->id_avance_tesis }}"></div>
  
    <div>{{ route('helper.fetch',['id_requerimiento' => 1, 'userId' => Auth::user()->id_user]) }}</div>

    <form id="form-avance" action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}"  method="POST">
        @csrf
        {{-- si no esta en el comite auditando es el alumno  --}}
            @if (!(comprobarIsInComite($comiteTesis->id_comite)))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#comentarios-mostrar">
                        Ver comentarios
                        </button>
        
                {{-- <textarea id="avance_tesis" name="contenido">{{ $avanceTesis?->contenido }}</textarea> --}}
                <div id="editor-avance"></div>
                <input id="contenido-hidden" type="hidden" value="" name="contenido">
                
                <a class=" mt-3 align-center btn btn-danger" href="{{ Route("home") }}"><i class="fa-solid fa-rectangle-xmark"></i> Regresar y cancelar</a>
                <button class=" mt-3 btn btn-primary " type="submit"><i class="fa-solid fa-pen-to-square"></i>Guardar Cambios</button>
          {{-- si no, entonces esta en el comite y es docente  --}}
            @else
                @if(!$rolesUsuario->contains('lector')) 
                    <button type="button" id="btn-comentar">realizar comentario</button>
                    
                @endif
                    <div data-es-comite="@json(Auth::user()->comites->contains("id_comite", $comiteTesis->id_comite))" id="editor-avance"></div>

                

            </div>
            @endif
        
    </form>
   
    <h1>{{ $avanceTesis?->estado }}</h1>
    @if (isDirectorInComite($comiteTesis->id_comite) > 0 && Auth::user()->esCoordinador === 0)
                            <!-- Mostrar los botones solo si es DIRECTOR -->
                            {{-- @dd($avanceTesis->id_avance_tesis) --}}
                            <div class="mt-2">
                                <div class="d-flex gap-2 mt-2">
                                    <form action="{{ route("avance.estado.update") }}" method="POST" class="d-inline">
                                        @csrf
                                        {{-- @method('POST')  --}}
                                        <input type="hidden" name="estado" value="ACEPTADO"> <!-- Estado a Aceptado -->
                                        <input type="hidden" name="id_avance" value="{{ $avanceTesis?->id_avance_tesis }}">
                                        <button type="submit" class="btn btn-sm btn-success">Aceptar</button>
                                    </form>

                                    <form action="{{ route("avance.estado.update") }}" method="POST" class="d-inline">
                                        @csrf
                                        {{-- @method('POST')  --}}
                                        <input type="hidden" name="estado" value="RECHAZADO"> <!-- Estado a Rechazado -->
                                        <input type="hidden" name="id_avance" value="{{ $avanceTesis?->id_avance_tesis }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                    </form>
                                </div>
                            </div>
     @endif
    
    

        @include('User.Tesis.Modals.ComentariosModal')

    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
