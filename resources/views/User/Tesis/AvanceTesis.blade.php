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
    </style>

@endsection
@section('content') 
<div class="container mt-5  ">
    <h1 class=" fw-semibold" style="color: var(--color-azul-principal)">{{ $requerimiento->nombre_requerimiento }}</h1>
    <!-- Textarea que será reemplazada por TinyMCE -->
    
    @foreach (getAlumnoAvance($requerimiento->id_requerimiento) as $alumno)
        <h4 class="  fw-semibold border-bottom border-primary p-2 mb-3">Alumno: <span style="color: var(--color-azul-principal)">{{ $alumno->usuario_nombre }}</span></h4>
    @endforeach

    <div data-route="{{ Route("comentario.create") }}"></div>
    <div data-avance-tesis= "{{ $avanceTesis?->id_avance_tesis }}"></div>
 
    <form  action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}" method="POST">
        @csrf
            @if (!(comprobarIsInComite($comiteTesis->id_comite)))
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
            <div class="fs-5 mb-5 py-4 px-4 " data-content-main = "{{ $contentHTML ? "procesado" : "no procesado" }}">
                <p class="fw-semibold fs-4"> <i class="fa-regular fa-file-lines"></i> Contenido:</p>
                {{-- @dd($contentHTML) --}}
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
        <aside>
            <button id="comentar" type="button">Agregar comentario</button>

        </aside>
                 
                 

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
    <h2 class="mb-1 mt-5"> <i class="fa-regular fa-comments"></i> Comentarios   </h2>
     {{-- cargar comentarios --}}
     {{-- <div data-req = {{ $requerimiento->id_requerimiento }}></div> --}}
     <input type="hidden" id="auth" value="{{ Auth::user()->id_user }}">
     <div id="comentarios-route" data-routec = "{{ route('helper.fetch',['id_requerimiento' => $requerimiento->id_requerimiento,'userId' => ':userId']) }}"></div>
     
     {{-- @dd(getInfoComentarioAvance( $requerimiento->id_requerimiento)) --}}
        {{-- @dd(getInfoComentarioAvance( $requerimiento->id_requerimiento)) --}}
{{-- desde aqui esta todo lo de lo comentario auxilio porfavor --}}
@if($contentHTML)
    {!! $contentHTML->comentario !!}
@else
    <div class="comentario-contenido" id="comentarios"></div>
@endif
    {{-- <div id="comentario-container">
    @if($comentarios->isEmpty())
        <div class="no-comentarios">
            No hay comentarios todavía.
        </div>
    @else
        @foreach($comentarios as $comentario)
            <div class="mensaje" data-autor="{{ $comentario->id_user }}">
                <!-- Aquí se llenará dinámicamente con JS -->
            </div>
        @endforeach
    @endif
</div> --}}


        {{-- @include('User.Tesis.Modals.ComentarioModal') --}}

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