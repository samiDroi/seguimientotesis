
// const { get } = require("jquery");
const ROUTE = document.querySelector('#comentarios-route')?.dataset.routec;
renderComentarios();
var existMain = document.querySelector('div[data-content-main]')?.dataset.contentMain == "";
    // let contentMain = document.querySelector('div[data-content-main]').dataset.contentMain
    // console.log(contentMain);
    
    if(existMain && document.querySelector('main')){
        buscar(document.querySelectorAll('main *'))
        console.log("existe main");
        const cont = document.querySelector('main')      
        cont.innerHTML = cont.innerHTML
        .replace(/&lt;inicio&gt;/g, '<span class="c">')
        .replace(/&lt;fin&gt;/g, ' </span>');
        
        let i = 0;
        for (const c of document.querySelectorAll("span.c")) {
            c.dataset.nodo = i++;
        }
    }
        // Añadimos un event listener a la sección de comentarios
document.querySelector("#comentarios")?.addEventListener("click", function (e) {
    // Nos aseguramos de que se hizo clic en un 'mensaje'
    if (e.target.classList.contains("comentario-cargado") || e.target.classList.contains("mensaje")) {
        const claveComentario = e.target.dataset.clave;
        let textoCompleto = '';

        // 1. Buscamos todos los spans de comentario con la misma clave
        const nodosComentados = document.querySelectorAll(`.comment[data-clave="${claveComentario}"]`);

        // 2. Juntamos el texto de cada nodo en una sola variable
        nodosComentados.forEach(nodo => {
            textoCompleto += nodo.textContent + ' '; // Añadimos un espacio para separar las palabras
        });

        // 3. Mostramos el texto resultante en el contenedor HTML
        const contenedor = document.querySelector(".selected-text");
        contenedor.innerHTML = `<p><strong>Texto comentado:</strong> ${textoCompleto.trim()}</p>`;
    }
});
        // document.querySelector('body').addEventListener('mouseover', function(e){
        //     document.querySelectorAll(`.comment`).forEach(element => {                    
        //             element.style.background = "none"
        //         })
        //     if(e.target.classList.contains('mensaje') || e.target.classList.contains('comentario-cargado')){
        //         document.querySelectorAll(`.comment[data-clave="${e.target.dataset.clave}"]`).forEach(element => {                    
        //             element.style.background = "yellow"
        //         })
        //     }
        //     document.querySelectorAll('.comment')
        // })
                
//         document.addEventListener('mouseover', function(e) {
//     if (e.target.closest('.mensaje, .comentario-cargado')) {
//         const comentario = e.target.closest('.mensaje, .comentario-cargado');
//         const clave = comentario.dataset.clave;

//         // quitar resaltado anterior
//         document.querySelectorAll('.comment').forEach(el => el.classList.remove('highlighted'));

//         // resaltar los relacionados
//         document.querySelectorAll(`.comment[data-clave="${clave}"]`).forEach(el => {
//             el.classList.add('highlighted');
//         });
//     }
// });

