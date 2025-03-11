@extends('layouts.form')
@section('form')
<form action="{{ route("tesis.create.requerimientos",$tesisComite->id_tesis_comite) }}" method="POST">
    @csrf
     <h1 class="text-center mt-5 mb-5">Requerimientos de Tesis</h1>
    <input type="hidden" name="id_tesis_comite" id="" value="{{ $tesisComite?->id_tesis_comite }}">
    
    <div id="requerimientos">
        @if($requerimientos->isNotEmpty())
            @foreach($requerimientos as $requerimiento)
            <div class="border-top border-primary">
               
                <label for="nombre_requerimiento">¿Qué requerimientos desea poner a la tesis?</label>
                <input class="form-control mb-5" type="text" required name="nombre_requerimiento[]" value="{{ $requerimiento->nombre_requerimiento }}" id="nombre_requerimiento" autocomplete="off" placeholder="Nombre del requerimiento">
                <textarea class="form-control" name="descripcion[]" id="descripcion" cols="30" rows="10" placeholder="Descripcion del requerimiento">{{ $requerimiento->descripcion }}</textarea>
            </div>
            @endforeach
        @else
            <div class="border-top border-primary">
                <label class="fs-3 fw-semibold mb-1 mt-3" for="nombre_requerimiento">¿Qué requerimientos desea poner a la tesis?</label>
                <input class="form-control mb-3" type="text" required name="nombre_requerimiento[]" value="" id="nombre_requerimiento" autocomplete="off" placeholder="Nombre del requerimiento">
                <textarea class="form-control" name="descripcion[]" id="descripcion" cols="30" rows="10" placeholder="Descripcion del requerimiento"></textarea>
            </div>
        @endif
        
    </div>
    
    {{-- <select name="usuarios" id="usuarios">
        @foreach ($usuarios as $usuario)
            <option value="{{ $usuario->id_user }}" {{ isset($tesis) && $tesis->id_usuario == $usuario->id_user ? 'selected' : '' }}>
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
    </select> --}}
    <button class="btn btn-secondary mt-5" id="newRequerimiento">Agregar nuevo requerimiento</button>
    <button class="btn btn-primary mt-5" type="submit">{{ isset($tesis) ? 'Actualizar información de tesis' : 'Guardar información de tesis' }}</button>
</form>
@endsection

@section('js')
<script>
    document.getElementById('newRequerimiento').addEventListener('click', function() {
        const container = document.getElementById('requerimientos');
        const newRequerimiento = document.createElement('div');
        newRequerimiento.innerHTML = `
        
            <label class="fs-3 fw-semibold mb-1 mt-5 " for="nombre_requerimiento">Escribe el siguiente requerimiento</label>
            <input class="form-control mb-3" type="text" required name="nombre_requerimiento[]" id="nombre_requerimiento" autocomplete="off" placeholder="Nombre del requerimiento">
            <textarea class="form-control" name="descripcion[]" id="descripcion[]" cols="30" rows="10" placeholder="Descripcion del requerimiento"></textarea>
            
        `;
        container.appendChild(newRequerimiento);
    });
</script>



@endsection

