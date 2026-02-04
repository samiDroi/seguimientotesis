// resources/js/editor/comentarios-api.js

/**
 * Obtiene los comentarios desde Laravel por id de avance
 */
const ROUTE_FETCH_COMENTARIOS = document.querySelector("[data-get-comment]")?.getAttribute("data-get-comment");

export async function getComentarios() {
  try {
    const res = await fetch(ROUTE_FETCH_COMENTARIOS, {
      headers: {
        Accept: "application/json",
      },
    });
    if (!res.ok) throw new Error(`Error ${res.status}`);
    return await res.json();
  } catch (err) {
  
    return [];
  }
}

/**
 * Guarda un nuevo comentario en Laravel
 */
const ROUTE_SAVE_COMENTARIO = document.querySelector("[data-route]")?.getAttribute("data-route");

export async function saveComentario({
  idAvance,
  idAutor,
  texto,
  range,
}) {
  try {
    const res = await fetch(ROUTE_SAVE_COMENTARIO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document
          .querySelector('meta[name="csrf-token"]')
          ?.getAttribute("content"),
      },
      body: JSON.stringify({
        id_avance_tesis: idAvance,
        id_autor: idAutor,
        comentario: texto,
        rango_seleccionado: range,
      }),
    });

    if (!res.ok) throw new Error(`Error ${res.status}`);
    return await res.json();
  } catch (err) {
    console.log('Error guardando comentario:', err);  
   
    return null;
  }
}

export async function getInfoComentario(id_requerimiento,id_user){
   try {
    const res = await fetch(`/requerimiento/req/data/${id_requerimiento}/${id_user}`, {
      headers: { Accept: "application/json" },
    });
    if (!res.ok) throw new Error(`Error ${res.status}`);
    return await res.json();
  } catch (err) {
    console.error("Error al obtener comentarios:", err);
    return [];
  }
}

export async function actualizarEstadoComentario(idComentario, estado) {
    try {
        const res = await fetch(`/requerimiento/tesis/comentario/actualizar-estado/${idComentario}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ estado })
        });

        if (!res.ok) throw new Error("Error " + res.status);

        return await res.json();

    } catch (error) {
        console.error("Error al actualizar comentario:", error);
    }
}

