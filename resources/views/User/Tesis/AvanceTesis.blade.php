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
.sidebar-comentarios {
    position: sticky;
    top: 10px;
    height: fit-content;
    align-self: flex-start;
}
z
/* sidebar comment box
.comentario-box {
  border: 1px solid rgba(0,0,0,0.08);
  padding: 10px;
  border-radius: 6px;
  background: #fff;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
   padding: 10px 10px 10px 35px; 
  cursor: pointer;
  position: relative;
}
.comentario-box .meta { font-size: 12px; color: #666; margin-bottom: 6px; }
.comentario-box .texto { font-size: 14px; white-space: pre-wrap; }
.comentario-box .checkbox-correct { position: absolute; left: 8px; top: 8px; }

/* estado corregido */
/* .comentario-box.corregido { opacity: 0.7; border-left: 4px solid #198754; }
.comentario-info {
    margin-bottom: 6px;
    font-size: 13px;
    line-height: 1.2;
} */ */
/* ---- SIDEBAR ---- */
.sidebar-comentarios {
    width: 320px;
    position: sticky;
    top: 0;
    align-self: flex-start;
    height: calc(100vh - 20px);
    overflow-y: auto;
    background: #fff;
    border-left: 1px solid #e5e7eb;
    padding: 15px;
    box-shadow: -4px 0 12px rgba(0,0,0,0.05);
}


/* Título */
.sidebar-comentarios .titulo {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

/* ---- CONTENEDOR DE COMENTARIOS ---- */
.lista-comentarios {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* ---- TARJETA INDIVIDUAL ---- */
.comentario-box {
    padding: 14px 16px;
    border-radius: 10px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    cursor: pointer;
}

.comentario-box:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

/* Estado corregido */
.comentario-box.corregido {
    background: #ecfdf5;
    border-color: #6ee7b7;
}

/* ---- TEXTO ---- */
.comentario-info {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 6px;
}

.comentario-box .texto {
    font-size: 0.95rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 10px;
}

/* ---- CHECKBOX ---- */
.checkbox-correct {
    margin-top: 5px;
    transform: scale(1.15);
    cursor: pointer;
}

/* highlight temporal en el editor */
.comentario-target {
  background: rgba(100, 200, 255, 0.35) !important;
  border-radius: 2px;
}
.comentario-no-pendiente {
    background: #e5e5e5;
    border-left: 4px solid #999;
    opacity: 0.7;
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
  
    {{-- <div>{{ route('helper.fetch',['id_requerimiento' => 1, 'userId' => Auth::user()->id_user]) }}</div> --}}

    <form id="form-avance" action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}"  method="POST">
        @csrf
       <div class="d-flex gap-3">
    {{-- CONTENEDOR DEL EDITOR (IZQUIERDA) --}}
    <div class="flex-grow-1">
        @if (!(comprobarIsInComite($comiteTesis->id_comite)))
            {{-- VISTA ALUMNO --}}
            <div id="editor-col">
                <div id="editor-avance"></div>
                <input id="contenido-hidden" type="hidden" name="contenido">
                <div class="mt-3">
                    <a class="btn btn-danger" href="{{ Route('home') }}">Regresar</a>
                    <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                </div>
            </div>
        @else
            {{-- VISTA COMITÉ --}}
            @if(!$rolesUsuario->contains('lector'))
                <button type="button" id="btn-comentar" class="btn btn-warning mb-2">Realizar comentario</button>
            @endif
            <div id="editor-avance"></div>
        @endif
    </div>

    {{-- SIDEBAR DE COMENTARIOS (DERECHA - SIEMPRE VISIBLE) --}}
    <aside id="sidebar-comentarios" class="sidebar-comentarios">
        <h5 class="titulo">Comentarios</h5>
        <div id="comentarios-list" class="lista-comentarios">
            </div>
    </aside>
</div>
        
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
