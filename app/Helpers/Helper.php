<?php

use App\Models\Comite;
use App\Models\Tesis;
use App\Models\Usuarios;
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

function userHasPermisos($permisos,$id_comite){
    return getPermisos($permisos,$id_comite) || null;
}

function getPermisos($permisos,$id_comite){
    return DB::table('usuarios as u')
        ->join('usuarios_comite as uc','uc.id_user','=','u.id_user')
        ->join('comite as c','c.id_comite','=','uc.id_comite')
        ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
        ->join('roles as r','r.id_rol','=','ucr.id_rol')
        ->join('roles_permisos as rp','rp.id_rol','=','r.id_rol')
        ->join('permisos as p','p.id_permisos','=','rp.id_permisos')
        ->where('p.clave',$permisos)
        ->where('c.id_comite',$id_comite)
        ->where('u.id_user',Auth::user()->id_user)
        ->select(
            'p.*',
            'r.*',
            'ucr.rol_personalizado'
        )
        ->get();
}
// function getInfoComentarioAvance($id_requerimiento){
//     return DB::table('comentario_avance as ca')
//     ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
//     ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
//     ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
//     ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
//     ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
//     ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
//     ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
//     ->where('ctr.id_requerimiento',$id_requerimiento)
//     ->select(
//         'ca.*',
//         'u.nombre as usuario_nombre',
//         'u.apellidos as usuario_apellidos',
//         'ucr.rol_personalizado as usuario_rol',
//         'ca.comentario as contenido',
//         'ctr.*'
//     )
//     ->get();
// }
function getInfoComentarioAvance($id_requerimiento, $userId){
    return DB::table('comentario_avance as ca')
        ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
        ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
        ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
        ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
        ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
        ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
        // Subquery de roles
        ->leftJoin(DB::raw('(SELECT uc.id_usuario_comite, GROUP_CONCAT(DISTINCT ucr.rol_personalizado SEPARATOR ", ") as roles
                            FROM usuarios_comite_roles ucr
                            JOIN usuarios_comite uc ON ucr.id_usuario_comite = uc.id_usuario_comite
                            GROUP BY uc.id_usuario_comite
                        ) as roles_table'), 'uc.id_usuario_comite', '=', 'roles_table.id_usuario_comite')
        ->where('at.id_requerimiento', $id_requerimiento)
        ->where('u.id_user',$userId)
        ->select(
            'ca.*',
            'u.nombre as usuario_nombre',
            'u.apellidos as usuario_apellidos',
            'roles_table.roles as usuario_roles',
            'ca.comentario as contenido',
            'ctr.*'
        )
         ->orderBy('ca.created_at', 'desc')// ->groupBy('ca.id_avance_tesis')  // Esto asegura que no se repitan
        ->first();
}
// function getInfoComentarioAvance($id_requerimiento){
//     return DB::table('comentario_avance as ca')
//         ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
//         ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
//         ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
//         ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
//         ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
//         ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
//         // Subquery de roles
//         ->leftJoin(DB::raw('(SELECT uc.id_usuario_comite, GROUP_CONCAT(DISTINCT ucr.rol_personalizado SEPARATOR ", ") as roles
//                             FROM usuarios_comite_roles ucr
//                             JOIN usuarios_comite uc ON ucr.id_usuario_comite = uc.id_usuario_comite
//                             GROUP BY uc.id_usuario_comite
//                         ) as roles_table'), 'uc.id_usuario_comite', '=', 'roles_table.id_usuario_comite')
//         ->where('at.id_requerimiento', $id_requerimiento)
//         ->select(
//             'ca.*',
//             'u.nombre as usuario_nombre',
//             'u.apellidos as usuario_apellidos',
//             'roles_table.roles as usuario_roles',
//             'ca.comentario as contenido',
//             'ctr.*'
//         )
//          ->orderBy('ca.created_at', 'desc')// ->groupBy('ca.id_avance_tesis')  // Esto asegura que no se repitan
//         ->first();
// }
// function getInfoComentarioAvance($id_requerimiento)
// {
//     return DB::table('comentario_avance as ca')
//         ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
//         ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
//         ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
//         ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
//         ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
//         ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
//         // Subquery de roles
//         ->leftJoin(DB::raw('(SELECT uc.id_usuario_comite, GROUP_CONCAT(DISTINCT ucr.rol_personalizado SEPARATOR ", ") as roles
//                             FROM usuarios_comite_roles ucr
//                             JOIN usuarios_comite uc ON ucr.id_usuario_comite = uc.id_usuario_comite
//                             GROUP BY uc.id_usuario_comite
//                         ) as roles_table'), 'uc.id_usuario_comite', '=', 'roles_table.id_usuario_comite')
//         ->where('at.id_requerimiento', $id_requerimiento)
//         ->select(
//             'ca.id_avance_tesis',
//             'ca.id_user',
//             'u.nombre as usuario_nombre',
//             'u.apellidos as usuario_apellidos',
//             'roles_table.roles as usuario_roles',
//             'ca.comentario as contenido',
//             'ca.created_at',
//             'ctr.*'
//         )
//         ->orderBy('ca.created_at', 'desc')
//         ->get()
//         ->groupBy('id_user')   // agrupa por usuario
//         ->map(function ($items) {
//             return $items->first(); // obtiene el último comentario por usuario
//         })
//         ->values();
// }


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
 
    return Tesis::with('comites')
    ->join('tesis_comite as tc', 'tc.id_tesis', '=', 'tesis.id_tesis')
    ->join('comite as c', 'c.id_comite', '=', 'tc.id_comite')
    ->join('programa_academico as pa', 'pa.id_programa', '=', 'c.id_programa')
    ->whereIn('pa.id_programa', Auth::user()->programas->pluck('id_programa')->toArray())
    ->select('tesis.*')
    ->distinct()
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
    ->join('usuarios as u', 'u.id_user', '=', 'uc.id_user')
    ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
    ->join('roles as r','r.id_rol','=','ucr.id_rol')  // Relación usuarios - usuarios_comite
    ->where('uc.id_user', Auth::user()->id_user)  // Filtrar por el id_user
    ->where('r.nombre_rol', 'administrador')  // Verifica que el rol sea 'DIRECTOR'
    ->count();  // Contar los registros que coinciden con la condición

}
function isDirectorInComite($id_comite){
    return DB::table('usuarios_comite as uc')
    ->join('comite as c', 'uc.id_comite', '=', 'c.id_comite')
    ->join('usuarios as u', 'u.id_user', '=', 'uc.id_user')
    ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
    ->join('roles as r','r.id_rol','=','ucr.id_rol')  // Relación usuarios - usuarios_comite
    ->where('uc.id_user', Auth::user()->id_user)  // Filtrar por el id_user
    ->where('r.nombre_rol', 'administrador')  // Verifica que el rol sea 'DIRECTOR'
    ->where('c.id_comite',$id_comite)
    ->count();  // Contar los registros que coinciden con la condición

}

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

