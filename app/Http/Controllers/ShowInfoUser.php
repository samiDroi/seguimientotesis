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
        //dd($comites->isNotEmpty());
    // Si el usuario tiene comités, obtener las tesis relacionadas a esos comités
    //dd($comites);
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
        $comitesAuditaUser = DB::table('comite as c')
            ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')  // Relación comités - tesis
            ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis')  // Relación tesis - comités
            ->join('tesis_usuarios as tu', 't.id_tesis', '=', 'tu.id_tesis')  // Relación tesis - usuarios
            ->join('usuarios_comite as uc', 'c.id_comite', '=', 'uc.id_comite')  // Relación comités - usuarios
            ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user')
            ->where('tu.id_user', Auth::user()->id_user)  // Filtrar tesis del usuario autenticado
            ->where('uc.id_user', '!=', Auth::user()->id_user)  // Excluir al usuario autenticado del comité
            ->select('c.*','u.*','uc.rol')  // Obtener datos del comité
            ->distinct()
            ->get()
            ->groupBy('id_comite');

        $comitesPerteneceUser = DB::table("usuarios_comite as uc")
            ->join("comite as c", "uc.id_comite", "=", "c.id_comite")  // Relación usuarios - comités
            ->join("usuarios_comite as uc2", "c.id_comite", "=", "uc2.id_comite")  // Relación para obtener otros usuarios del comité
            ->join("usuarios as u", "uc2.id_user", "=", "u.id_user")  // Relación usuarios_comite - usuarios
            ->where("uc.id_user", Auth::user()->id_user)  // Filtrar por comités donde el usuario pertenece
            ->select('c.*', 'u.*','uc.rol')  // Obtener información del comité y los usuarios
            ->distinct()
            ->get()
            ->groupBy('id_comite');

            return view('home.LateralPanel.Comites',compact('comitesAuditaUser','comitesPerteneceUser'));

    }
}
