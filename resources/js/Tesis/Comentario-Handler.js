// resources/js/editor/comentarios-handler.js

import { getComentarios } from "./Comentario-Api.js";
import { comentarioPluginKey } from "./ComentarioTesis.js"; // exporta esto desde tu editor

/**
 * Carga los comentarios guardados desde la base de datos
 * y los aplica visualmente al editor.
 */
export async function loadComments(view, idAvance) {
  const comentarios = await getComentarios(idAvance);
  if (!comentarios?.length) return;

  comentarios.forEach((com) => {
    // 'rango_seleccionado' puede venir como JSON string o como objeto
    const rango =
      typeof com.rango_seleccionado === "string"
        ? JSON.parse(com.rango_seleccionado)
        : com.rango_seleccionado;

    const tr = view.state.tr.setMeta(comentarioPluginKey, {
      addComentario: {
        id: com.id,
        from: rango.from,
        to: rango.to,
        text: com.comentario,
        author: com.id_autor,
      },
    });

    view.dispatch(tr);
  });
  console.log(comentarios);
  
  console.log(`✅ ${comentarios.length} comentarios cargados en el editor`);
}
