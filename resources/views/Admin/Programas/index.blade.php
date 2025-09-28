@extends("layouts.admin")
@section('content')
@section('css')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css"> --}}
@endsection



    <h1 class="text-center my-5">{{ $unidad->nombre_unidad }}</h1>
    <div class="d-flex justify-content-between mb-4">
       <div class="ms-5">
        <button class="btn btn-secondary   text-light"><a href="{{ route('unidades.index') }}" class=" text-decoration-none text-light"><i class="fa-solid fa-arrow-left"></i> Volver a unidades academicas</a></button> 
       </div>

        <div class="me-5">
       <button class="btn text-light" style="background-color: var(--color-verde-Nephiris)" data-bs-toggle="modal" data-bs-target="#modalCreateU">
         <i class="fa-solid fa-plus"></i> Agregar Nuevo Programa
        </button>
       </div>
        
        
    </div>
  

    
    <div class="container bg-light py-3 shadow-sm">
        <div class="mx-5 mt-3">
                  
              <table class="table mt-4 table-bordered table-striped" id="programa">
                  <thead class="table-primary">
                      <tr>
                          <th>Nombre del Programa</th>
                          <th class="text-center" >Acciones</th>
                          
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($unidad->programas as $programa)
                    <div class="modal fade" id="modalePrograma" tabindex="-1" aria-labelledby="modalProgramaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4 shadow">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="modalProgramaLabel">Editar Programa Académico</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
     
      <form action="{{ route('programas.update', $programa->id_programa) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="mb-4 text-center">
            <label for="nombre_programa" class="form-label fs-5 fw-semibold">Nombre del Programa Académico</label>
            <input
              type="text"
              id="nombre_programa"
              name="nombre_programa"
              class="form-control form-control-lg w-75 mx-auto"
              value="{{ old('nombre_programa', $programa->nombre_programa) }}"
              required
              placeholder="Ej. Ingeniería Industrial"
            >
          </div>
        </div>

        <div class="modal-footer border-0 justify-content-center pb-4">
          <button type="submit" class="btn btn-success px-4 py-2">
            <i class="fa-solid fa-check me-2"></i>
             Actualizar Programa
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
                          <tr>
                              <td >{{ $programa->nombre_programa }}</td>
                              <td class="text-center">
                                  <button class="btn btn-sm text-light" style="background-color: var(--color-amarillo)" data-bs-toggle="modal" data-bs-target="#modalePrograma">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                 </button>

                                  <form action="{{ route('programas.destroy', $programa->id_programa) }}" method="POST" class="delete" style="display:inline;">
                                      @csrf
                                      @method('DELETE')
                                      <button class="btn btn-danger btn-sm" type="submit"> <i class="fa-solid fa-trash"></i> Eliminar</button>
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
        







<div class="modal fade" id="modalCreateU" tabindex="-1" aria-labelledby="modalProgramaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4 shadow">

      <div class="modal-header border-0">
        <h2 class="modal-title fw-bold" id="modalProgramaLabel">Crear Programa Academico</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

        <div class="modal-body">
             <form action="{{ route('programas.create') }}" method="POST">
                @csrf   

                

                <div class="container   " id="programas-container">
                    <div class="programa-item text-center">
                        <div class="fs-3 fw-semibold mb-1"><label for="nombre_programa ">Nombre del Programa</label></div>
                        <div class="mx-5"><input type="text" name="nombre_programa[]" class="form-control" autocomplete="off" required></div>
                        </div>
                </div>
                
                <div class="text-end me-5 mt-3">
                <button class="btn btn-primary" type="button" id="agregarPrograma">Agregar otro programa</button>

                <button class="btn btn-success " type="submit">Crear Programas</button>
                    </div>
            </form>
        </div>

        
         
        </div>
      

    </div>
  </div>
</div>

@endsection