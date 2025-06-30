<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index(){
         $estadosTesis = getEstadosTesisConConteo();
        $alumnosPrograma = getAlumnosPorPrograma();
        $tesisDocentes = $this->getTesisDocente();
        return view('admin.index',compact('estadosTesis','alumnosPrograma','tesisDocentes'));
    }

    function getTesisDocente(){  
        return DB::table('usuarios as u')
        ->join('usuario_tipo_usuario as utu', 'u.id_user', '=', 'utu.id_usuario')
        ->join('tipo_usuario as tu', 'tu.id_tipo', '=', 'utu.id_tipo')
        ->join('usuarios_comite as uc', 'uc.id_user', '=', 'u.id_user')
        ->join('tesis_comite as tc', 'tc.id_comite', '=', 'uc.id_comite') // vínculo entre comité y tesis
        ->where('tu.nombre_tipo', 'docente') // solo docentes
        ->select('u.nombre', DB::raw('COUNT(tc.id_tesis_comite) as total_tesis'))
        ->groupBy('u.id_user', 'u.nombre') // agrupar por docente
        ->get();
    }
}
