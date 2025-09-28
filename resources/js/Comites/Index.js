
import jQuery from "jquery";
import $ from "jquery";
$(function() {
    $('#miTabla').DataTable();
    // new DataTable('#miTabla', { responsive: true });

            // Inicialización para selects dentro de modales
    $('[data-parent-modal]').each(function() {
        const parentModalId = $(this).data('parent-modal');
        const parentModal = $(`#${parentModalId}`);
        console.log(parentModalId);
        // Destruir si ya está inicializado
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        
        // Inicializar select2 con el modal padre correcto
        $(this).select2({
            theme: "bootstrap-5",
            dropdownParent: parentModal
        });
    });
            //   let boton=document.querySelector("#Btn_buscar")
            //   boton.addEventListener("click",()=>{
            //     let input = document.querySelector("#inputBuscar")
            //     let value = input.value.toLowerCase();
            //     let cards = document.querySelectorAll(".card")
                
                
            //     console.log(title)
            //     cards.forEach(card=>{
               
                    
                   
            //     })
                


            //   })
         });

        $("body").on("click",".delete > button",function(){
         
            preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Comite",
                text: "Estas a punto de eliminar este comite, esto no puede ser reversible ¿Estas seguro?",
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
    
   
//   $(document).ready(function() {
    
//   });