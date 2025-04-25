<?php

use App\Models\Tesis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function hola(){
    return "hola desde un helper";
}
function comprobarRolComite($rol, $comite){
    return DB::table("usuarios_comite as uc")
        ->join("comite as c", "uc.id_comite", "=", "c.id_comite")
        ->join("tesis_comite as tc", "c.id_comite", "=", "tc.id_comite")
        ->join("tesis as t", "tc.id_tesis", "=", "t.id_tesis")
        ->where("uc.id_user", Auth::user()?->id_user)
        ->where("c.id_comite",$comite) // Filtra por el usuario autenticado
        ->where("uc.rol",$rol)
        ->count();
    
    //return Auth::user()?->roles->where('nombre_rol',$rol)->where('id_comite',$comite)->count();
}
function getInfoComentarioAvance($id_requerimiento){
    return DB::table('comentario_avance as ca')
    ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
    ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
    ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
    ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
    ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
    ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
    ->where('ctr.id_requerimiento',$id_requerimiento)
    ->select(
        'ca.*',
        'u.nombre as usuario_nombre',
        'u.apellidos as usuario_apellidos',
        'uc.rol as usuario_rol',
        'ca.comentario as contenido',
        'ctr.*'
    )
    ->get();
}

function getAlumnoAvance($id_requerimiento){
    return DB::table('comite_tesis_requerimientos as ctr')
    ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite') // Conectar requerimiento con tesis-comité
    ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis') // Conectar tesis-comité con tesis
    ->join('tesis_usuarios as tu','tu.id_tesis','=','t.id_tesis')
    ->join('usuarios as u', 'tu.id_user', '=', 'u.id_user') // Obtener usuario asignado a la tesis
    ->where('ctr.id_requerimiento', $id_requerimiento) // Filtrar por el requerimiento específico
    ->select(
        'u.id_user',
        'u.nombre as usuario_nombre',
        't.id_tesis',
        'ctr.*'
    )
    ->get();

    
}

function comprobarIsInComite($comite){
    return DB::table("usuarios_comite as uc")
    ->join("comite as c", "uc.id_comite", "=", "c.id_comite")
    ->join("tesis_comite as tc", "c.id_comite", "=", "tc.id_comite")
    ->join("tesis as t", "tc.id_tesis", "=", "t.id_tesis")
    ->where("uc.id_user", Auth::user()?->id_user)
    ->where("c.id_comite",$comite) // Filtra por el usuario autenticado
    ->count();
}

function getDirectores(){
    return DB::table("tesis as t")
        //junta los comites con las tesis
        ->join("tesis_comite as tc", "t.id_tesis", "=", "tc.id_tesis")
        ->join("comite as c", "tc.id_comite", "=", "c.id_comite")
        //junta los comites con los usuarios
        ->join("usuarios_comite as uc", "c.id_comite", "=", "uc.id_comite")
        ->join("usuarios as u", "uc.id_user", "=", "u.id_user")
        ->where("uc.rol", "DIRECTOR") // Se filtra para obtener solo el Director
        ->select("t.id_tesis","u.*") // Se obtienen todos los datos del usuario con rol de Director y su tesis afiliada
        ->get();
}

function getTesisByUserProgram(){
    // return Tesis::with('comites')  // Cargar la relación comites
    // ->join('tesis_comite as tc', 'tesis.id_tesis', '=', 'tc.id_tesis')
    // ->join('usuarios_comite as uc', 'tc.id_comite', '=', 'uc.id_comite')
    // ->join('usuarios_programa_academico as upa', 'uc.id_user', '=', 'upa.id_user')
    // ->whereIn('upa.id_programa', Auth::user()->programas->pluck('id_programa'))
    // ->select('tesis.*')
    // ->distinct()
    // ->get();
    return Tesis::with('comites')
    //->table('tesis as t')
    ->join('tesis_programa_academico as tap', 'tesis.id_tesis', '=', 'tap.id_tesis')
    ->join('programa_academico as pa', 'pa.id_programa', '=', 'tap.id_programa')
    ->join('usuarios_programa_academico as upa', 'upa.id_programa', '=', 'pa.id_programa')
    ->whereIn('pa.id_programa', Auth::user()->programas->pluck("id_programa")->toArray())  // Filtrar por el usuario autenticado
    ->select('tesis.*')  // Seleccionar todas las columnas de tesis
    ->groupBy('tesis.id_tesis')
    ->get();
}
function getRequerimientos($id_requerimiento){
    return DB::table('comite_tesis_requerimientos as ctr')
    ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite') // Relaciona con tesis_comite
    ->where('ctr.id_requerimiento', $id_requerimiento) // Filtra por el ID del requerimiento
    ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis') // Relaciona con tesis
    ->select('ctr.*') // Selecciona todos los requerimientos
    ->get();
}

function getTesisByUserProgramAndComite() {
    return Tesis::with('comites')  // Cargar la relación comites
        ->join('tesis_comite as tc', 'tesis.id_tesis', '=', 'tc.id_tesis')
        ->join('usuarios_comite as uc', 'tc.id_comite', '=', 'uc.id_comite')
        ->join('usuarios_programa_academico as upa', 'uc.id_user', '=', 'upa.id_user')
        ->where('uc.id_user', Auth::user()?->id_user) // Filtrar por usuario autenticado en comites
        ->whereIn('upa.id_programa', Auth::user()->programas->pluck('id_programa')) // Filtrar por programas del usuario
        ->select('tesis.*')
        ->distinct()
        ->get();
}

function isDirector(){
    return DB::table('usuarios_comite as uc')
    ->join('comite as c', 'uc.id_comite', '=', 'c.id_comite')
    ->join('usuarios as u', 'u.id_user', '=', 'uc.id_user')  // Relación usuarios - usuarios_comite
    ->where('uc.id_user', Auth::user()->id_user)  // Filtrar por el id_user
    ->where('uc.rol', 'DIRECTOR')  // Verifica que el rol sea 'DIRECTOR'
    ->count();  // Contar los registros que coinciden con la condición

}

// function getUserRolesInComite($userId, $comiteId)
// {
//     return DB::table('usuarios_comite_roles as ucr')
//         ->join('roles as r', 'ucr.id_rol', '=', 'r.id_rol')
//         ->join('usuarios_comite as uc', function($join) use ($userId, $comiteId) {
//             $join->on('ucr.id_usuario_comite', '=', 'uc.id_usuario_comite')
//                 ->where('uc.id_user', '=', $userId)
//                 ->where('uc.id_comite', '=', $comiteId);
//         })
//         ->select('ucr.rol_personalizado')
//         ->get()
//         ->pluck('ucr.rol_personalizado');
// }
function getUserRolesInComite($userId, $comiteId)
{
    return DB::table('usuarios_comite_roles as ucr')
        ->join('usuarios_comite as uc', function($join) use ($userId, $comiteId) {
            $join->on('ucr.id_usuario_comite', '=', 'uc.id_usuario_comite')
                ->where('uc.id_user', '=', $userId)
                ->where('uc.id_comite', '=', $comiteId);
        })
        ->select('ucr.rol_personalizado')
        ->get()
        ->pluck('rol_personalizado')
        ->filter(); // Elimina valores nulos o vacíos
}



