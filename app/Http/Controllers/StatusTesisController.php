@ -1,54 +0,0 @@
<?php

namespace App\Http\Controllers;

use App\Models\Tesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class StatusTesisController extends Controller
{

    
    public function statusTesisToEnCurso($id){
        // Obtener el ID de la tesis a partir del requerimiento (id_requerimiento)
        $tesisId = DB::table('comite_tesis_requerimientos as ctr')
        ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite') // Relaciona con tesis_comite
        ->where('ctr.id_requerimiento', $id) // Filtra por el ID del requerimiento
        ->value('tc.id_tesis'); // Obtiene el id_tesis de la tesis asociada al requerimiento

        // Verificar si aún existen requerimientos pendientes para la tesis
        $requerimientosPendientes = DB::table('comite_tesis_requerimientos as ctr')
            ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite')
            ->where('tc.id_tesis', $tesisId) // Filtra por la tesis obtenida
            ->where('ctr.estado', 'PENDIENTE') // Verifica si el estado del requerimiento es pendiente
            ->count(); // Cuenta cuántos requerimientos están pendientes

        // Si no hay requerimientos pendientes, cambiamos el estado de la tesis a "EN CURSO"
        if ($requerimientosPendientes == 0) {
            DB::table('tesis')
                ->where('id_tesis', $tesisId)
                ->update(['estado' => 'EN CURSO']); // Actualiza el estado de la tesis
        }
    }

    public function statusTesisToPorEvaluar(){
        // Obtener la tesis con sus avances
        $tesis = DB::table('tesis as t')
            ->join('tesis_comite as tc', 't.id_tesis', '=', 'tc.id_tesis')
            ->join('comite as c', 'tc.id_comite', '=', 'c.id_comite')
            ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
            ->join('avance_tesis as at', 'ctr.id_requerimiento', '=', 'at.id_requerimiento')
            ->select('t.id_tesis', 't.nombre_tesis', 't.estado')
            ->groupBy('t.id_tesis')
            ->havingRaw('SUM(CASE WHEN at.estado != ? THEN 1 ELSE 0 END) = 0', ['ACEPTADO'])
            ->get();

            Foreach($tesis as $t){
                DB::table('tesis')
                ->where('id_tesis', $t->id_tesis)
                ->update(['estado' => 'POR EVALUAR']);
            }
    }
}