// document.addEventListener('mouseout', function(e) {
//     if (e.target.closest('.mensaje, .comentario-cargado')) {
//         document.querySelectorAll('.comment').forEach(el => el.classList.remove('highlighted'));
//     }
// });



        function buscar(nodos){
            for (const el of nodos) {
                if(el.childNodes.length > 1){
                    buscar(el.childNodes) 
                }else{                    
                    if(!isOnlyWhitespace(el.textContent) && !el.textContent.includes("<fin>")){
                        const palabras = el.textContent.replaceAll("\n", "").split(" ").filter( (el) => el).length;
                        let texto = el.textContent.replaceAll("\n", "").split(" ").filter( (el) => el).join("<fin><inicio>");
                        el.textContent = `<inicio>${texto}<fin>`
                    }
                }
            }
        }
        
        function isOnlyWhitespace(str) {
            return /^\s*$/.test(str);
        }

        document.querySelector("#comentar")?.addEventListener("click", async function () {
            const selection = window.getSelection();

                // --- INICIO DE LA MODIFICACIÓN ---

                // 1. Verificar si la selección está vacía o colapsada
                if (selection.isCollapsed) {
                    // Opcional: puedes alertar al usuario que debe seleccionar texto
                    // alert("Por favor, selecciona un texto para comentar.");
                    return; // Detiene la ejecución si no hay nada seleccionado
                }

                // 2. Verificar si la selección ya contiene un comentario
                const range = selection.getRangeAt(0);
                const selectedContent = range.cloneContents(); // Clonamos el contenido para no afectar el DOM
                
                if (selectedContent.querySelector('.comment[data-clave]')) {
                    alert("Error: No puedes comentar sobre un texto que ya tiene un comentario asignado.");
                    window.getSelection().removeAllRanges(); // Limpia la selección actual
                    return; // Detiene la ejecución de la función
                }
                
                // --- FIN DE LA MODIFICACIÓN ---
            const clave = `cm${Math.floor(Math.random() * 100000000)}`

            // let comentario = prompt("Escribe tu comentario")
            // <$('#comentModal').show('focus');
            
           
            // console.log(comentario);
            const nodoInicial = selection.anchorNode;
            const pasosInicial = selection.anchorOffset
            const numeroInicial = await buscarNodoPadre(nodoInicial)
            
            
            
            
            const nodoFinal = selection.extentNode;
            const pasosFinal = selection.extentOffset

            const numeroFinal = await buscarNodoPadre(nodoFinal)

             let { value: comentario } = await Swal.fire({
                input: "textarea",
                inputLabel: "Message",
                inputPlaceholder: "Type your message here...",
                inputAttributes: {
                    "aria-label": "Type your message here"
                },
                showCancelButton: true
                })
                if (!comentario) {
                Swal.fire('debes agregar un comentario');
                return false;
            }

            
            etiquetaInicial(nodoInicial, nodoFinal, pasosInicial, pasosFinal, clave);
            etiquetaFinal(numeroInicial, numeroFinal, nodoFinal, pasosFinal, clave)

            let idAutor = document.querySelector('#auth').value;
            const cont = document.querySelector('main')      
            cont.innerHTML = cont.innerHTML
            .replace(/&lt;comment/g, `<span class="comment" data-clave="${clave}"`)
                .replace(/&lt;\/comment&gt;/g, '</span>')
                .replace(/&gt;/g, `>`)

            document.querySelector("#comentarios").insertAdjacentHTML('beforeend', `
                <div class="mensaje" data-clave="${clave}" data-autor="${idAutor}">
                ${comentario}
                </div>
            `)
                    // 2️⃣ Esperar a que el DOM se actualice visualmente (importante)
            await new Promise(requestAnimationFrame);

            // 3️⃣ Renderizar los comentarios (esto modifica el contenido)
            await renderComentarios();

            // 4️⃣ Una vez renderizado, subir el contenido completo
            await submitContent();

            // 5️⃣ (Opcional) Limpiar el textarea
            document.querySelector("#nuevoComentario").value = "";
            // // await renderComentarios();
            // setTimeout(() => {
            //     renderComentarios();
            // }, 50);
            // //un pequeño delay porque sino no se guarda bien
            // setTimeout(() => submitContent(), 50);
            
        })


        function buscarNodoPadre(nodo){
            if(nodo.parentNode?.dataset?.nodo){                
                return +nodo.parentNode.dataset.nodo;
            }            
            
            buscarNodoPadre(nodo.parentNode)
        }


        function etiquetaInicial(inicio, fin, caracteres, caracteresFinal, clave) {
            const textoNodoInicial = inicio.textContent.slice(caracteres, caracteresFinal)
            
            if (inicio.parentNode.dataset.nodo == fin.parentNode.dataset.nodo) {
                inicio.parentNode.textContent = inicio.parentNode.textContent.replace(textoNodoInicial,`<comment data-nodo="${inicio.parentNode.dataset.nodo}">${textoNodoInicial}</comment>`)
            }else{
                const remplazo = inicio.textContent.slice(caracteres, inicio.textContent.length);
                inicio.parentNode.textContent = inicio.parentNode.textContent.replace(remplazo,`<comment data-nodo="${inicio.parentNode.dataset.nodo}">${remplazo}</comment>`)                
            }
        }

        function etiquetaFinal(numeroInicial, numeroFinal, fin, caracteres, clave) {
            
            if (numeroInicial != numeroFinal && !isNaN(numeroFinal)) {
                const remplazo = fin.parentNode.textContent.slice(0, caracteres);
                fin.parentNode.textContent = fin.parentNode.textContent.replace(remplazo,`<comment data-nodo="${fin.parentNode.dataset.nodo}">${remplazo}</comment>`)  
                window.getSelection().removeAllRanges();

                const numeroInicio = +numeroInicial;        
                
                for (let i = (numeroInicio+1); i < numeroFinal; i++) {
                    const nodo = document.querySelector(`[data-nodo="${i}"]`);                    
                    nodo.textContent = `<comment data-nodo="${nodo.dataset.nodo}">${nodo.textContent}</comment>`;
                }
            }
        }

        function submitContent(){
            let contenido_original = document.querySelector('main').outerHTML;
            let comentarios = document.querySelector('.comentario-contenido').outerHTML;
            let route = document.querySelector('div[data-route]').dataset.route;
            let avanceTesis = document.querySelector('div[data-avance-tesis]').dataset.avanceTesis;
            console.log("ruta: ",route);
            console.log("html: ",contenido_original);
            console.log("comentarios",comentarios);
            console.log("avanceTesis",avanceTesis);
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log('se esta guardando:',comentarios);
            
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    contenido_original: contenido_original,
                    contenido: comentarios,
                    id_avance_tesis: avanceTesis
                },
                success: function(response){
                    if(response.success){
                        Swal.fire(
                            "Guardado",
                            `El contenido se almacenó en la DB`,
                            "success"
                        );
                        
                    }
                },
                error: function(xhr){
                    console.error('no salio pibe', xhr.responseText);
                }
            })
            
        }
