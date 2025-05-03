@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
<style>
    .role-select {
        height: auto;
        min-height: 100px;
    }
    .selected-user-card {
        border-left: 4px solid #0d6efd;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center">{{ $comite ? 'Editar' : 'Crear' }} Comité</h1>
    
    <form action="{{ $comite ? route('comites.update', $comite->id_comite) : route('comites.create') }}" method="POST">
        @csrf
        @if($comite) @method('PUT') @endif

        <input type="hidden" name="id" value="{{ $comite?->id_comite }}">
        <a href="{{ Route("roles.index") }}">Editar roles</a>
        
        <button type="button" id="mostrarRoles" class="btn btn-info {{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}">
            Crear Nuevos Roles
        </button>
        @include('Admin.Comites.DefineRolesSection')
        <div id="editSection">
            <div class="mb-3">
                <label class="form-label fs-5 fw-semibold">Nombre del comité</label>
                <input class="form-control" type="text" name="nombre_comite" required 
                       value="{{ old('nombre_comite', $comite?->nombre_comite) }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label fs-5 fw-semibold">Programa académico</label>
                <select class="form-select" name="ProgramaAcademico" required>
                    <option value="">Seleccione un programa</option>
                    @foreach ($programas as $programa)
                        <option value="{{ $programa->id_programa }}" 
                            {{ ($comite?->id_programa == $programa->id_programa) ? 'selected' : '' }}>
                            {{ $programa->nombre_programa }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="row mt-4">
                <div class="col-md-7">
                    <h4>Docentes disponibles</h4>
                    <table class="table" id="docentes">
                        <thead class="table-primary">
                            <tr>
                                <th>Seleccionar</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($docentes as $docente)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               class="checkbox-docente" 
                                               value="{{ $docente->id_user }}"
                                               data-username="{{ $docente->username }}"
                                               {{ $comite && $comite->usuarios->contains($docente->id_user) ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $docente->username }}</td>
                                    <td>{{ $docente->nombre }}</td>
                                    <td>{{ $docente->apellidos }}</td>
                                    <td>{{ $docente->correo_electronico }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="col-md-5">
                    <h4>Miembros y roles del comité</h4>
                    <div id="confirmarComite">
                        @if($comite)
                            @foreach($comite->usuarios as $miembro)
                                <div class="card selected-user-card mb-3" data-user-id="{{ $miembro->id_user }}">
                                    <div class="card-body">
                                        <h5>{{ $miembro->nombre }} {{ $miembro->apellidos }}</h5>
                                        <input type="hidden" name="docentes[]" value="{{ $miembro->id_user }}">
                                        
                                        <label class="form-label">Roles asignados</label>
                                        <select class="form-select role-select" name="roles[{{ $miembro->id_user }}][]" multiple>
                                            @foreach($roles as $rol)
                                                @php
                                                    $selected = DB::table('usuarios_comite_roles')
                                                        ->join('usuarios_comite', 'usuarios_comite_roles.id_usuario_comite', '=', 'usuarios_comite.id_usuario_comite')
                                                        ->where('usuarios_comite.id_user', $miembro->id_user)
                                                        ->where('usuarios_comite.id_comite', $comite->id_comite)
                                                        ->where('usuarios_comite_roles.id_rol', $rol->id_rol)
                                                        ->exists();
                                                @endphp
                                                <option value="{{ $rol->id_rol }}" {{ $selected ? 'selected' : '' }}>
                                                    {{ $rol->rol_personalizado }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 py-3">
                {{ $comite ? 'Guardar cambios' : 'Registrar comité' }}
            </button>
        </div>
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
    $(document).ready(function() {
        // Inicializar DataTable
        new DataTable('#docentes', { responsive: true });

        // Actualizar sección de confirmación
        function actualizarConfirmacion() {
            let confirmarComiteHtml = '';

            $('.checkbox-docente:checked').each(function() {
                const userId = $(this).val();
                const username = $(this).data('username');
                const row = $(this).closest('tr');
                const nombre = row.find('td:nth-child(3)').text();
                const apellidos = row.find('td:nth-child(4)').text();

                // Verificar si ya existe en la sección de confirmación
                if ($(`.selected-user-card[data-user-id="${userId}"]`).length === 0) {
                    confirmarComiteHtml += `
                        <div class="card selected-user-card mb-3" data-user-id="${userId}">
                            <div class="card-body">
                                <h5>${nombre} ${apellidos}</h5>
                                <input type="hidden" name="docentes[]" value="${userId}">
                                
                                <label class="form-label">Roles asignados</label>
                                <select class="form-select role-select" name="roles[${userId}][]" multiple>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->id_rol }}">{{ $rol->rol_personalizado }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    `;
                }
            });

            // Eliminar usuarios deseleccionados
            $('.selected-user-card').each(function() {
                const userId = $(this).data('user-id');
                if ($(`.checkbox-docente[value="${userId}"]:checked`).length === 0) {
                    $(this).remove();
                }
            });

            // Agregar nuevos si hay
            if (confirmarComiteHtml) {
                $('#confirmarComite').append(confirmarComiteHtml);
            }
        }

        // Eventos
        $(document).on('change', '.checkbox-docente', actualizarConfirmacion);

        // Inicializar al cargar si es edición
        @if($comite)
            actualizarConfirmacion();
        @endif
    });
</script>
{{-- seccion crear roles --}}
<script>
    document.getElementById('mostrarRoles')?.addEventListener('click',function(){
        document.getElementById('editSection').classList.add('d-none');

        document.getElementById('roles-container').classList.remove('d-none');

        this.classList.add('d-none');

        document.querySelector('.roles-buttons').classList.remove('d-none');

    });
</script>
<script>
   document.getElementById('agregarRol').addEventListener('click', function () {
    const container = document.getElementById('roles-container');
    const newRol = document.createElement('div');
    newRol.classList.add('rol-item', 'row', 'g-3', 'align-items-start', 'mb-4', 'p-3', 'border', 'rounded', 'shadow-sm', 'bg-white');

    // Cambié la forma de enviar las opciones de roles base
    const options = @json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol, 'descripcion' => $r->descripcion]));

    let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
    options.forEach(opt => {
        optionsHtml += `<option value="${opt.id}" data-descripcion="${opt.descripcion}">${opt.nombre}</option>`;
    });

    newRol.innerHTML = `
        <div class="col-md-6">
            <label class="form-label">Nombre del Rol Personalizado</label>
            <input class="form-control" type="text" name="nombre_rol[]" autocomplete="off" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tipo de Rol Base</label>
            <select class="form-select mb-2 rol-base-select" name="tipo_rol_base[]">
                ${optionsHtml} <!-- Aquí insertamos las opciones dinámicas -->
            </select>
            <label class="form-label">Descripción del Rol</label>
            <textarea class="form-control descripcion-rol" name="descripcion_rol[]" rows="2" readonly></textarea>
        </div>
    `;

    container.appendChild(newRol);
});

// Lógica para manejar la definición de roles
document.getElementById('definirRoles').addEventListener('click', function () {
    let alMenosUnRolValido = false;

    document.querySelectorAll('.rol-item').forEach((item, index) => {
        const nombre = item.querySelector('input[name="nombre_rol[]"]').value.trim();
        let tipo = item.querySelector('select[name="tipo_rol_base[]"]').value;
        const descripcion = item.querySelector('textarea[name="descripcion_rol[]"]').value;

        if (nombre && tipo) {
            alMenosUnRolValido = true;

            // Desactivar inputs
            item.querySelectorAll('input, select, textarea').forEach(el => {
                el.setAttribute('hidden', true);
                el.classList.add('bg-light');
            });

            document.querySelectorAll('.user-role-select').forEach(select => {
                const option = document.createElement('option');
                option.value = tipo;
                option.textContent = nombre;
                select.appendChild(option);

                // Manejo de la selección de roles en cada usuario
                select.addEventListener('change', function (e) {
                    const userId = e.target.name.match(/\d+/)[0];
                    const previousHiddenInput = select.closest('td').querySelector('input[type="hidden"]');
                    if (previousHiddenInput) {
                        previousHiddenInput.remove();
                    }

                    const selectedOptions = Array.from(e.target.selectedOptions);
                    selectedOptions.forEach(option => {
                        const nombreRol = option.textContent.trim();
                        const tipoRol = option.value;

                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `newRoles[${userId}][${tipoRol}]`;
                        hiddenInput.value = nombreRol;

                        select.closest('td').appendChild(hiddenInput);
                    });
                });
            });
        }
    });

    if (alMenosUnRolValido) {
        document.getElementById('roles-container').style.display = 'none';
        document.querySelector('.roles-buttons').style.display = 'none';
        document.getElementById('editSection').classList.remove('d-none');

        this.disabled = true;
        this.innerText = "Roles definidos ✓";
    } else {
        alert("Debes llenar al menos un rol correctamente antes de continuar.");
    }
});

</script>
@endsection