@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')
{{-- <button ><a href="{{ Route("roles.index") }}">Personalizar roles del comite</a></button>
<form action="{{ Route("comites.create") }}" method="POST"> --}}

    @csrf
     {{-- listbox de programas academicos --}}
     {{-- <label for="programa_academico">Programas Academicos:</label>
     <select name="id_programa[]" multiple required>
         @foreach ($unidades as $unidad)
             <optgroup label="{{ $unidad->nombre_unidad }}">
                 @foreach ($unidad->programas as $programa)
                     <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
                 @endforeach
             </optgroup>
         @endforeach
     </select> --}}
     {{-- listbox de programas academicos --}}
    <label for="nombre_comite">Ingrese el nombre del comité</label>
    <input type="text" id="nombre_comite" name="nombre_comite">

    <div class="row">
        {{-- Tabla de docentes a la izquierda --}}
        <div class="col-md-6">
            <label for="docentes">Lista de docentes disponibles</label>
            <table id="docentes" >
                <thead>
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
                            <input type="checkbox" class="checkbox-docente" data-origen="docente" data-username="{{ $docente->username }}" data-username="{{ $docente->username }}" data-nombre="{{ $docente->nombre }}" data-apellidos="{{ $docente->apellidos }}" data-correo="{{ $docente->correo_electronico }}">
                        </td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Tabla de alumnos a la derecha --}}
        <div class="col-md-6">
            <label for="alumnos">Lista de alumnos disponibles</label>
            <table id="alumnos" >
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alumnos as $alumno)
                    <tr>
                        <td>{{ $alumno->username }}</td>
                        <td>{{ $alumno->nombre }}</td>
                        <td>{{ $alumno->apellidos }}</td>
                        <td>{{ $alumno->correo_electronico }}</td>
                        <td> 
                            <input type="checkbox" class="checkbox-alumno" value="{{ $alumno->id_user }}" data-username="{{ $alumno->username }}" data-nombre="{{ $alumno->nombre }}" data-origen="alumno" data-apellidos="{{ $alumno->apellidos }}" data-correo="{{ $alumno->correo_electronico }}">
                        </td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <h1>Confirmar información de comité</h1>
    <div id="confirmarComite"></div>

    <h2>Asesorados</h2>
    <div id="asesorados"></div>

    <button type="submit" >Crear comité</button>
</form>
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
   new DataTable('#alumnos', { responsive: true });

   // Función para manejar el cambio de los checkboxes
   function actualizarConfirmacion() {
       let confirmarComiteHtml = '';
       let asesoradosHtml = '';

       // Procesar docentes seleccionados
       $('.checkbox-docente:checked').each(function() {
           const username = $(this).data('username');
           const nombre = $(this).data('nombre');
           const apellidos = $(this).data('apellidos');
          

           confirmarComiteHtml += `
               <div>
                   ${nombre} ${apellidos}
                   <input type="hidden" name="docentes[]" value="${username}">
                    <h2>Confirmar rol</h2>
                   <input type="text" name="nombre_rol[]" placeholder="Confirmar rol" class="form-control mt-1" required>
               </div>
           `;
       });

       // Procesar alumnos seleccionados
       $('.checkbox-alumno:checked').each(function() {
           const username = $(this).data('username');
           const nombre = $(this).data('nombre');
           const apellidos = $(this).data('apellidos');
         

           asesoradosHtml += `
               <div>
                   ${nombre} ${apellidos}
                   <input type="hidden" name="alumnos[]" value="${username}">
               </div>
           `;
       });

       $('#confirmarComite').html(confirmarComiteHtml);
       $('#asesorados').html(asesoradosHtml);
   }
   // Evento de cambio en checkboxes
   $(document).on('change', '.checkbox-docente, .checkbox-alumno', function() {
       actualizarConfirmacion();
   });
</script>
@endsection