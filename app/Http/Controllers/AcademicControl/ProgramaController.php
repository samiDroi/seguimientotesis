<?php

namespace App\Http\Controllers\AcademicControl;
    use App\Http\Controllers\Controller;
    use App\Models\UnidadAcademica;
    use App\Models\ProgramaAcademico;
    use RealRashid\SweetAlert\Facades\Alert;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;


    class ProgramaController{

        //funciones que devuelven vistas
        public function index($id_unidad){
            $unidad = UnidadAcademica::with("programas")->findOrFail($id_unidad);
            session(['id_unidad' => $id_unidad]);
            return view("Admin/Programas/index",compact("unidad"));
        }
        //a
        public function store(){
            return view("Admin/Programas/Create");
        }

        public function edit($id){
            $programa = ProgramaAcademico::findOrFail($id);
            return view("Admin/Programas/Edit",compact('programa'));
        }
        //funciones que generan las operaciones en la db
        public function create(Request $request){
            $nuevosProgramas = $request->input('nombre_programa',[]);
            $id_unidad = session("id_unidad");

            foreach ($nuevosProgramas as $nombrePrograma) {
                // Validar cada programa individualmente
                $validator = $this->validateStored($nombrePrograma,$nombrePrograma);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        echo "<p style='color: red;'>$error</p>";
                    }
                } else {
                    $nuevoPrograma = new ProgramaAcademico();
                    $nuevoPrograma->nombre_programa = $nombrePrograma;
                    $nuevoPrograma->id_unidad = $id_unidad;
                    $nuevoPrograma->save(); // Guarda el nuevo programa
                }
            }

            return redirect()->route("programas.index",$id_unidad);
            
        }


        public function update(Request $request,$id){
            $programa = ProgramaAcademico::findOrFail($id);
            $nombrePrograma = $request->input('nombre_programa');
            $id_unidad = session("id_unidad");
            // Validar el nuevo nombre del programa, excluyendo el actual
            $validator = $this->validatedEdit($request,$id);
            if ($validator->fails()) {
                // Manejar el error de validación
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                // Actualizar el programa
                $programa->nombre_programa = $nombrePrograma;
                $programa->save();
            }     
            return redirect()->route("programas.index",$id_unidad);
        }

        public function delete($id){
            $programa = ProgramaAcademico::findOrFail($id);
            $programa->delete();
            return redirect()->route('programas.index',$programa->id_unidad);
        }

        //funciones que validan que lo que se edite o agregue no este ya en la DB
        public function validateStored($nombrePrograma,$id){
            return Validator::make(['nombre_programa' => $nombrePrograma], [ // Aquí cambiamos a un array con el nombre
                'nombre_programa' => [  
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('programa_academico')->ignore($id,"id_programa"),
                ],
            ]);
        }

        public function validatedEdit(Request $request, $id){
            return Validator::make($request->all(), [
                        'nombre_programa' => [
                            'required',
                            'string',
                            'max:255',
                            Rule::unique('programa_academico')->ignore($id, 'id_programa'), // Ignora el ID específico al validar
                        ],
                    ]);
        }
}
?>