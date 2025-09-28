@extends('layouts.admin')

@section('css')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css"> --}}
@endsection

@section('content')
    <h1>{{ $comite->nombre_comite }}</h1>
    <div class="container">
    <form action="{{ Route("comites.save.members",$comite->id_comite) }}" method="POST">
        @csrf
        <input type="hidden" name="id_comite" value="{{ $comite->id_comite }}">
    <div class="row mt-5">
        {{-- Tabla de docentes a la izquierda --}}
        <div class="col-7">
            <label for="docentes">Lista de docentes disponibles</label>
            <
            <table class="table" id="docentes">
                <thead class="table-primary">
                    <tr>
                        <th>Clave de trabajador</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docentes as $docente)
                    <tr>
                        <td>{{ $docente->username }}</td>
                        <td>{{ $docente->nombre }}</td>
                        <td>{{ $docente->apellidos }}</td>
                        <td>{{ $docente->correo_electronico }}</td>
                        <td>
                            <input type="checkbox" class="checkbox-docente" value="{{ $docente->username }}" {{ $comite && $comite->usuarios->contains($docente->id_user) ? 'checked' : '' }}>
                        </td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="col-5">
            <p class="fs-3 border-bottom border-primary border-2">Confirmar información de comité</p>
            <div id="confirmarComite" > </div>
        </div>
    




        <button class="col-12 btn btn-primary py-2 text-center mt-4" style="height: 50px;" type="submit" >{{ $comite?"Guardar cambios":"Registrar comite" }}</button>

    </form>

    </div>
    </div>
@endsection

