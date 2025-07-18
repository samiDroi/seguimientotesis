@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content') 
<div class="container mt-5  ">
    <h1 class=" fw-semibold" style="color: var(--color-azul-principal)">{{ $requerimiento->nombre_requerimiento }}</h1>
    <!-- Textarea que será reemplazada por TinyMCE -->
    
    @foreach (getAlumnoAvance($requerimiento->id_requerimiento) as $alumno)
        <h4 class="  fw-semibold border-bottom border-primary p-2 mb-3">Alumno: <span style="color: var(--color-azul-principal)">{{ $alumno->usuario_nombre }}</span></h4>
    @endforeach

    
   
    <form  action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}" method="POST">
        @csrf
            @if (!(comprobarIsInComite($comiteTesis->id_comite)))
                <textarea id="avance_tesis" name="contenido">{{ $avanceTesis?->contenido }}</textarea>
                
                <a class=" mt-2 align-center" href="{{ Route("home") }}">Regresar y cancelar</a>
                <button class=" mt-3 btn btn-primary " type="submit">Guardar Cambios</button>
           
            @else
                
            <div class="fs-5 mb-5 py-4 px-4 " >
                <p class="fw-semibold fs-4"> <i class="fa-regular fa-file-lines"></i> Contenido:</p>
                 {!! $avanceTesis?->contenido !!} 
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
     {{-- @dd(getInfoComentarioAvance( $requerimiento->id_requerimiento)) --}}
    @foreach (getInfoComentarioAvance( $requerimiento->id_requerimiento) as $comentario)
                            
     <div class="card comentario mt-2 mb-3 ps-3 py-4 shadow-sm">
        @if ($comentario)
            <div class=" fs-5 fw-semibold pb-2 ">{{ $comentario->usuario_nombre }}  {{ $comentario->usuario_apellidos }}
                 <span class="badge text-light  bg-secondary "> {{ $comentario->usuario_roles }}</span> 
                </div> 
    
            <div class=" "> {!! $comentario->contenido !!}</div> 
        
        @else
            <span>No hay avance todavia</span>

        @endif
       
    </div> 
    @endforeach

     @if (Auth::user()->comites->contains('id_comite', $comiteTesis->id_comite) && optional($avanceTesis)->contenido)
        <form action="{{ Route("comentario.create") }}" method="post">
           
            @csrf
            <input type="hidden" name="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
            <input type="hidden" name="id_avance_tesis" value="{{ $avanceTesis?->id_avance_tesis }}">
            {{-- @dd( $avanceTesis?->id_avance_tesis ); --}}
            <textarea id="comentario_avance" name="contenido"></textarea>
            <button class="btn btn-primary mt-3" type="submit">Subir comentario</button>
        </form>
     @endif
    

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