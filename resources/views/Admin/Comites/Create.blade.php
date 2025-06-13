
<!-- Modal -->
<div class="modal fade" id="crearComiteModal" tabindex="-1" aria-labelledby="crearComiteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('comites.create') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="crearComiteModalLabel">Crear Comité</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="{{ $comite?->id_comite }}">
           <label class="form-label fw-semibold" for="nombre_comite">Tesis</label>
          
          <select name="tesis" id="tesis" class="form-select">
            @foreach ($tesis as $tesisItem)
            <option value="{{ $tesisItem->id_tesis }}">{{ $tesisItem->nombre_tesis }}</option>
             @endforeach
          </select>
           
         
         
          
          <label class="form-label fw-semibold mt-3" for="programas">Programa académico:</label>
          <select class="form-select" name="ProgramaAcademico[]" id="programas">
              @foreach ($programas as $programa)
                  <option value="{{ $programa->id_programa }}">{{ $programa->nombre_programa }}</option>
              @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Continuar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>



