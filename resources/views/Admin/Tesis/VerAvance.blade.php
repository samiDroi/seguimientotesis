@extends('layouts.admin')
@section('content')
<div class="container">
    @if ($tesis->isNotEmpty())
        <h2 class="mb-4">Tesis: {{ $tesis->first()->nombre_tesis }}</h2>

        <div class="accordion" id="requerimientosAccordion">
            @foreach ($tesis as $index => $item)
                <details class="mb-3">
                    <summary class="h5 text-primary">
                        {{ $item->nombre_requerimiento ?? 'Requerimiento sin nombre' }}
                    </summary>
                    <div class="card card-body">
                        <p><strong>Descripción:</strong> {{ $item->descripcion ?? 'SIN DESCRIPCION' }}</p>
                        <p><strong>Estado:</strong> {{ ucfirst($item->estado) }}</p>

                        {{-- Aquí el botón para ir al detalle del requerimiento --}}
                        <a href="{{ Route('avance.index',$item->id_requerimiento) }}" class="btn btn-sm btn-outline-primary">
                            Ver requerimiento
                        </a>
                    </div>
                </details>
            @endforeach
        </div>
    @else
        <h4 class="text-danger">No se encontraron requerimientos para esta tesi.</h4>
    @endif
</div>


@endsection