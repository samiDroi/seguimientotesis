<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\UnidadAcademica;
use App\Models\ProgramaAcademico;
use App\Models\Comite;
use App\Http\Controllers\Admin\RolController;
use App\Models\ComiteRolUsusario;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;



class ComiteController extends Controller
{
    public function index(){
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
    $comites = Comite::with(['usuarios.roles'])->get();
    return view("Admin.Comites.index", compact("comites"));
    }

    public function store($id = null){
        $comite = $id ? Comite::with('Usuarios')->find($id) : null;
        $docentes = $this->getDocentes();
        $alumnos = $this->getAlumnos();
        $unidades = UnidadAcademica::all();
        $programas = ProgramaAcademico::all();
      
        return view("Admin.Comites.Create",compact("docentes","alumnos","unidades","programas","comite"));
    }
    

    public function create(Request $request){
        if($request->get("id")){
            $comite = Comite::findOrFail($request->get("id"));
        }else{
            $comite = new Comite();
        }
        $comite->nombre_comite = $request->nombre_comite;
        $comite->id_programa = 1;
        $comite->save();
        //dd($comite);
        $rol = new RolController();
        $rol->createRol($request,$comite);
        return redirect()->route("comites.index");
    }


    public function getDocentes(){
        return $docentes = Usuarios::whereHas('tipos', function($query) {
            $query->where('nombre_tipo', 'docente'); // o $query->where('id', 1);
        })->get();
    }

    public function getAlumnos(){
        return $alumnos = Usuarios::whereHas('tipos', function($query) {
            $query->where('nombre_tipo', 'alumno'); // o $query->where('id', 1);
        })->get();
    }


    public function destroy($id){

        DB::beginTransaction();

        try {
        // Paso 1: Eliminar relaciones en `usuarios_comite` basadas en `id_comite_rol` relacionado con `comite_rol_usuario`
        DB::table('usuarios_comite')
            ->whereIn('id_comite_rol', function ($query) use ($id) {
                $query->select('id_comite_rol')
                      ->from('comite_rol_usuario')
                      ->where('id_comite', $id);
            })
            ->delete();

            // Paso 2: Eliminar en `comite_rol_usuario`
            DB::table('comite_rol_usuario')->where('id_comite', $id)->delete();

            // Paso 3: Finalmente, eliminar el comité
            Comite::where('id_comite', $id)->delete();

            DB::commit();
            Alert::success('Deleted!', 'The user has been deleted successfully.');
            //return redirect()->route('comites.index')->with('success', 'Comité eliminado correctamente.');
            } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('comites.index')->with('error', 'Error al eliminar el comité: ' . $e->getMessage());
        }
    
    }
    public function edit($id){
        $comite = Comite::with('roles')->findOrFail($id); // Carga el comité junto con sus roles

        // Trae todos los roles existentes
        $roles = ComiteRolUsusario::all();
        return view("Admin.Comites.Edit",compact("comite","roles"));
    }
 
    public function validateComite(){

    }

    public function cloneComite($id){
        // Obtener el comité original
    $originalComite = Comite::with('usuarios')->findOrFail($id);

    // Crear un nuevo comité con las mismas características
    $clonedComite = $originalComite->replicate(); // Duplica el comité sin guardar
    $clonedComite->nombre_comite = $originalComite->nombre_comite . '(Copia)';
    $clonedComite->save();
    foreach ($originalComite->usuarios as $usuario) {
        // Obtener el `id_comite_rol` desde `usuarios_comite` para el usuario y el comité original
        $idComiteRol = DB::table('usuarios_comite')
            ->where('id_comite', $originalComite->id_comite)
            ->where('id_user', $usuario->pivot->id_user)
            ->value('id_comite_rol');
    
        // Insertar el usuario en el comité clonado en la tabla `usuarios_comite`, incluyendo `id_comite_rol`
        DB::table('usuarios_comite')->insert([
            'id_comite' => $clonedComite->id_comite,
            'id_user' => $usuario->pivot->id_user,
            'id_comite_rol' => $idComiteRol,
        ]);
    }     
    // Redirigir a la vista de edición del nuevo comité
    return redirect()->route('comites.store', $clonedComite->id_comite)
                     ->with('success', 'Comité clonado con éxito. Puede hacer cambios si lo desea.');
    }

}
