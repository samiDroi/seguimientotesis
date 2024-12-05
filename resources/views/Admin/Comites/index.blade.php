
@extends('layouts.base')
@section('content')
<h1>Lista de Comités</h1>
<div>
    <a href="{{ route("comites.store") }}">Crear nuevo comité</a>
</div>
@if ($comites->isEmpty())
    <h1>No hay comités registrados por el momento. Si desea registrar un comité, haga clic en 
        el botón "Crear nuevo comité" en la parte superior.
    </h1>
@else
    @foreach ($comites as $comite)
        <h1>{{ $comite->nombre_comite }}</h1>
        <form action="{{ route('comites.destroy', $comite->id_comite) }}" class="delete" method="POST" style="display:inline-block;">
            @csrf
            <button type="button" class="btn btn-danger">
                Eliminar Comité
            </button>
        </form>

        <a href="{{ route("comites.store", $comite->id_comite) }}">Editar comité</a>

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

        <details>
            <summary>Tesis asignadas</summary>
            @if ($comite->tesis->isNotEmpty())
                <ul>
                    @foreach ($comite->tesis as $tesis)
                        <li>{{ $tesis->nombre_tesis }}</li>
                    @endforeach
                </ul>
            @else
                <p>No hay tesis asignadas a este comité.</p>
            @endif
        </details>
    @endforeach
@endif

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>
    <script>
        
        $("body").on("click",".delete > button",function(){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Comite",
                text: "Estas a punto de eliminar este comite, esto no puede ser reversible ¿Estas seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar"
            }).then((result) => {
            if (result.isConfirmed) {
                $(formulario).submit();
               
            }
        });
    });
    </script>
@endsection








