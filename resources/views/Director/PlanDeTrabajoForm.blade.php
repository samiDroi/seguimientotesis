@extends('layouts.form')
@section('form')



<h1 class="text-center mt-3">Formulario de plan de trabajo</h1>

<div class="container">


 <div class="form-group">
    <label for="objetivo" class="form-label fs-4 fw-semibold ms-3 ">Objetivo</label>
    <input class="form-control mb-4" type="text" id="objetivo" name="objetivo" autocomplete="off">
</div>

<table> 
    <thead>
        <tr>
            <th>Tema o actividad </th>
            <th>Descripcion de la actividad</th>
            <th >Fecha de entrega esperada</th>
            <th>Responsable</th>
            
        </tr>    
    </thead>
    
    <tbody id="tablaActividades">
        <tr class="filaBase">
            <td><input class="form-control" type="text" name="actividad[]"></td>
            <td><input class="form-control" type="text" name="descripcion[]"></td>
            <td><input class="form-control" type="date" name="fecha_entrega" id=""></td>
            <td>
                <select name="responsable" id="" class="form-select">
                    @foreach ($comite->usuarios as $usuario)
                        <option value="{{ $usuario->id_user }}">{{ $usuario->nombre. " " .$usuario->apellidos }}</option>
                    @endforeach
                </select>

               
            </td>
            <td><button onclick="duplicarFila(this)" class="btn btn-success ms-5"> <i href="https://gifer.com/es/GtwU" class="fa-solid fa-plus"></i>  </button><button class="btn btn-danger ms-2 btnEliminar" type="button" onclick="eliminarFila(this)" style="display: none;"> <i class="fa-solid fa-minus"></i></button></td>
            
           
        </tr>
    </tbody>    
</table>    
<h3 class="mt-4 fs-3">Metas y resultados esperados</h3>
  <ol class="lista-clonable">
    <li class="item mb-3">
      <div class="d-flex">
        <input class="form-control" style="width: 500px" type="text" name="meta[]">
        <button onclick="clonarElemento(this)" class="btn btn-success ms-5"> <i class="fa-solid fa-plus"></i></button>
        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;"><i class="fa-solid fa-minus"></i></button>
      </div>
    </li>
  </ol>

  <h3 class="mt-4">Criterios de evaluación de los avances</h3>
  <ol class="lista-clonable">
    <li class="mb-3">
      <div class="d-flex">
        <input class="form-control" type="text" name="criterios[]" style="width: 700px">
        <button onclick="clonarElemento(this)" class="btn btn-success ms-5"> <i class="fa-solid fa-plus"></i></button>
        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;"><i class="fa-solid fa-minus"></i></button>
      </div>
    </li>
  </ol>

  <h3 class="mt-4">Compromisos del comité</h3>
  <ol class="lista-clonable">
    <li class="mb-2"><span>El Comité Tutorial se compromete a revisar los avances entregados por el(la) alumno(a) y proporcionar retroalimentación oportuna para garantizar el cumplimiento de los objetivos de la tesis.</span></li>
    <li class="mb-2"><span>El Comité Tutorial acuerda realizar reuniones periódicas (establecer cada cuando y por cual medio) con el alumno(a) para supervisar el progreso y resolver dudas o problemas que puedan surgir.</span></li>
    <li class="mb-2"><span>El Comité Tutorial se compromete a presentar avances a la coordinación del programa académico cuando se solicite. </span></li>
    <li class="mb-3">
      <div class="d-flex ">
        <input class="form-control" type="text" name="compromisos[]">
        <button onclick="clonarElemento(this)" class="btn btn-success ms-5"> <i class="fa-solid fa-plus"></i></button>
        <button onclick="eliminarElemento(this)" class="btn btn-danger ms-2 btnEliminar" style="display: none;"><i class="fa-solid fa-minus"></i></button>
      </div>
    </li>
  </ol>

</div>
@endsection
@section('js')
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