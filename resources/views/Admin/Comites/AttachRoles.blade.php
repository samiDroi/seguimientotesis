@extends('layouts.admin')
@section('css')
    {{-- @vite(['resources/js/Comites/Roles.js']) --}}
@endsection
@section('content')
<div id="datos-json" data-datos='@json($rolesBase->map(fn($r) => ['id' => $r->id_rol, 'nombre' => $r->nombre_rol]))'></div>
<div class="container mx-auto p-6">
    {{-- @dd($rolesBase) --}}
    <h1 class="text-center mt-4">Panel de roles</h1>
    <div class="container">
        <div class="row  fs-4 py-5 shadow mb-4 rounded" style="background-color: var(--color-azul-obscuro)">
            <p class="text-light">
                En este panel podrá definir los roles que usarán en los comités de su área. Una vez definidos, al crear comités nuevos los roles ingresados aquí aparecerán en una lista de roles permitidos para los usuarios del comité.
            </p>
        </div>
        <button type="button" id="mostrarRoles" class="btn btn-success mb-4 {{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}">
            <i class="fa-solid fa-plus"></i> Crear Nuevos Roles 
        </button>


        <form method="POST" action="{{ route('comites.saveRoles', $comite->id_comite) }}">
            @csrf
            
         <div id="users-roles" class="{{ $rolesExistentes->isNotEmpty() ? '' : 'd-none' }}  p-4">
    {{-- Asignación de Roles --}}
    <h2 class="h4 mb-4 text-primary fw-semibold">
        Asignar Roles a Usuarios del Comité: <span class="text-dark">{{ $comite->nombre_comite }}</span>
    </h2>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 60%;">Usuario</th>
                    <th>Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td class="fs-3 fw-semibold"><span style="margin-left: 70px">{{ $usuario->nombre." ".$usuario->apellidos }}</span></td>
                        <td>
                            <select id="" name="roles[{{ $usuario->id_user }}][]" multiple
                                    class="form-select user-role-select ">
                                @foreach($rolesExistentes->isEmpty() ? $roles : $rolesExistentes as $rol)
                                    <option class="fs-4" value="{{ $rol->id_rol }}" data-nombre_rol="{{ $rol->nombre_rol }}">
                                        {{ $rol->nombre_rol }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">
            Guardar Roles
        </button>
    </div>
</div>

    {{-- Panel de creacion de roles  --}}
            @include('Admin.Comites.DefineRolesSection')
           
        </form>
    </div>
</div>

@endsection

@section('js')
{{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="js/Comites/Roles.js"></script> --}}
@endsection