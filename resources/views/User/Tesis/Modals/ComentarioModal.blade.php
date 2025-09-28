<!-- Modal -->
<div class="modal fade" id="comentModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      
      <!-- Encabezado -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Título del modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <h4>Texto que se va a comentar</h4>
        <p class="selected-text">
        </p>
        <h5 class="mt-3">Elige un color para resaltar el texto seleccionado</h5>
        <select id="color_resaltado" name="color" class="form-select w-50">
          <option value="" selected>Selecciona tu color</option>
          <option value="yellow">Amarillo</option>
          <option value="lightgreen">Verde</option>
          <option value="lightblue">Azul</option>
          <option value="pink">Rosa</option>
          <option value="orange">Naranja</option>
        </select>

        <h3>Comentario</h3>
        <form action="{{ Route("comentario.create") }}" method="post">
           
            @csrf
            <input type="hidden" name="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
            <input type="hidden" name="id_avance_tesis" value="{{ $avanceTesis?->id_avance_tesis }}">
            {{-- @dd( $avanceTesis?->id_avance_tesis ); --}}
            <textarea id="comentario_avance" name="contenido"></textarea>
            {{-- <button class="btn btn-primary mt-3" type="submit">Subir comentario</button> --}}
        
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Subir Comentario</button>
      </div>
      </form>
    </div>
  </div>
</div>