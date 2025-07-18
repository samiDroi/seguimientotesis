{{-- Definición de Roles --}}
<div id="roles-container" class="{{ $rolesExistentes->isEmpty() ? '' : 'd-none' }}">
    <h2 class="text-2xl font-bold mt-10 mb-4">Definir Nuevos Roles Personalizados</h2>
    <div class="rol-item row g-3 align-items-start mb-4 p-3 border rounded shadow-sm bg-white">
        <button type="button" class="d-none delete-rol"> - </button>
        <div class="col-md-6">
            <label class="form-label">Nombre del Rol Personalizado</label>
            <input class="form-control" type="text" name="nombre_rol[]" autocomplete="off" >
        </div>
        <div class="col-md-6">
            <label class="form-label">Tipo de Rol Base</label>
            <select class="form-select mb-2 rol-base-select" name="tipo_rol_base[]">
                <option value="" selected disabled>Seleccione un tipo de rol</option>
                @foreach ($rolesBase as $rol)
                    <option value="{{ $rol->id_rol }}" 
                            data-descripcion="{{ $rol->descripcion }}">
                        {{ $rol->nombre_rol }}
                    </option>
                @endforeach
            </select>
            <label class="form-label">Descripción del Rol</label>
            <textarea class="form-control descripcion-rol" name="descripcion_rol[]" rows="2" readonly></textarea>
        </div>
    </div>
</div>
<div class="mt-3 roles-buttons {{ $rolesExistentes->isNotEmpty() ? 'd-none' : '' }}">
    <button class="btn btn-primary" type="button" id="agregarRol">Agregar otro rol</button>
    <button class="btn btn-success" type="button" id="definirRoles">Definir Roles</button>
    
    <button type="button" class="{{ $rolesExistentes->isEmpty() ? 'd-none' : '' }} btn btn-danger" id="cancelRoles">Cancelar</button>
</div>
