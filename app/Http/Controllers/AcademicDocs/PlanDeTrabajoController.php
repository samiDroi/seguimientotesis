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
        $plan->save();
        $this->createActividades($request,$plan->id_plan);
        // $planTrabajo = DB::table('tesis_comite as tc')
        // ->join('comite as c','c.id_comite','=','tc.id_comite')
        // ->join('')
        return redirect()->route('comites.index');

    }
    public function createActividades(Request $request,$id){
        $actividades = $request->input('actividad');
        $descripciones = $request->input('descripcion');
        $fechas = $request->input('fecha_entrega');
        $responsables = $request->input('responsable');
        
        foreach ($actividades as $index => $actividad) {
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
