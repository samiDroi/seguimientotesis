@extends($currentLayout)
@section('css')
    
@endsection

@section('content')
{{-- @dd($currentLayout) --}}
{{-- @dd(Auth::user()->esCoordinador) --}}
{{-- @dd(empty($tesisUser)) --}}
    <div>
        <h1>Mis Tesis</h1>
        @if ($tesisUser->isEmpty())
            <h1>No Haz tenido ningun seguimiento de tesis</h1>
        @else
            @foreach ($tesisUser as $tesis)
                 <h1>{{ $tesis->nombre_tesis }}</h1>
             @endforeach
        @endif
    </div>
    
    <div>
        <h1>tesis auditadas</h1>
        @if (isDirector())
        <a href="{{ Route("tesis.index") }}">Ir a modulo de revision de tesis</a>

        @endif
        {{-- @dd($tesisComite) --}}
        @if ($tesisComite)
            @foreach ($tesisComite as $tesisC)
            
                <h1>{{ $tesisC->nombre_tesis }}</h1>
            @endforeach
        @else

        @endif
    </div>
@endsection

@section('js')
    
@endsection