@extends('layouts.form')
@section("form")
<form action="{{ route('users.update',$usuario->id_user) }}" method="POST"a>
    @csrf
    @method('PUT')
    <h1 class="text-center mt-5">Informacion personal</h1>
    <div class="container bg-body-secondary shadow-lg mb-5">
        <p class="fs-4 pt-3 fw-semibold text-center ">Seleccione los Tipos de Usuario:</p>
           <div class="row">
              <label for="nombre_tipo" class=" text-center mb-3" >Seleccione los Tipos de Usuario:</label>
                @foreach ($tiposTotal as $tipo)
                {{-- @dd($tiposUsuario) --}}
                <div class="col-4 text-center ">
                    <input class="form-check-input" type="checkbox"  name="nombre_tipo[]" value="{{ $tipo->id_tipo }}"
                    {{ in_array($tipo->id_tipo, $tiposUsuario) ? 'checked' : '' }}>
                    <label  for="coordinador">{{ Str::ucfirst($tipo->nombre_tipo) }}</label>
                 </div>
                @endforeach
            </div>
    </div>
        <div class="px-5">
    <label for="correo_electronico">Correo Electronico</label>
    <input class="form-control" type="email" name="correo_electronico" required value="{{ $usuario->correo_electronico }}">
    </div>

    <div class="px-5 mt-3">
    <label  for="nombre">Nombre(s)</label>
    <input class="form-control  "  type="text" name="nombre" required value="{{ $usuario->nombre }}">
    </div>

    <div class="px-5 mt-3">
    <label for="apellidos">Apellidos</label>
    <input class="form-control" id='apellidos' type="text" name="apellidos" required value="{{ $usuario->apellidos }}">
    </div>

    <div class="px-5 mt-3" id="generacion" style="display: none">
    <label for="apellidos">Generacion</label>
    <input class="form-control" id='generacion' type="text" name="generacion" required value="{{ $usuario->generacion }}">
    </div>

    <div class="px-5 mt-3" id="matricula" style="display: none">
    <label for="matricula">Matricula</label>
    <input class="form-control" type="text" name="matricula" required value="{{ $usuario->matricula }}"> 
    </div>

  
    <div class="px-5 mt-3" id="claveT" style="display: none">
    <label for="username">Clave de trabajador</label>
    <input class="form-control" type="text" name="username" required value="{{ $usuario->username }}"> 
    </div>

    
    <button class="mt-3 ms-5 mt-5 btn btn-primary mb-5 text-centers" type="submit">Actualizar</button>
    
</form>
</div>
@endsection
@section('js')
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
        <script src="js/usuarios/registros.js"></script>
        <script>
            //Comprobacion si el usuario editado es docente y pertenece a un comite
            let isInComite = @json($isInComite);

            $('form').on('submit', function(e) {
                let docenteSelected = false;

                $('[name="nombre_tipo[]"]:checked').each(function() {
                    const text = $(this).parent().text().trim();
                    if (text.includes('Docente')) {
                        docenteSelected = true;
                    }
                });
                //si pertenece a un comite, pero docente no esta seleccionado, se manda una alerta
                if (isInComite > 0 && !docenteSelected) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El usuario esta asignado a un comite, no se le puede quitar el tipo de usuario docente, elimine al usuario del comite y vuelva a intentarlo',
                    });
                    e.preventDefault();
                }
            });
        </script>

@endsection