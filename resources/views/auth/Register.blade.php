@extends("layouts/form")
@section('form')
{{-- <form action="{{ route('register') }}" method="POST">
    @csrf
    <h1>Informacion personal</h1>
        <label for="nombre_tipo">Seleccione los Tipos de Usuario:</label>
        <div>
            <input type="checkbox" id="coordinador" name="nombre_tipo[]" value="5">
            <label  for="coordinador">Coordinador</label>
        </div>
        <div>
            <input type="checkbox"  id="docente" name="nombre_tipo[]" value="4">
            <label for="docente">Docente</label>
        </div>

        <div>
            <input type="checkbox"  id="alumno" name="nombre_tipo[]" value="3">
            <label  for="alumno">Alumno</label>
        </div>
    
        <!-- Agrega más checkboxes para otros tipos de usuario -->

    <label for="correo_electronico">Correo Electronico</label>
    <input type="email" name="correo_electronico" required>
    
        
    
        <!-- Agrega más checkboxes para otros tipos de usuario -->
        
     --}}

   

<style>
    *{
        body {
    background-image: url('images/background white.jpg'); /* Reemplaza con la ruta de tu imagen */
    background-size: cover; /* Ajusta la imagen al tamaño de la ventana */
    background-position: center; /* Centra la imagen */
    
     }
    }
   </style>



<h2 class="text-center my-3">Registrar nuevo usuario</h2>

<div class="container pt-5 px-5 bg-body-secondary text-center shadow-lg">
    
        <div class="row mb-4 fs-5 pe-5 ">
             <form action="{{ route('register') }}" method="POST">
              @csrf
                        {{-- listbox de programas academicos --}}
                <label for="programa_academico">Programas Academicos:</label>
                <select name="id_programa[]" multiple required>
                    @foreach ($unidades as $unidad)
                        <optgroup label="{{ $unidad->nombre_unidad }}">
                            @foreach ($unidad->programas as $programa)
                                <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                {{-- listbox de programas academicos --}}
                
              <label for="nombre_tipo" class=" text-center mb-3" >Seleccione los Tipos de Usuario:</label>
    
             <div class="col-12 col-md-4 ">
                 <input class="form-check-input" type="checkbox" id="coordinador" name="nombre_tipo[]" value="5">
                 <label  for="coordinador">Coordinador</label>
             </div>

             <div  class="col-12 col-md-4 px-0 mx-0 ">
                 <input class="form-check-input " type="checkbox"  id="docente" name="nombre_tipo[]" value="4">
                 <label for="docente">Docente</label>
             </div>

             <div class="col-12 col-md-4">
                 <input class="form-check-input" type="checkbox"  id="alumno" name="nombre_tipo[]" value="3">
                 <label  for="alumno">Alumno</label>
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

@endsection