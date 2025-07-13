<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
  
    @yield("css")
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/root.css','resources/css/variables.css'])
    @vite(['resources/js/app.js', 'resources/css/app.scss',])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    
    
   
</head>
<body>
    {{-- @dd(Auth::user()) --}}
    {{-- barra superior --}}
    <nav class="navbar navbar-expand-lg" style="background-color: var(--color-azul-obscuro);">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ Route('administrador') }}">Sistesis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('users.index') }}"> <i class="fa-solid fa-user"></i> Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('comites.index') }}"><i class="fa-solid fa-users"></i> Comites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('unidades.index') }}"><i class="fa-solid fa-school"></i> Unidades Academicas</a>
                    </li>
                    <li class="nav-item" style="display: none"> 
                        <a class="nav-link text-white" href="{{ Route('info.tesis') }}"> <i class="fa-solid fa-file"></i>Mis tesis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('tesis.review') }}"><i class="fa-solid fa-file"></i> Tesis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">{{ Auth::user()->nombre ." ".Auth::user()->apellidos }}</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn text-light "   style="background-color: var(--color-delete)">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    @yield('content')
    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
  

    @yield('js')

</body>
</html>