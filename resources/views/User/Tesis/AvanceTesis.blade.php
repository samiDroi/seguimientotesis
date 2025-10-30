@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
        #grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        #comentar {
            position: fixed;
            left: 2%;
            bottom: 2%;
            background-color: dodgerblue;
            color: white;
            border: 0;
            outline: 0;
            border-radius: 8px;
            padding: 16px 32px;

            &:hover {
                cursor: pointer;
                background-color: darkslateblue;
            }
        }

        .mensaje{
            border: 1px solid #3333;
            padding: 10px;
            &:hover{
                background-color: yellow;
            }
        }
       .comentario-cargado, .mensaje {
    border: 1px solid #3333;
    padding: 10px;
    transition: transform 0.2s ease, background-color 0.2s ease;
}

.comentario-cargado:hover, .mensaje:hover {
    transform: translateY(-5px); /* se mueve hacia arriba */
    background-color: #f1f1f1;   /* resalta el comentario mismo */
    cursor: pointer;
}

.comment {
    transition: background-color 0.3s ease;
}

.highlighted {
    background-color: yellow !important; /* el resaltado */
}

#visor-comentarios {
    display: grid;
    grid-template-columns: 2fr 1fr; /* 2/3 contenido, 1/3 comentarios */
    gap: 2rem;
    align-items: start;
}

/* #visor-comentarios main {
    background-color: #fff;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    overflow: auto;
    max-height: 85vh;
} */

#visor-comentarios aside {
    background-color: #f9f9f9;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    overflow-y: auto;
    max-height: 85vh;
}

#visor-comentarios aside::-webkit-scrollbar {
    width: 8px;
}
#visor-comentarios aside::-webkit-scrollbar-thumb {
    background: #bbb;
    border-radius: 4px;
}
@media (max-width: 992px) {
    #visor-comentarios {
        grid-template-columns: 1fr; /* se apilan */
    }
}

.grid-comment {
  display: grid;
  grid-template-columns: 1fr 2fr; /* Izquierda 1 parte, derecha 2 partes */
  gap: 20px; /* Espacio entre columnas */
  align-items: start; /* Alinea arriba */
}

.user-auditor {
  margin-bottom: 10px;
}
.user-auditor:hover {
    background-color: #f1f1f1; /* Resalta al pasar el mouse */
    cursor: pointer;
}
.user-auditor:focus {
    outline: 2px solid #0d6efd; /* Resalta al enfocar con teclado */
    background-color: #e7f1ff;
}
.comment-section {
  border-left: 2px solid #dee2e6; /* Línea divisoria opcional */
  padding-left: 15px;
  min-height: 300px; /* Altura mínima para visualización */
}

/* Cada fila de comentario y main */
.comentario-fila {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    align-items: flex-start; /* Alinea el contenido superior */
}

/* Columna del main */
.main-col {
    flex: 1;
    font-weight: bold;
}

/* Columna de comentario */
.coment-col {
    flex: 1;
}

/* Opcional: bordes y fondo ligero para diferenciar */
.comentario-fila {
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.5rem;
}

/* Responsivo: en pantallas pequeñas, apilar columnas */
@media (max-width: 768px) {
    .comentario-fila {
        flex-direction: column;
    }
}

</style>
@endsection
@section('content') 
<div  class="container mt-5  ">
    @php
        $rolesUsuario = getUserRolPermiso(Auth::user()->id_user, $comiteTesis->id_comite);
    @endphp

    <h1 class=" fw-semibold" style="color: var(--color-azul-principal)">{{ $requerimiento->nombre_requerimiento }}</h1>
    <!-- Textarea que será reemplazada por TinyMCE -->
    
    @foreach (getAlumnoAvance($requerimiento->id_requerimiento) as $alumno)
        <h4 class="  fw-semibold border-bottom border-primary p-2 mb-3">Alumno: <span style="color: var(--color-azul-principal)">{{ $alumno->usuario_nombre }}</span></h4>
    @endforeach

    <div data-route="{{ Route("comentario.create") }}"></div>
    <div data-avance-tesis= "{{ $avanceTesis?->id_avance_tesis }}"></div>
    <div id="comentarios-route" data-routec = "{{ route('helper.fetch',['id_requerimiento' => $requerimiento->id_requerimiento,'userId' => ':userId']) }}"></div>
    {{-- <div data-fetch="{{ route('helper.fetch.html',['id_avance_tesis' =>  $avanceTesis?->id_avance_tesis])}}"></div> --}}
    @if($avanceTesis)
    <div data-fetch="{{ route('helper.fetch.html', ['id_avance_tesis' => $avanceTesis->id_avance_tesis]) }}"></div>
