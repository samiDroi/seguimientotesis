@extends('layouts.base')
@section('content')
<div>
    {{ Auth::user()->correo_electronico }}
    <form action="{{ Route("logout") }}" method="POST">
        @csrf
        <button type="submit">Cerrar Sesion</button>
    </form>

</div>

    <div>
        <a href="">Mi perfil</a>
        <a href="">Mi comite</a>
        <a href="">Mis tesis</a>
        <a href="">Mi unidad</a>
        @if (Auth::user()->tipos->contains("nombre_tipo","coordinador"))
                <a href="">Gestionar comites</a>
                <a href="">Gestionar usuarios</a>
                <a href="">Gestionar informacion academica</a>
                <a href="">Gestionar tesis</a>
        @endif
        
    </div>

    <div>
        @foreach ($tesisUsuario as $tesis)
            <div>
                <h1>{{ $tesis->nombre_tesis }}</h1>
            </div>
        @endforeach
    </div>
@endsection