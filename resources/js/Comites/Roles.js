const rolesDefinidos = [];
$(function() {  
    $('.user-role-select').select2({
        width: '100%' // opcional, para que no se desborde en Bootstrap
    });

    $('#users-roles').on('change', '.user-role-select', function(e) {
    if (e.target && e.target.classList.contains('user-role-select')) {
        // Obtener el ID del usuario desde el 'name' del select
        const userId = e.target.name.match(/\d+/)[0];

        // Recoger el nombre del rol y el tipo de rol seleccionado
        const selectedOptions = Array.from(e.target.selectedOptions);
         const rolesData = selectedOptions.map(option => {
                return {
                    id_tipo: option.value,           // Obtenemos el id_tipo
                    nombre_rol: option.textContent.trim() // Obtenemos el nombre_rol
                };
            });
           
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `roles_json[${userId}]`;  // Usamos una clave para cada usuario
            hiddenInput.value = JSON.stringify(rolesData);  // Convertimos el array de roles a JSON
             // Añadir el input hidden al contenedor de roles
            e.target.closest('td').appendChild(hiddenInput);

            const previousHiddenInput = e.target.closest('td').querySelector('input[type="hidden"]');

            previousHiddenInput.forEach(input=>input.remove())
    }
});
     
$('#roles-container').on('click','.delete-rol',function(){
    $(this).closest('.rol-item').remove();
});  
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
       
        console.log(isSecond)
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
    const cancelButton = document.getElementById('cancelRoles');
    $('#cancelRoles').on('click',function(){
        //asignar roles aparece
        document.getElementById('users-roles').classList.remove('d-none');
        //crear roles desaparece
        document.getElementById('roles-container').classList.add('d-none');
        //boton de crear nuevos roles aparece otra vez
        document.getElementById('mostrarRoles').classList.remove('d-none');
        //este boton desaparece
        this.classList.add('d-none');
        //todos los botones desaparecen
        document.querySelector('.roles-buttons').classList.add('d-none');
    });

   document.getElementById('mostrarRoles')?.addEventListener('click', function () {
       document.getElementById('cancelRoles').classList.remove('d-none');
        // Ocultar la vista de asignación de roles
        document.getElementById('users-roles').classList.add('d-none');
        
        // Mostrar la vista de creación de roles
        document.getElementById('roles-container').classList.remove('d-none');
        
        // Ocultar el botón "Crear Nuevos Roles"
        this.classList.add('d-none');
    
        // Mostrar los botones de agregar roles y definir roles

        document.querySelector('.roles-buttons').classList.remove('d-none');
    });

    //esto esta ligado a la seccion de creacion de roles
    $('#agregarRol').on('click', function () {
        let datos= document.getElementById("datos-json");
        //roles-container es el contenedor de todos los cuadros de creacion de tesis
        const container = document.getElementById('roles-container');
        // const options = @json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]));
        const options = JSON.parse(datos.dataset.datos);
//          const options = {!! json_encode($rolesBase->map(fn($r) => [
//       'id' => $r->id_rol,
//       'nombre' => $r->nombre_rol
//   ])) !!};
        let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
        options.forEach(opt => {
            optionsHtml += `<option value="${opt.id}" data-descripcion="${opt.descripcion}">${opt.nombre}</option>`;
        });

       
        let clonar = document.querySelector('.rol-item').cloneNode(true);
        clonar.querySelector("input").value = "";
        clonar.querySelector("textarea").value = "";
        clonar.querySelector('.delete-rol').classList.remove('d-none');
        // .appendChild(clonar)
   

        container.appendChild(clonar);
    });

    //funcion que sucede al crear los roles en el boton de crear roles
    $('#definirRoles').on('click', function () {
        let alMenosUnRolValido = false;

        document.querySelectorAll('.rol-item').forEach((item, index) => {
            const nombre = item.querySelector('input[name="nombre_rol[]"]').value.trim();
            let tipo = item.querySelector('select[name="tipo_rol_base[]"]').value;
            // const descripcion = item.querySelector('textarea[name="descripcion_rol[]"]').value;

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
                    option.classList.add('fs-4');
                    select.appendChild(option);
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
    
});  
 
    