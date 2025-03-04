<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AvanceTesis;
use App\Models\ComentarioAvance;
use App\Models\Comite;
use App\Models\ComiteTesisRequerimientos;
use App\Models\TesisComite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class avanceTesisController extends Controller{
    public function showAvance($id){
        $requerimiento = ComiteTesisRequerimientos::where('id_requerimiento',$id)->first();
        $avanceTesis = AvanceTesis::where('id_requerimiento',$id)->first();
        $comiteTesis = DB::table('comite_tesis_requerimientos as ctr')
        //juna tesis_comite con requerimientos
        ->join("tesis_comite as tc", "tc.id_tesis_comite", "=", "ctr.id_tesis_comite")
        //junta comite con tesis_comite
        ->join("comite as c", "c.id_comite", "=", "tc.id_comite")
        //filtrado
        ->where("ctr.id_requerimiento",$id)

        ->select("c.*")
        ->first();

        //dd($avanceTesis);
        return view('User.tesis.AvanceTesis',compact('requerimiento','avanceTesis','comiteTesis'));
    }

    public function createAvance($id,Request $request){
       $avance = AvanceTesis::where('id_requerimiento', $id)->first();
       //si el avance ya existia, se actualiza el avance 
       if($avance){
            $avance->contenido = $request->get('contenido');
            $avance->save();
            return redirect()->route('home');
        }else{
            //sino, se crea el avance
            AvanceTesis::create([
                'contenido' => $request->input('contenido'),
                'id_requerimiento' => $id,
            ]);
        }
        return redirect()->route('home');
    }

    public function comentarioAvance(Request $request){
        //dd($request->get('id_avance_tesis'));
        ComentarioAvance::create([
            'contenido' => $request->get('contenido'),
            'id_avance_tesis' => $request->input('id_avance_tesis'),
            'id_user' => Auth::user()->id_user
        ]);

        $requerimiento = ComiteTesisRequerimientos::join('avance_tesis', 'comite_tesis_requerimientos.id_requerimiento', '=', 'avance_tesis.id_requerimiento')
        ->where('avance_tesis.id_avance_tesis', $request->input('id_avance_tesis'))
        ->first(['comite_tesis_requerimientos.*']);

        return redirect()->route("avance.index",$requerimiento->id_requerimiento);
    }

    public function updateEstadoAvance(Request $request){
        $avance = AvanceTesis::findOrFail($request->get("id_avance"));

        // Validar que el estado esté permitido
        $validStates = ['EN CURSO', 'ACEPTADO', 'RECHAZADO'];
        if (!in_array($request->estado, $validStates)) {
            Alert::error("error","No se puede eliminar un comite si este tiene asignado una tesis, asegurese de eliminar primero la tesis antes que el comite");
            return redirect()->back();
        }

        // Actualizar el estado del requerimiento
        $avance->estado = $request->estado;
        $avance->save();

        alert()->success("El avance del requerimiento ha sido {$request->estado} satisfactoriamente.")->persistent(true,false);
        return redirect()->route('home');
    }
    
}