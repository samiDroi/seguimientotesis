<!-- Modal con Textarea -->
<div class="modal fade" id="modalTextarea" tabindex="-1" aria-labelledby="modalTextareaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTextareaLabel">Rechazar estructura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- @dd($requerimiento->motivo_rechazo) --}}
                <textarea class="form-control" id="comentariosTextarea" rows="10" placeholder="Escribe un comentario..." style="resize: none;" name="comentario"  @if($requerimiento?->motivo_rechazo) readonly @endif>{{ $requerimiento?->motivo_rechazo }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if (Auth::user()->esCoordinador == 1)
                    <button type="button" class="btn btn-danger" id="submitRechazoBtn">Enviar comentario y Rechazar requerimiento</button>
                @endif
               
            </div>
        </div>
    </div>
</div>