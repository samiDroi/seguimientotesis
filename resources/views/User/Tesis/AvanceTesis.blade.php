@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
    <h1>{{ $requerimiento->nombre_requerimiento }}</h1>
    <!-- Textarea que será reemplazada por TinyMCE -->
    
    @foreach (getAlumnoAvance($requerimiento->id_requerimiento) as $alumno)
        <h4>Alumno: {{ $alumno->usuario_nombre }}</h4>
    @endforeach
    
    <form action="{{ Route("avance.create",$requerimiento->id_requerimiento) }}" method="POST">
        @csrf
            @if (!(comprobarIsInComite($comiteTesis->id_comite)))
                <textarea id="avance_tesis" name="contenido">{{ $avanceTesis?->contenido }}</textarea>
                <a href="{{ Route("home") }}">Regresar y cancelar</a>
                <button type="submit">Guardar Cambios</button>
            @else
                <p>{{ $avanceTesis?->contenido }}</p>
            @endif
        
    </form>
   
    
    @if (comprobarRolComite("DIRECTOR", $comiteTesis->id_comite) > 0 && optional($avanceTesis)->contenido)
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
                                        <input type="hidden" name="id_avance" value="{{ $avanceTesis?->id_avance }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                    </form>
                                </div>
                            </div>
     @endif

     {{-- cargar comentarios --}}
    @foreach (getInfoComentarioAvance( $requerimiento->id_requerimiento) as $comentario)
       {{ $comentario->usuario_nombre }}
       {{ $comentario->usuario_apellidos }}

        {{ $comentario->usuario_rol }}
       {{ $comentario->contenido }}
    @endforeach

     @if (Auth::user()->comites->contains('id_comite', $comiteTesis->id_comite) && optional($avanceTesis)->contenido)
        <form action="{{ Route("comentario.create") }}" method="post">
            @csrf
            <input type="hidden" name="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
            <input type="hidden" name="id_avance_tesis" value="{{ $avanceTesis?->id_avance_tesis }}">
            {{-- @dd( $avanceTesis?->id_avance_tesis ); --}}
            <textarea id="comentario_avance" name="contenido"></textarea>
            <button type="submit">Subir comentario</button>
        </form>
     @endif
    


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