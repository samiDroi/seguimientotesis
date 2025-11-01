// Estilos base
import "prosemirror-view/style/prosemirror.css";
import "prosemirror-menu/style/menu.css";
import "prosemirror-example-setup/style/style.css";

import { EditorState, Plugin } from "prosemirror-state"
import { EditorView, Decoration, DecorationSet } from "prosemirror-view"
import { schema } from "prosemirror-schema-basic"
import { exampleSetup } from "prosemirror-example-setup"

// 📌 Reemplaza con el ID real de la tesis si lo pasas desde Blade
// const tesisId = document.querySelector("div[data-avance-tesis]")?.getAttribute("data-avance-tesis");
const avanceId = document.querySelector("div[data-avance-tesis]")?.dataset.avanceTesis;

// Inicializamos el conjunto vacío de anotaciones
let decos = DecorationSet.empty

// Plugin que mantiene las anotaciones activas
const anotacionesPlugin = new Plugin({
  state: {
    init() { return decos },
    apply(tr, old) { return old.map(tr.mapping, tr.doc) }
  },
  props: {
    decorations(state) { return this.getState(state) }
  }
})

// Crear editor
const editorContainer = document.querySelector("#editor-avance");

// 🚀 Cargar contenido desde Laravel
fetch(`/cap/datajson/avance/show/${avanceId}`)
  .then(res => res.json())
  .then(data => {
    // Parseamos el HTML a un nodo de ProseMirror
    const doc = ProseMirrorDOMParser.fromSchema(schema).parse(
        new DOMParser().parseFromString(data.contenido, "text/html")
    );

    // Crear estado con el contenido cargado
    const state = EditorState.create({
        doc,
        plugins: [...exampleSetup({ schema }), anotacionesPlugin]
    });

    // Inicializar editor
    const view = new EditorView(editorContainer, { state });

    // ⚡ Si ya tienes anotaciones, aplicarlas aquí también
    fetch(`/tesis/${avanceId}/anotaciones`)
      .then(res => res.json())
      .then(anotaciones => {
        const decoraciones = anotaciones.map(c =>
          Decoration.inline(c.from, c.to, { class: "anotacion", title: c.comentario })
        );
        decos = DecorationSet.create(view.state.doc, decoraciones);
        view.updateState(view.state.reconfigure({
          plugins: [...exampleSetup({ schema }), anotacionesPlugin]
        }));
      });
  });
// // ⚙️ Cargar anotaciones existentes desde Laravel
// fetch(`/tesis/${tesisId}/anotaciones`)
//   .then(res => res.json())
//   .then(data => {
//     const decoraciones = data.map(c =>
//       Decoration.inline(c.from, c.to, {
//         class: "anotacion",
//         title: c.comentario
//       })
//     )
//     decos = DecorationSet.create(view.state.doc, decoraciones)
//     view.updateState(view.state.reconfigure({
//       plugins: [...exampleSetup({ schema }), anotacionesPlugin]
//     }))
//   })

// // ➕ Botón para añadir nueva anotación
// document.addEventListener("DOMContentLoaded", () => {
//   const btn = document.querySelector("#btn-comentar")
//   if (!btn) return

//   btn.addEventListener("click", () => {
//     const { from, to } = view.state.selection
//     if (from === to) {
//       alert("Selecciona un texto para comentar.")
//       return
//     }

//     const comentario = prompt("Escribe tu comentario:")
//     if (!comentario) return

//     fetch("/anotaciones", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
//       },
//       body: JSON.stringify({
//         tesis_id: tesisId,
//         from, to,
//         comentario
//       })
//     })
//       .then(res => res.json())
//       .then(a => {
//         const deco = Decoration.inline(a.from, a.to, { class: "anotacion", title: a.comentario })
//         decos = decos.add(view.state.doc, [deco])
//         view.dispatch(view.state.tr)
//       })
//   })
// })

// 💅 Estilos (puedes moverlo a tu CSS global)
const style = document.createElement("style")
style.innerHTML = `
  .anotacion {
    background-color: rgba(255, 255, 0, 0.5);
    border-radius: 3px;
    cursor: pointer;
  }
  .anotacion:hover {
    background-color: rgba(255, 220, 0, 0.8);
  }
`
document.head.appendChild(style)
