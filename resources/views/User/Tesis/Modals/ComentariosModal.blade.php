<div class="modal fade" id="comentarios-mostrar" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Comentarios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        Este es el cuerpo de mi modal en Bootstrap.
        {{-- @dd(getUsersComiteAudita()) --}}
        @foreach (getUsersComiteAudita() as $miembro)
    
        <p>{{ $miembro->nombre }} {{ $miembro->apellidos }} - {{ $miembro->rol_personalizado }}</p>
    
@endforeach
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>