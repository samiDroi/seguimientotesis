<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Rol;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\UnidadAcademica;
use App\Models\ProgramaAcademico;
use App\Models\Comite;
use App\Models\ComiteRolUsusario;
use App\Http\Controllers\Admin\RolController;
use App\Models\ComiteTesisRequerimientos;
use App\Models\UsuariosComite;
use App\Models\UsuariosComiteRol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Rol as ModelsRol;
use App\Models\TesisComite;
use App\Models\TesisUsuarios;

class ComiteController extends Controller
{
    public function index($id = null)
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $comites = Comite::with(['usuarios', 'programas','tesis.usuarios']) // Cargar ambas relaciones
            ->whereHas('programas', function ($query) {
                $query->whereIn('id_programa', Auth::user()->programas->pluck('id_programa'));
            })
            ->get();
            $comite = $id ? Comite::with('usuarios', 'tesis.usuarios')->find($id) : null;
            $programas = Auth::user()->programas;
            $tesis = getTesisByUserProgram();
            // dd($tesis);
        // $alumnos = DB::table('usuarios as u')
        //     ->join('tesis_usuarios as tu','tu.id_user')
        $alumnos = Usuarios::whereDoesntHave('tesis')
            ->whereHas('tipos', function ($q) {
                $q->where('nombre_tipo', 'alumno');
            })
            ->get();

        
        return view("Admin.Comites.index", compact('alumnos',"comites",'comite','programas','tesis'));
    }
   
    public function store($id = null)
    {
        $comite = $id ? Comite::with('Usuarios')->find($id) : null;
        $docentes = $this->getDocentes();
        $unidades = UnidadAcademica::all();
        //dd(Auth::user()->getAuthIdentifierName());
        //return (Auth::user());
        $roles = Rol::getEnumValues();
        $tesis = getTesisByUserProgram();
        return view("Admin.Comites.Create", compact("docentes", "unidades", "programas", "comite","roles","tesis"));
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
        $tesis = $request->get('tesis');
        
        foreach ($tesis as $tesisId) {
            TesisComite::create([
            "id_tesis" => $tesisId,
            "id_comite" => $comite->id_comite,
        ]);
        }
        
        return redirect()->route("comites.members",$comite->id_comite);
    }

    public function saveMembers($id,$idAlumno){
        $comite = Comite::findOrFail($id);
        $docentes = $this->getDocentes($idAlumno);

        return view('Admin.Comites.AttachMembers',compact('comite','docentes'));
    }

    public function registerMembers(Request $request){
        // $comite = $request->get('id_comite');
        // foreach ($request->docentes as $username) {
        //     $usuario = Usuarios::where('username', $username)->first(); // Obtener el docente por el username
        //     if ($usuario) {
        //             DB::table('usuarios_comite')->insert([
        //                 'id_user' => $usuario->id_user,
        //                 'id_comite' => $comite,
        //                 ]);
        //     }
        // }
        return redirect()->route('roles.store',["id" => $request->id_comite, "docentes" => json_encode($request->docentes)]);
    }
    public function cloneComite(Request $request,$id)
    {
        // return $request->all();
        // Obtener comité original con usuarios
        $originalComite = Comite::with('usuarios')->findOrFail($id);

        // Clonar comité
        $clonedComite = $originalComite->replicate();
        $clonedComite->nombre_comite = $originalComite->nombre_comite . ' (Copia)';
        $clonedComite->save();

        // Obtener todos los usuarios_comite originales del comité
        $usuariosComiteOriginales = UsuariosComite::where('id_comite', $originalComite->id_comite)->get();

        // Recorrer cada usuario del comité original
        foreach ($originalComite->usuarios as $usuario) {
            // Clonar la relación usuario_comite
            $nuevoUsuarioComite = UsuariosComite::create([
                'id_comite' => $clonedComite->id_comite,
                'id_user' => $usuario->id_user,
            ]);

            // Buscar la relación original correspondiente a este usuario
            $usuarioComiteOriginal = $usuariosComiteOriginales->firstWhere('id_user', $usuario->pivot->id_user);
           
            if ($usuarioComiteOriginal) {
                // Obtener los roles de ese usuario en el comité original
                $roles = UsuariosComiteRol::where('id_usuario_comite', 
                $usuarioComiteOriginal->id_usuario_comite)->get();
                //  dd($usuarioComiteOriginal);
                // Clonar cada rol y asignarlo al nuevo usuario_comite
                foreach ($roles as $rolOriginal) {
                    // dd($nuevoUsuarioComite);
                    $rolClonado = $rolOriginal->replicate();
                    $rolClonado->id_usuario_comite = $nuevoUsuarioComite->id_usuario_comite;
                    $rolClonado->save();
                }
            }
        }
        //se le asigna la tesis al comite recien clonado
        TesisComite::create([
            'id_comite' => $clonedComite->id_comite,
            'id_tesis'  => $request->get('tesis')
        ]);
        //se asignan los alumnos segun lo que seleccione el usuario
        $alumnos = $request->input('alumnos');
        foreach ($alumnos as $alumno) {
            TesisUsuarios::create([
                'id_user' => $alumno,
                'id_tesis' => $request->get('tesis')
            ]);
        }
        return redirect()->route('comites.index')
            ->with('success', 'Comité clonado con éxito. Puede hacer cambios si lo desea.');
    }
    

    public function edit($id)
    {
        $rolesExistentes = DB::table('usuarios_comite_roles')
            ->where('id_user_creador', Auth::id())
            ->select('id_rol', 'rol_personalizado as nombre_rol')
            ->get()
            ->unique('nombre_rol') // <-- aquí se filtran los duplicados
            ->values();
            $alumnoDocente = DB::table('usuarios as u')
            ->join('tesis_usuarios as tu','tu.id_user','=','u.id_user')
            ->join('tesis as t','t.id_tesis','=','tu.id_tesis')
            ->join('tesis_comite as tc','tc.id_tesis','=','t.id_tesis')
            ->where('tc.id_comite',$id)
            ->value('u.id_user');
               
        // $alumnoDocente = DB::table('usuarios_comite as uc')
        //     ->join('usuarios as u','uc.id_user','=','u.id_user')
        //     ->join('tesis_usuarios as tu','tu.id_user','=','u.id_user')
        //     ->join('tesis as t','t.id_tesis','=','tu.id_tesis')
        //     ->where('uc.id_comite',$id)
        //     ->count();
        $docentes = $this->getDocentes($alumnoDocente);
        $comite = Comite::where('id_comite',$id)->first();
        $programas = Auth::user()->programas;
        $roles = DB::table('usuarios_comite as uc')
        ->join('usuarios as u', 'uc.id_user', '=', 'u.id_user')
        ->leftJoin('usuarios_comite_roles as ucr', 'uc.id_usuario_comite', '=', 'ucr.id_usuario_comite')
        ->where('uc.id_comite', $id)
        ->where('id_user_creador', Auth::user()->id_user)

        ->select(
            'u.id_user',
            'u.nombre',
            'u.apellidos',
            'u.correo_electronico',
            'ucr.id_rol',
            'ucr.id_usuario_comite',
            'ucr.rol_personalizado'
        )
        ->get()
        ->unique(function ($item) {
        // Esto asegura registros únicos combinando id_user y rol_personalizado
             return $item->id_user.'|'.$item->rol_personalizado;
         });
         $rolesPersonalizados = DB::table('usuarios_comite_roles')
            ->where('id_user_creador', Auth::user()->id_user)
            ->count();
        $rolesBase = ModelsRol::all();
        // $roles = DB::table('usuarios_comite_roles')
        //     ->get();
        
        return view("Admin.Comites.Edit", compact("comite", "roles","docentes","programas","rolesExistentes","rolesBase"));
    }


    public function update(Request $request,$id){
        // dd($request);
        // return $request->all();
        $comite = Comite::where('id_comite',$id)->first();
        $comite->nombre_comite = $request->get('nombre_comite');
        $comite->id_programa = $request->get('ProgramaAcademico');
        $comite->save();

        DB::table('usuarios_comite_roles as ucr')
        ->join('usuarios_comite as uc','uc.id_usuario_comite','=','ucr.id_usuario_comite')
        ->where('uc.id_comite',$id)
        ->delete();

        DB::table('usuarios_comite')
        ->where('id_comite', $comite->id_comite)
        ->delete();

        // foreach ($docentes as $usuario) {
        //      // Obtener el docente por el username
            
        //             DB::table('usuarios_comite')->updateOrInsert([
        //                 'id_user' => $usuario->id_user,
        //                 'id_comite' => $comite->id_comite,
        //                 ]);
            
        // }
        // foreach ($docentes as $id_user) {
        //     if (Usuarios::where('id_user', $id_user)->exists()) {
        //         DB::table('usuarios_comite')->updateOrInsert([
        //             'id_user' => $id_user,
        //             'id_comite' => $comite->id_comite,
        //         ]);
        //     }
        // }
        // 3. Gestión simple de roles (nueva funcionalidad)
        if ($request->has('roles_json')) {
          
            $defRoles = new RolController;
            $defRoles->definirRolUsuarios($request,$id);
        
        }
        alert::success('Completado','El comite se ha actualizado satisfactoriamente');
        return redirect()->route('comites.index');

    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            DB::table('usuarios_comite_roles as ucr')
            ->join('usuarios_comite as uc','uc.id_usuario_comite','=','ucr.id_usuario_comite')
            ->where('uc.id_comite',$id)
            ->delete();

            DB::table('usuarios_comite')
                ->where('id_comite',$id)
                ->delete();
            
            Comite::where('id_comite', $id)->delete();

         

            DB::commit();

            Alert::success('Comite Eliminado', 'El comite ha sido eliminado con exito');
            return redirect()->route('comites.index');
        } catch (\Exception $e) {
            echo $e;
            DB::rollBack();
            Alert::error("error","No se puede eliminar un comite si este tiene asignado una tesis, asegurese de eliminar primero la tesis antes que el comite");
            return redirect()->route('comites.index')->with('error', 'Error al eliminar el comité: ' . $e->getMessage());
        }
    }
 
    public function getDocentes($idAlumno = null){
        // dd($idAlumno);
        if($idAlumno){
            $alumno = Usuarios::find($idAlumno);
            $programasAlumno = $alumno->programas->pluck('id_programa')->toArray();
            return Usuarios::whereHas('tipos', function ($query) {
                $query->where('nombre_tipo', 'docente');
                })
                ->whereHas('programas', function ($query) use ($programasAlumno) {
                    $query->whereIn('programa_academico.id_programa', $programasAlumno);
                })
                ->where('id_user', '!=', $idAlumno)
                ->get();
        }
        return Usuarios::whereHas('tipos', function ($query) {
            $query->where('nombre_tipo', 'docente');
        })->get();
    }
    public function editButton(Request $request){
        $updateTesis = new TesisController;
        // dd($request);
        // Tesis
        if ($request->has('tesis')) {
            $tesis = $request->input('tesis');

            // Filtrar tesis donde el título NO sea null o vacío
            $tesisValidas = array_filter($tesis, function ($titulo) {
                return isset($titulo) && trim($titulo) !== '';
            });

            if (!empty($tesisValidas)) {
                $updateTesis->updateTesis($tesisValidas);
            }
        }

        // Alumnos
        if ($request->has('alumno')) {
            $alumnos = $request->input('alumno');

            // Filtrar alumnos donde el id del alumno NO sea null o vacío
            $alumnosValidos = array_filter($alumnos, function ($idAlumno) {
                return isset($idAlumno) && trim($idAlumno) !== '';
            });

            if (!empty($alumnosValidos)) {
                $this->reasignarAlumno($alumnosValidos);
            }
        }

        
        Alert::success('Éxito', 'Los datos de tesis y alumno se han actualizado correctamente');
        return redirect()->route('comites.index');
    }
    public function reasignarAlumno($alumnoData){
        foreach ($alumnoData as $id_tesis => $id_alumno) {
            TesisUsuarios::where('id_tesis', $id_tesis)
                ->update(['id_user' => $id_alumno]);
        }    
    }
}

