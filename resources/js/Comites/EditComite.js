import DataTable from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';

const rolesDefinidos = [];
$(function() {
    if ($.fn.DataTable.isDataTable('#docentes')) {
    $('#docentes').DataTable().destroy();
}
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
             console.log(confirmarComiteHtml);
                
            }
            
        $('.user-role-select dinamic').each(function() {
            const select = this;
            const userId = select.dataset.user;
            const countRoles = JSON.parse(document.querySelector('div[data-roles]').getAttribute('data-roles'));
            // Evitar duplicar opciones si ya hay
            if (select.options.length <= countRoles) {
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
             console.log(confirmarComiteHtml);
            $('#confirmarComite').append(confirmarComiteHtml);
           
            
    
   
        }
    }

    $(document).on('change', '.checkbox-docente',function(){
        //al deseleccionar cada checkbox, se revisa si ya no hay seleccionado ningun docente
        if(!this.checked){
            let totalSelected = $('.checkbox-docente:checked').length;
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

    
$('#agregarRol').on('click', function () {
        //roles-container es el contenedor de todos los cuadros de creacion de tesis
        const container = document.getElementById('roles-container');
        // const options = @json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]));
        const options = JSON.parse(document.querySelector('div[data-rolesBase]').getAttribute('data-rolesBase'));
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
    $('#definirRoles').on('click', function () {
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