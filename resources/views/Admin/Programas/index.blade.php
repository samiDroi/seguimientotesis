@extends("layouts.form")
@section('form')
    <h1>{{ $unidad->nombre_unidad }}</h1>
    <button><a href="{{ route('programas.store') }}">Agregar Nuevo Programa</a></button> <!-- Enlace para agregar un nuevo programa -->

    <table>
        <thead>
            <tr>
                <th>Nombre del Programa</th>
                <th>Editar Programa</th>
                <th>Eliminar Programa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unidad->programas as $programa)
        
                <tr>
                    <td>{{ $programa->nombre_programa }}</td>
                    <td>
                        <button>
                            <a href="{{ route('programas.edit', $programa->id_programa) }}">Editar</a>
                        </button>
                    </td>
                    <td>
                        <form action="{{ route('programas.destroy', $programa->id_programa) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay programas académicos disponibles para esta unidad.</td> <!-- Mensaje cuando no hay programas -->
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection