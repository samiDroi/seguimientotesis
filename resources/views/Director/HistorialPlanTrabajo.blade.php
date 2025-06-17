@extends('layouts.base')
@section('content')
    <div class="container">
    <h1 class="mb-4">Historial de Movimientos</h1>
    <a href="{{ Route('plan.index',$id) }}">crear nuevo plan de trabajo</a>
    @forelse ($planMes as $mes => $items)
        <div class="mb-5">
            <h3 class="text-primary border-bottom pb-2">{{ \Carbon\Carbon::parse($mes)->locale('es')->translatedFormat('F Y') }}</h3>

            <ul class="list-group">
                @foreach ($items as $historial)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ ucfirst(str_replace('_', ' ', $historial->tipo_accion)) }}</strong><br>
                            <small class="text-muted">plan de trabajo creado el: 
                                {{ $historial->fecha_creacion }} <br>
                                <span class="text-secondary">Estado: {{ $historial->estado }} (ID: {{ $historial->estado }})</span>
                            </small>
                        </div>
                        <span class="badge bg-secondary">
                            {{ \Carbon\Carbon::parse($historial->fecha_creacio)->format('d/m/Y H:i') }}
                        </span>
                        <a href="{{ Route('plan.index') }}"></a>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p>No hay movimientos registrados aún.</p>
    @endforelse
</div>
@endsection
