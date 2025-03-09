
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
    <div class="col-4 text-end"><a class="text-decoration-none btn btn-outline-primary me-5" href="{{ Route("comites.store")}}">Crear nuevo comite</a></div>
</div>


@if ($comites->isEmpty())
    <div class="px-5">
       
         <span class="fs-3 fw-bold  row">No hay comites registrados por el momento  </span>
         <span class="fs-5 row">
          Si desea registrar un comite de click en  el boton "crear nuevo comite" en la parte superior.
          </span>
    </div>
@else
    <div class="d-flex gap-4 ms-4">
    @foreach ($comites as $comite)
        <div class="card px-4 py-2 border-black border-2 ">
    
        <h2 class="text-center title">{{ $comite->nombre_comite }}</h2>
        <div class="row">

        <form action="{{ route('comites.clone', $comite->id_comite) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary col-12 mt-2">Clonar Comité</button>
        </form>


        <form action="{{ route('comites.destroy', $comite->id_comite) }}" method="POST" style="display:inline-block;" class="delete">
            @csrf
            <button type="button" class="btn btn-danger col-12 mt-2">
                Eliminar Comité
            </button>
        </form>
       

        <div class=" mt-2"><a class=" text-decoration-none" href="{{ Route("comites.store",$comite->id_comite) }}">Editar comite </a></div>
        
       
        
        </div>
        



      
        <table class="mt-3">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
            
                @foreach ($comite->usuarios as $usuario)
                    <tr>
                        {{-- <td>{{ $comite->nombre_comite }}</td> --}}
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellidos }}</td>
                        <td class="text-primary fw-bold">{{ ucfirst($usuario->pivot->rol) }}</td> <!-- 'pivot' te da acceso al campo 'rol' de la tabla pivot -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <details>
            <summary class="text-center mt-3">Tesis asignadas</summary>
            @if ($comite->tesis->isNotEmpty())
                <ul >
                    @foreach ($comite->tesis as $tesis)
                        <li>{{ $tesis->nombre_tesis }}</li>
                    @endforeach
                </ul>
            @else
                <p>No hay tesis asignadas a este comité.</p>
            @endif
        </details>
        </div>
    @endforeach
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