async function renderComentarios() {
    const mensajes = document.querySelectorAll('.mensaje');

    for (const element of mensajes) {
        const userId = element.dataset.autor;
        const url = ROUTE.replace(':userId', userId);
        const comment = element.textContent;

        try {
            const res = await fetch(url);
            const data = await res.json();

            // Limpiar contenido anterior
            element.innerHTML = '';

            // Crear estructura con formato
            const h1 = document.createElement('h1');
            h1.textContent = `${data.usuario_nombre} ${data.usuario_apellidos}`;

            const rolesSpan = document.createElement('span');
            rolesSpan.classList.add('roles');
            rolesSpan.textContent = data.usuario_roles;

            const p = document.createElement('p');
            p.textContent = comment;
            p.classList.add('mensaje-text');

            const deleteBtn = document.createElement('button');
            deleteBtn.id = 'delete-comment';
            deleteBtn.type = 'button';
            deleteBtn.textContent = 'Eliminar comentario';
            deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-2');

            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.textContent = 'Editar comentario';
            editBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-2', 'ms-2', 'edit-comment');

            element.append(h1, rolesSpan, p, deleteBtn, editBtn);
            element.className = 'comentario-cargado';
        } catch (err) {
            console.error('Error al renderizar comentario:', err);
        }
    }
}

// function renderComentarios() {

//     document.querySelectorAll('.mensaje').forEach(element => {
//         let userId = element.dataset.autor; // id del usuario
//         let url = ROUTE.replace(':userId', userId); // reemplazamos el placeholder
//         let comment = element.textContent;
//         fetch(url)
//             .then(res => res.json())
//             .then(data => {
//                 console.log(data);
                
//                 // Limpiar contenido anterior
//                 element.innerHTML = '';

//                 // Crear elementos con la info del helper
//                 let h1 = document.createElement('h1');
//                 h1.textContent = `${data.usuario_nombre} ${data.usuario_apellidos}`;
//                 element.appendChild(h1);

//                 let rolesSpan = document.createElement('span');
//                 rolesSpan.classList.add('roles');
//                 rolesSpan.textContent = data.usuario_roles;
//                 element.appendChild(rolesSpan);

//                 let p = document.createElement('p');
//                 p.textContent = comment;
//                 p.classList.add('mensaje-text');                  
                
//                 let deleteBtn = document.createElement('button');
//                 deleteBtn.id = 'delete-comment';
//                 deleteBtn.type = 'button';
//                 deleteBtn.textContent = 'Eliminar comentario';
//                 deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-2');
                
//                 let editBtn = document.createElement('button');
//                 // editBtn.classList.add = 'edit-comment';
//                 editBtn.type = 'button';
//                 editBtn.textContent = 'Editar comentario';
//                 editBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-2', 'edit-comment', 'ms-2','edit-comment');
                
//                 element.appendChild(p);
//                 element.appendChild(deleteBtn);
//                 element.appendChild(editBtn);
//                 element.className ='comentario-cargado';
                

