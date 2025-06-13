<?php

namespace App\Http\Controllers\AcademicDocs;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use Illuminate\Http\Request;

class PlanDeTrabajoController extends Controller
{
    public function index($id){
        // $integrantes = getRolesComite($id);
        // $alumno = DB::table('');
        $comite = Comite::findOrFail($id);
        return view('Director.PlanDeTrabajoForm',compact('comite'));
    }
}
