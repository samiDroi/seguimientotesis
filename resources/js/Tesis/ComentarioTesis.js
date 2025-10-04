// const { get } = require("jquery");
const ROUTE = document.querySelector('#comentarios-route').dataset.routec;
renderComentarios();
buscar(document.querySelectorAll('main *'))
    // let contentMain = document.querySelector('div[data-content-main]').dataset.contentMain
    // console.log(contentMain);
    var existMain = document.querySelector('div[data-content-main]').dataset.contentMain == "";
    if(existMain){
        
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
document.querySelector("#comentarios").addEventListener("click", function (e) {
    // Nos aseguramos de que se hizo clic en un 'mensaje'
    if (e.target.classList.contains("mensaje")) {
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
        document.querySelector('body').addEventListener('mouseover', function(e){
            document.querySelectorAll(`.comment`).forEach(element => {                    
                    element.style.background = "none"
                })
            if(e.target.classList.contains('mensaje')){
                document.querySelectorAll(`.comment[data-clave="${e.target.dataset.clave}"]`).forEach(element => {                    
                    element.style.background = "yellow"
                })
            }
            document.querySelectorAll('.comment')
        })
                

        function buscar(nodos){
            for (const el of nodos) {
                if(el.childNodes.length > 1){
                    buscar(el.childNodes) 
                }else{                    
                    if(!isOnlyWhitespace(el.textContent) && !el.textContent.includes("<fin>") && existMain){
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

        document.querySelector("#comentar").addEventListener("click", async function () {
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
                
                if (selectedContent.querySelector('.comment')) {
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
            submitContent();
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

function renderComentarios() {

    document.querySelectorAll('.mensaje').forEach(element => {
        let userId = element.dataset.autor; // id del usuario
        let url = ROUTE.replace(':userId', userId); // reemplazamos el placeholder
        let comment = element.textContent;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                
                // Limpiar contenido anterior
                element.innerHTML = '';

                // Crear elementos con la info del helper
                let h1 = document.createElement('h1');
                h1.textContent = `${data.usuario_nombre} ${data.usuario_apellidos}`;
                element.appendChild(h1);

                let rolesSpan = document.createElement('span');
                rolesSpan.classList.add('roles');
                rolesSpan.textContent = data.usuario_roles;
                element.appendChild(rolesSpan);

                let p = document.createElement('p');
                p.textContent = comment;
                p.classList.add('mensaje');                  
                
                element.appendChild(p);
                element.className ='comentario-cargado';
                

            })
            .catch(err => console.error(err));
    });
}

// Llamar la función

