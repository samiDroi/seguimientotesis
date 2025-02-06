<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    @vite(['resources/js/app.js', 'resources/css/app.scss', 'resources/js/ejemplo/script.js','resources/css/root.css'])
</head>
<body>
    <div id="principal">
        <div class="container">
            @yield('form')
        </div>
        
    </div>
    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    
    @yield('js')

</body>
</html>