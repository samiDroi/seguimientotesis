<?php

namespace App\Http\Controllers;

use App\Models\Tesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowInfoUser extends Controller
{
    public function showTesis(){
        $currentLayout = Auth::user()->esCoordinador ? 'layouts.admin' : 'layouts.base'; 
        $tesisUser = Auth::user()->tesis ? Auth::user()->tesis : '';
        // Obtener los comités del usuario autenticado
          // Obtener los comités del usuario
        $comites = Auth::user()->comites;
        //dd($comites->isNotEmpty());
    // Si el usuario tiene comités, obtener las tesis relacionadas a esos comités
    //dd($comites);
        if ($comites->isNotEmpty()) {
            $tesisComite = Tesis::whereIn('id_tesis', function($query) use ($comites) {
                $query->select('id_tesis')
                      ->from('tesis_comite')  // La tabla intermedia
                      ->whereIn('id_comite', $comites->pluck('id_comite')); // Filtrar por los comités del usuario
            })->get();
        } else {
            $tesisComite = null; // Si el usuario no tiene comités, devolver una colección vacía
        }

       
       
        return view('User.TesisInfo',compact('tesisUser','currentLayout','tesisComite'));
        // if(Auth::user()->esCoordinador){
            
        // }else{
        //     return view('User.TesisInfo',compact('tesisUser'))->with('layout','layouts.base');
        // }
        
       
        
    }
}
