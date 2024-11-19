
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
        {{-- <form action="{{ route('comites.destroy', $comite->id_comite) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" data-confirm-delete="true"  class="btn btn-danger">
                Eliminar Comité
            </button>
        </form> --}}
        <a href="{{ route('comites.destroy', $comite->id_comite) }}" data-confirm-delete="true" class="btn btn-danger">Eliminar</a>

        <a href="{{ Route("comites.store",$comite->id_comite) }}">Editar comite</a>

        <form action="{{ route('comites.clone', $comite->id_comite) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Clonar Comité</button>
        </form>
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
@section('js')
 
 
    {{-- Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
        Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
        });
        }
    }); --}}
@endsection








