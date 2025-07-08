@extends('layouts.admin')
@section('content')
<h1 class="text-center mt-4 " style="color: var(--color-azul-obscuro)">Historial de títulos de tesis</h1>


<table class="table table-hover table-bordered table-striped  mx-auto mt-4 shadow-sm rounded" style="max-width: 90%;">
    <thead class="table-primary">
        <tr>
            <th scope="col">Nombre anterior</th>
            <th scope="col">Nombre actualizado</th>
            <th scope="col">Fecha de cambio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs->sortByDesc('created_at') as $log)
        <tr>
            <td class="fw-semibold text-danger">{{ $log->original }}</td>
            <td class="fw-semibold text-success">{{ $log->nuevo }}</td>
            <td class="fw-semibold">{{ $log->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

   
@endsection