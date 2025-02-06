 @extends('layouts.form')
 @section('form')
 <h1 class="text-center">Guardar nueva unidad</h1>



     <form action={{ route("unidades.store") }} method="POST">
        @csrf


        <div class="container">
         <div class="row bg-body-secondary">

         
        <div class="text-center">
        <label class="fs-3 fw-semibold py-4" for="unidadAcademica">Nombre de la unidad</label>
        <div class="mx-5 mb-5"><input class="form-control mb-4" type="text" id="unidadAcademica" name="nombre_unidad"></div>
        </div>
       
        <div class="text-center mb-4"><button class="btn btn-primary" type="submit">Guardar</button></div>
        
        </div>
        </div>


     </form>
 @endsection