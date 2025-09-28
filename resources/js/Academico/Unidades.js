import DataTable from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';

new DataTable('#unidades', {
                responsive: true
            });
        
            $("body").on("click",".delete > button",function(e){
            e.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Unidad",
                text: "Estas a punto de eliminar esta unidad academica junto con sus programas academicos relacionados, esto no puede ser reversible, ¿Estas seguro?",
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