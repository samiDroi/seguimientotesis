@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection
@section('content')

<div class="text-end me-5 mt-5 mb-4"><button class="btn btn-primary"><a href="{{ route("register") }}" class="text-decoration-none text-light">Añadir nuevo usuario</a></button></div>




<div class="container bg-body-secondary py-3 shadow-lg">
    <div class="row row mx-5 mt-3">
    <table id="users" class="table mt-4 table-bordered text-center table-striped">
            <thead class="table-primary">
                <tr>
                    <th>clave</th>
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
                            <button>
                                <a href="{{ route("users.edit",$usuario->id_user) }}">Editar</a>
                            </button>
                            <form action='{{ route("users.delete",$usuario->id_user) }}' method="POST" style="display:inline;">
                                @csrf
                                @method("DELETE")
                                <button type="submit">
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

@endsection
