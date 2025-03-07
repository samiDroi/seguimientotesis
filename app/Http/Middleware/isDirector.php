<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class isDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $isDirector = DB::table("usuarios_comite as uc")
        // ->join("comite as c", "uc.id_comite", "=", "c.id_comite")
        // ->join("tesis_comite as tc", "c.id_comite", "=", "tc.id_comite")
        // ->join("tesis as t", "tc.id_tesis", "=", "t.id_tesis")
        // ->where("uc.id_user", Auth::user()->id_user) 
        // ->where("uc.rol","DIRECTOR")
        // ->count();
        $isDirector = DB::table('usuarios_comite as uc')
            ->join('comite as c', 'uc.id_comite', '=', 'c.id_comite')
            ->join('usuarios as u', 'u.id_user', '=', 'uc.id_user')  // Relación usuarios - usuarios_comite
            ->where('uc.id_user', Auth::user()->id_user)  // Filtrar por el id_user
            ->where('uc.rol', 'DIRECTOR')  // Verifica que el rol sea 'DIRECTOR'
            ->count();  // Contar los registros que coinciden con la condición

        // dd(Auth::user()->id_user);
        //dd($isDirector);
        // Verifica si el usuario está autenticado y si es director de algún comité
        if ($isDirector == 0) {
           return redirect()->back();
        }
        return $next($request);
    }
}