//             })
//             .catch(err => console.error(err));
//     });
// }


    document.addEventListener('click', function (e) {
    // Solo actúa si se hizo clic en el botón con id "delete-comment"
    if (e.target.matches('#delete-comment')) {
        const containerComment = e.target.closest('.comentario-cargado'); // Aquí sí usamos closest en el elemento
        const CLAVE = containerComment.dataset.clave;

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminará el comentario seleccionado permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Eliminar data-clave de elementos relacionados
                document.querySelectorAll(`.comment[data-clave="${CLAVE}"]`).forEach(element => {
                    element.removeAttribute('data-clave');
                });

                // Eliminar comentario del DOM
                containerComment.remove();

                // Ejecutar función de guardado
                submitContent();

                // Mensaje de éxito opcional
                Swal.fire(
                    '¡Hecho!',
                    'El comentario ha sido eliminado satisfactoriamente.',
                    'success'
                );
            }
        });
    }
});

// document.addEventListener("click", function(e) {
//     //  e.stopPropagation()
//     if(e.target.matches('.comentario-cargado')) {
//         const comentario = e.target.closest(".comentario-cargado");
//         if (!comentario) return;

//         const clave = comentario.dataset.clave;
//         const destinos = document.querySelectorAll(`span[data-clave="${clave}"]`);
    
//         destinos.forEach(destino => {
//             destino.scrollIntoView({ behavior: "smooth", block: "center" });

//             destino.classList.add("highlighted");
//             setTimeout(() => destino.classList.remove("highlighted"), 2000);
//         });
//     }
    
// });
document.addEventListener("click", function(e) {
    const comentario = e.target.closest(".comentario-cargado");

    // si no hay un contenedor válido, salimos
    if (!comentario) return;

    // evitamos que botones internos disparen esto
    if (
        e.target.matches(".edit-comment") ||
        e.target.matches("#delete-comment") ||
        e.target.closest(".edit-comment") ||
        e.target.closest("#delete-comment")
    ) {
        return; // no hacemos scroll ni resaltado
    }

    const clave = comentario.dataset.clave;
    const destinos = document.querySelectorAll(`span[data-clave="${clave}"]`);

    destinos.forEach(destino => {
        destino.scrollIntoView({ behavior: "smooth", block: "center" });
        destino.classList.add("highlighted");
        setTimeout(() => destino.classList.remove("highlighted"), 2000);
    });
});

document.querySelector('#show-comment')?.addEventListener('click', function() {
    const comments = document.querySelector('.section-comments');
    // const comments = document.querySelector('#comentarios');

    comments.style.display = comments.style.display === 'none' ? 'block' : 'none';
    this.textContent = comments.style.display === 'none' ? 'Mostrar comentarios' : 'Ocultar comentarios';
});

//funcion para editar comentario
document.addEventListener('click', function(e) {
    if(e.target.matches('.edit-comment')){
        // e.stopImmediatePropagation();

        const container = e.target.closest('.comentario-cargado');
        const previousComment = container.querySelector('.mensaje-text');
        
        
        let currentText = previousComment.textContent;
        console.log(currentText);
        
        const textArea = document.createElement('textarea');
        textArea.value = currentText;
        textArea.classList.add('form-control', 'mb-2');
        previousComment.replaceWith(textArea);

        // Cuando el usuario presione Enter, guardar
        textArea.addEventListener('keydown', function(ev) {
            if (ev.key === 'Enter' && !ev.shiftKey) {
                ev.preventDefault(); // evita salto de línea
                guardarCambio(textArea);
            }
        });
    }

});

function guardarCambio(textArea) {
    const nuevoTexto = textArea.value.trim();

    // Crear nuevo <p>
    const nuevoP = document.createElement('p');
    nuevoP.classList.add('mensaje-text');
    nuevoP.textContent = nuevoTexto || 'Comentario vacío';
    //
    textArea.replaceWith(nuevoP);
    submitContent();
}


// document.addEventListener('DOMContentLoaded', function () {
//     const comentarios = document.querySelectorAll('.comentario-cargado');
    
//     comentarios.forEach(comentario => {
//         comentario.addEventListener('click', function() {
//             const clave = this.dataset.clave;
//             const destinos = document.querySelectorAll(`span[data-clave="${clave}"]`);
//             destinos.forEach(destino => {
//                 destino.scrollIntoView({ behavior: 'smooth', block: 'center' });

//                 // agregar clase temporal de resaltado
//                 destino.classList.add('highlighted');
                
//                 setTimeout(() => {
//                     destino.classList.remove('highlighted');
//                 }, 2000);
//             });
            
//         });
//     });
// });


// Llamar la función

