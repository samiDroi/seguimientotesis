<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\UnidadAcademica;
use App\Models\ProgramaAcademico;
use App\Models\Comite;
use App\Models\ComiteRolUsusario;
use Illuminate\Support\Facades\DB;


class RolController extends Controller
{
    public function index(){
        return view("Admin.Comites.RolesComite");
    }

    public function createRol(Request $request,$comite){
        $roles = $request->input('nombre_rol', []);
        $rolesId = []; // Inicializamos la variable
    
        foreach ($roles as $nombreRol) {
            // Verificamos si el rol ya existe (sin importar mayúsculas o minúsculas)
            $rolExistente = ComiteRolUsusario::where('nombre_rol', 'LIKE', $nombreRol)->where('id_comite', $comite->id_comite)->first();
    
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
    //aqui se definen los roles
    public function definirRolUsuarios(Request $request,$rolesId,$comite){
        //actualizar los roles por si acaso se esta realizando una edicion
        $this->updateRoles($request,$comite->id_comite);
        
        // Guardar roles para docentes
        foreach ($request->docentes as $index=>$username) {
            $user = Usuarios::where('username', $username)->first();
                    //Si el usuario existe se realiza la operacion
                    if ($user) {
                        //se comprueba si el usuario junto con su rol ya existe en un comite
                        $isEdit = DB::table('usuarios_comite')
                        ->where('id_user', $user->id_user)
                        ->where('id_comite', $comite->id_comite)
                        ->first();
                        //si la relacion ya existe entonces se trata de una edicion
                        //
                        if($isEdit){
                            //si isEdit es verdad, se actualizan tanto los roles como 
                            //los nuevos usuarios (si se cambiaran)
                            DB::table('usuarios_comite')->where('id_user', $user->id_user)
                            ->where('id_comite', $comite->id_comite)
                            ->update(['id_comite_rol' => $rolesId[$index]]);
                        }else{
                            //sino, se crean los nuevos usuarios
                            DB::table('usuarios_comite')->insert([
                                'id_user' => $user->id_user,
                                'id_comite' => $comite->id_comite,
                                'id_comite_rol' => $rolesId[$index], // Referencia al ID del rol
                            ]);
                        }    
                    }
             
            }

        // Guardar roles para alumnos (puedes agregar lógica similar si se requiere)
        foreach ($request->alumnos as $alumno) {
            $user = Usuarios::where('username', $alumno)->first();

            $rolAsesorado = ComiteRolUsusario::where("nombre_rol","asesorado")->where('id_comite', $comite->id_comite)->first();

            $id_asesorado = "";
            if(!$rolAsesorado){
                $asesorado = new ComiteRolUsusario();
                $asesorado->nombre_rol = "asesorado";
                $asesorado->id_comite = $comite->id_comite;
                $asesorado->save();

                $id_asesorado = $asesorado->id_comite_rol;
            }else{
                $id_asesorado = $rolAsesorado->id_comite_rol;
            }
            if ($user) {
                $isEdit = DB::table('usuarios_comite')
                        ->where('id_user', $user->id_user)
                        ->where('id_comite', $comite->id_comite)
                        ->first();
                if($isEdit){
                    DB::table('usuarios_comite')->where('id_user', $user->id_user)
                    ->where('id_comite', $comite->id_comite)
                    ->update(['id_comite_rol' => $id_asesorado]);
                }else{
                    DB::table('usuarios_comite')->insert([
                        'id_user' => $user->id_user,
                        'id_comite' => $comite->id_comite,
                        'id_comite_rol' => $id_asesorado,
                    ]);
                }
               
                    
            }
        }
        return redirect()->route("comites.index");
    }

    public function updateRoles(Request $request,$id_comite){
        // Obtener lista de usernames de docentes y alumnos del request
        $newUsernames = array_merge($request->docentes, $request->alumnos);
        
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
