<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusTesisController;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteTesisRequerimientos;
use App\Models\Tesis;
use App\Models\TesisComite;
use App\Models\TesisUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\table;

class TesisController extends Controller
{
    public function index(){
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
        $tesis = getTesisByUserProgramAndComite();
         
       
        $usuarios = Usuarios::whereIn('usuarios.id_user', function ($query) {
            $query->select('usuarios_programa_academico.id_user') // Especifica la tabla en la subconsulta
                ->from('usuarios_programa_academico')
                ->whereIn('usuarios_programa_academico.id_programa', Auth::user()->programas->pluck('id_programa'));
        })->get();
        
        // Obtener los comités relacionados con los programas del usuario
        $comites = Comite::with('programas')  // Cargar la relación programas
        ->whereHas('programas', function ($query) {
            $query->whereIn('id_programa', Auth::user()->programas->pluck('id_programa'));
        })
        ->get();


        return view('Admin.Tesis.index', compact('tesis','requerimientos','tesisComites','comites','usuarios'));
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
        if ($existsRequerimientos) {
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
        Tesis::create([
            'nombre_tesis' => $nombreTesis
        ]);
        return redirect()->route('tesis.index');
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
        $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $idTesisComite)->get(); // Obtener los requerimientos existentes
    
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
         
       
        $usuarios = Usuarios::whereIn('usuarios.id_user', function ($query) {
            $query->select('usuarios_programa_academico.id_user') // Especifica la tabla en la subconsulta
                ->from('usuarios_programa_academico')
                ->whereIn('usuarios_programa_academico.id_programa', Auth::user()->programas->pluck('id_programa'));
        })->get();
        
        // Obtener los comités relacionados con los programas del usuario
        $comites = Comite::with('programas')  // Cargar la relación programas
        ->whereHas('programas', function ($query) {
            $query->whereIn('id_programa', Auth::user()->programas->pluck('id_programa'));
        })
        ->get();

        return view('Admin.Tesis.standbyTesis', compact('tesis','requerimientos','tesisComites','comites','usuarios','directores'));
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
}
