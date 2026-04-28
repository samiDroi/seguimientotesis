<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - Sistesis</title>
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard</h1>
        
        <h2>Mis Roles</h2>
        @if($rolesUnicos->isEmpty())
            <p>No tienes roles asignados en ningún comité.</p>
        @else
            <ul>
                @foreach($rolesUnicos as $rol)
                    <li>
                        <a href="{{ route('dashboard.tesis.filtrar', ['rol_id' => $rol['id_rol']]) }}" 
                           class="rol-link {{ request('rol_id') == $rol['id_rol'] ? 'active' : '' }}">
                            {{ $rol['nombre_rol'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Tesis</h2>
        @if($tesis->isEmpty())
            <p>Selecciona un rol para ver las tesis.</p>
        @else
            @foreach($tesis as $tesisItem)
                <div class="tesis-card">
                    <h3>{{ $tesisItem->nombre_tesis }}</h3>
                    <p>Estado: {{ $tesisItem->estado }}</p>
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>