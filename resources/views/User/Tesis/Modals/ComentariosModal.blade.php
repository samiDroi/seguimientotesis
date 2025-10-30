<div class="modal fade" id="comentarios-mostrar" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Comentarios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body grid-comment">
        <div>
          @foreach (getUsersComiteAudita() as $miembro)
    
            <p class="user-auditor" data-doc = "{{ $miembro->id_user }}" tabindex="0">{{ $miembro->nombre }} {{ $miembro->apellidos }} - {{ $miembro->roles_concatenados }}</p>
        
          @endforeach
        </div>
        {{-- @dd(getUsersComiteAudita()) --}}
        
        <div class="comment-section">

        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        {{-- <button type="button" class="btn btn-primary">Guardar Cambios</button> --}}
      </div>
    </div>
  </div>
</div>