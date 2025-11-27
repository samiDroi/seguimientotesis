
// 🧩 Estilos base
import "prosemirror-view/style/prosemirror.css";
import "prosemirror-menu/style/menu.css";
import "prosemirror-example-setup/style/style.css";

import { EditorState, Plugin, PluginKey } from "prosemirror-state";
import { EditorView, Decoration, DecorationSet } from "prosemirror-view";
import { schema } from "prosemirror-schema-basic";
import { exampleSetup } from "prosemirror-example-setup";
import { Schema, DOMParser as ProseMirrorDOMParser } from "prosemirror-model";
import { addListNodes } from "prosemirror-schema-list";
import { DOMSerializer } from "prosemirror-model";

// funciones de mi api de comentarios
import { getComentarios, saveComentario, getInfoComentario } from './Comentario-Api.js';
import { loadComments } from './Comentario-Handler.js';
import { get } from "jquery";

const ROUTE_FETCH_AVANCE = document.querySelector("div[data-avance]")?.dataset.avance;
const ID_AVANCE_TESIS = document.querySelector("div[data-avance-tesis]")?.dataset.avanceTesis;
const ID_REQUERIMIENTO = document.querySelector("div[data-requerimiento]")?.dataset.requerimiento;
// Esquema extendido---------------------------------------------------------------------------
const mySchema = new Schema({
  nodes: addListNodes(schema.spec.nodes, "paragraph block*", "block"),
  marks: schema.spec.marks,
});

//Plugin para anotaciones
const anotacionesPlugin = new Plugin({
  state: {
    init: () => DecorationSet.empty,
    apply: (tr, old) => old.map(tr.mapping, tr.doc),
  },
  props: { decorations: s => anotacionesPlugin.getState(s) },
});

// Funciones auxiliares
const htmlToDoc = (html, schema) => {
  const div = document.createElement("div");
  div.innerHTML = html;
  return ProseMirrorDOMParser.fromSchema(schema).parse(div);
};

const crearDoc = (texto = " ") =>
  mySchema.nodeFromJSON({
    type: "doc",
    content: [{ type: "paragraph", content: [{ type: "text", text: texto }] }],
  });

const cargarContenido = async () => {
  if (!ROUTE_FETCH_AVANCE) return ;
  try {
    const res = await fetch(ROUTE_FETCH_AVANCE, {
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
    });
    if (!res.ok) throw new Error(`Error ${res.status}`);
    return await res.json();
  } catch (err) {
    console.error("Error cargando contenido:", err);
    return null;
  }
};

// Inicialización del editor
const inicializarEditor = async () => {
const contenedor = document.querySelector("#editor-avance");
contenedor.innerHTML = '';
const esComite = contenedor?.dataset.esComite === "true"; // true si pertenece al comité
// console.log(esComite);

  if (!contenedor) return console.error("No se encontró el contenedor del editor");

  // contenedor.innerHTML = "<div class='loading'>Cargando editor...</div>";
  const datos = await cargarContenido();
  let doc;

  try {
    doc = datos?.contenido
      ? htmlToDoc(datos.contenido, mySchema)
      : crearDoc();
  } catch {
    doc = crearDoc("Error cargando contenido. Editando nuevo documento.");
  }
 
  let state = EditorState.create({
    doc,
    plugins: [...exampleSetup({ schema: mySchema }), comentarioPlugin(async (comentario) => {
      

      // 2. Obtén el id_user
      const id_user = comentario.author; 

      // 3. Llama a tu nueva API
      if (!ID_REQUERIMIENTO) {
          alert("Error: No se encontró data-requerimiento");
          return;
      }
      //Aqui sucede todo lo de buscar info del autor del comentario
      console.log(`Buscando info para req: ${ID_REQUERIMIENTO}, user: ${id_user}`);
      const infoAutor = await getInfoComentario(ID_REQUERIMIENTO, id_user);
      console.log("Info del autor obtenida:", infoAutor);
      // 4. Muestra la información combinada
      if (infoAutor) {
        const nombre = `${infoAutor.usuario_nombre} ${infoAutor.usuario_apellidos}`;
        const roles = infoAutor.usuario_roles || 'Sin rol'; // Asegura que no sea null
        const fechaComentario = infoAutor.fecha_comentario || 'Fecha Desconocida';
        // Aquí puedes mostrarlo en un modal, un sidebar, etc.
        // Por ahora, usamos un alert mejorado:
        alert(`Fecha del Comentario: ${fechaComentario}\n
          Autor: ${nombre} (${roles})\n\nComentario: ${comentario.text}
          `);
      
      } else {
        // Si falla la API, muestra al menos el comentario
        console.error("No se pudo cargar la info del autor.");
        alert("Comentario: " + comentario.text);
      }
      // alert("comentario: "+ comentario.text);
    })],
     
  });
  //Cargar comentarios existentes
  const comentariosExistentes = await getComentarios();
  if (comentariosExistentes?.length){
      comentariosExistentes.forEach(c => {
      console.log('comentarios existentes:',c);
      // 1. ¡AQUÍ ESTÁ EL CAMBIO! Parsea el string
      const rango = JSON.parse(c.rango_seleccionado);

      const tr = state.tr.setMeta(comentarioPluginKey, {
        addComentario: {
          id: c.id_comentario,
          from: rango.from,
          to: rango.to,
          text: c.comentario,
          author: c.id_user
        }
      });
      state = state.apply(tr);
  });
  }
  

  //  window.view = new EditorView(contenedor, {
  //   state,
  //   dispatchTransaction: tr => {
  //     view.updateState(view.state.apply(tr));
  //     clearTimeout(window.autoSaveTimeout);
  //     window.autoSaveTimeout = setTimeout(() => {
  //       // guardarContenido(view.state.doc.toJSON());
  //     }, 2000);
  //   },
  // });
  //Guarda la vista del editor prosemirror en window
window.editorView = new EditorView(contenedor, {
  state,
  dispatchTransaction: tr => {
    const v = window.editorView;
    v.updateState(v.state.apply(tr));
    clearTimeout(window.autoSaveTimeout);
    window.autoSaveTimeout = setTimeout(() => {
      // guardarContenido(v.state.doc.toJSON());
    }, 2000);
  },
  editable: () => !esComite,  
});
//Cargar comentarios guardados desde la base de datos
// await loadComments(window.editorView, ID_AVANCE_TESIS);
const comentarios = await getComentarios();
const infoComentarios = await getInfoComentario(ID_REQUERIMIENTO, null);
renderSidebarComments(window.editorView, comentarios,infoComentarios);

if(esComite) document.querySelector('.ProseMirror-menubar')?.remove();
};
//  async function renderCommentData(comentario){
//     const id_user = comentario.id_user;
   

