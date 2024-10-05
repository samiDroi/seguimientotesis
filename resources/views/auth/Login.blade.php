@extends("layouts/form")
@section("form")
<form action="/login" method="POST">
    @csrf
    <label for="username">Matricula o clave de trabajador</label>
    <input id="username" name="username" type="text" required autocomplete="off">

    <label for="password">Contraseña</label>
    {{-- <a href="{{ route('recoveryPassword') }}">¿Olvidaste tu contraseña?</a> --}}
    <input id="password" name="password" type="password" required autocomplete="current-password">

    <button type="submit">🚪 Iniciar sesión</button>
</form>
@endsection