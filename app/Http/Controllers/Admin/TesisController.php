<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusTesisController;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteTesisRequerimientos;
use App\Models\Logs;
use App\Models\Tesis;
use App\Models\TesisComite;
use App\Models\TesisProgramaAcademico;
use App\Models\TesisUsuarios;
use App\Services\LogService;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\table;

class TesisController extends Controller
{
    public function index(Request $request)
    {
        $rolSeleccionado = $request->query('rol');
        
        if ($rolSeleccionado) {
            $datosTesis = obtenerTesisPorRol($rolSeleccionado);
        } else {
            if (isDirector() > 0) {
                $datosTesis = getDirectorTesis();
            } else {
                $datosTesis = collect();
            }
        }

        $rolesUnicos = obtenerRolesUnicosSidebar(Auth::user()->id_user);
        $todosAceptados = $datosTesis->every(function ($item) {
            return $item->estado === 'ACEPTADO' || is_null($item->estado);
        });

        return view('Admin.Tesis.index', compact('datosTesis', 'todosAceptados', 'rolesUnicos', 'rolSeleccionado'));
    }

    public function store($id = null){
        if ($id) {
            $tesisComite = TesisComite::findOrFail($id);
            $tesis = Tesis::findOrFail($tesisComite->id_tesis);
            $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $tesisComite->id_tesis_comite)->get();
        } else {
            $tesisComite = null;
            $tesis = null;
            $requerimientos = [];
        }
    
        $usuarios = Usuarios::all();
        $comites = Comite::all();
        
