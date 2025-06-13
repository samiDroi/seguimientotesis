@extends('layouts.base')
@section('content')
<h1>formulario de plan de trabajo</h1>
<label for="objetivo">Objetivo</label>
<input type="text" id="objetivo" name="objetivo">

<table>
    <thead>
        <tr>
            <th>Tema o actividad</th>
            <th>Descripcion de la actividad</th>
            <th>Fecha de entrega esperada</th>
            <th>responsable</th>
        </tr>    
    </thead>
    
    <tbody>
        <tr>
            <td><input type="text" name="actividad[]"></td>
            <td><input type="text" name="descripcion[]"></td>
            <td><input type="date" name="fecha_entrega" id=""></td>
            <td>
                <select name="responsable" id="">
                    @foreach ($comite->usuarios as $usuario)
                        <option value="{{ $usuario->id_user }}">{{ $usuario->nombre. " " .$usuario->apellidos }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </tbody>    
</table>   
<h3>metas y resultados esperados</h3>
<ol>
    <li><input type="text" name="meta[]"></li>
</ol>
<h3>criterios de evaluacion de los avances</h3>
<ol>
    <li><input type="text" name="criterios[]"></li>
</ol>
<h3>criterios de evaluacion de los avances</h3>
<ol>
    <li><span>El Comité Tutorial se compromete a revisar los avances entregados por el(la) alumno(a) y proporcionar retroalimentación oportuna para garantizar el cumplimiento de los objetivos de la tesis.</span></li>
    <li><span>El Comité Tutorial acuerda realizar reuniones periódicas (establecer cada cuando y por cual medio) con el alumno(a) para supervisar el progreso y resolver dudas o problemas que puedan surgir.</span></li>
    <li><span>El Comité Tutorial se compromete a presentar avances a la coordinación del programa académico cuando se solicite. </span></li>
    <li><span></span></li>
    <li><input type="text" name="compromisos[]"></li>
</ol>
@endsection