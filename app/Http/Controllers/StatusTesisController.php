<?php

namespace App\Http\Controllers;

use App\Models\Tesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusTesisController extends Controller
{

    
    public function statusTesisToEnCurso($id){
        // // Obtener todos los requerimientos de la tesis a partir del ID del requerimiento
        // $requerimientos = DB::table('comite_tesis_requerimientos as ctr')
        // ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite') // Relaciona con tesis_comite
        // ->join('tesis as t', 'tc.id_tesis', '=', 't.id_tesis') // Relaciona con tesis
        // ->where('ctr.id_requerimiento', $id) // Filtra por el ID del requerimiento
        // ->where('t.estado','PENDIENTE')
        // ->select('ctr.*') // Selecciona todos los requerimientos
        // ->count();

        // if($requerimientos >= 1){
        //     $tesisId = DB::table('comite_tesis_requerimientos as ctr')
        //     ->join('tesis_comite as tc', 'ctr.id_tesis_comite', '=', 'tc.id_tesis_comite') // Relaciona con tesis_comite
        //     ->where('ctr.id_requerimiento', $id) // Filtra por el ID del requerimiento
        //     ->value('tc.id_tesis'); // Obtiene el id_tesis de la tesis asociada al requerimiento
            
        //     DB::table('tesis')
        //     ->where('id_tesis', $tesisId)
        //     ->update(['estado' => 'EN CURSO']);
        // }
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
}
