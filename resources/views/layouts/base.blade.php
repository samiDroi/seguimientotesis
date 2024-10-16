<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-KyZXEAg3QhqLMpG8r+Knujsl5/5hb7ieaV0L5ttE5ms=" crossorigin="anonymous"></script>
    @yield("css")
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
</head>
<body>
    @yield('content')
    @yield('js')
    
</body>
</html>