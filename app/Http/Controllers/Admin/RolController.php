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
            $rolExistente = ComiteRolUsusario::where('nombre_rol', 'LIKE', $nombreRol)->first();
    
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

    public function definirRolUsuarios(Request $request,$rolesId,$comite){
        // Guardar roles para docentes
        foreach ($request->docentes as $index=>$username) {
            $user = Usuarios::where('username', $username)->first();

                    if ($user) {
                        // Asocia el usuario al comité con el ID del rol correspondiente
                            
                                DB::table('usuarios_comite')->insert([
                                    'id_user' => $user->id_user,
                                    'id_comite' => $comite->id_comite,
                                    'id_comite_rol' => $rolesId[$index], // Referencia al ID del rol
                                ]);
                            
                           
                        
                    }
            }

        // Guardar roles para alumnos (puedes agregar lógica similar si se requiere)
        foreach ($request->alumnos as $alumno) {
            $user = Usuarios::where('username', $alumno)->first();

            $rolAsesorado = ComiteRolUsusario::where("nombre_rol","asesorado")->first();
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
                // Puedes definir un rol predeterminado si es necesario
                // Aquí puedes definir un rol predeterminado o crear uno si no existe
                    DB::table('usuarios_comite')->insert([
                        'id_user' => $user->id_user,
                        'id_comite' => $comite->id_comite,
                        'id_comite_rol' => $id_asesorado,
                    ]);
            }
        }
        return redirect()->route("comites.index");
    }


    public function validateRolExists(Request $request){
        return $request->validate([
            'nombre_rol' => 'required|array',
            'nombre_rol.*' => 'required|string|max:255', // Validación para cada rol
        ]);
    }
}
