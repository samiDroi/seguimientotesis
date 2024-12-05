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


class ComiteController extends Controller
{
    public function index(){
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
            return redirect()->route('comites.index')->with('success', 'Comité eliminado correctamente.');
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

    

}
