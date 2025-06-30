<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Editar datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo del modal -->
      <div class="modal-body">
        <form action="">
          <input type="hidden" name="id_tesis" id="id_tesis">
          
            <label for="titulo_tesis">Cambiar titulo de tesis</label>
            <input type="text" id="titulo_tesis">

            <label for="alumno">Reasignar alumno</label>
            <select name="" id="alumno">
                @foreach ($alumnos as $alumno)
                    <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                @endforeach
            </select>
        </form>
      </div>

      <!-- Pie del modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar cambios</button>
      </div>

    </div>
  </div>
</div>
