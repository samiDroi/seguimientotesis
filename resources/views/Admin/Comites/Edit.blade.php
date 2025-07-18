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
    <h1 class="text-center mt-4">Editar Comité</h1>
    
    <form action="{{ route('comites.update', $comite->id_comite) }}" id="edit-form" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $comite->id_comite }}">
        
        <button class="btn mb-4 " style="background-color:var(--color-amarillo)"><a class="text-decoration-none text-light" href="{{ Route("roles.index") }}"> <i class="fa-solid fa-pencil"></i> Editar roles</a></button>
        <button type="button" id="create-roles" class="{{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}">Crear roles</button>
        @include('Admin.Comites.DefineRolesSection')
        <div id="editSection">
            <div class="mb-3">
                <label class="form-label fs-5 fw-semibold">Programa académico</label>
                <select class="form-select" name="ProgramaAcademico" required>
                    <option value="">Seleccione un programa</option>
                    @foreach ($programas as $programa)
                        <option value="{{ $programa->id_programa }}" 
                            {{ ($comite->id_programa == $programa->id_programa) ? 'selected' : '' }}>
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
                                               {{ $comite->usuarios->contains($docente->id_user) ? 'checked' : '' }}>
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
                        @foreach($comite->usuarios as $miembro)
                            <div class=" users-roles card selected-user-card mb-3" id="" data-user-id="{{ $miembro->id_user }}">
                                <div class="card-body">
                                    <h5>{{ $miembro->nombre }} {{ $miembro->apellidos }}</h5>
                                    <input type="hidden" name="docentes[]" value="{{ $miembro->id_user }}">
                                    
                                    <label class="form-label">Roles asignados</label>
                                    <select class="form-select role-select user-role-select" id="select-roles" data-user="{{ $miembro->id_user }}" name="roles[{{ $miembro->id_user }}][]" multiple>
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
                    </div>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 py-3">
                Guardar cambios
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
{{-- Todo este script es la section de decidir docentes y que aparezcan a la derecha --}}
<script>
    const rolesDefinidos = [];
$(document).ready(function() {
    
    new DataTable('#docentes', { responsive: true });
    
        
    //Boton que oculta y aparece la seccion de crear roles dinamicamente
    $('#create-roles').on('click', function() {
        let isSecond = false;
        let containersData = [];
        let emptyItems = [];
       
        //evalua si es la segunda vez que se clickea el elemento
        $('#roles-container .rol-item').each(function(){
                let rolInput = $(this).find('input').val().trim();
                //console.log(rolInput)
                if(rolInput !== ''){
                    containersData.push($(this));
                    isSecond = true;
                }

                if(rolInput === '' && $('#roles-container .rol-item').length > 1){
                    $(this).remove();
                }
                
                
        });
       
        //console.log(isSecond)
        //si es la segunda vez que se clickea, se remueven los roles ya escritos y aparece uno nuevo y vacio
        if(isSecond){
            console.log(containersData);
            let cleanContainer = containersData[0].clone();
            cleanContainer.find('input,select,textarea').val('');
            cleanContainer.removeClass('d-none');
            $('#roles-container').append(cleanContainer);

            containersData.forEach(function(item){
                $(item).remove();
                //$(item).find('input,select,textarea').val('').addClass('d-none');
            })
            isSecond = false;
        }

        $(this).addClass('d-none');
        $('#roles-container').removeClass('d-none');
        $('.roles-buttons').removeClass('d-none');
     
        
    });
    //boton para cancelar la creacion de roles y volver a la pantalla principal
    $('#cancelRoles').on('click', function() {
        $('#roles-container input').val('');
        $('#roles-container select').val('');
        $('#roles-container textarea').val('');
        
        $('#roles-container').addClass('d-none');
        $('.roles-buttons').addClass('d-none');
        $('#create-roles').removeClass('d-none');
    });
    //boton para eliminar el rol
    $('#roles-container').on('click','.delete-rol',function(){
        $(this).closest('.rol-item').remove();
    });  

    function actualizarConfirmacion() {
        let confirmarComiteHtml = '';

        $('.checkbox-docente:checked').each(function() {

            const userId = $(this).val();
            const row = $(this).closest('tr');
            const nombre = row.find('td:nth-child(3)').text();
            const apellidos = row.find('td:nth-child(4)').text();
            let bandera = false;

            if ($(`.selected-user-card[data-user-id="${userId}"]`).length === 0) {
                console.log(userId);
                confirmarComiteHtml = $(".selected-user-card:first").clone()
                confirmarComiteHtml.attr('data-user-id', userId);
                confirmarComiteHtml.find('h5').text(`${nombre} ${apellidos}`);
                confirmarComiteHtml.find('[name="docentes[]"]').val(userId);
                confirmarComiteHtml.find('.user-role-select').attr('data-user', userId);
                confirmarComiteHtml.find('.user-role-select').attr('name', `roles[${userId}][]`);
                
            }
            
        $('.user-role-select dinamic').each(function() {
            const select = this;
            const userId = select.dataset.user;

            // Evitar duplicar opciones si ya hay
            if (select.options.length <= @json($roles->count())) {
                rolesDefinidos.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.nombre;
                    select.appendChild(option);
                });
            }
        });
        });
        // Agregar roles definidos al nuevo select
        

        $('.selected-user-card').each(function() {
            const userId = $(this).data('user-id');
            if ($(`.checkbox-docente[value="${userId}"]:checked`).length === 0) {
                $(this).remove();
            }
        });

        if (confirmarComiteHtml) {
            console.log('pegando');
            
            $('#confirmarComite').append(confirmarComiteHtml);

            
    // Después de haber insertado el HTML al DOM:
   
        }
    }

    $(document).on('change', '.checkbox-docente',function(){
        //al deseleccionar cada checkbox, se revisa si ya no hay seleccionado ningun docente
        if(!this.checked){
            totalSelected = $('.checkbox-docente:checked').length;
            if(totalSelected === 0){
                //si no hay ninguno seleccionado, se alerta y se vuelve a seleccionar el ultimo que se deselecciono
                alert('debes seleccionar al menos un docente');
                this.checked = true;
                return;
            }
        }

        actualizarConfirmacion();
    } );
    actualizarConfirmacion();
});

