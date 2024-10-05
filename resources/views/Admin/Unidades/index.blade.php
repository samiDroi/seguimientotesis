@extends('layouts.base')
@section('content')
<button><a href="{{ route('unidades.create') }}">Nueva Unidad</a></button>
    
<table>
    <thead>
        <tr>
            <th>Nombre de la Unidad</th>
            <th>Editar Unidad</th>
            <th>Eliminar Unidad</th>
            <th>Acceder a Programas Académicos</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unidades as $unidad)
            <tr>
                <td>{{ $unidad->nombre_unidad }}</td>
                <td>
                    <button><a href="{{ route('unidades.edit', $unidad->id_unidad) }}">Editar</a></button>
                </td>
                <td>
                    <form action="{{ route('unidades.destroy', $unidad->id_unidad) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
                <td>
                    <button><a href="{{ route('programas.index',$unidad->id_unidad) }}">Programas</a></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
   
@endsection
route('programas.index', ['id_unidad' => $unidad->id_unidad])