//   }
// Estilos de anotación
document.head.insertAdjacentHTML(
  "beforeend",
  `<style>
    .anotacion{background:rgba(255,255,0,.5);border-radius:3px;cursor:pointer;}
    .anotacion:hover{background:rgba(255,220,0,.8);}
  </style>`
);

// Inicializar el editor prosemirror al cargar el DOM
document.addEventListener("DOMContentLoaded", () => inicializarEditor());

//A PARTIR DE AQUI INICIA TODO LO RELACIONADO A COMENTARIOS
// Clave del plugin
export const comentarioPluginKey = new PluginKey("comentarios");
// export const comentarioPluginKey = new PluginKey("comentarios");
// Plugin de comentarios
export function comentarioPlugin(onSelectComentario) {
  return new Plugin({
    key: comentarioPluginKey,

    state: {
      init() {
        return { comentarios: [], decoraciones: DecorationSet.empty, transientHighlight: DecorationSet.empty,
};
      },
      apply(tr, old) {
        let { comentarios, decoraciones,transientHighlight } = old;

        // Si se añadieron comentarios nuevos
        const meta = tr.getMeta(comentarioPluginKey);
        if (meta && meta.addComentario) {
          let nuevo = meta.addComentario;
          comentarios = [...comentarios, nuevo];
          
          
          const deco = Decoration.inline(
            nuevo.from,
            nuevo.to,
            //AQUI ESTA EL PROBLEMA, EL DATA-ID SE REPITE
            { class: "comentario-resaltado", 'data-id': nuevo.id },
            // { inclusiveStart: true, inclusiveEnd: true }
          );
          decoraciones = decoraciones.add(tr.doc, [deco]);
        }
        // resaltado temporal al clickear en la sidebar
        if (meta && meta.highlightRange) {
            const { from, to } = meta.highlightRange;
            transientHighlight = DecorationSet.create(tr.doc, [
                Decoration.inline(from, to, { class: "comentario-target" })
            ]);
        }

        if (meta && meta.clearHighlight) {
            transientHighlight = DecorationSet.empty;
        }
        // Ajustar decoraciones si el documento cambió
        decoraciones = decoraciones.map(tr.mapping, tr.doc);
        transientHighlight = transientHighlight.map(tr.mapping, tr.doc);

        return { comentarios, decoraciones,transientHighlight, };
      },
      provide(state) {
        return null;
      }
    },

    props: {
      decorations(state) {
        const st = this.getState(state);
        return st.decoraciones.add(state.doc, st.transientHighlight.find());
        // return this.getState(state).decoraciones;
      },
      // Manejar clics en comentarios y los muestra
      handleClick(view, pos, event) {
        const target = event.target.closest(".comentario-resaltado");
        if (target) {
          const id = parseInt(target.dataset.id, 10);
          const stateData = this.getState(view.state);
          console.log('id del dataset:',id);
          
          // Buscar el comentario exacto por ID
          const comentario = stateData.comentarios.find(c => c.id === id);
          console.log(comentario);
          
          if (comentario && onSelectComentario) {
            onSelectComentario(comentario);
          }
          return true;
         
        }
        return false;
      }
    }
  });
}
//funcion la cual agrega un comentario al texto seleccionado
async function agregarComentario(view, textoComentario) {
  const { from, to } = view.state.selection;
  if (from === to) return alert("Selecciona un texto para comentar.");

 const idAutor = document.querySelector("div[data-autor]")?.dataset.autor || "Desconocido";
 const idAvance = ID_AVANCE_TESIS;
 //Guardar el comentario en la db via api
  const saved = await saveComentario({
    idAvance,
    idAutor,
    texto: textoComentario,
    range: { from, to },
  });

  if (!saved) {
    alert("Error guardando el comentario en el servidor");
    return;
  }

  // Reflejar visualmente el comentario guardado
  const tr = view.state.tr.setMeta(comentarioPluginKey, {
    addComentario: {
      id: saved.id_comentario,
      from,
      to,
      text: textoComentario,
      author: idAutor,
    },
  });
  view.dispatch(tr);

  alert("Comentario guardado correctamente");
  await inicializarEditor();

  // const tr = view.state.tr.setMeta(comentarioPluginKey, {
  //   addComentario: { id, from, to, text: textoComentario, author: "Docente" }
  // });
  // view.dispatch(tr);
}

