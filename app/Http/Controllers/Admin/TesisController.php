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
    public function index(){
        $datosTesis = getDirectorTesis();
        $todosAceptados = $datosTesis->every(function ($item) {
            return $item->estado === 'ACEPTADO' || is_null($item->estado);
        });

        return view('Admin.Tesis.index', compact('datosTesis','todosAceptados'));
    }

   
    public function store($id = null){
        if ($id) {
            // Caso de edición: Si hay un ID, busca el TesisComite y sus detalles.
            $tesisComite = TesisComite::findOrFail($id);
            $tesis = Tesis::findOrFail($tesisComite->id_tesis);
            $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $tesisComite->id_tesis_comite)->get();
        } else {
            // Caso de creación: Si no hay ID, inicializa valores por defecto o vacíos.
            $tesisComite = null;
            $tesis = null;
            $requerimientos = [];
        }
    
        $usuarios = Usuarios::all();
        $comites = Comite::all();
        
        // Pasamos los datos a la vista
        return view("Admin.Tesis.formulary", compact("tesis", "tesisComite", "requerimientos"));
    }

    public function viewRequerimientos($id){
        $tesisComite = TesisComite::findOrFail($id);
        $requerimientos = ComiteTesisRequerimientos::where("id_tesis_comite", $id)
            ->whereIn("estado", ["RECHAZADO", "PENDIENTE"]) // Filtrar por los estados "rechazado" o "aceptado"
            ->get();
        return view("Admin.Tesis.formulary", compact("tesisComite", "requerimientos"));
    }

    public function editRequerimientos(){

    }

    //elementos llamados para crear elementos del formulario de tesis
    public function createRequerimientos(Request $request,$idTesisComite){
        $existsRequerimientos = ComiteTesisRequerimientos::where('id_tesis_comite',$idTesisComite);
        $todosAceptados = ComiteTesisRequerimientos::where('id_tesis_comite', $idTesisComite)
            ->get()
            ->every(function ($req) {
                return $req->estado === 'ACEPTADO';
            });
            
        if ($existsRequerimientos && !$todosAceptados) {
            $this->updateRequerimientos($request,$idTesisComite);

        }else{
            $nombreRequerimientos = $request->input('nombre_requerimiento', []);
            $descripciones = $request->input('descripcion', []);
            $requerimientos = [];
            //$tesisComite = $this->asignarComite($request);
            //dd($idTesisComite);
            foreach ($nombreRequerimientos as $index => $nombre) {
                $descripcion = $descripciones[$index] ?? null;
                // dd($descripcion);
                // Aquí puedes crear el requerimiento o hacer lo que necesites con los datos
            $requerimiento = ComiteTesisRequerimientos::create([
                    'nombre_requerimiento' => $nombre,
                    'descripcion' => $descripcion,
                    'id_tesis_comite' => $idTesisComite,
                    // Otros campos necesarios
                ]);
                $requerimientos[] = $requerimiento;
            }
                alert()->success('Los requerimientos de la tesis se han creado satisfactoriamente')->persistent(true,false);
                return redirect()->route('tesis.index');
        }
        return redirect()->route('tesis.index');
    }
    
    public function create(Request $request){
        $nombreTesis = $request->input('nombre_tesis');
        
        $tesis = Tesis::create([
            'nombre_tesis' => $nombreTesis
        ]);

        // $idProgramas = Auth::user()->programas->pluck('id_programa')->toArray();

        // $tesis->programas()->attach($idProgramas);
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
            // $this->asignarAlumno($tesis->id_tesis,$unAlumno);
        }
        
        // if ($request->boolean('comite')) {
            $comite = Comite::create([
            'id_programa'   => $request->get('programa'),
            ]);
            TesisComite::create([
                'id_tesis' => $tesis->id_tesis,
                'id_comite' => $comite->id_comite
            ]);
            return redirect()->route('comites.members',[$comite->id_comite,'idAlumno' => $unAlumno]);
        // }
        // return redirect()->route('tesis.index');

    }
    

    public function asignarComite(Request $request,$id){
        $idComite = $request->input('comite');
        //$tesis = $this->createTesis($request);
            TesisComite::create([
                "id_tesis" => $id,
                "id_comite" => $idComite,
            ]);
       $this->asignarAlumno($id,$request);
       return redirect()->route('tesis.index');
    }

    public function updateRequerimientos(Request $request, $idTesisComite) {
        $tesisComite = TesisComite::findOrFail($idTesisComite); // Obtener el TesisComite con el id proporcionado
        $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $idTesisComite)->whereNotIn('estado',['ACEPTADO'])->get(); // Obtener los requerimientos existentes
       
        // Obtener los nuevos valores para los requerimientos
        $nombresRequerimientos = $request->input('nombre_requerimiento', []);
        $descripcionesRequerimientos = $request->input('descripcion', []);
    
        // Editar los requerimientos existentes
        foreach ($requerimientos as $index => $requerimiento) {
            // Si hay un nombre y descripción para actualizar, se actualizan
            $requerimiento->nombre_requerimiento = $nombresRequerimientos[$index] ?? $requerimiento->nombre_requerimiento;
            $requerimiento->descripcion = $descripcionesRequerimientos[$index] ?? $requerimiento->descripcion;
            $requerimiento->save();
        }
    
        // Crear nuevos requerimientos si no existen en la base de datos
        foreach ($nombresRequerimientos as $index => $nombre) {
            if (!isset($requerimientos[$index])) {
                // Si el requerimiento no existe, crear uno nuevo
                ComiteTesisRequerimientos::create([
                    'id_tesis_comite' => $idTesisComite,
                    'nombre_requerimiento' => $nombresRequerimientos[$index],
                    'descripcion' => $descripcionesRequerimientos[$index],
                ]);
            }
        }
    
        // Retornar a la vista o redirigir, según sea necesario
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


    //FUNCIONES PARA QUE EL COORDINADOR MANEJE EL ESTADO DE LA TESIS
    public function standbyIndex(){ 
        $directores = getDirectores();
        // Retornar la vista con los datos
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
        $tesis = getTesisByUserProgram();
        //  dd($tesis);   
       
        $alumnos = filterAlumnosPrograma();
        // $alumnos = $alumnos->filter(function ($alumno) {
        //     DB
        // });
        
        // Obtener los comités relacionados con los programas del usuario
        $comites = filterComiteProgramasAuth();

        return view('Admin.Tesis.standbyTesis', compact('tesis','requerimientos','tesisComites','comites','alumnos','directores'));
        //return view('Admin.Tesis.standbyTesis', compact('tesisComites','requerimientos','directores'));
    }

    public function updateState(Request $request,$id){
        $requerimiento = ComiteTesisRequerimientos::findOrFail($id);

        // Validar que el estado esté permitido
        $validStates = ['PENDIENTE', 'ACEPTADO', 'RECHAZADO'];
        if (!in_array($request->estado, $validStates)) {
            Alert::error("error","No se puede eliminar un comite si este tiene asignado una tesis, asegurese de eliminar primero la tesis antes que el comite");
            return redirect()->back();
        }
       
        // Actualizar el estado del requerimiento
        $requerimiento->estado = $request->estado;
        if($request->estado == "RECHAZADO"){
            $requerimiento->motivo_rechazo = $request->get('comentario');
        }
        $requerimiento->save();
        $statusTesis = new StatusTesisController;
        $statusTesis->statusTesisToEnCurso($id);
        $statusTesis->statusTesisToPorEvaluar($id);
        alert()->success("El requerimiento ha sido {$request->estado} satisfactoriamente.")->persistent(true,false);
        return redirect()->route('tesis.review');
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
                    ->whereColumn("tc.id_tesis", "t.id_tesis") 
                    ->where("ctr.estado", "PENDIENTE");
            })
            ->select("t.*")
            ->get();
        //dd($tesisUsuario);
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();

        $tesisDeComite = Tesis::whereIn('id_tesis', function($query) use ($comites) {
            $query->select('id_tesis')
                  ->from('tesis_comite')  // La tabla intermedia
                  ->whereIn('id_comite', $comites->pluck('id_comite')); // Filtrar por los comités del usuario
        })->get();

         // Cargar la relación 'comites' en las tesis
        $tesisUsuario = Tesis::with('comites')->whereIn('id_tesis', $tesisUsuario->pluck('id_tesis'))->get();
        //return view('Admin.Tesis', compact('tesis','requerimientos','tesisComites','comites','usuarios'));
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
        //dd($tesis);
        return view('Admin.Tesis.VerAvance',compact('tesis'));
    }
    
    public function updateTesis($tesisData){

        // dd($tesisData);
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
