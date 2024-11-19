<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comite;
use App\Models\ComiteTesisRequerimientos;
use App\Models\Tesis;
use App\Models\TesisComite;

class TesisController extends Controller
{
    public function index(){
        $tesisComites= TesisComite::with(["tesis","comite","requerimientos"])->get();
        $requerimientos = ComiteTesisRequerimientos::with('tesisComite')->get();
        // Retornar la vista con los datos
        return view('Admin.Tesis.index', compact('tesisComites','requerimientos'));
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
        return view("Admin.Tesis.formulary", compact("usuarios", "comites", "tesis", "tesisComite", "requerimientos"));
        
    }
    //Este metodo llama a todos los elementos del formulario de crear tesis para cumplir con el flujo de los datos, es como un metodo main de la operacion de crear
    public function create(Request $request){
       
        if ($request->get("id_tesis_comite")) { 
            $this->update($request,$request->get("id_tesis_comite"));
            return redirect()->route('tesis.index')->with('success', 'Tesis actualizada correctamente.');
        }else{
            $tesisComite = $this->asignarComite($request); // Llama a asignarComite, crea la tesis y el comite
            // Luego, crear los requerimientos, pasando el id del tesis_comite generado
            $this->createRequerimiento($request, $tesisComite->id_tesis_comite);
            return redirect()->route('tesis.index')->with('success', 'Tesis actualizada correctamente.');
        }
       
    }
    //elementos llamados para crear elementos del formulario de tesis
    public function createRequerimiento(Request $request,$idTesisComite){
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
        //return $requerimientos;

    }
    public function createTesis(Request $request){
        $nombreTesis = $request->input('nombre_tesis');
        $tesis = Tesis::create([
            'nombre_tesis' => $nombreTesis
        ]);
        //dd($tesis);
        return $tesis;
    }
    

    public function asignarComite(Request $request){
        $idComite = $request->input('comite');
        //$requerimientos[] = $this->createRequerimiento($request);
        $tesis = $this->createTesis($request);
            $tesisComite = TesisComite::create([
                "id_tesis" => $tesis->id_tesis,
                "id_comite" => $idComite,
            ]);
       
       return $tesisComite;
    }


    
    public function update(Request $request, $id)
{
    $tesisComite =  TesisComite::findOrFail($id);
    $tesis = Tesis::findOrFail($tesisComite->id_tesis);
    $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $tesisComite->id_tesis_comite)->get();

    $tesis->nombre_tesis = $request->get("nombre_tesis");
    $tesis->save();
    
    $tesisComite->id_comite = $request->get("comite");
    $tesisComite->save();

    $nombresRequerimientos = $request->input('nombre_requerimiento',[]);
    $descripcionesRequerimientos = $request->input("descripcion",[]);
    //Editar requerimientos existentes
    foreach ($requerimientos as $index => $requerimiento) {
        $requerimiento->nombre_requerimiento = $nombresRequerimientos[$index] ?? $requerimiento->nombre_requerimiento;
        $requerimiento->descripcion = $descripcionesRequerimientos[$index] ?? $requerimiento->descripcion;
        $requerimiento->save();
    }
    //Crear nuevos requerimientos si es que en la vista edit se desean crear mas
    foreach ($nombresRequerimientos as $index => $nombre) {
        if (!isset($requerimientos[$index])) {
            // Si el requerimiento no existe, crear uno nuevo
            ComiteTesisRequerimientos::create([
                'id_tesis_comite' => $tesisComite->id_tesis_comite,
                'nombre_requerimiento' => $nombresRequerimientos[$index],
                'descripcion' => $descripcionesRequerimientos[$index],
            ]);
        }
    }
  
    }
    public function delete($id){
        
        $comiteTesis = TesisComite::findOrFail($id);
        $id_tesis = $comiteTesis->id_tesis;
        $tesis = Tesis::findOrFail($id_tesis);
        $requerimientos = ComiteTesisRequerimientos::where('id_tesis_comite', $comiteTesis->id_tesis_comite)->get();

        foreach ($requerimientos as $requerimiento) {
            $requerimiento->delete();
        }
        $comiteTesis->delete();
        $tesis->delete();

        alert()->success('La tesis se ha eliminado satisfactoriamente')->persistent(true,false);
        return redirect()->route("tesis.index");
    }
}
