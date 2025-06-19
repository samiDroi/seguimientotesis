<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Trabajo Comité Tutorial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h2, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td, th {
            padding: 6px;
            vertical-align: top;
        }
        .bordered th, .bordered td {
            border: 1px solid #000;
        }
        .section {
            margin-top: 20px;
        }
        .firma {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    {{-- @dd($rolesComite) --}}
    <h2>PLAN DE TRABAJO COMITÉ TUTORIAL</h2>
    <h3>Maestría en Tecnologías de Información Emergentes Aplicadas a la Educación<br>
        Unidad Académica de Economía / Universidad Autónoma de Nayarit</h3>

    <p><strong>Lugar:</strong> {{ $plan->lugar ?? '_________' }} &nbsp;&nbsp;&nbsp;&nbsp;
       <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($plan->fecha_creacion)->format('d/m/Y') }}</p>

       @foreach ($alumnosPorTesis as $idTesis => $grupo)
    @php
        // Tomamos el primer registro del grupo para acceder a datos comunes
        $alumnoEjemplo = $grupo->first();
    @endphp
    {{-- @dd($grupo) --}}
    <div class="section">
    <strong>Datos del alumno(a)</strong>
    @foreach ($grupo as $alumno)
        <table style="margin-bottom: 10px;">
            <tr>
                <td><strong>Nombre del alumno(a):</strong></td>
                <td>{{ $alumno->alumno_nombre }} {{ $alumno->alumno_apellidos }}</td>
            </tr>
            <tr>
                <td><strong>Generación:</strong></td>
                <td>{{ $alumno->generacion ?? 'N/D' }}</td>
            </tr>
        </table>
    @endforeach

    <table>
        <tr>
            <td><strong>Título del documento de tesis:</strong></td>
            <td>{{ $alumnoEjemplo->nombre_tesis }}</td>
        </tr>
    </table>
</div>

@endforeach

    <div class="section">
        <strong>Integrantes del Comité Tutorial</strong>
        <table>
    @foreach ($rolesComite as $miembro)
        <tr>
            <td>{{ $miembro->rol_personalizado }}:</td>
            <td>{{ $miembro->nombre }} {{ $miembro->apellidos }}</td>
        </tr>
    @endforeach
</table>
    </div>

    <div class="section">
        <strong>Plan de trabajo</strong>
        <ol>
            <li><strong>Objetivo:</strong><br><br>{{ $plan->objetivo }}</li>
            <li><strong>Cronograma de actividades:</strong></li>
        </ol>

        <table class="bordered">
            <thead>
                <tr>
                    <th>Tema o actividad</th>
                    <th>Descripción de la actividad</th>
                    <th>Fecha de entrega esperada</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plan->actividades as $actividad)
                <tr>
                    <td>{{ $actividad->tema }}</td>
                    <td>{{ $actividad->descripcion }}</td>
                    <td>{{ \Carbon\Carbon::parse($actividad->fecha_entrega)->format('d/m/Y') }}</td>
                    <td>{{ $actividad->responsable->nombre ?? 'Sin asignar' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <ol start="3">
            <li><strong>Metas y resultados esperados</strong>
                <ul>
                    
                    @foreach ($plan->metas as $meta)
                   
                        <li>{{ $meta }}</li>
                    @endforeach
                </ul>
            </li>
            <li><strong>Criterios de evaluación de los avances</strong>
                <ul>
                    @foreach ($plan->criterios as $criterio)
                        <li>{{ $criterio }}</li>
                    @endforeach
                </ul>
            </li>
        </ol>
    </div>

    <div class="section">
        <strong>Compromisos del comité tutorial</strong>
        <ol type="a">
            @foreach ($plan->compromisos as $compromiso)
                <li>{{ $compromiso }}</li>
            @endforeach
        </ol>
    </div>

    <div class="section firma">
        <strong>Firmas de aprobación</strong>
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Rol</th>
                    <th style="text-align: left;">Nombre</th>
                    <th style="text-align: left;">Firma</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Alumno(a)</td>
                    <td style="border-bottom: 1px solid #000;">{{ $plan->alumno->nombre ?? '' }}</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td>Director(a)</td>
                    <td style="border-bottom: 1px solid #000;">{{ $plan->director->nombre ?? '' }}</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td>Co-director(a)</td>
                    <td style="border-bottom: 1px solid #000;">{{ $plan->codirector->nombre ?? '' }}</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td>Asesor(a)</td>
                    <td style="border-bottom: 1px solid #000;">{{ $plan->asesor->nombre ?? '' }}</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section" style="font-size: 0.9em;">
        <strong>Notas:</strong>
        <ul>
            <li>Se deberá enviar una copia firmada a la Coordinación del PA de la MTIEAE para su resguardo.</li>
            <li>Este documento deberá ser llenado al inicio del seguimiento de la tesis y actualizado conforme sea necesario.</li>
            <li>Se deberá enviar una copia firmada al Tutor(a) de seguimiento para su seguimiento.</li>
        </ul>
    </div>
</body>
</html>
