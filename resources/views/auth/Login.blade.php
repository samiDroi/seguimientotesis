@extends("layouts/form")
@section("form")


   <style>
    *{
        body {
    background-image: url('images/background white.jpg'); /* Reemplaza con la ruta de tu imagen */
    background-size: cover; /* Ajusta la imagen al tamaño de la ventana */
    background-position: center; /* Centra la imagen */
    
     }
    }
   </style>
    <!-- Contenedor principal -->
    <div class="container text-center " style="margin-top: 200px; ">
        <div class="row" >

            <!--Imagen  -->
            <div class="d-none d-md-block col-md-4 col-lg-6 " >
                <img src="images\Logo_de_la_UAN.png" class="img-fluid " alt="" style="height:300px" >
            </div>
        
            
            <div class=" col-12 col-md-8 col-lg-6 pt-3 bg-gradient-primary" >  
                <h5 class="mb-3">Ingresa tus datos!</h5>
                <div class="card-header"><h2 class="col-12 bg-primary text-white mb-0">Inicia sesion</h2></div>

                         <div class="bg-body-secondary pt-2 shadow-lg " >
                            
                             <form action="{{ Route("login.post") }}" method="POST">
                                @csrf
                             <label for="username" class="Col-md-12 ">Matricula o clave de trabajador <span class="text-warning">*</span></label>
                             <div class="mx-5">
                                <input id="username" class="col-md-12 mt-2 form-control" name="username" type="text" required autocomplete="off"></div>
                                  <label for="password"class="col-md-12 pt-3">Contraseña<span class="text-warning">*</span></label>
                                  <div class="mx-5"><input id="password" class="col-md-12 mt-2 form-control"  name="password" type="password" required autocomplete="current-password"></i></div>

                                  <button type="submit" class="btn btn-primary my-3 mx-5 " >🚪 Iniciar sesión</button>
                                  <div class="text-end me-5 pb-4">
                                  <a  href="{{ route('forgotPassword') }}" >¿Olvidaste tu contraseña?</a>
                                  </div>
                                 
                         </div>
               
                             
                        
                     
              </div>
             </form>
            </div>
            
        </div>
       

    </div>
    <script>

    $(document).ready(function() {
    console.log('jQuery está funcionando!');});
    </script>

    



@endsection