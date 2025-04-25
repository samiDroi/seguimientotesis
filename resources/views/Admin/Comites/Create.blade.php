{{-- @extends('layouts.base')
@section('content')
 <button><a href="{{ Route('roles.index') }}">Personalizar roles del comité</a></button> 
<form action="{{ Route('comites.create') }}" method="POST">
    <br>
    <h1 class="text-center">Crear comites</h1>
    <div class="container "> 

<!-- <button class="btn btn-primary mt-5 mb-1 ms-4"><a class="text-decoration-none text-light" href="{{ Route("roles.index")}}">Personalizar roles del comite</a></button> -->
<form action="{{ Route("comites.create")}}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $comite?->id_comite }}">
    <label class="fs-5 fw-semibold" for="nombre_comite">Ingrese el nombre del comité</label>
    <input class="form-control" type="text" id="nombre_comite" name="nombre_comite" required value="{{ $comite?->nombre_comite }}">
   
    <label class="fs-5 fw-semibold mt-4"  for="programas">Selecciona el programa academico al que pertenecera el comite:</label>
    <select class="form-select" name="ProgramaAcademico[]" id="programas" >
        @foreach ($programas as $programa)
            <option  value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
        @endforeach
    </select>

    <button type="submit">Guardar Comite</button>
@endsection --}}


<!-- Botón que abre el modal -->


<!-- Botón para personalizar roles -->
{{-- <a href="{{ route('roles.index') }}" class="btn btn-secondary ms-2">Personalizar roles del comité</a> --}}

<!-- Modal -->
<div class="modal fade" id="crearComiteModal" tabindex="-1" aria-labelledby="crearComiteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('comites.create') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="crearComiteModalLabel">Crear Comité</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="{{ $comite?->id_comite }}">

          <label class="form-label fw-semibold" for="nombre_comite">Nombre del comité:</label>
          <input class="form-control" type="text" id="nombre_comite" name="nombre_comite" required value="{{ $comite?->nombre_comite }}">

          <label class="form-label fw-semibold mt-3" for="programas">Programa académico:</label>
          <select class="form-select" name="ProgramaAcademico[]" id="programas">
              @foreach ($programas as $programa)
                  <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
              @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>



