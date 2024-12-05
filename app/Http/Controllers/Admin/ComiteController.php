<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\UnidadAcademica;
use App\Models\ProgramaAcademico;
use App\Models\Comite;
use App\Models\ComiteRolUsusario;
use App\Http\Controllers\Admin\RolController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ComiteController extends Controller
{
    public function index()
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $comites = Comite::with(['usuarios.roles'])->get();
        return view("Admin.Comites.index", compact("comites"));
    }
   
    public function store($id = null)
    {
        $comite = $id ? Comite::with('Usuarios')->find($id) : null;
        $docentes = $this->getDocentes();
        $unidades = UnidadAcademica::all();
        //dd(Auth::user()->getAuthIdentifierName());
        //return (Auth::user());
        $programas = Auth::user()->programas;


        return view("Admin.Comites.Create", compact("docentes", "unidades", "programas", "comite"));
    }

    public function create(Request $request)
    {
        if ($request->get("id")) {
            $comite = Comite::findOrFail($request->get("id"));
        } else {
            $comite = new Comite();
        }
        $programas = $request->input("ProgramaAcademico");
        $comite->nombre_comite = $request->nombre_comite;
        foreach ($programas as $programa) {
            $comite->id_programa = $programa;
        }
        $comite->save();

        $rol = new RolController();
        $rol->createRol($request, $comite);
        return redirect()->route("comites.index");
    }

    public function getDocentes()
    {
        return Usuarios::whereHas('tipos', function ($query) {
            $query->where('nombre_tipo', 'docente');
        })->get();
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            DB::table('usuarios_comite')
                ->whereIn('id_comite_rol', function ($query) use ($id) {
                    $query->select('id_comite_rol')
                          ->from('comite_rol_usuario')
                          ->where('id_comite', $id);
                })
                ->delete();

            DB::table('comite_rol_usuario')->where('id_comite', $id)->delete();

            Comite::where('id_comite', $id)->delete();

            DB::commit();

            Alert::success('Comite Eliminado', 'El comite ha sido eliminado con exito');
            return redirect()->route('comites.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error("error","No se puede eliminar un comite si este tiene asignado una tesis, asegurese de eliminar primero la tesis antes que el comite");
            return redirect()->route('comites.index')->with('error', 'Error al eliminar el comité: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $comite = Comite::with('roles')->findOrFail($id);
        $roles = ComiteRolUsusario::all();
        return view("Admin.Comites.Edit", compact("comite", "roles"));
    }

    public function validateComite()
    {
    }

    public function cloneComite($id)
    {
        $originalComite = Comite::with('usuarios')->findOrFail($id);

        $clonedComite = $originalComite->replicate();
        $clonedComite->nombre_comite = $originalComite->nombre_comite . '(Copia)';
        $clonedComite->save();

        foreach ($originalComite->usuarios as $usuario) {
            $idComiteRol = DB::table('usuarios_comite')
                ->where('id_comite', $originalComite->id_comite)
                ->where('id_user', $usuario->pivot->id_user)
                ->value('id_comite_rol');

            DB::table('usuarios_comite')->insert([
                'id_comite' => $clonedComite->id_comite,
                'id_user' => $usuario->pivot->id_user,
                'id_comite_rol' => $idComiteRol,
            ]);
        }

        return redirect()->route('comites.store', $clonedComite->id_comite)
            ->with('success', 'Comité clonado con éxito. Puede hacer cambios si lo desea.');
    }
}
