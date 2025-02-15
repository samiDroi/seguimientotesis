<?php
namespace App\Http\Controllers\Site;

use App\Models\ComiteTesisRequerimientos;
use App\Models\TesisComite;

class avanceTesisController{
    public function showAvance($id){
        $requerimiento = ComiteTesisRequerimientos::where('id_requerimiento',$id)->first();
        //  dd($requerimiento);
        return view('User.tesis.AvanceTesis',compact('requerimiento'));
    }

}