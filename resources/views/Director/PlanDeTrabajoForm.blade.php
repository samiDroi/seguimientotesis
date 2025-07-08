@extends('layouts.form')

@section('form')
<h1>{{ isset($plan) ? 'Editar plan de trabajo' : 'Formulario de plan de trabajo' }}</h1>

<form action="{{ isset($plan) ? route('plan.update', $plan->id_plan) : route('plan.create') }}" method="POST">
    @csrf

    <input type="hidden" name="id_comite" value="{{ $comite->id_comite }}">

   <div class="form-group">
    <label for="objetivo" class="form-label fs-4 fw-semibold ms-3 ">Objetivo</label>
    <input class="form-control mb-4" type="text" id="objetivo" name="objetivo" autocomplete="off" value="{{ old('objetivo', $plan->objetivo ?? '') }}">
</div>
    <table id="tablaActividades">
        <thead>
            <tr>
                <th>Tema o actividad</th>
                <th>Descripción de la actividad</th>
                <th>Fecha de entrega esperada</th>
                <th>Responsable</th>
                <th>accion</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($plan->actividades) --}}
            @if(isset($plan) && $plan->actividades)
                @foreach($plan->actividades as $actividad)
                <tr>
                    <td><input class="form-control" type="text" name="actividad[]" value="{{ $actividad->tema }}"></td>
                    <td><input class="form-control" type="text" name="descripcion[]" value="{{ $actividad->descripcion }}"></td>
                    <td><input class="form-control" type="date" name="fecha_entrega[]" value="{{ $actividad->fecha_entrega }}"></td>
                    <td>
                        <select name="responsable[]" class="form-select">
                            @foreach ($comite->usuarios as $usuario)
                                <option value="{{ $usuario->id_user }}" 
                                    {{ $usuario->id_user == $actividad->responsable_id ? 'selected' : '' }}>
                                    {{ $usuario->nombre . " " . $usuario->apellidos }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button onclick="duplicarFila(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i>  </button>
                        
                        <button class="btn btn-danger ms-2 btnEliminar" type="button" onclick="eliminarFila(this)" style="display: none;"> <i class="fa-solid fa-minus"></i></button>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td><input type="text" class="form-control" name="actividad[]"></td>
                    <td><input type="text" class="form-control" name="descripcion[]"></td>
                    <td><input type="date" class="form-control" name="fecha_entrega[]"></td>
                    <td>
                        <select class="form-control" name="responsable[]">
                            @foreach ($comite->usuarios as $usuario)
                                <option value="{{ $usuario->id_user }}">
                                    {{ $usuario->nombre . " " . $usuario->apellidos }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <button onclick="duplicarFila(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i>  </button>
                        
                        <button class="btn btn-danger ms-2 btnEliminar" type="button" onclick="eliminarFila(this)" style="display: none;"> <i class="fa-solid fa-minus"></i></button>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3 class="mt-4 fs-3">Metas y resultados esperados</h3>
   
    <ol class="lista-clonable">
        @if(isset($plan) && $plan->metas)
            @foreach($plan->metas as $meta)
            <li class="item mb-3">
                <div class="d-flex">
                    <input class="form-control" style="width: 500px" type="text" name="meta[]" value="{{ $meta }}">
                    <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                    <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                </div>
            </li>
            @endforeach 
        @else
              <li class="item mb-3">
                <div class="d-flex">
                    <input class="form-control" style="width: 500px" type="text" name="meta[]">
                    <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                    <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                </div>
            </li>
        @endif
    </ol>

    <h3 class="mt-4">Criterios de evaluación de los avances</h3>
  
    
    <ol class="lista-clonable">
        @if(isset($plan) && $plan->criterios)
            @foreach($plan->criterios as $criterio)
                {{-- <li><input type="text" name="criterios[]" value="{{ $criterio }}"></li> --}}
                <li class="mb-3">
                    <div class="d-flex">
                        <input class="form-control" type="text" name="criterios[]" style="width: 700px" value="{{ $criterio }}">
                        <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                    </div>
                </li>
            @endforeach
        @else
            {{-- <li><input type="text" name="criterios[]"></li> --}}
            <li class="mb-3">
                <div class="d-flex">
                    <input class="form-control" type="text" name="criterios[]" style="width: 700px">
                    <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                    <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                </div>
            </li>
        @endif
    </ol>

    
    <h3 class="mt-4">Compromisos del Comité Tutorial</h3>
    <ol class="lista-clonable">
         <li class="mb-2"><span>El Comité Tutorial se compromete a revisar los avances entregados por el(la) alumno(a) y proporcionar retroalimentación oportuna para garantizar el cumplimiento de los objetivos de la tesis.</span></li>
        <li class="mb-2"><span>El Comité Tutorial acuerda realizar reuniones periódicas (establecer cada cuando y por cual medio) con el alumno(a) para supervisar el progreso y resolver dudas o problemas que puedan surgir.</span></li>
        <li class="mb-2"><span>El Comité Tutorial se compromete a presentar avances a la coordinación del programa académico cuando se solicite. </span></li>
    
        @if(isset($plan) && $plan->compromisos)
            @foreach($plan->compromisos as $compromiso)
                 <li class="mb-3">
                    <div class="d-flex ">
                        <input class="form-control" type="text" name="compromisos[]" value="{{ $compromiso }}">
                        <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                    </div>
                </li>
            @endforeach
        @else
            <li class="mb-3">
                    <div class="d-flex ">
                        <input class="form-control" type="text" name="compromisos[]" >
                        <button onclick="clonarElemento(this)" class="btn btn-success ms-5" type="button"> <i class="fa-solid fa-plus"></i></button>
                        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;" type="button"><i class="fa-solid fa-minus"></i></button>
                    </div>
                </li>
        @endif
    </ol>

    <button type="submit">{{ isset($plan) ? 'Actualizar' : 'Crear' }}</button>
</form>
@endsection
@section('js')
<script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
<script>
  function duplicarFila(boton) {
    const fila = boton.closest('tr');
    const clon = fila.cloneNode(true);

    // Limpia los valores
    clon.querySelectorAll('input, select').forEach(el => el.value = '');
    clon.querySelector('.btnEliminar').style.display = 'inline-block';

    document.getElementById('tablaActividades').appendChild(clon);
  }

  function eliminarFila(boton) {
    const fila = boton.closest('tr');
    fila.remove();
  }


   function clonarElemento(btn) {
      const liOriginal = btn.closest('li');
      const clon = liOriginal.cloneNode(true);

      // Limpiar solo los inputs del clon
      clon.querySelectorAll('input').forEach(input => input.value = '');

      // Mostrar botón eliminar
      clon.querySelector('.btnEliminar').style.display = 'inline-block';

      // Insertar el clon después del original
      liOriginal.parentNode.insertBefore(clon, liOriginal.nextSibling);
    }

    function eliminarElemento(btn) {
      const li = btn.closest('li');
      li.remove();
    }

</script>
@endsection