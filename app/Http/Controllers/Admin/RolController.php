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
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al asignar los roles: ' . $e->getMessage());
        }
    }
    
    
//     public function definirRolUsuarios(Request $request, $id_comite)
// {
//     if (!$request->has('roles')) {
//         return redirect()->back()->with('error', 'No se han seleccionado roles para asignar');
//     }

//     DB::beginTransaction();

//     try {
//         foreach ($request->roles as $id_user => $rolesSeleccionados) {

//             $usuarioComite = UsuariosComite::where('id_user', $id_user)
//                 ->where('id_comite', $id_comite)
//                 ->first();

//             if (!$usuarioComite) continue;

//             // Eliminar roles anteriores
//             UsuariosComiteRol::where('id_usuario_comite', $usuarioComite->id_usuario_comite)->delete();

//             foreach ($rolesSeleccionados as $jsonRol) {
//                 $rolData = json_decode($jsonRol, true);
//                 dd($request);
//                 $id_rol = $rolData['id'];
//                 $nombre_rol = $rolData['nombre'];

//                 // Verificar si ese rol (id + nombre personalizado) ya existe creado por este usuario
//                 $rolExistente = UsuariosComiteRol::where('id_rol', $id_rol)
//                     ->where('rol_personalizado', $nombre_rol)
//                     ->where('id_user_creador', Auth::id())
//                     ->first();
//                 if($rolExistente){
//                     UsuariosComiteRol::create([
//                         'id_user_creador'   => Auth::id(),
//                         'id_usuario_comite' => $usuarioComite->id_usuario_comite,
//                         'id_rol'            => $id_rol,
//                         'rol_personalizado' =>  $rolExistente->rol_personalizado,
//                     ]);
//                 }else{
//                     $nombreRoles = $request->input('nombre_rol', []);
//                     $newRoles = $request->input('newRoles');
//                                         //dd($newRoles);
//                      foreach ($newRoles as $id_user => $rolesBase) {
//                         $usuario = UsuariosComite::where('id_user',$id_user)
//                                         ->where('id_comite',$id_comite)->first();
 
//                          foreach ($rolesBase as $id_tipo => $nombre_rol) {
//                              if (in_array($nombre_rol, $nombreRoles)){
//                                  UsuariosComiteRol::create([
//                                      'id_user_creador'   => Auth::id(),
//                                     'id_usuario_comite' => $usuario->id_usuario_comite,
//                                     'id_rol'            => $id_tipo, // ID del rol
//                                     'rol_personalizado' => $nombre_rol, // Nombre del rol asignado
//                                 ]);
//                              }
//                          }
//                      }
//                 }
//                 // Usamos el rol existente o lo asignamos como nuevo personalizado
                
//             }
//         }

//         DB::commit();
//         return redirect()->route('comites.index')->with('success', 'Roles asignados correctamente');

