<div class="modal fade" id="clone-modal-{{ $comite->id_comite }}" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Título del modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo del modal -->
      <div class="modal-body">
        <form action="{{ route('comites.clone',$comite->id_comite) }}" method="POST">
            @csrf
            <label for="alumno">Asignar alumno</label>
            <select name="alumnos[]" class="form-control select2-modal" data-select2-modal="true" 
          data-parent-modal="clone-modal-{{ $comite->id_comite }}">
                @foreach ($alumnos as $alumno)
                      <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                @endforeach
            </select>
           
            <label for="tesis">Asignar tesis</label>
            <select name="tesis" class="form-control select2-modal" data-select2-modal="true" 
              data-parent-modal="clone-modal-{{ $comite->id_comite }}" >
                
                @foreach ($tesis as $tesisItem)
                    <option value="{{ $tesisItem->id_tesis }}">{{ $tesisItem->nombre_tesis }}</option>
                @endforeach
            </select>

        
      </div>

      <!-- Pie del modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
      </form>
    </div>
  </div>
</div>