@endif

    <form  action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}" method="POST">
        @csrf
            @if (!(comprobarIsInComite($comiteTesis->id_comite)))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#comentarios-mostrar">
                        Ver comentarios
                        </button>
        
                <textarea id="avance_tesis" name="contenido">{{ $avanceTesis?->contenido }}</textarea>
                
                <a class=" mt-3 align-center btn btn-danger" href="{{ Route("home") }}"><i class="fa-solid fa-rectangle-xmark"></i> Regresar y cancelar</a>
                <button class=" mt-3 btn btn-primary " type="submit"><i class="fa-solid fa-pen-to-square"></i>Guardar Cambios</button>
           
            @else
                {{-- <button type="button" class="btn btn-primary" id="comentar" data-bs-toggle="modal" data-bs-target="#comentModal">
                    Abrir modal
                </button> --}}
                @if (Auth::user()->comites->contains('id_comite', $comiteTesis->id_comite) && optional($avanceTesis)->contenido)
                {{-- <button id="comentar" type="button">dale aqui</button> --}}
                
                @endif
                {{-- @dd($contentHTML?->contenido_original) --}}
                <div class="fs-5 mb-5 py-4 px-4 " data-content-main = "{{ $contentHTML ? "procesado" : "" }}">
                <p class="fw-semibold fs-4"> <i class="fa-regular fa-file-lines"></i> Contenido:</p>
                {{-- @dd($contentHTML) --}}
                @if(!$rolesUsuario->contains('lector')) 
                    <button type="button" id="show-comment">Ocultar comentarios</button>
                    
                @endif
                <div id="visor-comentarios" class="mt-4">
                    @if($contentHTML?->contenido_original)
                {{-- @dd($contentHTML->contenido_original) --}}
                    {{-- {!! $contentHTML->contenido_original !!}    --}}
                {!! $contentHTML->contenido_original !!}
                 
                @else
                <main>
                    {{-- @dd($avanceTesis->contenido) --}}
                    {!! $avanceTesis?->contenido !!}
                </main> 
                @endif
                
                    
                @if(!$rolesUsuario->contains('lector')) 
                
                <aside class="section-comments" style="position: sticky; top: 1rem;">
                    <button id="comentar" type="button">Agregar comentario</button>

                    <h2 class="mb-1 mt-5"> <i class="fa-regular fa-comments"></i> Comentarios   </h2>
                    {{-- cargar comentarios --}}
                    {{-- <div data-req = {{ $requerimiento->id_requerimiento }}></div> --}}
                    <input type="hidden" id="auth" value="{{ Auth::user()->id_user }}">
                    
                        {{-- @dd(getInfoComentarioAvance( $requerimiento->id_requerimiento)) --}}
                            {{-- @dd(getInfoComentarioAvance( $requerimiento->id_requerimiento)) --}}
                    {{-- desde aqui esta todo lo de lo comentario auxilio porfavor --}}
                    @if($contentHTML)
                        {!! $contentHTML->comentario !!}
                    @else
                        <div class="comentario-contenido" id="comentarios"></div>
                    @endif
                    

                </aside>
                @endif
                </div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js" integrity="sha512-YJgZG+6o3xSc0k5wv774GS+W1gx0vuSI/kr0E0UylL/Qg/noNspPtYwHPN9q6n59CTR/uhgXfjDXLTRI+uIryg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="trumbowyg/dist/plugins/upload/trumbowyg.cleanpaste.min.js"></script>
    <script src="trumbowyg/dist/plugins/upload/trumbowyg.pasteimage.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/upload/trumbowyg.upload.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/colors/trumbowyg.colors.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/fontsize/trumbowyg.fontsize.min.js"></script>

    <script>
        $("#avance_tesis").trumbowyg({
            btns: [
                ['viewHTML'],
                ['formatting'],
                ['bold', 'italic', 'underline'],
                ['foreColor', 'backColor'], // Plugin Colors
                ['fontsize'], // Plugin FontSize
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['removeformat'],
                ['upload'], // Plugin Upload
                ['emoji'], // Plugin Emoji
                ['fullscreen']
            ],
        });

        $("#comentario_avance").trumbowyg({
            btns: [
                ['viewHTML'],
                ['formatting'],
                ['bold', 'italic', 'underline'],
                ['foreColor', 'backColor'], // Plugin Colors
                ['fontsize'], // Plugin FontSize
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['removeformat'],
                ['upload'], // Plugin Upload
                ['emoji'], // Plugin Emoji
                ['fullscreen']
            ],
        });
    </script>
@endsection