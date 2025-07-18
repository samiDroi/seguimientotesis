
                <style>
                  .select2-container--open{
                    z-index: 99999 !important;
                  }
                </style>
         <!-- Modal -->
     <div class="modal fade"  id="tesisModal" tabindex="-1" aria-labelledby="tesisModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg"> <!-- Esto hace que el modal sea más grande -->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="tesisModalLabel">Ingrese el Título de la Tesis</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Formulario dentro de la modal -->
              <form action="{{ route("tesis.create") }}" method="POST">
                @csrf
                <div class="mb-3">
                  <!-- Poner el texto arriba con una clase adicional -->
                  {{-- {{ isset($tesis) ? $tesis->nombre_tesis : '' }} --}}
                  <label for="nombre_tesis" class="form-label fs-4">Ingrese el título que llevará la tesis</label>
                  <input type="text" name="nombre_tesis" id="nombre_tesis" class="form-control form-control-lg" required autocomplete="off" placeholder="Título de la tesis">
                  
                  <label for="alumno">Asignacion de tesis a alumno</label>
                  <select class="form-select select2" name="alumno" id="alumno">
                      @foreach ($alumnos as $alumno)
                      <option value="{{ $alumno?->id_user }}">{{ $alumno?->nombre . " " . $alumno->apellidos}}</option>    
                      @endforeach
                    
                    
                  </select>
                </div>
                {{-- <label for="comite">Continuar con la creacion del comite</label>
                <input type="checkbox" name="comite" id="comite"> --}}

                <div id="programa_comite" class="mt-2">
                   <label for="programa">Asignacion de Programa academico</label>
                  <select class="form-select select2" name="programa" id="programa">
                    @foreach (Auth::user()->programas as $programa)
                      <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa}}</option>    
                    @endforeach
                  </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
              </form>
            </div>
           
          </div>
        </div>
      </div>
      
      
      {{-- <script>

  const checkbox = document.getElementById('comite');
  const divPrograma = document.getElementById('programa_comite');

  // Detectar cambios en el checkbox
  checkbox.addEventListener('change', function() {
    if (this.checked) {
      divPrograma.style.display = 'block';
    } else {
      divPrograma.style.display = 'none';
    }
  });
     </script> --}}
 
   