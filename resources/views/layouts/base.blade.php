<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing page</title>
    @yield("css")
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/root.css'])
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
  <!-- TinyMCE CDN -->
<!-- Place the first <script> tag in your HTML's <head> -->
    

   


</head>
<body>
    @yield('content')
    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://cdn.tiny.cloud/1/urwrxmsleu3b744kjom91xeido5jy6oujj95v82jp8ixig9s/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    @yield('js')

</body>
</html>