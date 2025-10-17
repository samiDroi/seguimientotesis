    import DataTable from "datatables.net-bs5";
    // Inicializar DataTables
    new DataTable('#docentes-members', { responsive: true });

    function actualizarConfirmacion() {
        let confirmarComiteHtml = '';

        // Procesar docentes seleccionados
        $('.checkbox-docente:checked').each(function() {
            const username = $(this).val(); // Obtener el valor (username)
            const nombre = $(this).closest('tr').find('td:nth-child(2)').text(); // Obtener el nombre
            const apellidos = $(this).closest('tr').find('td:nth-child(3)').text(); // Obtener los apellidos

            confirmarComiteHtml += `
             <label >Nombre del docente</label>
                <div class="mb-3">
                     
                <div class="fs-2 fw-semibold"> ${nombre} ${apellidos}</div>
                     <input type="hidden" name="docentes[]" value="${username}">
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