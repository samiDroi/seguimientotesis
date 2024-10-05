 @extends('layouts.base')
 @section('content')
 <h1>Guardar nueva unidad</h1>
     <form action={{ route("unidades.store") }} method="POST">
        @csrf
        <label for="unidadAcademica">nombre de la unidad</label>
        <input type="text" id="unidadAcademica" name="nombre_unidad">
        <button type="submit">Guardar</button>
     </form>
 @endsection