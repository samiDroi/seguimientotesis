import { actualizarEstadoComentario } from "./Comentario-Api.js";
const lista = document.getElementById("comentarios-list");

lista?.addEventListener("change", e => {
    if (!e.target.classList.contains("checkbox-correct")) return;

    const idComentario = e.target.closest('[data-id]')?.dataset.id;
    const estado = e.target.checked ? "EN REVISION" : "PENDIENTE";

    console.log("clickeado", idComentario, estado);

    actualizarEstadoComentario(idComentario, estado);
});

// document.querySelectorAll(".checkbox-correct").forEach(chk => {
//     chk.addEventListener("change", e => {
//         const idComentario = e.target.parentElement.dataset.id;
//         const estado = e.target.checked ? "EN REVISION" : "PENDIENTE";
//         console.log('clickeado');
        
//         actualizarEstadoComentario(idComentario, estado);
//     });
// });

