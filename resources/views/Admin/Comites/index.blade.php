@extends('layouts.admin')
{{-- @section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endsection  --}}
@section('content')



<div class="container mt-2 ">
    <x-Titulos text="Lista de comites"/>


    <div class="text-end mt-4 mb-3 ">
    {{-- <x-boton-modal target=crearComiteModal text="Crear comite" clases=btn-crear-comite icon=user-group/> --}}
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
<div class="container table-wrapper">
<table class="custom-table table-responsive mt-4" id="miTabla" >
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
                <x-boton-modal clases="boton-ver-tesis" target="verTesisModal-{{ $comite->id_comite }}" icon="eye" text="Ver tesis"/>
                   
                      {{-- <a href="{{ Route('tesis.avance.admin',$tesis->id_tesis) }}" class="btn mb-2 btn-sm text-light" style="background-color: #9FA6B2">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a> --}}
                            {{-- @foreach ($comite->tesis as $tesisc)
                                <a href="{{ route('tesis.avance.admin', $tesisc->id_tesis) }}" class="btn mb-2 btn-sm text-light" style="background-color: #9FA6B2">
                                    <i class="fa-regular fa-eye"></i> Ver {{ $loop->iteration }}
                                </a>
                                 
                            @endforeach --}}

 
@include('Admin.Comites.Modals.AvanceTesisModal')
                       
                        <a href="{{ route('plan.historial',$comite->id_comite) }}" class="a-personalizado mt-2 text-decoration-none">
                            <i class="fa-solid fa-briefcase mt-2"></i> Plan de trabajo
                        </a>
                        
                        
                    
                        <a href="{{ Route("comites.edit",$comite->id_comite) }}" class="a-modificar text-decoration-none mt-2" >
                            <i class="fa-solid fa-pencil mt-2"></i> Modificar comité
                        </a>
                        
                        <button type="button" class="a-clonar mt-2" data-bs-toggle="modal" data-bs-target="#clone-modal-{{ $comite->id_comite }}">
                         <i class="fa-solid fa-clone"></i> Clonar comite
                        </button>
                        @include('Admin.Comites.Modals.CloneModal')

                        
                        <!-- Botón -->
                        {{-- <button type="button" class="btn mb-2 btn-sm text-white" style="background-color:#d2ca37" data-bs-toggle="modal" data-bs-target="#miModal">
                            <i class="fa-solid fa-pencil"></i> Editar
                        </button> --}}
    

    <button type="button"
        class="boton-editar2 mt-2"
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