//     } catch (\Exception $e) {
//         echo $e;
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Error al asignar los roles: ' . $e->getMessage());
//     }
// }

    // public function definirRolUsuarios(Request $request, $id_comite)
    // {
    //     //dd($request);
    //     // Validar que hay roles para asignar
    //     if (!$request->has('roles')) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'No se han seleccionado roles para asignar');
    //     }

    //     // Iniciar transacción para asegurar integridad de datos
    //     DB::beginTransaction();
        
    //     try {
    //         //dd($request->roles);
    //         foreach ($request->roles as $id_user => $rolesSeleccionados) {
    //             foreach ($rolesSeleccionados as $jsonRol) {
    //                 # code...
    //             }
    //             $rolData = json_decode($jsonRol, true);
    //             $usuarioComite = UsuariosComite::where('id_user', $id_user)
    //                 ->where('id_comite', $id_comite)
    //                 ->first();

    //             if (!$usuarioComite) {
    //                 continue; // Saltar si no existe la relación
    //             }

    //             // Eliminar roles anteriores del usuario en este comité
    //             UsuariosComiteRol::where('id_usuario_comite', $usuarioComite->id_usuario_comite)
    //                 ->delete();

    //             // Asignar los nuevos roles seleccionados
    //             foreach ($rolesSeleccionados as $id_rol) {
    //                 $rolExistente = UsuariosComiteRol::where('id_rol', $id_rol)
    //                     ->where('id_user_creador', Auth::id())
    //                     ->where('id_usuario_comite', $usuarioComite->id_usuario_comite)
    //                     ->first();

    //                 if ($rolExistente) {
    //                     // Crear nueva asignación usando el rol existente
    //                     UsuariosComiteRol::create([
    //                         'id_user_creador'   => Auth::id(),
    //                         'id_usuario_comite' => $usuarioComite->id_usuario_comite,
    //                         'id_rol'           => $id_rol,
    //                         'rol_personalizado' => $rolExistente->rol_personalizado,
    //                     ]);
    //                 }else{
    //                     $nombreRoles = $request->input('nombre_rol', []);
    //                     $newRoles = $request->input('newRoles');
    //                     //dd($newRoles);
    //                     foreach ($newRoles as $id_user => $rolesBase) {

    //                         $usuario = UsuariosComite::where('id_user',$id_user)
    //                                     ->where('id_comite',$id_comite)->first();

    //                         // UsuariosComiteRol::where('id_usuario_comite', $usuarioComite->id_usuario_comite)
    //                         //             ->delete();

    //                         foreach ($rolesBase as $id_tipo => $nombre_rol) {
    //                              if (in_array($nombre_rol, $nombreRoles)){
    //                                 UsuariosComiteRol::create([
    //                                     'id_user_creador'   => Auth::id(),
    //                                     'id_usuario_comite' => $usuario->id_usuario_comite,
    //                                     'id_rol'            => $id_tipo, // ID del rol
    //                                     'rol_personalizado' => $nombre_rol, // Nombre del rol asignado
    //                                 ]);
    //                              }
    //                         }
    //                     }
    //                 }
                   

    //                 // foreach ($rolesSeleccionados as $id_rol) {
    //                 //     // Verificar si es un rol personalizado
    //                 //     $rolPersonalizado = $nombreRoles[$id_user][$id_rol] ?? null;
    //                 //     dd($rolesSeleccionados);
    //                 //     UsuariosComiteRol::create([
    //                 //         'id_user_creador'   => Auth::id(),
    //                 //         'id_usuario_comite' => $usuarioComite->id_usuario_comite,
    //                 //         'id_rol'            => $id_rol,
    //                 //         'rol_personalizado' => $rolPersonalizado, // Si no existe, será null
    //                 //     ]);
    //                 // }
    //                 // if($request->has('nombre_rol')){
    //                 //     $newRoles = $request->get('nombre_rol');
    //                 //         UsuariosComiteRol::create([
    //                 //             'id_user_creador'   => Auth::id(),
    //                 //             'id_usuario_comite' => $usuarioComite->id_usuario_comite,
    //                 //             'id_rol'           => $id_rol,
    //                 //             'rol_personalizado' => $newRoles,
    //                 //         ]);
                        
    //                 // }
    //             }
    //         }
    //         DB::commit();
    //         return redirect()
    //             ->route('comites.index')
    //             ->with('success', 'Roles asignados correctamente');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         echo $e;
    //         // return redirect()
    //         //     ->back()
    //         //     ->with('error', 'Ocurrió un error al asignar los roles: ' . $e->getMessage());
    //     }
    // }

//     public function definirRolUsuarios(Request $request, $id_comite)
// {
//     if (!$request->has('roles')) {
//         return redirect()->back()->with('error', 'No se han seleccionado roles para asignar');
//     }

//     DB::beginTransaction();

//     try {
//         // Primero creamos nuevos roles personalizados (si los hay)
//         $nuevosRolesCreados = [];