const comentBtn = document.querySelector('#btn-comentar');
comentBtn?.addEventListener('click', () => {
  const texto = prompt("Ingrese el comentario:");
  if(texto) agregarComentario(window.editorView, texto);
});


document.querySelector("#form-avance")?.addEventListener("submit", (event) => {
  // event.preventDefault(); // Detiene el envío para depuración
    const serializer = DOMSerializer.fromSchema(mySchema);
    const doc = window.editorView.state.doc;
    const hidden = document.querySelector("#contenido-hidden");
    // Serializa el contenido del documento (doc.content)
    const fragment = serializer.serializeFragment(doc.content);
    //se mete dentro de un html temporal para obtener el innerHTML
    const tempContainer = document.createElement("div");
    tempContainer.appendChild(fragment);
    // document.querySelector('body').appendChild(tempContainer); // Solo para depuración
    hidden.value = tempContainer.innerHTML;
    console.log(hidden);
});
//funcion para cargar los comentarios en la barra lateral
function renderSidebarComments(view, comentarios, infoComentarios) {
    const cont = document.querySelector("#comentarios-list");
    if (!cont) return;

    cont.innerHTML = "";

    comentarios.forEach(c => {
        // Buscar la información del autor según el id_user del comentario
        const autor = infoComentarios.find(u => u.id_user == c.id_user);

        let nombreCompleto = "Autor desconocido";
        let roles = "Sin rol";
        let fecha = c.fecha_comentario || "Fecha desconocida";

        if (autor) {
            nombreCompleto = `${autor.usuario_nombre} ${autor.usuario_apellidos}`;
            roles = autor.usuario_roles || "Sin rol";
            fecha = autor.fecha_comentario ?? fecha;
        }

        // Crear la caja del comentario
        let box = document.createElement("div");
        box.className = "comentario-box";
        if (c.estado === "corregido") box.classList.add("corregido");

        box.dataset.id = c.id_comentario;

        // checkbox corregido
        let chk = document.createElement("input");
        chk.type = "checkbox";
        chk.className = "checkbox-correct";
        chk.checked = c.estado === "corregido";

        // texto del comentario
        let texto = document.createElement("div");
        texto.className = "texto";
        texto.textContent = c.comentario;

        // ➕ información extra del autor
        let info = document.createElement("div");
        info.className = "comentario-info";
        info.innerHTML = `
            <strong>${nombreCompleto}</strong><br>
            <small>${roles}</small><br>
            <small>${fecha}</small>
        `;

        box.appendChild(info);
        box.appendChild(texto);
        box.appendChild(chk);
        cont.appendChild(box);

        // click en la caja → scroll + highlight
        box.addEventListener("click", e => {
            if (e.target === chk) return;

            const rango = JSON.parse(c.rango_seleccionado);
            scrollToPos(view, rango.from);
            highlightRangeTemporary(view, rango.from, rango.to);
        });
    });
}

function scrollToPos(view, pos) {
    const coords = view.coordsAtPos(pos);
    window.scrollTo({
        top: coords.top + window.scrollY - 150,
        behavior: "smooth"
    });
}

function highlightRangeTemporary(view, from, to, ms = 3500) {
    const tr = view.state.tr.setMeta(comentarioPluginKey, {
        highlightRange: { from, to }
    });
    view.dispatch(tr);

    setTimeout(() => {
        const tr2 = view.state.tr.setMeta(comentarioPluginKey, {
            clearHighlight: true
        });
        view.dispatch(tr2);
    }, ms);
}

