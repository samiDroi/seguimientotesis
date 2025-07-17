<form action={{ $ruta }} method="POST" style="display:inline;">
      @csrf
      @method("DELETE")
         <button type="submit" class="boton-eliminar" >
           Eliminar <i class="fa-solid fa-trash "></i>
       </button>
</form>