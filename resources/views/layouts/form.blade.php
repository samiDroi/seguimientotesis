<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    @vite(['resources/js/app.js', 'resources/css/app.scss', 'resources/js/ejemplo/script.js','resources/css/root.css','resources/css/variables.css'])
</head>
<body>
    <div id="principal">
        <button class="ms-5 mt-4 btn btn-secondary" > <i class="fa-solid fa-left-long"></i>  Volver</button>
        <div class="row text-center ">
            <h1 class="text-primary" id="SistesisTitulo">Sistesis</h1>
        </div>
        
        
        <div class="container">
            @yield('form')
        </div>
        
    </div>
    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
    @yield('js')

</body>
</html>