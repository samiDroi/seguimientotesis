<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusTesisController;
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
        $contentHTML = $avanceTesis?->id_avance_tesis ? ComentarioAvance::where('id_avance_tesis', $avanceTesis->id_avance_tesis)->whereNotNull('contenido_original')->latest()->first() : null;
        
        return view('User.tesis.AvanceTesis',compact('requerimiento','avanceTesis','comiteTesis','contentHTML'));
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

    // public function comentarioAvance(Request $request){
    //     // dd($request->all());
    //     //dd($request->get('id_avance_tesis'));
    //     //dd($request->get('contenido'));
    //     ComentarioAvance::create([
    //         'comentario' => $request->get('contenido'),
    //         'id_avance_tesis' => $request->input('id_avance_tesis'),
    //         'id_user' => Auth::user()->id_user,
    //         'contenido_original' => $request->get('contenido_original')
    //     ]);
   
    //     $requerimiento = ComiteTesisRequerimientos::join('avance_tesis', 'comite_tesis_requerimientos.id_requerimiento', '=', 'avance_tesis.id_requerimiento')
    //     ->where('avance_tesis.id_avance_tesis', $request->input('id_avance_tesis'))
    //     ->first(['comite_tesis_requerimientos.*']);

    //     return redirect()->route("avance.index",$requerimiento->id_requerimiento);
    // }
        public function comentarioAvance(Request $request)
        {
            // Buscar el primer registro asociado a ese avance_tesis
            $comentario = ComentarioAvance::where('id_avance_tesis', $request->input('id_avance_tesis'))
                ->first();
            // dd($comentario);
            if ($comentario) {
                // Si existe, lo actualizamos
                $comentario->update([
                    'comentario' => $request->get('contenido'),
                    'id_user' => Auth::user()->id_user,
                    'contenido_original' => $request->get('contenido_original'),
                ]);
            } else {
                // Si no existe, lo creamos (opcional, por si quieres garantizar que exista)
                ComentarioAvance::create([
                    'comentario' => $request->get('contenido'),
                    'id_avance_tesis' => $request->input('id_avance_tesis'),
                    'id_user' => Auth::user()->id_user,
                    'contenido_original' => $request->get('contenido_original'),
                ]);
            }

            // Relacionar con requerimiento
            $requerimiento = ComiteTesisRequerimientos::join(
                'avance_tesis', 
                'comite_tesis_requerimientos.id_requerimiento', 
                '=', 
                'avance_tesis.id_requerimiento'
            )
            ->where('avance_tesis.id_avance_tesis', $request->input('id_avance_tesis'))
            ->first(['comite_tesis_requerimientos.*']);

            return redirect()->route("avance.index", $requerimiento->id_requerimiento);
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
        $statusAvance = new StatusTesisController;
        $statusAvance->statusTesisToPorEvaluar();
        alert()->success("El avance del requerimiento ha sido {$request->estado} satisfactoriamente.")->persistent(true,false);
        if (Auth::user()->isAdmin) {
            return redirect()->route('tesis.index');
        }
        return redirect()->route('home');
    }

    function getInfoComentarioAvance($id_requerimiento, $userId){
        return DB::table('comentario_avance as ca')
        ->join('avance_tesis as at', 'ca.id_avance_tesis', '=', 'at.id_avance_tesis')
        ->join('usuarios as u', 'ca.id_user', '=', 'u.id_user')
        ->join('usuarios_comite as uc', 'u.id_user', '=', 'uc.id_user')
        ->join('comite as c','c.id_comite', '=', 'uc.id_comite')
        ->join('tesis_comite as tc', 'c.id_comite', '=', 'tc.id_comite')
        ->join('comite_tesis_requerimientos as ctr', 'tc.id_tesis_comite', '=', 'ctr.id_tesis_comite')
        // Subquery de roles
        ->leftJoin(DB::raw('(SELECT uc.id_usuario_comite, GROUP_CONCAT(DISTINCT ucr.rol_personalizado SEPARATOR ", ") as roles
                            FROM usuarios_comite_roles ucr
                            JOIN usuarios_comite uc ON ucr.id_usuario_comite = uc.id_usuario_comite
                            GROUP BY uc.id_usuario_comite
                        ) as roles_table'), 'uc.id_usuario_comite', '=', 'roles_table.id_usuario_comite')
        ->where('at.id_requerimiento', $id_requerimiento)
        ->where('u.id_user',$userId)
        ->select(
            'ca.*',
            'u.nombre as usuario_nombre',
            'u.apellidos as usuario_apellidos',
            'roles_table.roles as usuario_roles',
            'ca.comentario as contenido',
            'ctr.*'
        )
         ->orderBy('ca.created_at', 'desc')// ->groupBy('ca.id_avance_tesis')  // Esto asegura que no se repitan
        ->first();
    }

    function deleteComentarioAvance($id_comentario){
        $comentario = ComentarioAvance::findOrFail($id_comentario);
        $comentario->delete();
        alert()->success("El comentario ha sido eliminado satisfactoriamente.")->persistent(true,false);
        return redirect()->back();
    }
    
}
