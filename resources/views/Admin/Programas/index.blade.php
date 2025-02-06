@extends("layouts.admin")
@section('content')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection



    <h1 class="text-center my-5">{{ $unidad->nombre_unidad }}</h1>
    <div class="text-end me-5 mt-5 mb-4"><button class="btn btn-primary text-light"><a href="{{ route('programas.store') }}" class=" text-decoration-none text-light">Agregar Nuevo Programa</a></button> <!-- Enlace para agregar un nuevo programa --></div>
    <div class="text-end me-5 mt-5 mb-4"><button class="btn btn-danger text-light"><a href="{{ route('unidades.index') }}" class=" text-decoration-none text-light">Volver a unidades academicas</a></button> <!-- Enlace para agregar un nuevo programa --></div>

    
    <div class="container bg-light py-3 shadow-lg">
        <div class="row mx-5 mt-3">
                  
              <table class="table mt-4 table-bordered table-striped" id="programa">
                  <thead class="table-primary">
                      <tr>
                          <th>Nombre del Programa</th>
                          <th class="text-center" >Acciones</th>
                          
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($unidad->programas as $programa)

                          <tr>
                              <td >{{ $programa->nombre_programa }}</td>
                              <td class="text-center">
                                  <button class="btn btn-secondary btn-sm">
                                      <a href="{{ route('programas.edit', $programa->id_programa) }}" class="text-decoration-none text-light">Editar</a>
                                  </button>

                                  <form action="{{ route('programas.destroy', $programa->id_programa) }}" method="POST" class="delete" style="display:inline;">
                                      @csrf
                                      @method('DELETE')
                                      <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                  </form>
                              </td>
                              
                          </tr>
                      @empty
                          <tr>
                              <td colspan="3">No hay programas académicos disponibles para esta unidad.</td> <!-- Mensaje cuando no hay programas -->
                          </tr>
                      @endforelse
                  </tbody>
              </table>

              </div>
        </div>
     
   
    @section('js')
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js
            https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
        <script>
              $("body").on("click",".delete > button",function(){
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Programa academico",
                text: "Estas a punto de eliminar este programa academico, junto con su informacion relacionada, esto no puede ser reversible ¿Estas seguro?",
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

@endsection