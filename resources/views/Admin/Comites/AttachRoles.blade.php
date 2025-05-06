@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-center mt-4">Panel de roles</h1>
    <div class="container">

        <div class="row bg-body-secondary fs-4 py-5 shadow-lg mb-4">
            <p>
                En este panel podrá definir los roles que usarán en los comités de su área. Una vez definidos, al crear comités nuevos los roles ingresados aquí aparecerán en una lista de roles permitidos para los usuarios del comité.
            </p>
        </div>
        <button type="button" id="mostrarRoles" class="btn btn-info {{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}">
            Crear Nuevos Roles
        </button>
        <form method="POST" action="{{ route('comites.saveRoles', $comite->id_comite) }}">
            @csrf
            
            <div id="users-roles" class="{{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}">
                {{-- Asignación de Roles --}}
                <h2 class="text-2xl font-bold mb-6">Asignar Roles a Usuarios del Comité: {{ $comite->nombre_comite }}</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Usuario</th>
                            <th class="border px-4 py-2">Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comite->usuarios as $usuario)
                            <tr>
                                <td class="border px-4 py-2">{{ $usuario->nombre }}</td>
                                <td class="border px-4 py-2">
                                    <select name="roles[{{ $usuario->id_user }}][]" multiple class="w-full border rounded p-2 user-role-select">
                                       
                                        @foreach($rolesExistentes->isEmpty() ? $roles : $rolesExistentes as $rol)
                                            <option value="{{ $rol->id_rol }}" data-nombre_rol="{{ $rol->nombre_rol }}">
                                                {{ $rol->nombre_rol }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Guardar Roles
                </button>
            </div>

            @include('Admin.Comites.DefineRolesSection')
           
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.getElementById('users-roles').addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('user-role-select')) {
        // Obtener el ID del usuario desde el 'name' del select
        const userId = e.target.name.match(/\d+/)[0];

        // Limpiar los inputs ocultos previos
        const previousHiddenInput = e.target.closest('td').querySelector('input[type="hidden"]');
        if (previousHiddenInput) {
            previousHiddenInput.remove();
        }

        // Recoger el nombre del rol y el tipo de rol seleccionado
        const selectedOptions = Array.from(e.target.selectedOptions);
        const rolesData = selectedOptions.map(option => {
            return {
                id_tipo: option.value,           // Obtenemos el id_tipo
                nombre_rol: option.dataset.nombre_rol // Obtenemos el nombre_rol
            };
        });
         // Crear un nuevo input hidden con la información JSON de los roles seleccionados
         const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `roles_json[${userId}]`;  // Usamos una clave para cada usuario
        hiddenInput.value = JSON.stringify(rolesData);  // Convertimos el array de roles a JSON

        // Añadir el input hidden al contenedor de roles
        e.target.closest('td').appendChild(hiddenInput);
         selectedOptions.forEach(option => {
             const nombreRol = option.textContent.trim();
             const tipoRol = option.value;

             // Crear un nuevo input hidden con la información del rol
             const hiddenInput = document.createElement('input');
             hiddenInput.type = 'hidden';
             hiddenInput.name = `newRoles[${userId}][${tipoRol}]`;
             hiddenInput.value = nombreRol;

             // Añadir el input hidden al contenedor de roles
             e.target.closest('td').appendChild(hiddenInput);
         });
    }
});

</script>
<script>
   
   document.getElementById('mostrarRoles')?.addEventListener('click', function () {
    // Ocultar la vista de asignación de roles
    document.getElementById('users-roles').classList.add('d-none');
    
    // Mostrar la vista de creación de roles
    document.getElementById('roles-container').classList.remove('d-none');
    
    // Ocultar el botón "Crear Nuevos Roles"
    this.classList.add('d-none');
    
    // Mostrar los botones de agregar roles y definir roles
    document.querySelector('.roles-buttons').classList.remove('d-none');
});
   
