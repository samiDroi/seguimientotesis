<?php
namespace App\Http\Controllers\Admin;

use App\Enums\Rol;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteRolUsusario;
use App\Models\Rol as ModelsRol;
use App\Models\UsuariosComite;
use App\Models\UsuariosComiteRol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function index(){
        $rolesBase = ModelsRol::all();
        $rolesUsuario = DB::table('usuarios as u')
        ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
        ->leftJoin(DB::raw('(
            SELECT DISTINCT id_usuario_comite, id_rol, rol_personalizado 
            FROM usuarios_comite_roles
            WHERE rol_personalizado IS NOT NULL
        ) as ucr'), 'uc.id_usuario_comite', '=', 'ucr.id_usuario_comite')
        ->join('roles as r','r.id_rol','=','ucr.id_rol')
        ->select(
            'ucr.id_rol',
            'ucr.id_usuario_comite',
            'ucr.rol_personalizado',
            'r.nombre_rol',
            'r.descripcion'
        )
        ->get()
        ->unique('rol_personalizado') // Solo un resultado por cada rol personalizado único
        ->values(); // Reindexa la colección (opcional)
        return view("Admin.Comites.RolesComite",compact('rolesBase','rolesUsuario'));
    }


    public function createRol(Request $request){
        $usuario = Auth::user(); // o el área si lo manejas por áreas
        $usuarioComite = UsuariosComite::where('id_user',$usuario->id_user);
        foreach ($request->nombre_rol as $i => $nombre) {
            UsuariosComiteRol::create([
                'id_user_creador' => $usuario->id_user, // o usa id_area si lo ligas al área
                'id_usuarios_comite' => $usuarioComite->id_usuario_comite,
                'id_rol' => $request->tipo_rol_base[$i],
                'rol_personalizado' => $nombre,
            ]);
        }
        return redirect()->route('roles.index');
    }

    public function storeRoles($id_comite){
        $comite = Comite::where('id_comite',$id_comite)->first();
        $rolesPersonalizados = DB::table('usuarios_comite_roles')
            ->where('id_user_creador', Auth::user()->id_user)
            ->count();
        $rolesBase = ModelsRol::all();
        $roles = DB::table('usuarios_comite_roles')
             ->where('id_user_creador', Auth::user()->id_user)
            ->get();
            $rolesExistentes = DB::table('usuarios_comite_roles')
            ->where('id_user_creador', Auth::id())
            ->select('id_rol', 'rol_personalizado as nombre_rol')
            ->get()
            ->unique('nombre_rol') // <-- aquí se filtran los duplicados
            ->values();
        // $rolesExistentes = DB::table('usuarios_comite_roles')
        // ->where('id_user_creador', Auth::id())
        // ->select('id_rol', 'rol_personalizado as nombre_rol') // Usamos alias para mantener consistencia
        // ->get();
    return view('Admin.Comites.AttachRoles',compact('rolesPersonalizados','rolesBase','roles','comite','rolesExistentes'));

    }

    public function definirRolUsuarios(Request $request, $id_comite)
    {
        //dd($request);
        if (!$request->has('roles_json')) {
            return redirect()->back()->with('error', 'No se han seleccionado roles para asignar');
        }
    
        DB::beginTransaction();
    
        try {
            foreach ($request->roles_json as $id_user => $jsonRoles) {
    
                $usuarioComite = UsuariosComite::where('id_user', $id_user)
                    ->where('id_comite', $id_comite)
                    ->first();
    
                if (!$usuarioComite) continue;
    
                // Eliminar roles anteriores
                UsuariosComiteRol::where('id_usuario_comite', $usuarioComite->id_usuario_comite)->delete();
    
                $roles = json_decode($jsonRoles, true);
    
                foreach ($roles as $rolData) {
                    $id_rol = $rolData['id_tipo'] ?? null;
                    $nombre_rol = $rolData['nombre_rol'] ?? null;
    
                    // Si el nombre_rol no viene, es un nuevo rol creado manualmente (desde newRoles)
                    if ($nombre_rol === null) {
                        $newRoles = $request->input('newRoles');
    
                        // Validar que exista para ese usuario y ese tipo de rol
                        if (isset($newRoles[$id_user][$id_rol])) {
                            $nombre_rol = $newRoles[$id_user][$id_rol];
                        }
                    }
    
                    UsuariosComiteRol::create([
                        'id_user_creador'   => Auth::id(),
                        'id_usuario_comite' => $usuarioComite->id_usuario_comite,
                        'id_rol'            => $id_rol,
                        'rol_personalizado' => $nombre_rol,
                    ]);
                }
            }
    
            DB::commit();
            return redirect()->route('comites.index')->with('success', 'Roles asignados correctamente');
    
        } catch (\Exception $e) {
            echo $e;
            // DB::rollBack();
            //return redirect()->back()->with('error', 'Error al asignar los roles: ' . $e->getMessage());
        }
    }

    public function updateRoles(Request $request)
    {
        $roles = $request->input('nombre_rol');
        $rolesBase = $request->input('tipo_rol_base');
    
        foreach ($roles as $nombreAnterior => $nombreNuevo) {
            $idRolNuevo = $rolesBase[$nombreAnterior] ?? null;
            //dd($idRolNuevo);
            $updateData = ['rol_personalizado' => $nombreNuevo];
            if ($idRolNuevo !== null) {
                $updateData['id_rol'] = $idRolNuevo;
            }
    
            UsuariosComiteRol::where('rol_personalizado', $nombreAnterior)
                ->where('id_user_creador', Auth::user()->id_user)
                ->update($updateData);
        }
    
        return redirect()->route('comites.index');
    }


    public function validateRolExists(Request $request){
        return $request->validate([
            'nombre_rol' => 'required|array',
            'nombre_rol.*' => 'required|string|max:255', // Validación para cada rol
        ]);
    }
}
