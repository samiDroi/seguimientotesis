<?php

namespace App\Http\Controllers;

use App\Models\Tesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowInfoUser extends Controller
{
    public function showTesis(){
        $currentLayout = Auth::user()->esCoordinador ? 'layouts.admin' : 'layouts.base'; 
        $tesisUser = Auth::user()->tesis ? Auth::user()->tesis : '';
        // Obtener los comités del usuario autenticado
          // Obtener los comités del usuario
        $comites = Auth::user()->comites;
        
        if ($comites->isNotEmpty()) {
            $tesisComite = Tesis::whereIn('id_tesis', function($query) use ($comites) {
                $query->select('id_tesis')
                      ->from('tesis_comite')  // La tabla intermedia
                      ->whereIn('id_comite', $comites->pluck('id_comite')); // Filtrar por los comités del usuario
            })->get();
        } else {
            $tesisComite = null; // Si el usuario no tiene comités, devolver una colección vacía
        }

       
       
        return view('User.TesisInfo',compact('tesisUser','currentLayout','tesisComite'));
        // if(Auth::user()->esCoordinador){
            
        // }else{
        //     return view('User.TesisInfo',compact('tesisUser'))->with('layout','layouts.base');
        // }
    }

    public function showComites(){
        // $comitesAuditaUser = DB::table('comite as c')
        //     ->join('usuarios_comite as uc','uc.id_comite','=','c.id_comite')
        //     ->join('usuarios as u','u.id_user','=','uc.id_user')
        //     ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.  id_usuario_comite')
        //     ->join()
        $comitesAuditaUser = DB::table('comite as c')
            ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')  // Relación comités - tesis
            ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis')  // Relación tesis - comités
            ->join('tesis_usuarios as tu', 't.id_tesis', '=', 'tu.id_tesis')  // Relación tesis - usuarios
            ->join('usuarios_comite as uc', 'c.id_comite', '=', 'uc.id_comite')  // Relación comités - usuarios
            ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user')
            ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc.id_usuario_comite')
            ->where('tu.id_user', Auth::user()->id_user)  // Filtrar tesis del usuario autenticado
            ->where('uc.id_user', '!=', Auth::user()->id_user)  // Excluir al usuario autenticado del comité
            ->select('c.*','u.*','ucr.rol_personalizado')  // Obtener datos del comité
            ->distinct()
            ->get()
            ->groupBy('id_comite');

        $comitesPerteneceUser = DB::table("usuarios_comite as uc")
            ->join("comite as c", "uc.id_comite", "=", "c.id_comite")  // Relación usuarios - comités
            ->join("usuarios_comite as uc2", "c.id_comite", "=", "uc2.id_comite")  // Relación para obtener otros usuarios del comité
            ->join("usuarios as u", "uc2.id_user", "=", "u.id_user")  // Relación usuarios_comite - usuarios
            ->join('usuarios_comite_roles as ucr','ucr.id_usuario_comite','=','uc2.id_usuario_comite')
            ->join('roles as r','r.id_rol','=','ucr.id_rol')
            ->where("uc.id_user", Auth::user()->id_user)  // Filtrar por comités donde el usuario pertenece
            ->select('c.*', 'u.*','uc.*','ucr.rol_personalizado')  // Obtener información del comité y los usuarios
            ->distinct()
            ->get()
            ->groupBy('id_comite');

            return view('home.LateralPanel.Comites',compact('comitesAuditaUser','comitesPerteneceUser'));

    }

    public function showUnidad(){
        $usuarioId = Auth::user()->id_user;

        // Obtener los programas del usuario desde la tabla pivote `programas`
        $programas = DB::table('programa_academico as pa')
            ->join('usuarios_programa_academico as upa', 'pa.id_programa', '=', 'upa.id_programa')
            ->where('upa.id_user', $usuarioId)
            ->select('pa.id_programa', 'pa.nombre_programa', 'pa.id_unidad')
            ->get();
    
        // Obtener la unidad académica del primer programa (asumiendo que todos pertenecen a la misma)
        $unidadAcademica = null;
        if ($programas->isNotEmpty()) {
            $unidadAcademica = DB::table('unidad_academica')
                ->where('id_unidad', $programas->first()->id_unidad)
                ->select('id_unidad', 'nombre_unidad as nombre')
                ->first();
        }
    //     $usuario = Auth::user();

    //     // Obtener los programas del usuario
    //    $programas = $usuario->programas->with('unidad')->get();

    //     // Obtener la unidad académica del primer programa (asumiendo que todos pertenecen a la misma unidad)
    //     $unidadAcademica = $programas->first()?->unidad;

        return view('home.LateralPanel.Unidad',compact('programas','unidadAcademica'));

    }
}
