

$('[name="nombre_tipo[]"]').on("change", function () {
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
    } else {
        $("#generacion").hide();
    }

    if (mostrarClaveT) {
        $("#claveT").show();
    } else {
        $("#claveT").hide();
    }

    if (mostrarMatricula) {
        $("#matricula").show();
    } else {
        $("#matricula").hide();
    }
});$('[name="nombre_tipo[]"]').on("change", function () {
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
    } else {
        $("#generacion").hide();
    }

    if (mostrarClaveT) {
        $("#claveT").show();
    } else {
        $("#claveT").hide();
    }

    if (mostrarMatricula) {
        $("#matricula").show();
    } else {
        $("#matricula").hide();
    }


    
});
