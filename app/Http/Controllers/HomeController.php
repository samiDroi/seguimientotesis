<?php

namespace App\Http\Controllers;

use App\Models\AvanceTesis;
use App\Models\ProgramaAcademico;
use App\Models\Tesis;
use App\Models\TesisComite;
use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $programas = $user->programas;
        $comites = $user->comites;

        $rolesUnicos = $this->obtenerRolesUnicosDelUsuario($user->id_user);
        
        $tesisUsuario = collect();
        $rolSeleccionado = null;

        if ($request->has('rol_id')) {
            $rolSeleccionado = $request->rol_id;
            $resultado = $this->obtenerTesisYComitesPorRol($user->id_user, $rolSeleccionado);
            $tesisUsuario = $resultado['tesis'];
        } else {
            $tesisUsuario = DB::table("tesis as t")
                ->join("tesis_usuarios as tu", "t.id_tesis", "=", "tu.id_tesis")
                ->join("usuarios as u", "u.id_user", "=", "tu.id_user")
                ->where("u.id_user", $user->id_user)
                ->select("t.*")
                ->get();
            
            $tesisUsuario = Tesis::with('comites')
                ->whereIn('id_tesis', $tesisUsuario->pluck('id_tesis'))
                ->get();
        }

        $tesisComites = TesisComite::with(["tesis", "comite", "requerimientos.avances"])->get();

        $tesisDeComite = Tesis::with(['comites'])
            ->whereIn('id_tesis', function ($query) use ($comites) {
                $query->select('id_tesis')
                    ->from('tesis_comite')
                    ->whereIn('id_comite', $comites->pluck('id_comite'));
            })->get();

        $rolesUsuarioActual = [];
        foreach ($comites as $comite) {
            $rol = getRolComite($comite->id_comite)->first();
            $rolesUsuarioActual[$comite->id_comite] = $rol ? $rol->rol_personalizado : 'Miembro';
        }

        return view("home.index", compact(
            "programas", 
            "comites", 
            "tesisUsuario", 
            "tesisComites", 
            "tesisDeComite",
            "rolesUsuarioActual",
            "rolesUnicos",
            "rolSeleccionado"
        ));
    }

    public function obtenerRolesUnicosDelUsuario($userId)
    {
        return DB::table('usuarios_comite_roles as ucr')
            ->join('usuarios_comite as uc', 'uc.id_usuario_comite', '=', 'ucr.id_usuario_comite')
            ->join('roles as r', 'r.id_rol', '=', 'ucr.id_rol')
            ->where('uc.id_user', $userId)
            ->orWhere('ucr.id_user_creador', $userId)
            ->select('r.id_rol', 'r.nombre_rol', 'uc.id_comite')
            ->distinct()
            ->get()
            ->groupBy('id_rol')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'id_rol' => $first->id_rol,
                    'nombre_rol' => $first->nombre_rol,
                    'comites' => $items->pluck('id_comite')->unique()->values()
                ];
            })
            ->values();
    }

    public function obtenerTesisYComitesPorRol($userId, $rolId)
    {
        $comiteIds = DB::table('usuarios_comite_roles as ucr')
            ->join('usuarios_comite as uc', 'uc.id_usuario_comite', '=', 'ucr.id_usuario_comite')
            ->where('ucr.id_rol', $rolId)
            ->where(function ($query) use ($userId) {
                $query->where('uc.id_user', $userId)
                      ->orWhere('ucr.id_user_creador', $userId);
            })
            ->distinct()
            ->pluck('uc.id_comite');

        $comites = \App\Models\Comite::whereIn('id_comite', $comiteIds)->get();

        $tesis = Tesis::whereHas('comites', function ($query) use ($comiteIds) {
            $query->whereIn('comite.id_comite', $comiteIds);
        })->with(['comites', 'usuarios'])->get();

        return [
            'tesis' => $tesis,
            'comites' => $comites
        ];
    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/login');
    }

    public function showComite()
    {
        $comites = Auth::user()->comites;
        $programas = Auth::user()->programas;
        $unidades = [];
        foreach ($programas as $programa) {
            $unidades[] = UnidadAcademica::where('id_unidad', $programa->id_unidad);
        }
        return view("user.comite", compact("comites", "programas", "unidades"));
    }
}