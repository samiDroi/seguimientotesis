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
        <form action="{{ Route('comites.edit.button') }}" method="POST">
           @csrf
            @if ($tesis->count() > 1)
              @foreach ($tesis as $tesisItem)
                  <details>
                      <summary>{{ $tesisItem->nombre_tesis }}</summary>
                      <label for="titulo_tesis_{{ $tesisItem->id_tesis }}">Cambiar título de tesis</label>
                      <input type="text" name="tesis[{{ $tesisItem->id_tesis }}]" id="titulo_tesis_{{ $tesisItem->id_tesis }}">
                  
                      <label for="alumno">Reasignar alumno</label>
                        <select name="alumno[{{ $tesisItem->id_tesis }}]" id="alumno">
                            @foreach ($alumnos as $alumno)
                                <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                            @endforeach
                        </select>
                  </details>
              @endforeach   
            @else
              <label for="titulo_tesis">Cambiar titulo de tesis</label>
              <input type="text" name='tesis[{{ $tesis->id_tesis }}]' id="titulo_tesis">

              <label for="alumno">Reasignar alumno</label>
              <select name="alumno[{{ $tesis->id_tesis }}]" id="alumno">
                  @foreach ($alumnos as $alumno)
                      <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                  @endforeach
              </select>
            @endif
            
        
      </div>

      <!-- Pie del modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
</form>
    </div>
  </div>
</div>