function getRolesComite($comiteId)
{
    return DB::table('usuarios_comite_roles as ucr')
        ->join('usuarios_comite as uc', 'ucr.id_usuario_comite', '=', 'uc.id_usuario_comite')
        ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user') // Asumiendo que es 'id_user'
        ->where('uc.id_comite', $comiteId)
        ->select('u.id_user', 'u.nombre','u.apellidos','ucr.rol_personalizado')
        ->get();
}

function getEstadosTesisConConteo(){
    return DB::table('tesis')
        ->select('estado', DB::raw('COUNT(*) as total'))
        ->groupBy('estado')
        ->get();
}

function getAlumnosPorPrograma(){
    return DB::table('usuarios as u')
        ->join('usuarios_programa_academico as upc','upc.id_user','=','u.id_user')
        ->join('programa_academico as p', 'upc.id_programa', '=', 'p.id_programa')
        ->select('p.nombre_programa', DB::raw('COUNT(*) as total_alumnos'))
        ->groupBy('p.nombre_programa')
        ->get();
}

function filterAlumnosPrograma(){
    $programaIds = Auth::user()->programas->pluck('id_programa');

     return Usuarios::whereHas('tipos', function ($q) {
        $q->where('nombre_tipo', 'alumno');
    })
    ->whereHas('programas', function ($q) use ($programaIds) {
        $q->whereIn('programa_academico.id_programa', $programaIds);
    })
    ->get();
    
}

