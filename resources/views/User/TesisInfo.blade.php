@extends($currentLayout)
@section('css')
    
@endsection

@section('content')
{{-- @dd($currentLayout) --}}
{{-- @dd(Auth::user()->esCoordinador) --}}
{{-- @dd(empty($tesisUser)) --}}
    <div>
        
        <div class="mb-5">
            <h1 class="mt-4 mb-2">Mis Tesis</h1>
        @if ($tesisUser->isEmpty())
            <p class="text-danger fs-3">No tienes ningun seguimiento de tesis</p>
        @else
            @foreach ($tesisUser as $tesis)
            <div class=" bg-personalizado rounded-3 my-3"> <p class="fs-3 ps-4 py-2 "> {{ $tesis->nombre_tesis }}</p></div>
             @endforeach
        @endif
   
    </div>
    </div>
    
    <div class="mt-5">
        
        @if (isDirector())
        <div class="d-flex">
        <p class="fs-1 fw-semibold">Tesis auditadas</p>
       <div> <button class="btn btn-secondary mt-3 ms-3"><a class="text-light text-decoration-none" href="{{ Route("tesis.index") }}">Ir a modulo de revision de tesis</a></button></div>
    </div>

        @endif
        {{-- @dd($tesisComite) --}}
        @if ($tesisComite)
            @foreach ($tesisComite as $tesisC)
            
            <div class=" bg-personalizado rounded-3 my-3"> <p class="fs-3  ps-4 py-2 "> {{ $tesisC->nombre_tesis }}</p></div>
            @endforeach
        @else

        @endif
    </div>
@endsection

@section('js')
    
@endsection