@extends('layouts.base')
@section('content')
<div class="container">
    <h1>Todas las Tesis y sus Requerimientos</h1>
    <a href="{{ Route('tesis.store',$tesisComite->id_tesis_comite ?? '') }}" class="btn btn-primary">Crear nueva tesis</a>
    <div class="container mt-4">
        @foreach ($tesisComites as $tesisComite)
            <div class="card mb-4 border-secondary">
                <div class="card-body">
                    <!-- Contenedor flex para nombre de tesis, botones y comité -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Título de la Tesis -->
                        <h2 class="card-title h4 font-weight-bold text-dark flex-grow-1">{{ $tesisComite->tesis->nombre_tesis }}</h2>
                        
                        <!-- Botones: Editar y Eliminar -->
                        <div class="d-flex gap-2">
                            <a href="{{ Route("tesis.store",$tesisComite->id_tesis_comite) }}" class="btn btn-sm btn-warning">Editar</a>

                            <form action="{{ Route("tesis.delete",$tesisComite->id_tesis_comite) }}" class="delete" method="POST">
                                @csrf
                                
                                <button type="button" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                            </form>
                        </div>

                        <!-- Texto del Comité -->
                        @if ($tesisComite->comite)
                            <span class="text-muted small ms-2">Comité: {{ $tesisComite->comite->nombre_comite }}</span>
                        @endif
                    </div>

                    <!-- Requerimientos -->
                    <details>
                        <summary class="h6 text-secondary">Requerimientos</summary>
                        <ul class="list-group list-group-flush">
                            @foreach ($tesisComite->requerimientos as $requerimiento)
                                <li class="list-group-item px-0">
                                    <strong>{{ $requerimiento->nombre_requerimiento }}</strong>
                                    <br>
                                    <span>Descripción:</span> {{ $requerimiento->descripcion }}
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>
    <script>
        
        $("body").on("click",".delete > button",function(){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Tesis",
                text: "Estas a punto de eliminar esta tesis, esto no puede ser reversible ¿Estas seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar"
            }).then((result) => {
            if (result.isConfirmed) {
                $(formulario).submit();
               
            }
        });
    });
    </script>
@endsection
