import DataTable from "datatables.net-bs5";
$('#programa').DataTable();
 $("body").on("click",".delete > button",function(event){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Programa academico",
                text: "Estas a punto de eliminar este programa academico, junto con su informacion relacionada, esto no puede ser reversible ¿Estas seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar"
            }).then((result) => {
            if (result.isConfirmed) {
                $(formulario).submit();
               
            }
        });
    });
    $('#agregarPrograma').on('click', function() {
            const container = document.getElementById('programas-container');
            const newPrograma = document.createElement('div');
            newPrograma.classList.add('programa-item');
            newPrograma.innerHTML = `
                <div class="programa-item text-center mt-5">
            <div class="fs-3 fw-semibold mb-1"><label for="nombre_programa">Nombre del Programa</label></div>
            <div class="mx-5"><input type="text" name="nombre_programa[]" class="form-control" autocomplete="off" required></div>
                </div>
            `;
            container.appendChild(newPrograma);
        });