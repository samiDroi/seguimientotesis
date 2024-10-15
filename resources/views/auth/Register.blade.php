@extends("layouts/form")
@section('form')
<form action="{{ route('register') }}" method="POST">
    @csrf
    <h1>Informacion personal</h1>
        <label for="nombre_tipo">Seleccione los Tipos de Usuario:</label>
    
        <div>
            <input type="checkbox" id="coordinador" name="nombre_tipo[]" value="5">
            <label  for="coordinador">Coordinador</label>
        </div>
        <div>
            <input type="checkbox"  id="docente" name="nombre_tipo[]" value="4">
            <label for="docente">Docente</label>
        </div>

        <div>
            <input type="checkbox"  id="alumno" name="nombre_tipo[]" value="3">
            <label  for="alumno">Alumno</label>
        </div>
    
        <!-- Agrega más checkboxes para otros tipos de usuario -->
        
    

    <label for="correo_electronico">Correo Electronico</label>
    <input type="email" name="correo_electronico" required>

    <label for="nombre">Nombre(s)</label>
    <input type="text" name="nombre" required>

    <label for="apellidos">Apellidos</label>
    <input id='apellidos' type="text" name="apellidos" required>

    <label for="username">Clave de trabajador</label>
    <input type="text" name="username" required> 

    <label for="password">Contraseña</label>
    <input type="password" name="password" required>

    <button type="submit">Registrarse</button>
</form>
@endsection