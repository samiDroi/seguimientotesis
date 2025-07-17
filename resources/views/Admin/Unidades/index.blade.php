@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection

@section('content')

<div class="container">
  <x-Titulos text="Lista de unidades"/>
</div>

<div class="text-end mt-5 me-5 mb-4"><button data-bs-toggle="modal" data-bs-target="#modalCrear" class="btn" style="background-color: var(--color-verde-Nephiris)"><a  class="text-decoration-none text-light"> <i class="fa-solid fa-plus"></i> Agregar nueva Unidad</a></button> </div>

    <div class="container bg-light py-3 shadow-lg">
        <div class="row ms-2  mt-3">
            <div class="table-wrapper"> 
                <table id="unidades" class=" custom-table table-responsive my-3">
                <thead class=" ">
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
                                <button class="btn btn-outline-secondary btn-sm"><a href="{{ route('programas.index',$unidad->id_unidad) }}" class="text-decoration-none text-black"> <i class="fa-solid fa-graduation-cap"></i> Programas</a></button>
                            </td>


                            <td>
                                <x-editar-modal target=""/>
                                <x-boton-eliminar ruta="{{ route('unidades.destroy', $unidad->id_unidad) }}"/>
                            </td>
                           
                           
                        </tr>
                    @endforeach
                </tbody>
                </table>

            </div>

            
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Unidad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
          <form action="{{ route('unidades.update', $unidad->id_unidad) }}" method="POST">
             @csrf
              @method('PUT') <!-- Necesario para indicar que se trata de una actualización -->

            <div class="container">
            <div class="row  text-center ">
                  <div class="mb-4 ">
                        <label class=" my-4 fs-3 fw-semibold" for="nombre_unidad">Nombre de la Unidad</label>
                        <div class=""><input type="text" class="form-control form-control-lg form-floating mb-4 " id="nombre_unidad" name="nombre_unidad" value="{{ old('nombre_unidad', $unidad->nombre_unidad) }}" required></div>
                    </div>
                    
          </div>
                
                    </div>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        
         <button class="btn btn-success mb-4" type="submit"> <i class="fa-solid fa-check me-2"></i> Actualizar Unidad</button>
          </form>
      </div>
    </div>
  </div>
</div>  




<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-lg rounded-4">
      
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="modalCrearLabel">Crear Unidad Académica</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('unidades.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-4 text-center">
            <label for="unidadAcademica" class="form-label fs-3 fw-semibold">Nombre de la nueva unidad</label>
            <input type="text" class="form-control form-control-lg  mx-auto" id="unidadAcademica" name="nombre_unidad" placeholder="Ej. Licenciatura en Economia" required>
          </div>
        </div>

        <div class="modal-footer border-0 justify-content-center pb-4">
          <button type="submit" class="btn text-light px-4 py-2" style="background-color: var(--color-verde-Nephiris)">
            <i class="fa-solid fa-plus me-2"></i>Crear
          </button>
        </div>
      </form>

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
   

