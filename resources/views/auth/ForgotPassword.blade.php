@extends('layouts.form')
@section('form')
    <div class="row text-center">

    
        <h1 class="mt-5">Recuperacion de contraseña</h1>
        <div class="bg-body-secondary py-4 shadow-lg">   
            
          <h3 class="mb-4">Ingrese el correo electronico</h3>
          <form action="{{ Route("forgotPassword")}}" method="POST">
          @csrf
          <div class="form-floating mb-3 mx-5">
              <input type="email" class="form-control" name="correo_electronico" id="floatingInput" placeholder="name@example.com" autocomplete="off">
             <label for="floatingInput ">Correo Electronico</label>
            </div>
          <button type="submit" class=" btn btn-primary">Aceptar</button>
          </form>


        </div>

        

    </div>




@endsection