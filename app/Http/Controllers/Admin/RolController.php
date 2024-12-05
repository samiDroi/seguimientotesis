<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteRolUsusario;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function index(){
        return view("Admin.Comites.RolesComite");
    }

    public function createRol(Request $request, $comite){
        $roles = $request->input('nombre_rol', []);
        $rolesId = []; // Inicializamos la variable
    
        foreach ($roles as $nombreRol) {
            // Verificamos si el rol ya existe (sin importar mayúsculas o minúsculas)
            $rolExistente = ComiteRolUsusario::where('nombre_rol', 'LIKE', $nombreRol)
                                              ->where('id_comite', $comite->id_comite)
                                              ->first();
    
            if ($rolExistente) {
                // Si el rol ya existe, almacenamos su ID
                $rolesId[] = $rolExistente->id_comite_rol;
            } else {
                // Si no existe, creamos el rol y almacenamos el ID
                $nuevoRol = new ComiteRolUsusario();
                $nuevoRol->nombre_rol = $nombreRol; // Aquí usamos el nombre del rol directamente
                $nuevoRol->id_comite = $comite->id_comite;
                $nuevoRol->save();
    
                // Almacenamos el ID del rol recién creado
                $rolesId[] = $nuevoRol->id_comite_rol;
            }
        }
    
        // Llamamos a la función para definir roles de usuario
        $this->definirRolUsuarios($request, $rolesId, $comite);
    }

    public function definirRolUsuarios(Request $request, $rolesId, $comite){
        // Actualizar los roles por si acaso se está realizando una edición
        $this->updateRoles($request, $comite->id_comite);
        
        // Guardar roles para docentes
        foreach ($request->docentes as $index => $username) {
            $user = Usuarios::where('username', $username)->first();
            if ($user) {
                // Comprobar si el usuario junto con su rol ya existe en un comité
                $isEdit = DB::table('usuarios_comite')
                    ->where('id_user', $user->id_user)
                    ->where('id_comite', $comite->id_comite)
                    ->first();

                if ($isEdit) {
                    // Si la relación ya existe, se actualizan tanto los roles como los nuevos usuarios
                    DB::table('usuarios_comite')->where('id_user', $user->id_user)
                        ->where('id_comite', $comite->id_comite)
                        ->update(['id_comite_rol' => $rolesId[$index]]);
                } else {
                    // Si no existe, se crean los nuevos usuarios
                    DB::table('usuarios_comite')->insert([
                        'id_user' => $user->id_user,
                        'id_comite' => $comite->id_comite,
                        'id_comite_rol' => $rolesId[$index], // Referencia al ID del rol
                    ]);
                }
            }
        }

        return redirect()->route("comites.index");
    }

    public function updateRoles(Request $request, $id_comite){
        // Obtener lista de usernames de docentes del request
        $newUsernames = $request->docentes;

        // Convertir usernames a IDs
        $newIds = Usuarios::whereIn('username', $newUsernames)
            ->pluck('id_user')
            ->toArray();

        // Eliminar relaciones de usuarios antiguos que no están en la nueva lista
        DB::table('usuarios_comite')
            ->where('id_comite', $id_comite)
            ->whereNotIn('id_user', $newIds)
            ->delete();
    }

    public function validateRolExists(Request $request){
        return $request->validate([
            'nombre_rol' => 'required|array',
            'nombre_rol.*' => 'required|string|max:255', // Validación para cada rol
        ]);
    }
}