//         if ($request->has('nombre_rol') && $request->has('tipo_rol_base')) {
//             foreach ($request->input('nombre_rol') as $index => $nombreRol) {
//                 $nuevoRol = UsuariosComiteRol::create([
//                     'id_user_creador'   => Auth::id(),
//                     'id_usuario_comite' => null, // aún no asignado a nadie
//                     'id_rol'            => $request->input('tipo_rol_base')[$index],
//                     'rol_personalizado' => $nombreRol,
//                 ]);

//                 // Guardamos el ID del nuevo rol para usarlo luego
//                 $nuevosRolesCreados[$request->input('tipo_rol_base')[$index]] = $nuevoRol->rol_personalizado;
//             }
//         }

//         // Ahora recorremos cada usuario
//         foreach ($request->roles as $id_user => $rolesSeleccionados) {
//             $usuarioComite = UsuariosComite::where('id_user', $id_user)
//                 ->where('id_comite', $id_comite)
//                 ->first();

//             if (!$usuarioComite) {
//                 continue;
//             }

//             // Borramos sus roles anteriores
//             UsuariosComiteRol::where('id_usuario_comite', $usuarioComite->id_usuario_comite)->delete();

//             // Asignamos solo los roles seleccionados
//             foreach ($rolesSeleccionados as $id_rol) {
//                 $rolExistente = UsuariosComiteRol::where('id_rol', $id_rol)
//                     ->where('id_user_creador', Auth::id())
//                     ->whereNotNull('rol_personalizado') // evita duplicar
//                     ->first();

//                 UsuariosComiteRol::create([
//                     'id_user_creador'   => Auth::id(),
//                     'id_usuario_comite' => $usuarioComite->id_usuario_comite,
//                     'id_rol'            => $id_rol,
//                     'rol_personalizado' => $rolExistente ? $rolExistente->rol_personalizado : null,
//                 ]);
//             }
//         }

//         DB::commit();
//         return redirect()->route('comites.index')->with('success', 'Roles asignados correctamente');
//     } catch (\Exception $e) {
//         echo $e;
//         // DB::rollBack();
//         // return redirect()->back()->with('error', 'Ocurrió un error al asignar los roles: ' . $e->getMessage());
//     }
// }

    // public function definirRolUsuarios(Request $request, $comiteId)
    // {
    //     // Validar los datos del formulario
    //     $request->validate([
    //         'nombre_rol' => 'array',
    //         'nombre_rol.*' => 'required|string|max:255',
    //         'tipo_rol_base' => 'array',
    //         'tipo_rol_base.*' => 'required|exists:roles,id_rol',
    //         'roles' => 'array',
    //     ]);
    //     dd($request);
    //     // Obtener el ID del usuario autenticado
    //     $userId = Auth::id();
    
    //     // Crear los nuevos roles personalizados
    //     $nuevosRoles = [];
    //     foreach ($request->nombre_rol as $index => $nombreRol) {
    //         $nuevosRoles[] = [
    //             'id_comite' => $comiteId,
    //             'id_rol' => $request->tipo_rol_base[$index],  // ID de rol base
    //             'rol_personalizado' => $nombreRol,             // Nombre del rol personalizado
    //             'id_user_creador' => $userId,                  // ID del usuario que creó el rol
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ];
    //     }
    
    //     // Insertar los nuevos roles en la base de datos
    //     DB::table('usuarios_comite_roles')->insert($nuevosRoles);
    
    //     // Asignar los roles a los usuarios del comité
    //     foreach ($request->roles as $usuarioId => $roles) {
    //         foreach ($roles as $rolId) {
    //             DB::table('usuario_comite_roles')->insert([
    //                 'id_user' => $usuarioId,
    //                 'id_comite' => $comiteId,
    //                 'id_rol' => $rolId,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     }
    
    //     return redirect()->route('comites.index')->with('success', 'Roles creados y asignados correctamente.');
    // }
    

     // Asegúrate de importar el modelo de la tabla usuarios_comite_rol

   

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
