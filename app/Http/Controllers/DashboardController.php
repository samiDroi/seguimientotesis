<?php

namespace App\Http\Controllers;

use App\Models\Comite;
use App\Models\Rol;
use App\Models\Tesis;
use App\Models\Usuarios;
use App\Models\UsuariosComite;
use App\Models\UsuariosComiteRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $rolesUnicos = $this->obtenerRolesUnicosDelUsuario($user->id_user);
        
        $tesis = collect();
        $comites = collect();
        
        if ($request->has('rol_id')) {
            $resultado = $this->obtenerTesisYComitesPorRol($user->id_user, $request->rol_id);
            $tesis = $resultado['tesis'];
            $comites = $resultado['comites'];
        }
        
        return view('dashboard.index', compact('rolesUnicos', 'tesis', 'comites', 'user'));
    }

    public function obtenerRolesUnicosDelUsuario($userId)
    {
        return DB::table('usuarios_comite_roles as ucr')
            ->join('usuarios_comite as uc', 'uc.id_usuario_comite', '=', 'ucr.id_usuario_comite')
            ->join('roles as r', 'r.id_rol', '=', 'ucr.id_rol')
            ->where('uc.id_user', $userId)
            ->orWhere('ucr.id_user_creador', $userId)
            ->select(
                'r.id_rol',
                'r.nombre_rol',
                'uc.id_comite',
                'uc.id_usuario_comite'
            )
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

        $comites = Comite::whereIn('id_comite', $comiteIds)->get();

        $tesis = Tesis::whereHas('comites', function ($query) use ($comiteIds) {
            $query->whereIn('comite.id_comite', $comiteIds);
        })->with(['comites', 'usuarios'])->get();

        return [
            'tesis' => $tesis,
            'comites' => $comites
        ];
    }

    public function obtenerTesisFiltradas(Request $request)
    {
        if (!$request->has('rol_id')) {
            return response()->json(['error' => 'Parámetro rol_id requerido'], 400);
        }

        $user = Auth::user();
        $resultado = $this->obtenerTesisYComitesPorRol($user->id_user, $request->rol_id);

        return response()->json([
            'tesis' => $resultado['tesis'],
            'comites' => $resultado['comites'],
            'success' => true
        ]);
    }
}