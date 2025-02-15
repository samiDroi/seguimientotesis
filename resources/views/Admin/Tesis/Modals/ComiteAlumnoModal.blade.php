
    <!-- Segundo Modal (Asignar Comité) -->
<div class="modal fade" id="asignarComiteModal" tabindex="-1" aria-labelledby="asignarComiteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignarComiteModalLabel">Asignar Comité a la Tesis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tesis.comite.attach',$tesisItem->id_tesis) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="comite" class="form-label fs-4">Asignar comite a la tesis</label>
                        <select name="comite" id="comite">
                            {{-- <option class="form-label fs-4">Seleccione el comité que estará a cargo de la tesis</option> --}}
                            @foreach ($comites as $comite)
                                <option value="{{ $comite->id_comite }}" {{ isset($tesisComite) && $tesisComite->id_comite == $comite->id_comite ? 'selected' : '' }}>
                                    {{ $comite->nombre_comite }}
                                </option>
                            @endforeach
                        </select>

                        <label for="usuarios" class="form-label fs-4">Asignar alumno a la tesis</label>
                        <select name="usuarios" id="usuarios">
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id_user }}">
                                    {{ $usuario->username }} {{ $usuario->nombre }} {{ $usuario->apellidos }}
                                </option>
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
</div>

