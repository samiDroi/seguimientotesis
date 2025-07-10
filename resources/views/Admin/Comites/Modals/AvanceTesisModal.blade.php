<!-- Modal de Tesis por Comité -->
<div class="modal fade" id="verTesisModal-{{ $comite->id_comite }}" tabindex="-1" aria-labelledby="verTesisModalLabel-{{ $comite->id_comite }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verTesisModalLabel-{{ $comite->id_comite }}">Tesis del Comité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        @forelse ($comite->tesis as $tesis)
            <div class="mb-3">
                <h6 class="fw-bold">{{ $loop->iteration }}. {{ $tesis->nombre_tesis }}</h6>
                <a href="{{ route('tesis.avance.admin', $tesis->id_tesis) }}" class="btn btn-sm btn-primary">
                    <i class="fa-regular fa-eye"></i> Ver avances
                </a>
            </div>
        @empty
            <p>No hay tesis asociadas a este comité.</p>
        @endforelse
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
