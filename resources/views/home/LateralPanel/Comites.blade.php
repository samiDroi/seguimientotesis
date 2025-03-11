@extends('layouts.base')
@section('content')
    <div class="container">
        {{-- Sección de comités que están auditando al usuario --}}
        <h2>Comités que te están auditando</h2>
        @forelse($comitesAuditaUser as $id_comite => $usuarios)
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Comité: {{ $usuarios->first()->nombre }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->nombre." ".$usuario->apellidos }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->rol ?? 'Miembro' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p>No hay comités auditándote.</p>
        @endforelse

        {{-- Sección de comités a los que pertenece el usuario --}}
        <h2 class="mt-5">Comités a los que perteneces</h2>
        @forelse($comitesPerteneceUser as $id_comite => $usuarios)
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Comité: {{ $usuarios->first()->nombre }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    {{-- @dd( $usuario ) --}}
                                    <td>{{ $usuario->nombre." ".$usuario->apellidos  }}</td>
                                    <td>{{ $usuario->correo_electronico }}</td>
                                    <td>{{ $usuario->rol  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p>No perteneces a ningún comité.</p>
        @endforelse
    </div>

@endsection