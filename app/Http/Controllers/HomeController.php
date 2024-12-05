<?php

namespace App\Http\Controllers;

use App\Models\ProgramaAcademico;
use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

class HomeController extends Controller
{
    public function index(){
        $programas = Auth::user()->programas;
        $comites = Auth::user()->comites;
        $tesisUsuario = DB::table("tesis as t")
                    ->join("tesis_usuarios as tu","t.id_tesis","tu.id_tesis")
                    ->join("usuarios as u","u.id_user","tu.id_user")
                    ->where("u.id_user",Auth::user()->id_user)
                    ->select("t.*")
                    ->get();
        //dd($tesisUsuario);
        
        return view("home/index",compact("programas","comites","tesisUsuario"));
    }

    public function logout(){
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/login');
    }

   public function showComite(){
        $comites = Auth::user()->comites;
        // DB::table('unidad_academica as ua')
        //         ->join('programa_academico as pa',"ua.id_programa","pa.id_programa")
        //         ->where();
        $programas = Auth::user()->programas;
        $unidades = [];
        foreach ($programas as $programa) {
            $unidades[] = UnidadAcademica::where('id_unidad',$programa->id_unidad); // Almacena el ID del programa
        }
        
        


        return view("user.comite",compact("comites","programas","unidades"));
    }
}
