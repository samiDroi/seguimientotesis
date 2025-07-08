<!-- Modal -->
<div class="modal fade" id="edit-modal-{{ $comite->id_comite }}" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
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
            @if (count($comite->tesis) === 1)
              @foreach ($comite->tesis as $tesis)
                <h2>{{ $tesis->nombre_tesis }}</h2>
                <label for="titulo_tesis">Cambiar titulo de tesis</label>
              <input type="text" name='tesis[{{ $comite->tesis->first()->id_tesis }}]' id="titulo_tesis">

              <label for="alumno">Reasignar alumno</label>
              <select name="alumno[{{ $comite->tesis->first()->id_tesis }}]" id="alumno">
                  <option value="" selected> Selecciona un alumno </option>  
  
                @foreach ($alumnos as $alumno)
                      <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                  @endforeach
              </select>    
              @endforeach
              
            @else
              @foreach ($comite->tesis as $tesis)
                     <details>
                      <summary>{{ $tesis->nombre_tesis }}</summary>
                      <label for="titulo_tesis_{{ $tesis->id_tesis }}">Cambiar título de tesis</label>
                      <input type="text" name="tesis[{{ $tesis->id_tesis }}]" id="titulo_tesis_{{ $tesis->id_tesis }}">
                  
                      <label for="alumno">Reasignar alumno</label>
                        <select name="alumno[{{ $tesis->id_tesis }}]" id="alumno">
                          <option value="" selected> Selecciona un alumno </option>  
                          @foreach ($alumnos as $alumno)
                                <option value="{{ $alumno->id_user }}">{{ $alumno->nombre.' '.$alumno->apellidos }}</option>
                            @endforeach
                        </select>
                  </details>
                
                 
              @endforeach   
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