</script>
<script>
    document.getElementById('agregarRol').addEventListener('click', function () {
        const container = document.getElementById('roles-container');
        const newRol = document.createElement('div');
        newRol.classList.add('rol-item', 'row', 'g-3', 'align-items-start', 'mb-4', 'p-3', 'border', 'rounded', 'shadow-sm', 'bg-white');

        const options = @json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]));

        // let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
        // options.forEach(opt => {
        //     optionsHtml += `<option value="${opt.id}">${opt.nombre}</option>`;
        // });
        let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
        options.forEach(opt => {
            optionsHtml += `<option value="${opt.id}" data-descripcion="${opt.descripcion}">${opt.nombre}</option>`;
        });
        // let inputsHtml = '';
        // usuarios.forEach(user => {
        // inputsHtml += `
        //     <div class="mb-3">
        //         <label class="form-label">Nombre del Rol para ${user.nombre}</label>
        //         <input class="form-control" type="text" name="nombre_rol[]" autocomplete="off" required>
        //     </div>
        // `;
    // });
        let clonar = document.querySelector('.rol-item').cloneNode(true);
        clonar.querySelector("input").value = "";
        clonar.querySelector("textarea").value = "";
        newRol.appendChild(clonar)
        // newRol.innerHTML = `
        //     <div class="col-md-6">
        //         <label class="form-label">Nombre del Rol Personalizado</label>
        //         <input class="form-control" type="text" name="nombre_rol[]" autocomplete="off" required>
        //     </div>
        //     <div class="col-md-6">
        //         <label class="form-label">Tipo de Rol Base</label>
        //         <select class="form-select mb-2 rol-base-select" name="tipo_rol_base[]">
        //             ${optionsHtml}
        //         </select>
        //         <label class="form-label">Descripción del Rol</label>
        //         <textarea class="form-control descripcion-rol" name="descripcion_rol[]" rows="2" readonly></textarea>
        //     </div>
        // `;

        container.appendChild(newRol);
    });

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
                    //const existingRoles = Array.from(select.options).map(option => option.value); // Extraemos los valores de los roles existentes
                    option.value = tipo;
                    option.textContent = nombre;
                    select.appendChild(option);
                    
                    select.addEventListener('change', function (e) {
                        // Obtener el ID del usuario desde el 'name' del select
                        const userId = e.target.name.match(/\d+/)[0];

                        // Limpiar los inputs ocultos previos
                        const previousHiddenInput = select.closest('td').querySelector('input[type="hidden"]');
                        if (previousHiddenInput) {
                            previousHiddenInput.remove();
                        }

                        // Recoger el nombre del rol y el tipo de rol seleccionado
                        const selectedOptions = Array.from(e.target.selectedOptions);
                        selectedOptions.forEach(option => {
                            const nombreRol = option.textContent.trim();
                            const tipoRol = option.value;

                            // Crear un nuevo input hidden con la información del rol
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = `newRoles[${userId}][${tipoRol}]`;
                            hiddenInput.value = nombreRol;

                            // Añadir el input hidden al contenedor de roles
                            select.closest('td').appendChild(hiddenInput);  // Aquí es donde agregamos el input hidden al td
                        });
                    });
                });
           
            }
        });

        if (alMenosUnRolValido) {
            document.getElementById('roles-container').style.display = 'none';
            document.querySelector('.roles-buttons').style.display = 'none';
            document.getElementById('users-roles').classList.remove('d-none');

            this.disabled = true;
            this.innerText = "Roles definidos ✓";
        } else {
            alert("Debes llenar al menos un rol correctamente antes de continuar.");
        }
    });

    // Actualizar descripción cuando cambia el tipo de rol base
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('rol-base-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const descripcion = selectedOption.getAttribute('data-descripcion');
            const descripcionField = e.target.closest('.rol-item').querySelector('.descripcion-rol');
            
            descripcionField.value = descripcion || '';
        }
    });

    // Para debug: ver datos del formulario antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log([...formData.entries()]);
        this.submit();
    });
</script>
@endsection