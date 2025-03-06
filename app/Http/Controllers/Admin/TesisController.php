<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteTesisRequerimientos;
use App\Models\Tesis;
use App\Models\TesisComite;
use App\Models\TesisUsuarios;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\table;

class TesisController extends Controller
{
    public function index(){
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
         $tesis = Tesis::all();
         $comites = Comite::all();
         $usuarios = Usuarios::all();
        
        // Retornar la vista con los datos
        //$tesis = Tesis::with(['tesisComite.comite', 'tesisComite.requerimientos'])->get();

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
    //Este metodo llama a todos los elementos del formulario de crear tesis para cumplir con el flujo de los datos, es como un metodo main de la operacion de crear
    // public function create(Request $request){   
    //     if ($request->get("id_tesis_comite")) { 
    //         $this->update($request,$request->get("id_tesis_comite"));
    //         return redirect()->route('tesis.index')->with('success', 'Tesis actualizada correctamente.');
    //     }else{
    //         $this->createTesis($request);
    //         //$tesisComite = $this->asignarComite($request); // Llama a asignarComite, crea la tesis y el comite
    //         // Luego, crear los requerimientos, pasando el id del tesis_comite generado
    //         //$this->createRequerimiento($request, $tesisComite->id_tesis_comite);
    //         return redirect()->route('tesis.index')->with('success', 'Tesis actualizada correctamente.');
    //     }
       
    // }
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
    
    // public function update(Request $request, $id) {

    // $tesisComite =  TesisComite::findOrFail($id);
    // $tesis = Tesis::findOrFail($tesisComite->id_tesis);
    // $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $tesisComite->id_tesis_comite)->get();

    // // $tesis->nombre_tesis = $request->get("nombre_tesis");
    // // $tesis->save();
    
    // // $tesisComite->id_comite = $request->get("comite");
    // // $tesisComite->save();

    // $nombresRequerimientos = $request->input('nombre_requerimiento',[]);
    // $descripcionesRequerimientos = $request->input("descripcion",[]);
    // //Editar requerimientos existentes
    // foreach ($requerimientos as $index => $requerimiento) {
    //     $requerimiento->nombre_requerimiento = $nombresRequerimientos[$index] ?? $requerimiento->nombre_requerimiento;
    //     $requerimiento->descripcion = $descripcionesRequerimientos[$index] ?? $requerimiento->descripcion;
    //     $requerimiento->save();
    // }
    // //Crear nuevos requerimientos si es que en la vista edit se desean crear mas
    // foreach ($nombresRequerimientos as $index => $nombre) {
    //     if (!isset($requerimientos[$index])) {
    //         // Si el requerimiento no existe, crear uno nuevo
    //         ComiteTesisRequerimientos::create([
    //             'id_tesis_comite' => $tesisComite->id_tesis_comite,
    //             'nombre_requerimiento' => $nombresRequerimientos[$index],
    //             'descripcion' => $descripcionesRequerimientos[$index],
    //         ]);
    //     }
    // }
  
    //}
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
        // $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        // $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
        // // $director = DB::table("tesis as t")
        // //             ->join("tesis_comite as tc","tc.id_tesis","t.id_tesis")
        // //             ->join("")
        
       
        $directores = DB::table("tesis as t")
        //junta los comites con las tesis
        ->join("tesis_comite as tc", "t.id_tesis", "=", "tc.id_tesis")
        ->join("comite as c", "tc.id_comite", "=", "c.id_comite")
        //junta los comites con los usuarios
        ->join("usuarios_comite as uc", "c.id_comite", "=", "uc.id_comite")
        ->join("usuarios as u", "uc.id_user", "=", "u.id_user")
        ->where("uc.rol", "DIRECTOR") // Se filtra para obtener solo el Director
        ->select("t.id_tesis","u.*") // Se obtienen todos los datos del usuario con rol de Director y su tesis afiliada
        ->get();
        // Retornar la vista con los datos
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
         $tesis = Tesis::all();
         $comites = Comite::all();
         $usuarios = Usuarios::all();
        // Retornar la vista con los datos
        //$tesis = Tesis::with(['tesisComite.comite', 'tesisComite.requerimientos'])->get();

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
        $requerimiento->save();
        alert()->success("El requerimiento ha sido {$request->estado} satisfactoriamente.")->persistent(true,false);
        return redirect()->route('tesis.review');
    }

}
