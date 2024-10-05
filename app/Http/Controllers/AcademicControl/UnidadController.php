<?php
namespace App\Http\Controllers\AcademicControl;
    use App\Http\Controllers\Controller;
    use App\Models\UnidadAcademica;
    use App\Models\ProgramaAcademico;
    use RealRashid\SweetAlert\Facades\Alert;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class UnidadController extends Controller{
        public function index(){
            $unidades = UnidadAcademica::all();
            return view('Admin/Unidades/index',compact("unidades"));
        }
        
        public function store(){
            return view("Admin/Unidades/Create");
        }
        
        
        public function edit($id){
            $unidad = UnidadAcademica::findOrFail($id);
            return view("Admin/Unidades/Edit",compact("unidad"));
        }

        public function create(Request $request){
            $unidad = new UnidadAcademica();
            $unidad->nombre_unidad=$request->input('nombre_unidad');
            $validator = $this->validateUnidad($request);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            }else {
                $unidad->nombre_unidad=$request->input('nombre_unidad');
                $unidad->save();
                return redirect()->route('unidades.index');
            }
            
        }

        public function update(Request $request,$id){
            $unidad = UnidadAcademica::findOrFail($id);
            $validator = $this->validateUnidad($request);
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            }else{
                $unidad->nombre_unidad = $request->input('nombre_unidad');
                $unidad->save();
                return redirect()->route('unidades.index');
            }

        }

        public function delete($id){
            ProgramaAcademico::where('id_unidad',$id)->delete();
            $unidad = UnidadAcademica::findOrFail($id);
            $unidad->delete();
            return redirect()->route('unidades.index');
        }

        public function validateUnidad(Request $request){
            return $validator = Validator::make($request->all(), [
                'nombre_unidad' => 'required|string|max:255|unique:unidad_academica,nombre_unidad',
            ]);
        }

        public function getUnidades(){
            return UnidadAcademica::all();
        }
    }
?>