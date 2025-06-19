<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    @yield("css")
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/root.css','resources/css/variables.css'])
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    
  <!-- TinyMCE CDN -->
<!-- Place the first <script> tag in your HTML's <head> -->
    

   


</head>
<body>
    <nav class="navbar  " style="height: 70px; background-color:var(--color-belize);">
        <div class="container-fluid">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-10">
                    <p class="fs-1 ms-3 display-4 text-light">Sistesis</p>
                </div>
                <div class="col-2 d-flex justify-content-end align-items-center gap-3">
                    <p class="text-light mt-3">{{ Auth::user()->correo_electronico }}</p>
                    <form action="{{ Route('logout') }}" method="POST">
                        @csrf
                        <button class="btn  text-nowrap text-light" style="background-color: var(--color-delete)" type="submit">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- 🟢 Agregar el contenedor de la fila para alinear el menú y el contenido -->
    <div class="container-fluid">
        <div class="row ">
            <!-- Menú lateral -->
            <div class="col-2 ms-0 ">
                <nav class="nav flex-column  gap-5  pt-5 pb-5 fs-4 text-center vh-100 " style="background-color: white; box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);">
                        <div class="option my-1 py-3">
                            <img src="{{ asset('images/person.svg') }}" style="height: 20px;">
                            <a href="{{ Route('home') }}" class="text-decoration-none text-dark fw-semibold diva">Inicio</a>
                        </div>
                        <div class="option my-1 py-3">
                            <img src="{{ asset('images/people.svg') }}" style="height: 20px;">
                            <a href="{{ Route('info.comites') }}" class="text-decoration-none text-dark fw-semibold diva">Mi comité</a>
                        </div>
                        <div class="option my-1 py-3">
                            <img src="{{ asset('images/archive.svg') }}" style="height: 20px;">
                            <a href="{{ Route('info.tesis') }}" class="text-decoration-none text-dark fw-semibold diva">Mis tesis</a>
                        </div>
                        <div class="option my-1 py-3">
                            <img src="{{ asset('images/backpack.svg') }}" style="height: 20px;">
                            <a href="{{ Route('info.unidad') }}" class="text-decoration-none text-dark fw-semibold diva">Mi unidad</a>
                        </div>
                </nav>
            </div>
    
            <!-- 🟢 Aquí se renderiza el contenido de cada vista dentro de la misma row -->
            <div class="col-9">
                @yield('content')
            </div>
        </div>
    </div>
    
    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://cdn.tiny.cloud/1/urwrxmsleu3b744kjom91xeido5jy6oujj95v82jp8ixig9s/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
    @yield('js')
    </body>
    </html>