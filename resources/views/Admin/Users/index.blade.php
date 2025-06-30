@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')

<div class="text-end me-5 mt-5 mb-4"><button class="btn " style="background-color: var(--color-azul-principal)"><a href="{{ route("register.index") }}" class="text-decoration-none text-light">Añadir nuevo usuario</a></button></div>

<div class="container  py-3 mb-5" ">
    <div class="row row mx-2 mt-3">
    <table id="users" class="table rounded mt-4 table-bordered text-center ">
            <thead class="table-primary" >
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo_electronico</th>
                    <th>Tipo de usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->username }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellidos }}</td>
                        <td>{{ $usuario->correo_electronico }}</td>
                        <td>

                         @foreach ($usuario->tipos as $tipo)


                            {{ $tipo->nombre_tipo }}@if(!$loop->last), @endif
                        @endforeach  
                            {{-- @dd($usuario->latest()->first())    --}}
                        </td> 
                        <td>
                            <button class="btn btn-sm "style="background-color:var(--color-amarillo)">
                                <a class="text-light text-decoration-none" href="{{ route("users.edit",$usuario->id_user) }}">Editar</a>
                            </button>
                            <form action='{{ route("users.delete",$usuario->id_user) }}' method="POST" style="display:inline;">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-sm btn-danger" type="submit">
                                    Eliminar
                                </button>
                            </form>

                        </td>   
                    </tr>    
                @endforeach
            </tbody>
        </table>

    </div>
</div>
   
@endsection  
        
    

        @section('js')
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js
            https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

        <script>
           new DataTable('#users', {
                responsive: true
            });
        </script>
@endsection


