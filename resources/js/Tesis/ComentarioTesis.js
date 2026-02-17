
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
import { getComentarios, saveComentario, getInfoComentario, actualizarEstadoComentario } from './Comentario-Api.js';
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
  plugins: [
    ...exampleSetup({ schema: mySchema }),
    comentarioPlugin(comentario => {
        // Aquí puedes poner lo que haces cuando clickean un comentario
        console.log("Comentario seleccionado:", comentario);
    })
  ]
});

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
    .comentario-autor{background:#fff7ed;border-color:#ffedd5;}
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
async function renderSidebarComments(view, comentarios) {
    
    const cont = document.querySelector("#comentarios-list");
    if (!cont) return;

    cont.innerHTML = "";

    for (const c of comentarios) {

        // Obtener info del autor de ESTE comentario
        const infoAutor = await getInfoComentario(ID_REQUERIMIENTO, c.id_user);

        let nombreCompleto = "Desconocido";
        let roles = "Sin rol";
        let fecha = "Fecha desconocida";

        if (infoAutor) {
            nombreCompleto = `${infoAutor.usuario_nombre} ${infoAutor.usuario_apellidos}`;
            roles = infoAutor.usuario_roles || "Sin rol";
            fecha = infoAutor.fecha_comentario || "Fecha desconocida";
        }

        // Detectar usuario actual y rol (se asume que si existe el botón de comentar, es comité)
        const currentUserId = document.querySelector("div[data-autor]")?.dataset.autor;
        const isComiteView = !!document.querySelector('#btn-comentar');
        const isAlumnoView = !isComiteView;

        // Crear elementos HTML
        let box = document.createElement("div");
        box.className = "comentario-box";
        // Usar el campo `comentario_estado` como fuente de verdad
        if (c.comentario_estado === "CORREGIDO") box.classList.add("corregido");

        box.dataset.id = c.id_comentario;

        let chk = document.createElement("input");
        let labelChk = document.createElement("label");
        
        chk.type = "checkbox";
        chk.className = "checkbox-correct";
        chk.id = `chk-corregido-${c.id_comentario}`;

        // Detectar si el usuario es el autor del comentario
        const isAuthor = String(currentUserId) === String(c.id_user);
        
        if (isAuthor) {
            box.classList.add('comentario-autor');
        }

        // Lógica de renderizado del checkbox según la vista y estado
        let mostrarCheckbox = false;
        let labelCheckboxText = "Corregido";

        if (isAlumnoView) {
            // VISTA ALUMNO
            mostrarCheckbox = true;
            labelCheckboxText = "Marcar como corregido";
            
            if (c.comentario_estado === "EN REVISION") {
                chk.checked = true; // checkbox marcado si está EN REVISION
                chk.disabled = true; // inhabilitar checkbox en EN REVISION
            } else if (c.comentario_estado === "CORREGIDO") {
                chk.checked = true; // checkbox marcado si ya está CORREGIDO
                chk.disabled = true; // inhabilitar checkbox si ya está CORREGIDO
                chk.style.display = "none"; // ocultar checkbox si ya está CORREGIDO
                labelCheckboxText = "Corregido"; // cambiar texto a "Corregido" si ya está corregido
            } else {
                chk.checked = false; // checkbox desmarcado si está PENDIENTE
            }
            
            // // Solo hace interactivo si es el autor
            // if (!isAuthor) {
            //     chk.disabled = true;
            // }
        } else {
            // VISTA COMITÉ
            if (isAuthor && c.comentario_estado === "EN REVISION") {
                mostrarCheckbox = true;
                labelCheckboxText = "Aceptar corrección";
                chk.checked = false;
            } else {
                mostrarCheckbox = false;
            }
        }

        labelChk.textContent = labelCheckboxText;
        labelChk.htmlFor = chk.id;

        let texto = document.createElement("div");
        texto.className = "texto";
        texto.textContent = c.comentario;

        let info = document.createElement("div");
        info.className = "comentario-info";
        info.innerHTML = `
            <strong>${nombreCompleto}</strong><br>
            <small>${roles}</small><br>
            <small>${fecha}</small>
        `;

        box.appendChild(info);
        box.appendChild(texto);
        
        // Solo agregar checkbox si debe mostrarse
        if (mostrarCheckbox) {
            box.appendChild(chk);
            box.appendChild(labelChk);
        }
        
        cont.appendChild(box);
        
        if (c.comentario_estado !== "PENDIENTE") {
          box.classList.add("comentario-no-pendiente");
          box.style.cursor = "default";
          
        }else{
          box.style.cursor = "pointer";
        }
        

        
        // CLICK → scroll + highlight
        box.addEventListener("click", async e => {
            if (e.target === chk) return;

            // Si no es autor y el comentario no está en PENDIENTE, no permitir click
            if (!isAuthor && c.comentario_estado !== "PENDIENTE") return;

            const rango = JSON.parse(c.rango_seleccionado);
            if(window.editorView == null) return;

            // Primero hacer highlight
            highlightRangeTemporary(view, rango.from, rango.to);
            
            // Luego hacer scroll con pequeño delay
            setTimeout(() => {
                scrollToPos(window.editorView, rango.from);
            }, 100);

            // Si el autor hace click sobre un comentario en EN REVISION o PENDIENTE (solo en vista alumno),
            // marcarlo como CORREGIDO y persistir en la BD
            if (isAlumnoView && isAuthor && c.comentario_estado !== 'CORREGIDO') {
                const nuevoEstado = 'CORREGIDO';
                const resp = await actualizarEstadoComentario(c.id_comentario, nuevoEstado, null);
                if (resp && resp.success) {
                    c.comentario_estado = nuevoEstado;
                    if (mostrarCheckbox) chk.checked = true;
                    box.classList.add('corregido');
                }
            }
        });

        // Si el checkbox está presente, agregar event listener
        if (mostrarCheckbox) {
            chk.addEventListener('change', async (ev) => {
                if (!isAuthor) return;
                
                let nuevoEstado;
                if (isAlumnoView) {
                    // Vista alumno: cambiar entre CORREGIDO y EN REVISION
                    nuevoEstado = ev.target.checked ? 'CORREGIDO' : 'EN REVISION';
                } else {
                    // Vista comité: "Aceptar corrección" significa cambiar de EN REVISION a CORREGIDO
                    nuevoEstado = ev.target.checked ? 'CORREGIDO' : 'EN REVISION';
                }
                
                const resp = await actualizarEstadoComentario(c.id_comentario, nuevoEstado, ev.target);
                if (resp && resp.success) {
                    c.comentario_estado = nuevoEstado;
                    // actualizar clase visual
                    if (nuevoEstado === 'CORREGIDO') {
                        box.classList.add('corregido');
                    } else {
                        box.classList.remove('corregido');
                    }
                } else {
                    // revertir checkbox si hubo error
                    ev.target.checked = !ev.target.checked;
                    alert('No se pudo actualizar el estado del comentario.');
                }
            });
        }
    }
}



function scrollToPos(view, pos) {
    const offset = 150; // Offset para dejar espacio arriba
    
    try {
        // Obtener el nodo DOM en esa posición
        const node = view.domAtPos(pos);
        
        if (node && node.node) {
            // Si es un nodo de texto, obtener el padre
            let element = node.node.nodeType === 3 ? node.node.parentElement : node.node;
            
            console.log("Scrolling to node:", element);
            
            // Usar scrollIntoView para un navegador más confiable
            element.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
            
            return;
        }
        
        // Fallback: usar coordenadas
        const targetCoords = view.coordsAtPos(pos);
        const editorElement = view.dom;
        const editorRect = editorElement.getBoundingClientRect();
        const targetTop = editorRect.top + window.scrollY + targetCoords.top - offset;
        
        console.log("Fallback scroll to:", targetTop);
        
        window.scrollTo({
            top: targetTop,
            behavior: "smooth"
        });
    } catch (err) {
        console.error("Error en scrollToPos:", err);
    }
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

