@extends('layouts.base')
@section('content')
<form action="{{ route("tesis.create") }}" method="POST">
    @csrf
 
    <label for="nombre_tesis">Ingrese el titulo que llevará la tesis</label>
    <input type="text" name="nombre_tesis" id="nombre_tesis" value="{{ isset($tesis) ? $tesis->nombre_tesis : '' }}" required autocomplete="off" placeholder="Titulo de la tesis">
    
    <h1>Requerimientos de Tesis</h1>
    <input type="hidden" name="id_tesis_comite" id="" value="{{ $tesisComite?->id_tesis_comite }}">
    
    <div id="requerimientos">
        @foreach($requerimientos as $requerimiento)
        <div>
            <label for="nombre_requerimiento">¿Qué requerimientos desea poner a la tesis?</label>
            <input type="text" required name="nombre_requerimiento[]" value="{{ $requerimiento->nombre_requerimiento }}" id="nombre_requerimiento" autocomplete="off" placeholder="Nombre del requerimiento">
            <textarea name="descripcion[]" id="descripcion" cols="30" rows="10" placeholder="Descripcion del requerimiento">{{ $requerimiento->descripcion }}</textarea>
        </div>
        @endforeach
        
        <button id="newRequerimiento">+</button>
    </div>
    
    <select name="usuarios" id="usuarios">
        @foreach ($usuarios as $usuario)
            <option value="{{ $usuario->id_usuario }}" {{ isset($tesis) && $tesis->id_usuario == $usuario->id_usuario ? 'selected' : '' }}>
                {{ $usuario->username }} {{ $usuario->nombre }} {{ $usuario->apellidos }}
            </option>
        @endforeach
    </select>
    
    <select name="comite" id="comite">
        <option>Seleccione el comité que estará a cargo de la tesis</option>
        @foreach ($comites as $comite)
            <option value="{{ $comite->id_comite }}" {{ isset($tesisComite) && $tesisComite->id_comite == $comite->id_comite ? 'selected' : '' }}>
                {{ $comite->nombre_comite }}
            </option>
        @endforeach
    </select>

    <button type="submit">{{ isset($tesis) ? 'Actualizar información de tesis' : 'Guardar información de tesis' }}</button>
</form>
@endsection

@section('js')
<script>
    document.getElementById('newRequerimiento').addEventListener('click', function() {
        const container = document.getElementById('requerimientos');
        const newRequerimiento = document.createElement('div');
        newRequerimiento.innerHTML = `
            <input type="text" required name="nombre_requerimiento[]" id="nombre_requerimiento" autocomplete="off" placeholder="Nombre del requerimiento">
            <textarea name="descripcion[]" id="descripcion[]" cols="30" rows="10" placeholder="Descripcion del requerimiento"></textarea>
        `;
        container.appendChild(newRequerimiento);
    });
</script>
@endsection

