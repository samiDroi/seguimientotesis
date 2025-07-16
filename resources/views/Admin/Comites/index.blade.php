@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection 
@section('content')
<h1 class="text-center mt-4 text-end">Lista de Comités</h1>


<div class="container mt-2 ">


    <div class="text-start  mb-5 "><button class="btn text-light" style="background-color: var(--color-azul-principal)" data-bs-toggle="modal" data-bs-target="#crearComiteModal">
      <i class="fa-solid fa-users"></i>   Crear Comité
    </button></div>


@include('Admin.Comites.Create')
@if ($comites->isEmpty())
    <div class="px-5">
       
         <span class="fs-3 fw-bold  row">No hay comites registrados por el momento  </span>
         <span class="fs-5 row">
          Si desea registrar un comite de click en  el boton "crear nuevo comite" en la parte superior.
          </span>
    </div>
@else
<div class="container">
<table class="table table-bordered table align-middle display mt-4   " id="miTabla" >
        <thead class="table-primary">
            <tr>
                <th class="col-2 ">Alumno</th>
                <th class="col-2 ">Nombre Tesis </th>
                <th class="col-1 ">Generacion</th>
                <th class="col-2 ">Roles</th>
                <th class="col-2 ">Estado</th>
                <th class="col-2">Acciones</th>
            </tr>
        </thead>
        <tbody class="">
            @forEach($comites as $comite) 
            
            {{-- @dd($tesis) --}}
            <tr>
                <td>
    @foreach ($comite->tesis->pluck('usuarios')->flatten()->unique('id_user') as $usuario)
        <p>{{ $usuario->nombre.' '. $usuario->apellidos }}</p>
    @endforeach
                <td>
                    <ol>
                        
                          @foreach ($comite->tesis as $tesisc)
                           
                                <li>
                                     <a href="{{ Route('tesis.historial',$tesisc->id_tesis) }}">
                                    {{ $tesisc->nombre_tesis }}
                                    </a>
                                </li>
                            
                           
                            {{-- <li>
                               
                            </li> --}}
                         @endforeach
                   
                    </ol>
                </td>
                    
                <td>
                     @foreach ($comite->tesis->pluck('usuarios')->flatten()->unique('id_user') as $usuario)
        <p>{{ $usuario->generacion }}</p>
    @endforeach
                </td>
                <td>
                   
                    @foreach ($comite->usuarios as $usuario)
                       
                        {{ $usuario->nombre . " " . $usuario->apellidos }} 
                       
                       <ul>
                        @foreach (getUserRolesInComite($usuario->id_user, $comite->id_comite) as $rol)
                            
                                <li> <span class="badge bg-primary me-1">{{ ucfirst($rol) }}</span>   </li>
                            
                           
                             
                        @endforeach
                       </ul>    
                    @endforeach
                   
                </td>
                
              <td>    
    <ol>
        @foreach ($comite->tesis as $tesisc)
            <li>
                @php
                    switch (strtolower($tesisc->estado)) {
                        case 'en definición':
                        case 'en definicion': // por si falta tilde
                        $badgeClass = 'text-secondary';
                        break;
                        case 'en curso':
                            $badgeClass = 'text-primary ';
                            break;
                        case 'por evaluar':
                        case 'por evalular': // corregido posible typo
                            $badgeClass = 'text-warning ';
                            break;
                        case 'rechazada':
                            $badgeClass = 'text-danger';
                            break;
                        case 'aceptada':
                            $badgeClass = 'text-success';
                            break;
                        default:
                            $badgeClass = 'text-dark';
                            break;
                    }
                @endphp
                <span class="fw-bold {{ $badgeClass }}">{{ $tesisc->estado }}</span>
            </li>
        @endforeach
    </ol>
</td>
                
              
               <td class="d-flex flex-column align-items-center">
                   
                      {{-- <a href="{{ Route('tesis.avance.admin',$tesis->id_tesis) }}" class="btn mb-2 btn-sm text-light" style="background-color: #9FA6B2">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a> --}}
                            {{-- @foreach ($comite->tesis as $tesisc)
                                <a href="{{ route('tesis.avance.admin', $tesisc->id_tesis) }}" class="btn mb-2 btn-sm text-light" style="background-color: #9FA6B2">
                                    <i class="fa-regular fa-eye"></i> Ver {{ $loop->iteration }}
                                </a>
                                 
                            @endforeach --}}
                            <!-- Botón para abrir el modal -->
<button type="button"
    class="btn mb-2 btn-sm text-white"
    style="background-color:#9FA6B2"
    data-bs-toggle="modal"
    data-bs-target="#verTesisModal-{{ $comite->id_comite }}">
    <i class="fa-regular fa-eye"></i> Ver Tesis 
</button>
@include('Admin.Comites.Modals.AvanceTesisModal')
                        <a href="{{ route('plan.historial',$comite->id_comite) }}" class="btn mb-2 btn-sm text-light" style="background-color:var(--color-azul-principal)">
                            <i class="fa-solid fa-briefcase"></i> Plan de trabajo
                        </a>
                    
                        <a href="{{ Route("comites.edit",$comite->id_comite) }}" class="btn mb-2 btn-sm text-light" style="background-color: #355C7D">
                            <i class="fa-solid fa-pencil"></i> Modificar comité
                        </a>
                        
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clone-modal">
                            Clonar comite
                        </button>
                        @include('Admin.Comites.Modals.CloneModal')

                        
                        <!-- Botón -->
                        {{-- <button type="button" class="btn mb-2 btn-sm text-white" style="background-color:#d2ca37" data-bs-toggle="modal" data-bs-target="#miModal">
                            <i class="fa-solid fa-pencil"></i> Editar
                        </button> --}}
    

    <button type="button"
        class="btn mb-2 btn-sm text-white btn-editar"
        style="background-color:#d2ca37"
        data-bs-toggle="modal"
        data-bs-target="#edit-modal-{{ $comite->id_comite }}"
        data-idtesis="{{ $tesisc->id_tesis }}"
        data-titulotesis="{{ $tesisc->nombre_tesis }}"
        data-idalumno="{{ $tesisc->usuarios->first()->id_user ?? '' }}"
    >
        <i class="fa-solid fa-pencil"></i> Editar 
    </button>


    @include('Admin.Comites.Modals.EditModal')

                       
            </td>
            </tr>
           
             @endforeach
        </tbody>
    </table>
    </div>

   
    </div>
@endif


@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>
    <script>
          $(document).ready(function() {

             
              let boton=document.querySelector("#Btn_buscar")
              boton.addEventListener("click",()=>{
                let input = document.querySelector("#inputBuscar")
                let value = input.value.toLowerCase();
                let cards = document.querySelectorAll(".card")
                
                
                console.log(title)
                cards.forEach(card=>{
               
                    
                   
                })
                


              })
         });

        $("body").on("click",".delete > button",function(){
         
            event.preventDefault();
            console.log("boton clickeado");
            
            let formulario = $(this).closest("form");
            Swal.fire({
                title: "Eliminar Comite",
                text: "Estas a punto de eliminar este comite, esto no puede ser reversible ¿Estas seguro?",
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
    <script>
  $(document).ready(function() {
    $('#miTabla').DataTable();
  });
</script>
{{-- <script>
$(document).ready(function () {
    $('.btn-editar').on('click', function () {
        let idTesis = $(this).data('idtesis');
        let tituloTesis = $(this).data('titulotesis');
        let idAlumno = $(this).data('idalumno');

        $('#id_tesis').val(idTesis);
        $('#titulo_tesis').val(tituloTesis);
        $('#alumno').val(idAlumno);
    });
});

</script> --}}
@endsection