@extends("layouts.base")
@section('content')
    @foreach ($unidades as $unidad)
    @dd($unidad)
        <h1>{{ $unidad->nombre_unidad }}</h1>
    @endforeach
    @foreach ($programas as $programa)
        <h2>{{ $programa->nombre_programa }}</h2>
    @endforeach

    
@endsection