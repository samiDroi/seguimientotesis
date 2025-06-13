@extends('layouts.admin')
@section('content')
<h1 class="text-center mt-4">Lista de Comités</h1>


<div class="container  py-5">

<div class=" row mb-5 ">
    <div class="col-4 text-start "><input id="inputBuscar" class="ms-2 form-control" placeholder="Buscar Comite" type="text"> 
</div>
<div class="col-4"> 
<button class="btn btn-secondary btn-sm  mt-1" id="Btn_buscar" >Buscar</button>
</div>
    <div class="col-4 text-end"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearComiteModal">
        Crear Comité
    </button></div>
</div>

@include('Admin.Comites.Create')
@if ($comites->isEmpty())
    <div class="px-5">
       
         <span class="fs-3 fw-bold  row">No hay comites registrados por el momento  </span>
         <span class="fs-5 row">
          Si desea registrar un comite de click en  el boton "crear nuevo comite" en la parte superior.
          </span>
    </div>
@else

<table class="table table-bordered table align-middle    ">
        <thead class="table-primary" >
            <tr>
                <th class="col ">Alumno</th>
                <th class="col ">Nombre Tesis </th>
                <th class="col ">Generacion</th>
                <th class="col ">Roles</th>
                <th class="col ">Estado</th>
                <th class="col">Acciones</th>
            </tr>
        </thead>
        <tbody class="">
            @forEach($comites as $comite) 
            <tr>
                <td>
    @foreach ($comite->tesis->pluck('usuarios')->flatten()->unique('id_user') as $usuario)
        <p>{{ $usuario->nombre }}</p>
    @endforeach
                <td>
                    <ol>
                        
                          @foreach ($comite->tesis as $tesis)
                            <li>
                                {{ $tesis->nombre_tesis }}
                            </li>
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
                        @foreach (getUserRolesInComite($usuario->id_user, $comite->id_comite) as $rol)
                            <span class="badge bg-primary me-1">{{ ucfirst($rol) }}</span>    
                        @endforeach
                        
                    @endforeach
                </td>
                
              <td>    
    <ol>
        @foreach ($comite->tesis as $tesis)
            <li>
                @php
                    switch (strtolower($tesis->estado)) {
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
                <span class="fw-bold {{ $badgeClass }}">{{ $tesis->estado }}</span>
            </li>
        @endforeach
    </ol>
</td>
                
              
               <td class="d-flex flex-column align-items-center">
                     {{-- <button class="btn  mb-2 btn-sm text-light" style="background-color: #9FA6B2"><i class="fa-regular fa-eye"></i> Ver</button>
                    <button class="btn  mb-2 btn-sm text-light" style="background-color: #4C6EF5"> <i class="fa-solid fa-briefcase"></i> Plan de trabajo</button>
                    <button class="btn  mb-2 btn-sm text-light" style="background-color:#355C7D"><i class="fa-solid fa-pencil"></i> Modificar comite</button>
                      <button class="btn  mb-2 btn-sm text-white" style="background-color:#d2ca37"><i class="fa-solid fa-pencil"></i> Editar</button> --}}
                      <a href="" class="btn mb-2 btn-sm text-light" style="background-color: #9FA6B2">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('plan.index', $comite->id_comite) }}" class="btn mb-2 btn-sm text-light" style="background-color: #4C6EF5">
                            <i class="fa-solid fa-briefcase"></i> Plan de trabajo
                        </a>
                        <a href="" class="btn mb-2 btn-sm text-light" style="background-color:#355C7D">
                            <i class="fa-solid fa-pencil"></i> Modificar comité
                        </a>
                        <a href="" class="btn mb-2 btn-sm text-white" style="background-color:#d2ca37">
                            <i class="fa-solid fa-pencil"></i> Editar
                        </a>

            </td>
            </tr>
             @endforeach
        </tbody>
    </table>

   
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
@endsection