<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Hola, {{ $usuario }}</h1>

    <p>Tu cuenta ha sido creada exitosamente.</p>

    <p>A continuación te proporcionamos tus datos de acceso:</p>
    
    <ul>
        <li><strong>Nombre de usuario:</strong> {{ $usuario }}</li>
        <li><strong>Contraseña:</strong> {{ $newPassword }}</li>
    </ul>

    <p>Por razones de seguridad, te recomendamos cambiar tu contraseña una vez inicies sesión.</p>

    <p>Gracias por registrarte en nuestro sitio.</p>
</body>
</html>