const ROUTE_FETCH = document.querySelector('div[data-fetch]')?.dataset.fetch;
if (ROUTE_FETCH) {
    fetch(ROUTE_FETCH)
    .then(res => res.json())
    .then(data => {
        // console.log("RESPUESTA DEL SERVIDOR:", data);
        // let comentariosPorClave={};
        // HTML comentarios
        const mainDoc = new DOMParser().parseFromString(data.main, 'text/html');
        getSelectedText(mainDoc);
        
        const comentariosDoc = new DOMParser().parseFromString(data.comentarios, 'text/html');
        // getComentariosByClave(comentariosDoc);
        
        const mainPorClave = getSelectedText(mainDoc);
        
        // const comentariosPorClave = getComentariosByClave(comentariosDoc);

        renderModal(mainPorClave, comentariosDoc);
        
    })
    .catch(err => console.error('Error fetching HTML:', err));
// document.querySelectorAll('.user-auditor').forEach(el => {
//     el.addEventListener('click', function() {
//         const userId = el.dataset.doc;

//     });
// });
function renderModal(mainPorClave, comentariosDoc) {
    document.querySelectorAll('.user-auditor').forEach(userEl => {
        userEl.addEventListener('click', () => {
            const auditorId = userEl.dataset.doc;
            const commentSection = document.querySelector('.comment-section');
            commentSection.innerHTML = ''; // limpiar el modal

            // Se obtienen los comentarios agrupados por clave y filtrados por autor
            let comentariosPorClave = getComentariosByClave(comentariosDoc, auditorId);

            console.log('Comentarios filtrados para el auditor seleccionado:', comentariosPorClave);
            console.log('Main por clave:', mainPorClave);
                if(Object.keys(comentariosPorClave).length === 0){
                    commentSection.innerHTML = '<p class="text-muted">Este miembro del comite no ha realizado comentarios por el momento</p>';
                }else{
                     for (const clave in mainPorClave) {
                    const contenidoMain = mainPorClave[clave];
                    const comentarios = comentariosPorClave[clave];

                    // Si hay comentarios para esa clave
                    if (comentarios && comentarios.length > 0) {
                        comentarios.forEach(comentario => {
                            // contenedor principal
                            const container = document.createElement('div');
                            container.classList.add('mb-4', 'pb-2', 'border-bottom');

                            // Texto comentado (resaltado)
                            const textSelected = document.createElement('p');
                            textSelected.style.backgroundColor = '#fff3cd'; // amarillo claro (Bootstrap warning)
                            textSelected.style.padding = '8px';
                            textSelected.style.borderRadius = '6px';
                            textSelected.style.marginBottom = '5px';
                            textSelected.textContent = contenidoMain;
                            // textSelected.innerHTML = `<strong>Texto comentado:</strong> ${contenidoMain}`;

                            // Comentario debajo
                            const commentSelected = document.createElement('p');
                            commentSelected.style.marginLeft = '10px';
                            commentSelected.style.marginTop = '2px';
                            commentSelected.textContent = comentario.contenido;
                            // commentSelected.innerHTML = `<strong>Comentario:</strong> ${comentario.contenido}`;

                            // Agregar ambos al contenedor
                            container.appendChild(textSelected);
                            container.appendChild(commentSelected);

                            // Agregar al modal
                            commentSection.appendChild(container);
                        });
                    }
                } 
                }
           
               
           
   
        });
    });
}

// function renderModal(mainPorClave, comentariosDoc) {
//     document.querySelectorAll('.user-auditor').forEach(userEl => {
//         userEl.addEventListener('click', () => {
//             const auditorId = userEl.dataset.doc;
//             const commentSection = document.querySelector('.comment-section');
//             commentSection.innerHTML = ''; // limpiar el modal

//             // Se obtienen los comentarios agrupados por clave y filtrados por autor
//             let comentariosPorClave = getComentariosByClave(comentariosDoc, auditorId);

//             console.log('Comentarios filtrados para el auditor seleccionado:', comentariosPorClave);
//             console.log('Main por clave:', mainPorClave);

//             // Recorremos las claves del main y verificamos si existen comentarios
//             for (const clave in mainPorClave) {
//                 const contenidoMain = mainPorClave[clave];
//                 const comentarios = comentariosPorClave[clave];

//                 // Solo mostrar si hay comentarios
//                 if (comentarios && comentarios.length > 0) {
//                     comentarios.forEach(comentario => {
//                         // contenedor de cada bloque de comentario
//                         const container = document.createElement('div');
//                         container.classList.add('d-flex', 'justify-content-between', 'mb-3', 'border-bottom', 'pb-2');

//                         // columna izquierda: texto comentado
//                         const textSelected = document.createElement('div');
//                         textSelected.classList.add('w-50', 'pe-3');
//                         textSelected.innerHTML = `
//                             <strong>Contenido comentado:</strong>
//                             <p>${contenidoMain}</p>
//                         `;

//                         // columna derecha: comentario del auditor
//                         const commentSelected = document.createElement('div');
//                         commentSelected.classList.add('w-50', 'ps-3');
//                         commentSelected.innerHTML = `
//                             <strong>Comentario:</strong>
//                             <p>${comentario.contenido}</p>
//                         `;

//                         // unir ambos
//                         container.appendChild(textSelected);
//                         container.appendChild(commentSelected);

//                         // agregar al modal
//                         commentSection.appendChild(container);
//                     });
//                 }
//             }
//         });
//     });
// }

// function renderModal(mainPorClave, comentariosDoc){
    
//      document.querySelectorAll('.user-auditor').forEach(userEl => {
//         userEl.addEventListener('click', () => {
//             const auditorId = userEl.dataset.doc;
//             const commentSection = document.querySelector('.comment-section');
//             commentSection.innerHTML = '';
//             //se obtiene los comentarios junto con el data-clave y todo filtrado por el autor
//             let comentariosPorClave = getComentariosByClave(comentariosDoc, auditorId);
//             console.log('Comentarios filtrados para el auditor seleccionado:', comentariosPorClave);
//             console.log('Main por clave:', mainPorClave);

//             const commentContainer = document.createElement('div');
//             commentContainer.classList.add('comment-container');

//             const textSelected = document.createElement('p');
//             const commentSelected = document.createElement('p');
//             for(const clave in mainPorClave){
//                 if(comentariosPorClave[clave]){
//                     textSelected.textContent
//                 }
//             }
//         });
//     });

// }

function getSelectedText(mainDoc) {
     // Objeto para agrupar por data-clave
    const contenidoPorClave = {};

    mainDoc.querySelectorAll('[data-clave]').forEach(el => {
        const clave = el.dataset.clave;
        const contenido = el.textContent; 

        // Si la clave no existe, inicializamos
        if (!contenidoPorClave[clave]) {
            contenidoPorClave[clave] = '';
        }

        // Concatenamos el contenido
        contenidoPorClave[clave] += contenido; // '\n' o '<br>' según necesites
    });
    console.log('Contenido agrupado por data-clave:', contenidoPorClave);
    return contenidoPorClave;
}

//funcion que filtrara segun los autores que se esten pasando
function getComentariosByClave(comentariosDoc, idAuditor) {
    const comentariosPorClave = {};

    comentariosDoc.querySelectorAll('[data-clave]').forEach(el => {
        const clave = el.dataset.clave;
        const autor = el.dataset.autor;

        let contenido = '';
        el.querySelectorAll('.mensaje-text').forEach(mensajeEl => {
            contenido += mensajeEl.innerHTML + '\n';
        });
        console.log(comentariosDoc);
        if (!comentariosPorClave[clave]) {
            comentariosPorClave[clave] = [];
        }

        comentariosPorClave[clave].push({
            autor,
            contenido: contenido.trim()
        });
    });

    console.log('Comentarios agrupados por data-clave (sin filtrar):', comentariosPorClave);

    // 🔹 Filtrar solo los comentarios del auditor indicado
    const filtrados = {};
    for (const clave in comentariosPorClave) {
        const comentarios = comentariosPorClave[clave].filter(c => c.autor === idAuditor);
        if (comentarios.length > 0) {
            filtrados[clave] = comentarios;
        }
    }

    console.log('Comentarios filtrados por auditor:', filtrados);
    return filtrados;
}

// function getComentariosByClave(comentariosDoc){
//     comentariosDoc.querySelectorAll('[data-autor]').forEach(el => {
//             let autor = el.dataset.autor;
//             let clave = el.dataset.clave;
//             let contenido = '';
//             el.querySelectorAll('.mensaje-text').forEach(mensajeEl => {
//                 contenido += mensajeEl.innerHTML + '\n'; // o '<br>' si quieres HTML
//                 console.log('MENSAJE - Contenido:', mensajeEl.innerHTML,
//                             'clave:', clave
//                 );
//             });
            
//         });
// }

}
