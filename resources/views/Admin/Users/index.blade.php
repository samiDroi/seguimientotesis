@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')
<div class="container">
<div class="text-end mt-5 mb-4"><button class="btn " style="background-color: var(--color-azul-principal)"><a href="{{ route("register.index") }}" class="text-decoration-none text-light"><i class="fa-solid fa-user-plus"></i> Añadir usuario</a></button></div>
</div>

<div class="container  py-3 mb-5  my-5" >
    <div class="row row mx-2 mt-3">
    <div class="table-wrapper ">
  <table class="custom-table table-responsive my-3" id="users">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Matricula</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Correo electrónico</th>
        <th>Tipo de usuario</th>
        <th>Programas Académicos</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody class="fw-semibold">
      @foreach ($usuarios as $usuario)
        <tr>
          <td>{{ $usuario->username ?? 'NA' }}</td>
          <td>{{ $usuario->matricula ?? 'NA' }}</td>
          <td>{{ $usuario->nombre }}</td>
          <td>{{ $usuario->apellidos }}</td>
          <td>{{ $usuario->correo_electronico }}</td>
          <td>
            @foreach ($usuario->tipos as $tipo)
              {{ $tipo->nombre_tipo }}@if(!$loop->last), @endif
            @endforeach
          </td>
          <td>
            @foreach ($usuario->programas as $programa)
              {{ $programa->nombre_programa }}@if(!$loop->last), @endif
            @endforeach
          </td>
          <td> 
            <a href="{{ route('users.edit', $usuario->id_user)}}" style="all: unset;">
              <i class="fa-solid fa-pen-to-square edit-icon" ></i> 
            </a>
            <form action="{{ route('users.delete', $usuario->id_user) }}" method="POST" style="display:inline;">
              @csrf
              @method("DELETE")
              <button type="submit" style="background: none; border: none; padding: 0; margin-left: 0.5rem;">
                <i class="fa-solid fa-trash icono-trash"></i>
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

            document.querySelectorAll('.deleteConfirm').forEach(function(btn){
                btn.addEventListener('click',function(e){
                    e.preventDefault();

                    const form = this.closest('form');
                     
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
@endsection


