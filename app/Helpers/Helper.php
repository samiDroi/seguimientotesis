<?php

use Illuminate\Support\Facades\Auth;

function hola(){
    return "hola desde un helper";
}
function comprobarRolComite($rol, $comite){
    return Auth::user()?->roles->where('nombre_rol',$rol)->where('id_comite',$comite)->count();
}