@extends('layouts.base')
@section('content')
<h1>Editar Comité</h1>
    
<form action="{{ route('comites.update', $comite->id_comite) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="nombre_comite">Nombre del Comité:</label>
        <input type="text" id="nombre_comite" name="nombre_comite" value="{{ $comite->nombre_comite }}" readonly>
    </div>

    <div>
        <label for="roles">Roles:</label>
        <select name="roles[]" id="roles" multiple>
            @foreach($roles as $rol)
                <option value="{{ $rol->id_comite_rol }}" 
                    @if($comite->roles->contains($rol->id_comite_rol)) selected @endif>
                    {{ $rol->nombre_rol }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit">Actualizar Comité</button>
    <a href="{{ route('comites.index') }}">Cancelar</a>
</form>
@endsection