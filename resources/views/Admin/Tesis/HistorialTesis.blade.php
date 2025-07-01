@extends('layouts.admin')
@section('content')
<h1>Historial de títulos de tesis</h1>
    @foreach ($logs->sortByDesc('created_at') as $log)
        <div class="mb-3 border-bottom pb-2">
            <h4>Nuevo</h4>
            <span>{{ $log->nuevo }}</span>
            <h4 class="mt-2">Anterior</h4>
            <span>{{ $log->original }}</span>
            <br>
            <small>Fecha de cambio: {{ $log->created_at->format('d/m/Y H:i') }}</small>
        </div>
    @endforeach
@endsection