@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')
<div class="text-end mt-5 me-5 mb-3"><button class="btn btn-primary"><a href="{{ route('unidades.create') }}" class="text-decoration-none text-light">Agregar nueva Unidad</a></button> </div>

    <div class="container bg-light py-3 shadow-lg">
        <div class="row mx-5 mt-3">
            <div> 
                <table id="unidades" class="table mt-4 table-bordered text-center table-striped ">
                <thead class="table-primary  ">
                    <tr>
                        <th class="col-6 text-center">Nombre de la Unidad</th>
                        <th class="col-4 text-center">Programas Academicos</th>
                        <th class="col-3 text-center">Acciones</th>
                        
                    </tr>
                </thead>
                <tbody class="" >
                    @foreach ($unidades as $unidad)
                        <tr class="text-center" >
                            <td class="text-start ">{{ $unidad->nombre_unidad }}</td>

                            <td >
                                <button class="btn btn-outline-secondary btn-sm"><a href="{{ route('programas.index',$unidad->id_unidad) }}" class="text-decoration-none text-black">Programas</a></button>
                            </td>


                            <td >
                                <button class="btn btn-primary btn-sm"><a href="{{ route('unidades.edit', $unidad->id_unidad) }}" class="text-decoration-none text-light">Editar</a></button>
                                <form action="{{ route('unidades.destroy', $unidad->id_unidad) }}" method="POST" style="display:inline;" class="delete">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                </form>
                            </td>
                           
                           
                        </tr>
                    @endforeach
                </tbody>
                </table>

            </div>

            
        </div>
    </div>

@endsection
@section('js')
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js
            https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

        <script>
           new DataTable('#unidades', {
                responsive: true
            });
        
            $("body").on("click",".delete > button",function(){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Unidad",
                text: "Estas a punto de eliminar esta unidad academica junto con sus programas academicos relacionados, esto no puede ser reversible, ¿Estas seguro?",
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
   

