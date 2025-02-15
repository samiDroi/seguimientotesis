
@extends("layouts.form")
@section('form')
    {{-- @dd(Auth::user()) --}}
        <!-- Agrega más checkboxes para otros tipos de usuario -->  
        <h2 class="text-center my-3 ">Registrar nuevo usuario</h2>

      <div class="container pt-5 px-5 bg-body-secondary  text-center shadow-lg">
    
        <div class="row mb-4 fs-5 pe-5 ">
         <form action="{{ route('register.post') }}" method="POST">
              @csrf
                        {{-- listbox de programas academicos --}}
                <div class="mb-4"><label for="programa_academico">Programas Academicos:</label></div>

                <div class="mb-5"><select name="id_programa[]"class="w-40"  multiple required>
                    @foreach ($unidades as $unidad)
                        <optgroup label="{{ $unidad->nombre_unidad }}">
                            @foreach ($unidad->programas as $programa)
                                <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select></div>
                
                
                {{-- listbox de programas academicos --}}
             <div class="row">
              <label for="nombre_tipo" class=" text-center mb-3" >Seleccione los Tipos de Usuario:</label>
                @foreach ($tiposUsuario as $tipo)
                <div class="col-12 col-md-4 ">
                    <input class="form-check-input" type="checkbox" id="coordinador" name="nombre_tipo[]" value="{{ $tipo->id_tipo }}">
                    <label  for="coordinador">{{ $tipo->nombre_tipo }}</label>
                    </div>
                @endforeach
            </div>
        </div>




            <div class="row mb-3">
                 <label for="correo_electronico" class="col-12 col-md-2 text-center mb-1">Correo Electronico<span class="text-warning">*</span></label>
                 <div class=" col-12 col-md-10 ">
                 <input type="email" name="correo_electronico" class=" form-control " autocomplete="off" required>
                 </div>
            </div>

                 
            <div class="row mb-3">
                 <label for="nombre" class="col-12 col-md-2 text-center mb-1">Nombre(s)<span class="text-warning">*</span></label>
                 <div class="col-12 col-md-10"> 
                    <input type="text" name="nombre" class=" form-control " autocomplete="off" required>
                </div>
             </div>
                 

            <div class="row mb-3">
                 <label for="apellidos" class="col-12 col-md-2 text-center mb-1" >Apellidos<span class="text-warning">*</span></label>
                 <div class="col-12 col-md-10">
                    <input id='apellidos' type="text" name="apellidos"class="col-12 form-control" autocomplete="off" required>
                </div>
            </div>


                <div class="row mb-3">
                 <label for="username" class="col-12 col-md-2 text-center mb-2 ">Clave de trabajador<span class="text-warning">*</span></label>
                 <div class=" col-12 col-md-10">
                     <input type="text" name="username"class="col-12 form-control" autocomplete="off" " required> 
                    </div>
                
                </div>


            <div class="row  mb-3">
                 <label for="password" class="col-12 col-md-2 text-center mb-1">Contraseña<span class="text-warning">*</span></label>
                 <div class="col-12 col-md-10">
                 <input type="password" name="password" class=" form-control mb-3" required>
                 </div>
                 
        </div>

                 <button type="submit" class="btn btn-primary  mb-4 ">Registrarse</button>
        </form>
        </div>
</div>
    
@endsection