// Esta parte busca solucionar todo los de los roles
$(document).on('change', '.user-role-select', function(e) {
    const $select = $(this);
    const userId = $select.data('user'); 

    const rolesData = $select.find('option:selected').map(function() {
        return {
            id_tipo: $(this).val(),
            nombre_rol: $(this).text().trim()
        };
    }).get();

    

    // Crear nuevo input hidden con los datos en JSON
     // Verifica si ya existe un input hidden justo después de este select
    let $existingInput = $select.next('input[type="hidden"][name^="roles_json"]');

   
    // Quitar solo el input justo después del select (si existe y coincide con el usuario)
    $select.nextAll(`input[name^="roles_json[${userId}]"]`).first().remove();
    
        const $hiddenInput = $('<input>', {
            type: 'hidden',
            name: `roles_json[${userId}]`, // ARRAY para cada input
            value: JSON.stringify(rolesData)
        });
    
    

    $select.after($hiddenInput);
});

    
document.getElementById('agregarRol').addEventListener('click', function () {
        //roles-container es el contenedor de todos los cuadros de creacion de tesis
        const container = document.getElementById('roles-container');
        const options = @json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]));
       
        let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
        options.forEach(opt => {
            optionsHtml += `<option value="${opt.id}" data-descripcion="${opt.descripcion}">${opt.nombre}</option>`;
        });

       
        let clonar = document.querySelector('.rol-item').cloneNode(true);
        clonar.querySelector("input").value = "";
        clonar.querySelector("textarea").value = "";
        clonar.querySelector(".delete-rol").classList.remove('d-none');
        $(clonar).removeClass('d-none');
        // .appendChild(clonar)
   

        container.appendChild(clonar);
    });
    //funcion que sucede al crear los roles en el boton de crear roles
    //para definir roles, se cierra la pestaña de creacion de roles y regresa el boton de crear roles
    document.getElementById('definirRoles').addEventListener('click', function () {
        let alMenosUnRolValido = false;

        document.querySelectorAll('.rol-item').forEach((item, index) => {
            const nombre = item.querySelector('input[name="nombre_rol[]"]').value.trim();
            let tipo = item.querySelector('select[name="tipo_rol_base[]"]').value;
            // const descripcion = item.querySelector('textarea[name="descripcion_rol[]"]').value;

            if (nombre && tipo) {
                alMenosUnRolValido = true;

                rolesDefinidos.push({ id: tipo, nombre: nombre });
                // Desactivar inputs
                item.querySelectorAll('input, select, textarea').forEach(el => {
                    // el.setAttribute('hidden', true);
                    //el.classList.add('bg-light');
                });

               
                document.querySelectorAll('.user-role-select').forEach(select => {
                    
                    const option = document.createElement('option');
                    //const existingRoles = Array.from(select.options).map(option => option.value); // Extraemos los valores de los roles existentes
                    option.value = tipo;
                    option.textContent = nombre;
                    option.classList.add('fs-4');
                    select.appendChild(option);
                });
           
            }
        });

        if (alMenosUnRolValido) {
            // document.getElementById('roles-container').style.display = 'none';
            // document.querySelector('.roles-buttons').style.display = 'none';
            //document.getElementById('users-roles').classList.remove('d-none');
            // $('#definirRoles').on('click', function() {
            $('#roles-container').addClass('d-none');
            $('.roles-buttons').addClass('d-none');
            $('#create-roles').removeClass('d-none');
            // });
            // this.disabled = true;
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
</script>
@endsection
