
// $('[name="nombre_tipo[]"]:checked').each(function(){

// });
$(function(){
    updateTipos();
});
function updateTipos(){
    let mostrarGeneracion = false;
    let mostrarClaveT = false;
    let mostrarMatricula = false;

    $('[name="nombre_tipo[]"]:checked').each(function () {
        const texto = $(this).parent().text().trim();

        if (texto.includes("Alumno")) {
            mostrarGeneracion = true;
            mostrarMatricula =true;
        }

        if (texto.includes("Docente") || texto.includes("Coordinador")) {
            mostrarClaveT = true;
        }
    });
   

    if (mostrarGeneracion) {
        $("#generacion").show();
        $('#generacion').find('input').prop('required',true);
    } else {
        $("#generacion").hide();
        $('#generacion').find('input').prop('required',false);
        $('#generacion').find('input').prop('required',false).val('');

    }

    if (mostrarClaveT) {
        $("#claveT").show();
        $('#claveT').find('input').prop('required',true);

    } else {
        $("#claveT").hide();
        $('#claveT').find('input').prop('required',false).val('');

    }

    if (mostrarMatricula) {
        $("#matricula").show();
        $('#matricula').find('input').prop('required',true);

    } else {
        $("#matricula").hide();
        $('#matricula').find('input').prop('required',false).val('');

    }
}
$('[name="nombre_tipo[]"]').on("change",updateTipos);
