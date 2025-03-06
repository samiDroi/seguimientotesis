@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')
<!-- <button><a href="{{ Route('roles.index') }}">Personalizar roles del comité</a></button> -->
<form action="{{ Route('comites.create') }}" method="POST">


    <br>
    <h1 class="text-center">Crear comites</h1>
    <div class="container "> 

<!-- <button class="btn btn-primary mt-5 mb-1 ms-4"><a class="text-decoration-none text-light" href="{{ Route("roles.index")}}">Personalizar roles del comite</a></button> -->
<form action="{{ Route("comites.create")}}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $comite?->id_comite }}">
    <label class="fs-5 fw-semibold" for="nombre_comite">Ingrese el nombre del comité</label>
    <input class="form-control" type="text" id="nombre_comite" name="nombre_comite" required value="{{ $comite?->nombre_comite }}">
   
    <label class="fs-5 fw-semibold mt-4"  for="programas">Selecciona el programa academico al que pertenecera el comite:</label>
    <select class="form-select" name="ProgramaAcademico[]" id="programas" >
        @foreach ($programas as $programa)
            <option  value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
        @endforeach
    </select>
    <div class="row mt-5">
        {{-- Tabla de docentes a la izquierda --}}
        <div class="col-md-12">
            <label for="docentes">Lista de docentes disponibles</label>



            <table class="table" id="docentes">
                <thead class="table-primary">
                    <tr>
                        <th>Clave de trabajador</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docentes as $docente)
                    <tr>
                        <td>{{ $docente->username }}</td>
                        <td>{{ $docente->nombre }}</td>
                        <td>{{ $docente->apellidos }}</td>
                        <td>{{ $docente->correo_electronico }}</td>
                        <td>
                            <input type="checkbox" class="checkbox-docente" value="{{ $docente->username }}" {{ $comite && $comite->usuarios->contains($docente->id_user) ? 'checked' : '' }}>
                        </td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
        
        
       

    </div>
    
    <p class="fs-3 border-bottom border-primary border-2">Confirmar información de comité</p>
    <div id="confirmarComite"></div>
   
    <button class="col-12 btn btn-primary py-2 text-center mt-4" style="height: 50px;" type="submit" >{{ $comite?"Guardar cambios":"Registrar comite" }}</button>
</form>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

<script>
    // Inicializar DataTables
    new DataTable('#docentes', { responsive: true });

    function actualizarConfirmacion() {
        let confirmarComiteHtml = '';

        // Procesar docentes seleccionados
        $('.checkbox-docente:checked').each(function() {
            const username = $(this).val(); // Obtener el valor (username)
            const nombre = $(this).closest('tr').find('td:nth-child(2)').text(); // Obtener el nombre
            const apellidos = $(this).closest('tr').find('td:nth-child(3)').text(); // Obtener los apellidos

            confirmarComiteHtml += `
                <div class="mb-3">
                <div class="fs-2 fw-semibold"> ${nombre} ${apellidos}</div>
                   
                   
                    <label for="rol">Selecciona Rol</label>
                     <input type="hidden" name="docentes[]" value="${username}">
                    <select class="form-select" name="rol[]" id="rol">
                        @foreach($roles as $rol)
                            <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                        @endforeach
                    </select>
                </div>
            `;
        });

        $('#confirmarComite').html(confirmarComiteHtml);
    }

    // Inicializar la confirmación al cargar la página si se está editando
    $(document).ready(function() {
        actualizarConfirmacion();
    });

    // Evento de cambio en checkboxes
    $(document).on('change', '.checkbox-docente', function() {
        actualizarConfirmacion();
    });
</script>
@endsection
