<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    @vite(['resources/js/app.js', 'resources/css/app.scss', 'resources/js/ejemplo/script.js','resources/css/root.css','resources/css/variables.css'])
</head>
<body>
    <!-- Contenedor principal -->
        <div class="container text-center pe-5" id style="margin-top: 150px; ">
        <div class="row" >

            <!--Imagen  -->
            <div class="d-none d-lg-block  col-lg-6 text-center mt-5 pe-5" >
                <img src="images\Logo_de_la_UAN.png" class="img-fluid pe-5 " alt="" style="height:300px" >
            </div>
        
            
            <div class=" col-12  col-lg-6 pt-3 bg-gradient-primary">  
                <h5 class="mb-3 ">Ingresa tus datos!</h5>
                <div class="card-header login-header" style="background-color: var(--color-azul-principal)"><h2 class="col-12  text-white mb-0" >Inicia sesion</h2></div>

                         <div class="bg-body-secondary pt-2 shadow-lg " >
                            
                             <form action="{{ Route("login.post") }}" method="POST">
                                @csrf
                             <label for="username" class="Col-md-12 ">Matricula o clave de trabajador <span class="text-warning">*</span></label>
                             <div class="mx-5">
                                <input id="username" class="col-md-12 mt-2 form-control" name="username" type="text" required autocomplete="off"></div>
                                  <label for="password"class="col-md-12 pt-3">Contraseña<span class="text-warning">*</span></label>
                                  <div class="mx-5"><input id="password" class="col-md-12 mt-2 form-control"  name="password" type="password" required autocomplete="current-password"></i></div>

                                  <button type="submit" class=" btn btn  my-3 mx-5 text-light " style="background-color: var(--color-azul-principal)"> Iniciar sesión <i class="fa-solid fa-right-to-bracket"></i></button>
                                  <div class="text-end me-5 pb-4">
                                  <a  href="{{ route('forgotPassword')}}" >¿Olvidaste tu contraseña? </a>
                                  </div>
                                 
                         </div>
              </div>
             </form>
            </div>
            
        </div>
       

        </div>
        @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
</body>
</html>