        return view("Admin.Tesis.formulary", compact("tesis", "tesisComite", "requerimientos"));
    }

    public function viewRequerimientos($id){
        $tesisComite = TesisComite::findOrFail($id);
        $requerimientos = ComiteTesisRequerimientos::where("id_tesis_comite", $id)
            ->get();
        return view("Admin.Tesis.formulary", compact("tesisComite", "requerimientos"));
    }

    public function editRequerimientos(){}

    public function createRequerimientos(Request $request,$idTesisComite){
        $existsRequerimientos = ComiteTesisRequerimientos::where('id_tesis_comite',$idTesisComite);
        if ($existsRequerimientos) {
            $this->updateRequerimientos($request,$idTesisComite);
        }else{
            $nombreRequerimientos = $request->input('nombre_requerimiento', []);
            $descripciones = $request->input('descripcion', []);
            $requerimientos = [];
            
            foreach ($nombreRequerimientos as $index => $nombre) {
                $descripcion = $descripciones[$index] ?? null;
                $requerimiento = ComiteTesisRequerimientos::create([
                    'nombre_requerimiento' => $nombre,
                    'descripcion' => $descripcion,
                    'id_tesis_comite' => $idTesisComite,
                ]);
                $requerimientos[] = $requerimiento;
            }
                alert()->success('Los requerimientos de la tesis se han creado satisfactoriamente')->persistent(true,false);
                return redirect()->route('tesis.index');
        }
        return redirect()->route('tesis.index');
    }

    public function deleteRequerimiento($id){
        $requerimiento = ComiteTesisRequerimientos::findOrFail($id);
        $requerimiento->delete();
        alert()->success('El requerimiento de la tesis se ha eliminado satisfactoriamente')->persistent(true,false);
        return redirect()->route('tesis.index');
    }
    
    public function create(Request $request){
        $nombreTesis = $request->input('nombre_tesis');
        
        $tesis = Tesis::create([
            'nombre_tesis' => $nombreTesis
        ]);

        if (is_array($request->input('alumno'))) {
            foreach ($request->input('alumno') as $alumno) {
                TesisUsuarios::create([
                    'id_tesis' => $tesis->id_tesis,
                    'id_user' =>  $alumno
                ]);
            }
        }else{
             TesisUsuarios::create([
                'id_tesis' => $tesis->id_tesis,
                'id_user' =>  $request->get('alumno')
            ]);
            $unAlumno = $request->get('alumno');
        }
        
        $comite = Comite::create([
            'id_programa'   => $request->get('programa'),
        ]);
        TesisComite::create([
            'id_tesis' => $tesis->id_tesis,
            'id_comite' => $comite->id_comite
        ]);
        return redirect()->route('comites.members',[$comite->id_comite,'idAlumno' => $unAlumno]);
    }

    public function asignarComite(Request $request,$id){
        $idComite = $request->input('comite');
        TesisComite::create([
            "id_tesis" => $id,
            "id_comite" => $idComite,
        ]);
       $this->asignarAlumno($id,$request);
       return redirect()->route('tesis.index');
    }

    public function updateRequerimientos(Request $request, $idTesisComite) {
        $tesisComite = TesisComite::findOrFail($idTesisComite);
        $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $idTesisComite)->whereNotIn('estado',['ACEPTADO'])->get();
        
        $nombresRequerimientos = $request->input('nombre_requerimiento', []);
        $descripcionesRequerimientos = $request->input('descripcion', []);
    
        foreach ($requerimientos as $index => $requerimiento) {
            $requerimiento->nombre_requerimiento = $nombresRequerimientos[$index] ?? $requerimiento->nombre_requerimiento;
            $requerimiento->descripcion = $descripcionesRequerimientos[$index] ?? $requerimiento->descripcion;
            $requerimiento->save();
        }
    
        foreach ($nombresRequerimientos as $index => $nombre) {
            if (!isset($requerimientos[$index])) {
                ComiteTesisRequerimientos::create([
                    'id_tesis_comite' => $idTesisComite,
                    'nombre_requerimiento' => $nombresRequerimientos[$index],
                    'descripcion' => $descripcionesRequerimientos[$index],
                ]);
            }
        }
    
        alert()->success('Los requerimientos de la tesis se han actualizado satisfactoriamente')->persistent(true,false);
        return redirect()->route('tesis.index');
    }

    public function delete($id){
        $comiteTesis = TesisComite::findOrFail($id);
        $id_tesis = $comiteTesis->id_tesis;
        $tesis = Tesis::where('id_tesis',$id_tesis);
        $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $comiteTesis->id_tesis_comite)->get();
        $usuarioTesis = TesisUsuarios::where('id_tesis',$id_tesis);
        foreach ($requerimientos as $requerimiento) {
            $requerimiento->delete();
        }
        $usuarioTesis->delete();
        $comiteTesis->delete();
        $tesis->delete();

        alert()->success('La tesis se ha eliminado satisfactoriamente')->persistent(true,false);
        return redirect()->route("tesis.index");
    }

    public function asignarAlumno($id,$request){
        $idUsuario = $request->input("usuarios");
        TesisUsuarios::create([
             "id_user" => $idUsuario,
             "id_tesis" => $id
        ]);
    }

    public function standbyIndex(){ 
        $directores = getDirectores();
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
        $tesis = getTesisByUserProgram();
       
        $alumnos = filterAlumnosPrograma();
        $comites = filterComiteProgramasAuth();

        return view('Admin.Tesis.standbyTesis', compact('tesis','requerimientos','tesisComites','comites','alumnos','directores'));
    }

    public function showCurrentlyTesis(){
        $programas = Auth::user()->programas;
        $comites = Auth::user()->comites;

        $tesisUsuario = DB::table("tesis as t")
            ->join("tesis_usuarios as tu", "t.id_tesis", "=", "tu.id_tesis")
            ->join("usuarios as u", "u.id_user", "=", "tu.id_user")
            ->where("u.id_user", Auth::user()->id_user)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from("comite_tesis_requerimientos as ctr")
                    ->join("tesis_comite as tc", "ctr.id_tesis_comite", "=", "tc.id_tesis_comite")
                    ->whereColumn("tc.id_tesis", "t.id_tesis"); 
            })
            ->select("t.*")
            ->get();
            
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();

        $tesisDeComite = Tesis::whereIn('id_tesis', function($query) use ($comites) {
            $query->select('id_tesis')
                  ->from('tesis_comite')
                  ->whereIn('id_comite', $comites->pluck('id_comite'));
        })->get();

        $tesisUsuario = Tesis::with('comites')->whereIn('id_tesis', $tesisUsuario->pluck('id_tesis'))->get();
        return view('Admin.Tesis',compact("programas","comites","tesisUsuario","tesisComites","tesisDeComite"));
    }

    public function showAvanceAdmin($id_tesis){
        $tesis = DB::table('tesis as t')
        ->join('tesis_comite as tc','tc.id_tesis','=','t.id_tesis')
        ->join('comite_tesis_requerimientos as ctr','ctr.id_tesis_comite','=','tc.id_tesis_comite')
        ->join('avance_tesis as at','at.id_requerimiento','=','ctr.id_requerimiento')
        ->where('t.id_tesis',$id_tesis)
        ->select('ctr.*','at.*','t.nombre_tesis')
        ->get();
        
        return view('Admin.Tesis.VerAvance',compact('tesis'));
    }
    
    public function updateTesis($tesisData){
        foreach ($tesisData as $id_tesis => $nombre_tesis) {
            $tesis = Tesis::findOrFail($id_tesis);
            LogService::logRegister(
                $tesis->id_tesis,
                'tesis',
                'tesis.update',
                'Se actualizo el nombre de la tesis',
                $tesis->nombre_tesis,
                $nombre_tesis
            );
           $tesis->nombre_tesis = $nombre_tesis;
           $tesis->save();
        }  
    }

    public function historialTesis($id_tesis){
        $logs = Logs::where('clave','tesis.update')
                ->where('model_id',$id_tesis)
                ->orderBy('created_at', 'desc')
                ->get();
        return view('Admin.Tesis.HistorialTesis',compact('logs'));
    }
}