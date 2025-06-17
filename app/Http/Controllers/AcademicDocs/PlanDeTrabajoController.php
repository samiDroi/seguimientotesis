<?php

namespace App\Http\Controllers\AcademicDocs;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use App\Models\PlanesTrabajo;
use App\Models\PlanesTrabajoActividades;
use App\Models\responsables;
use App\Models\TesisComite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanDeTrabajoController extends Controller
{
    public function index($id){
        $comite = Comite::findOrFail($id);
        return view('Director.PlanDeTrabajoForm',compact('comite'));
    }

    public function create(Request $request){
        
        $tesisComite = TesisComite::where('id_comite',$request->get('id_comite'))->first();
        
        $plan = new PlanesTrabajo();

        $plan->objetivo = $request->input('objetivo');
        $plan->metas = json_encode($request->input('metas'));
        $plan->criterios = json_encode($request->input('criterios'));
        $plan->compromisos = json_encode($request->input('compromisos'));
        $plan->id_tesis_comite = $tesisComite->id_tesis_comite;
        $plan->fecha_creacion = now();
        $plan->estado = 'EN CURSO';
        $plan->fecha_creacion = now();
        $plan->save();
        

        $this->createActividades($request,$plan->id_plan);
        
        return redirect()->route('comites.index');
    }

    public function createActividades(Request $request,$id){
        // dd($id);
        $actividades = $request->input('actividad');
        $descripciones = $request->input('descripcion');
        $fechas = $request->input('fecha_entrega');
        $responsables = $request->input('responsable');
        
        foreach ($actividades as $index => $actividad) {
//             dd([
//     'id_plan' => $id,
//     'tema' => $actividad,
//     'descripcion' => $descripciones[$index],
//     'fecha_entrega' => $fechas[$index],
// ]);

            $planActividad = PlanesTrabajoActividades::create([
                'id_plan' => $id,
                'tema' => $actividad,
                'descripcion' => $descripciones[$index],
                'fecha_entrega' => $fechas[$index],
            ]);
            Responsables::create([
                'id_actividad' => $planActividad->id_actividad,
                'id_user' => $responsables[$index],
            ]);
        }   
    }

    public function update(Request $request, $id_plan_original){
        // 1. Obtener el plan original
        $originalPlan = PlanesTrabajo::findOrFail($id_plan_original);

        // 2. Clonar los datos originales
        $newPlan = $originalPlan->replicate(); // Clona todos los atributos, excepto la clave primaria

        // 3. Modificar los campos con los valores nuevos
        $newPlan->objetivo = $request->input('objetivo', $originalPlan->objetivo);
        $newPlan->metas = json_encode($request->input('metas', json_decode($originalPlan->metas)));
        $newPlan->criterios = json_encode($request->input('criterios', json_decode($originalPlan->criterios)));
        $newPlan->compromisos = json_encode($request->input('compromisos', json_decode($originalPlan->compromisos)));

        $newPlan->fecha_creacion = now();
        $newPlan->estado = 'HISTORICO'; // O lo que sea necesario
        $newPlan->save();

        // 4. Si tienes actividades relacionadas, puedes clonarlas también (ver siguiente bloque)
        $actividades = $originalPlan->actividades; // Relación debe estar definida en el modelo

        foreach ($actividades as $actividad) {
            $newActividad = $actividad->replicate();
            $newActividad->id_plan = $newPlan->id_plan;
            $newActividad->save();

            // Clonar responsables también, si aplica
            foreach ($actividad->responsables as $responsable) {
                $newResponsable = $responsable->replicate();
                $newResponsable->id_actividad = $newActividad->id_actividad;
                $newResponsable->save();
            }
        }

        return redirect()->route('comites.index')->with('success', 'Plan de trabajo actualizado y versión guardada');
    }

    public function historial($id){
        
        $planMes = PlanesTrabajo::select(
            DB::raw('DATE_FORMAT(fecha_creacion, "%Y-%m") as mes'),
            'plan_trabajo.*'
        )
        
        ->orderBy('fecha_creacion', 'desc')
        ->get()
        ->groupBy('mes'); // Agrupa por año-mes
        
        return view('Director.HistorialPlanTrabajo',compact('planMes','id'));
    }
    public function createHistorial(){

    }
}
