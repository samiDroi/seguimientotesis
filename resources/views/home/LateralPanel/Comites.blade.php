@extends('layouts.base')
@section('content')
<div class="container">
    <ul class="nav nav-tabs" id="comiteTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="audita-tab" data-bs-toggle="tab" data-bs-target="#audita" type="button" role="tab" aria-controls="audita" aria-selected="true">
                Comités que te están auditando
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pertenece-tab" data-bs-toggle="tab" data-bs-target="#pertenece" type="button" role="tab" aria-controls="pertenece" aria-selected="false">
                Comités a los que perteneces
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="comiteTabsContent">
        {{-- TAB: Comités que te están auditando --}}
        <div class="tab-pane fade show active" id="audita" role="tabpanel" aria-labelledby="audita-tab">
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
                                        <td>{{ $usuario->nombre . ' ' . $usuario->apellidos }}</td>
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
        </div>

        {{-- TAB: Comités a los que perteneces --}}
        <div class="tab-pane fade" id="pertenece" role="tabpanel" aria-labelledby="pertenece-tab">
            @forelse($comitesPerteneceUser as $id_comite => $usuarios)
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Comité: {{ $usuarios->first()->nombre_comite }}</h3>
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
                                        <td>{{ $usuario->nombre . ' ' . $usuario->apellidos }}</td>
                                        <td>{{ $usuario->correo_electronico }}</td>
                                        <td>{{ $usuario->rol }}</td>
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
    </div>
</div>
@endsection
