@extends('layouts.admin')

@section('css')
@vite(['resources/js/Comites/EditComite.js'])
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css"> --}}
<style>
    .role-select {
        height: auto;
        min-height: 100px;
    }
    .selected-user-card {
        border-left: 4px solid #0d6efd;
        margin-bottom: 15px;
    }
</style>

@endsection

@section('content')
<div class="container">
    <h1 class="text-center mt-4">Editar Comité</h1>
    <div data-roles="@json($roles->count())"></div>
    <div data-rolesBase='@json($rolesBase->map(fn($r) => ["id" => $r->id_rol, "nombre" => $r->nombre_rol]))'></div>

    {{-- <div data-rolesBase="@json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]))"></div> --}}
    <form action="{{ route('comites.update', $comite->id_comite) }}" id="edit-form" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $comite->id_comite }}">
        
        <button class="btn mb-4 " style="background-color:var(--color-amarillo)"><a class="text-decoration-none text-light" href="{{ Route("roles.index") }}"> <i class="fa-solid fa-pencil"></i> Editar roles</a></button>
        <button type="button" id="create-roles" class="{{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }} btn mb-4 btn-success">Crear roles</button>
        @include('Admin.Comites.DefineRolesSection')
        <div id="editSection">
            <div class="mb-3">
                <label class="form-label fs-5 fw-semibold">Programa académico</label>
                <select class="form-select" name="ProgramaAcademico" required>
                    <option value="">Seleccione un programa</option>
                    @foreach ($programas as $programa)
                        <option value="{{ $programa->id_programa }}" 
                            {{ ($comite->id_programa == $programa->id_programa) ? 'selected' : '' }}>
                            {{ $programa->nombre_programa }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="row mt-4">
                <div class="col-md-7">
                    <h4>Docentes disponibles</h4>
                    <table class="table" id="docentes">
                        <thead class="table-primary">
                            <tr>
                                <th>Seleccionar</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($docentes as $docente)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               class="checkbox-docente" 
                                               value="{{ $docente->id_user }}"
                                               data-username="{{ $docente->username }}"
                                               {{ $comite->usuarios->contains($docente->id_user) ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $docente->username }}</td>
                                    <td>{{ $docente->nombre }}</td>
                                    <td>{{ $docente->apellidos }}</td>
                                    <td>{{ $docente->correo_electronico }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="col-md-5">
                    <h4>Miembros y roles del comité</h4>
                    <div id="confirmacion-comite">
                        @foreach($comite->usuarios as $miembro)
                            <div class="users-roles card selected-user-card mb-3" id="" data-user-id="{{ $miembro->id_user }}">
                                <div class="card-body">
                                    <h5>{{ $miembro->nombre }} {{ $miembro->apellidos }}</h5>
                                    <input type="hidden" name="docentes[]" value="{{ $miembro->id_user }}">
                                    
                                    <label class="form-label">Roles asignados</label>
                                    {{-- @dd($roles) --}}
                                    {{-- en edit es user-role-selection --}}
                                    <select class="form-select role-select user-role-selection" id="select-roles" data-user="{{ $miembro->id_user }}" name="roles[{{ $miembro->id_user }}][]" multiple>
                                        @foreach($roles as $rol)
                                            @php
                                                $selected = DB::table('usuarios_comite_roles')
                                                    ->join('usuarios_comite', 'usuarios_comite_roles.id_usuario_comite', '=', 'usuarios_comite.id_usuario_comite')
                                                    ->where('usuarios_comite.id_user', $miembro->id_user)
                                                    ->where('usuarios_comite.id_comite', $comite->id_comite)
                                                    ->where('usuarios_comite_roles.id_rol', $rol->id_rol)
                                                    ->exists();
                                            @endphp
                                            <option value="{{ $rol->id_rol }}" {{ $selected ? 'selected' : '' }}>
                                                {{ $rol->rol_personalizado }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 py-3">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection


