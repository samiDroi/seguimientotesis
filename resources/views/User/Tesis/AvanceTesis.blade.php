@extends('layouts.base')

@section('content')
    <h1>{{ $requerimiento->nombre_requerimiento }}</h1>
    <!-- Textarea que será reemplazada por TinyMCE -->
    <textarea id="editor" name="contenido"></textarea>
@endsection

@section('js')
<script>
    // Inicializar TinyMCE
    tinymce.init({
        selector: '#editor', // Se refiere al textarea que queremos convertir en editor
        plugins: 'link image table', // Plugins disponibles (puedes agregar más si lo deseas)
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | link image', // Botones en la barra de herramientas
        menubar: false, // Deshabilitar el menú superior si no lo necesitas
    });
</script>
@endsection