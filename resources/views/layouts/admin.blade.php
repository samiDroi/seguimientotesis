<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    @yield("css")
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/root.css'])
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
   
</head>
<body>
    {{-- @dd(Auth::user()) --}}
    {{-- barra superior --}}
    <nav class="navbar navbar-expand-lg" style="background-color: #007bff;">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ Route('administrador') }}">Sistesis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('users.index') }}">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('comites.index') }}">Comites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('unidades.index') }}">Unidades Academicas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('info.tesis') }}">Mis tesis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ Route('tesis.admin') }}">Tesis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">{{ Auth::user()->nombre ." ".Auth::user()->apellidos }}</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    @yield('content')
    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    @yield('js')

</body>
</html>