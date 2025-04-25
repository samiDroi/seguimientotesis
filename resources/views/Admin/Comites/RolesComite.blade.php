@extends('layouts.admin')
@section('content')
<h1 class="text-center mt-4">Panel de roles</h1>
<div class="container">
    <div class="row bg-body-secondary fs-4 py-5 shadow-lg mb-4">
        <p>
           En este panel podra editar los roles que previamente creo, RECUERDE si los roles tienen usuarios asignados, el nombre y permisos de estos roles cambiaran y por lo tanto el de sus usuarios previamente asignados tambien
        </p>
    </div>
    <form action="{{ route('roles.update') }}" method="POST">
        @csrf
        <div id="roles-container">
            {{-- Iteración de los roles ya definidos --}}
            @foreach ($rolesUsuario as $roles)
            <div class="rol-item row g-3 align-items-start mb-4 p-3 border rounded shadow-sm">
                <div class="col-md-6">
                    <label class="form-label">Nombre del Rol Personalizado</label>
                    <input class="form-control" type="text" name="nombre_rol[{{ $roles->rol_personalizado }}]" autocomplete="off" required value="{{ $roles->rol_personalizado }}">
                </div>
        
                <div class="col-md-6">
                    <label class="form-label">Tipo de Rol Base</label>
                    <select class="form-select rol-base-select" name="tipo_rol_base[{{ $roles->rol_personalizado }}]">
                        <option value="" disabled>Seleccione un tipo de rol</option>
                        @foreach ($rolesBase as $rol)
                            <option value="{{ $rol->id_rol }}" {{ $rol->id_rol == $roles->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre_rol }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="col-12">
                    <label class="form-label">Descripción del Rol</label>
                    <textarea class="form-control descripcion-rol" name="descripcion_rol[]" rows="2" readonly></textarea>
                </div>
            </div>
            @endforeach
        </div>
        

        <button class="btn btn-primary" type="button" id="agregarRol">Agregar otro rol</button>
        <button class="btn btn-success" type="submit">Definir Roles</button>
    </form>
</div>
@endsection

@section('js')
<script>
    const descripciones = @json($rolesBase->pluck('descripcion', 'id_rol'));

    function attachSelectListener(select) {
        select.addEventListener('change', function () {
            const descripcion = descripciones[this.value] || "";
            this.closest('.rol-item').querySelector('.descripcion-rol').value = descripcion;
        });
    }

    document.getElementById('agregarRol').addEventListener('click', function () {
        const container = document.getElementById('roles-container');
        const newRol = document.createElement('div');
        newRol.classList.add('rol-item', 'row', 'g-3', 'align-items-start', 'mb-4', 'p-3', 'border', 'rounded', 'shadow-sm');

        const options = @json($rolesBase->map(fn($r) => ['id' => $r->id, 'nombre' => $r->nombre]));

        let optionsHtml = '<option value="" selected disabled>Seleccione un tipo de rol</option>';
        options.forEach(opt => {
            optionsHtml += `<option value="${opt.id}">${opt.nombre}</option>`;
        });

        newRol.innerHTML = `
            <div class="col-md-6">
                <label class="form-label">Nombre del Rol Personalizado</label>
                <input class="form-control" type="text" name="nombre_rol[]" autocomplete="off" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo de Rol Base</label>
                <select class="form-select mb-2 rol-base-select" name="tipo_rol_base[]">
                    ${optionsHtml}
                </select>
                <label class="form-label">Descripción del Rol</label>
                <textarea class="form-control descripcion-rol" name="descripcion_rol[]" rows="2" readonly></textarea>
            </div>
        `;

        container.appendChild(newRol);
        attachSelectListener(newRol.querySelector('.rol-base-select'));
    });

    document.querySelectorAll('.rol-base-select').forEach(attachSelectListener);
</script>
@endsection
