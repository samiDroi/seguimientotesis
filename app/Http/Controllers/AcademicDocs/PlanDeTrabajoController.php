<?php

namespace App\Http\Controllers\AcademicDocs;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use App\Models\PlanesTrabajo;
use App\Models\PlanesTrabajoActividades;
use App\Models\responsables;
use App\Models\Tesis;
use App\Models\TesisComite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


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
        $plan->metas = $request->input('meta');
        $plan->criterios = $request->input('criterios');
        $plan->compromisos = $request->input('compromisos');
        $plan->id_tesis_comite = $tesisComite->id_tesis_comite;
        $plan->fecha_creacion = now();
        $plan->estado = 'EN CURSO';
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

    public function edit($id_comite,$id_plan){
        $plan = PlanesTrabajo::with('actividades')->findOrFail($id_plan);

        $comite = Comite::findOrFail($id_comite);
        return view('Director.PlanDeTrabajoForm',compact('plan','comite'));
    }

    public function update(Request $request, $id_plan_original){
        // 1. Obtener el plan original
        $originalPlan = PlanesTrabajo::findOrFail($id_plan_original);
        $originalPlan->estado = 'HISTORICO';
        // 2. Clonar los datos originales
        $newPlan = $originalPlan->replicate(); // Clona todos los atributos, excepto la clave primaria
        $newPlan->objetivo = $request->input('objetivo', $originalPlan->objetivo);
        $newPlan->metas = $request->input('meta', $originalPlan->metas);
        $newPlan->criterios = $request->input('criterios', $originalPlan->criterios);
        $newPlan->compromisos = $request->input('compromisos', $originalPlan->compromisos);
        $newPlan->fecha_creacion = now();
        $newPlan->estado = 'EN CURSO'; // O lo que sea necesario
        $newPlan->save();
        $originalPlan->save();

        $this->createActividades($request,$newPlan->id_plan);
        return redirect()->route('comites.index')->with('success', 'Plan de trabajo actualizado y versión guardada');
    }

    public function historial($id){
        $tesisComite = TesisComite::where('id_comite',$id)->first();
        $planExist = PlanesTrabajo::where('id_tesis_comite',$tesisComite->id_tesis_comite)->first();
        if ($planExist) {
             $planMes = PlanesTrabajo::select(
            DB::raw('DATE_FORMAT(fecha_creacion, "%Y-%m") as mes'),
            'plan_trabajo.*'
            )
            ->where('id_tesis_comite',$tesisComite->id_tesis_comite)
            ->orderBy('fecha_creacion', 'desc')
            ->get()
            ->groupBy('mes'); // Agrupa por año-mes
            
            return view('Director.HistorialPlanTrabajo',compact('planMes','id'));
        }else{
            return redirect()->route('plan.index',$id);
        }
        
       
    }
    
    public function exportarPDF($id_plan) {
    $plan = PlanesTrabajo::with('actividades')->findOrFail($id_plan);

    $comiteUsuarios = DB::table('plan_trabajo as pt')
        ->join('tesis_comite as tc','tc.id_tesis_comite','=','pt.id_tesis_comite')
        ->join('comite as c','c.id_comite','=','tc.id_comite')
        ->join('tesis as t','t.id_tesis','=','tc.id_tesis')
        ->join('tesis_usuarios as tu','tu.id_tesis','=','t.id_tesis')
        ->join('usuarios as u','u.id_user','=','tu.id_user')
        ->where('pt.id_plan', $plan->id_plan)
        ->select(
            'c.*',
            'u.nombre as alumno_nombre',
            'u.apellidos as alumno_apellidos',
            'u.generacion',
            't.nombre_tesis',
            'tc.id_comite',
            't.id_tesis'
        )
        ->get();
        
    // Agrupar por ID de tesis (clave única)
    $alumnosPorTesis = $comiteUsuarios->groupBy('id_tesis');
    $comite = $comiteUsuarios->first();
    
    $rolesComite = getRolesComite($comite->id_comite);
    $pdf = Pdf::loadView('Docs.PlanDeTrabajo', [
        'plan' => $plan,
        'alumnosPorTesis' => $alumnosPorTesis,
        'comiteUsuarios' => $comiteUsuarios,
        'rolesComite' => $rolesComite
    ]);

    return $pdf->stream('plan_trabajo_'.$id_plan.'.pdf');
}

}
