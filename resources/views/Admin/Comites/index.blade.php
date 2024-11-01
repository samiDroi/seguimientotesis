
@extends('layouts.base')
@section('content')
<h1>Lista de Comités</h1>
<div>
    <a href="{{ Route("comites.store") }}">Crear nuevo comite</a>
</div>
@if ($comites->isEmpty())
    <h1>No hay comites registrados por el momento, si desea registrar un comite de click en 
        el boton "crear nuevo comite" en la parte superior
    </h1>
@else
    @foreach ($comites as $comite)
        <h1>{{ $comite->nombre_comite }}</h1>
        <form action="{{ route('comites.destroy', $comite->id_comite) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este comité y sus datos relacionados?')" class="btn btn-danger">
                Eliminar Comité
            </button>
        </form>
        <a href="{{ Route("comites.store",$comite->id_comite) }}">Editar comite</a>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Rol</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($comite->usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->apellidos }}</td>
                    <td>
                    
                        @foreach ($usuario->roles as $rol)
                            @if ($rol->pivot->id_comite == $comite->id_comite)
                            {{ $rol->nombre_rol }}
                            @endif
                        @endforeach
                    </td>
                </tr>
                @endforeach 
            </tbody>
        </table>
    @endforeach
@endif

@endsection










{{-- @extends('layouts.base')
@section('content')
<h1>lista de comites</h1>
@foreach ($comites as $comite)
<h1>{{ $comite->nombre_comite }}</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre del Usuario</th>
                <th>Rol</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comite->usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombre }}</td>
                <td>
                    {{-- @dd($usuarios) --}}
                    {{-- @foreach ($usuarios->roles as $rol)
                        {{-- @dd($rol->roles) --}}
                        {{-- {{ $rol->nombre_rol }}
                    @endforeach 
                 
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
@endforeach --}}








{{-- <div class="container">
    <h1>Lista de Comités</h1>

    @foreach ($comites as $comite)
        <h2>{{ $comite->nombre_comite }}</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre del Usuario</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @if ($comite->usuarios->isEmpty())
                    <tr>
                        <td colspan="2">No hay usuarios asignados a este comité.</td>
                    </tr>
                @else
                    @foreach ($comite->usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->roles->pluck("nombre_rol") ?? 'Sin rol' }}</td>
                            {{-- <td>
                                @if ($usuario->roles->isNotEmpty())
                                    {{ $usuario->roles->pluck('nombre_rol')->implode(', ') }} <!-- Muestra todos los roles -->
                                @else
                                    Sin rol
                                @endif
                            </td> --}}
                        {{-- </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @endforeach
</div>  --}}
{{-- @endsection --}}