function filterComiteProgramasAuth(){
    return Comite::with('programas')  
        ->whereHas('programas', function ($query) {
            $query->whereIn('id_programa', Auth::user()->programas->pluck('id_programa'));
        });
}
function getDirectorTesis(){
    return DB::table('tesis as t')
        ->join('tesis_comite as tc','tc.id_tesis','=','t.id_tesis')
        ->join('comite as c','c.id_comite','=','tc.id_comite')
        ->join('usuarios_comite as uc','uc.id_comite','=','c.id_comite')
        ->join('usuarios as u','u.id_user','=','uc.id_user')
        ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
        ->join('roles as r','r.id_rol','=','ucr.id_rol')
       ->leftJoin('comite_tesis_requerimientos as ctr', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite')

        ->where('uc.id_user',Auth::user()->id_user)
        ->where('r.nombre_rol','administrador')
        ->select('t.*','c.*','ctr.*','ucr.*','r.*','tc.id_tesis_comite as id_tc','ctr.descripcion as desc')
        ->orderBy('ctr.id_requerimiento','asc')
        ->get();
}

function getRolComite($id_comite){
     return DB::table('usuarios_comite as uc')
    ->join('comite as c', 'uc.id_comite', '=', 'c.id_comite')
    ->join('usuarios as u', 'u.id_user', '=', 'uc.id_user')
    ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
    ->join('roles as r','r.id_rol','=','ucr.id_rol')  // Relación usuarios - usuarios_comite
    ->where('uc.id_user', Auth::user()->id_user)  // Filtrar por el id_user
    ->where('uc.id_comite',$id_comite)
    ->select('ucr.rol_personalizado','uc.id_comite') // Verifica que el rol sea 'DIRECTOR'
    ->get();  // Contar los registros que coinciden con la condición
}

function isEstudiante(){
    return DB::table('usuarios as u')
    ->join('tesis_usuarios as tu','tu.id_user','=','u.id_user')
    ->join('tesis as t','t.id_tesis','=','tu.id_tesis')
    ->where('u.id_user',Auth::user()->id_user)
    ->where('esCoordinador',0)
    ->count();
}
function getUsersComiteAudita(){
    return DB::table('comite as c')
        ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite') // Comités de tesis
        ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis') // Tesis
        ->join('tesis_usuarios as tu', 't.id_tesis', '=', 'tu.id_tesis') // Usuarios de la tesis
        ->join('usuarios_comite as uc', 'c.id_comite', '=', 'uc.id_comite') // Usuarios del comité
        ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user') // Datos del usuario
        ->join('usuarios_comite_roles as ucr', 'ucr.id_usuario_comite', '=', 'uc.id_usuario_comite') // Rol personalizado
        ->where('tu.id_user', Auth::user()->id_user) // Solo comités que auditan la tesis del usuario actual
        ->where('uc.id_user', '!=', Auth::user()->id_user) // Excluir al usuario autenticado
        ->select(
            'c.id_comite',
            'c.nombre_comite',
            'u.id_user',
            'u.nombre',
            'u.apellidos',
            'u.correo_electronico',
            'ucr.rol_personalizado'
        )
        ->distinct()
        ->get();
            // return DB::table('comite as c')
            // ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')  // Relación comités - tesis
            // ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis')  // Relación tesis - comités
            // ->join('tesis_usuarios as tu', 't.id_tesis', '=', 'tu.id_tesis')  // Relación tesis - usuarios
            // ->join('usuarios_comite as uc', 'c.id_comite', '=', 'uc.id_comite')  // Relación comités - usuarios
            // ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user')
            // ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
            // ->where('tu.id_user', Auth::user()->id_user)  // Filtrar tesis del usuario autenticado
            // ->where('uc.id_user', '!=', Auth::user()->id_user)  // Excluir al usuario autenticado del comité
            // ->select('c.*','u.*','u.nombre as nombre','u.apellidos as apellidos','ucr.rol_personalizado')  // Obtener datos del comité
            // ->distinct()
            // ->get()
            // ->groupBy('id_comite');
}

