<?php
namespace App\Http\Controllers\Admin;
    use App\Http\Controllers\Controller;
    use App\Models\Usuarios;
    use RealRashid\SweetAlert\Facades\Alert;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\DB;

    class ShowUsers extends Controller{

        public function index(Request $request){
            $usuarios = Usuarios::with('tipos')->get();
            //$ultimoUsuario = Usuarios::with('tipos')->latest();
            //dd($ultimoUsuario);
            
            return view("Admin.Users.index",compact("usuarios"));
        }

        public function edit($id_user){
            $usuario = Usuarios::find($id_user);
            $tiposUsuario = DB::table('usuario_tipo_usuario')
            ->join('tipo_usuario', 'usuario_tipo_usuario.id_tipo', '=', 'tipo_usuario.id_tipo')
            ->where('usuario_tipo_usuario.id_usuario', $id_user)
            ->select('tipo_usuario.id_tipo')  // Aquí especificamos de qué tabla viene `id_tipo`
            ->pluck('tipo_usuario.id_tipo')   // Recoge los `id_tipo` de `tipo_usuario`
            ->toArray(); // Recupera los tipos de usuario seleccionados
        
            return view("Admin.Users.Edit",compact("usuario","tiposUsuario"));
        }

        public function update(Request $request,$id){
            $usuario = Usuarios::findOrFail($id);
            
            
            $validator = $this->validateEditUser($request,$id);
            
            if($validator->fails()){
                // dd($validator->fails(), $validator->errors()); 
                foreach ($validator->errors()->all() as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
                
            }else{
                $usuario->correo_electronico = $request->correo_electronico;
                $usuario->nombre = $request->nombre;
                $usuario->apellidos = $request->apellidos;
                $usuario->username = $request->username;
                $usuario->save(); // Guarda los cambios en la tabla `usuarios`
            
                // Sincronizar los tipos de usuario en la tabla pivote `usuario_tipo_usuario`
                $usuario->tipos()->sync($request->nombre_tipo);
                return redirect("/admin/users");
            }
            
        
        }

        public function delete($id){
            $usuario = Usuarios::findOrFail($id);
            $usuario->delete();
            return redirect("/admin/users");
        }

        public function validateEditUser(Request $request,$id){
            return Validator::make($request->all(), [
                'correo_electronico' => [
                    'required',
                    'email',
                    Rule::unique('usuarios',"correo_electronico")->ignore($id, 'id_user'), // Ignora el ID específico al validar
                ],
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('usuarios',"username")->ignore($id, 'id_user'), // Ignora el ID específico al validar
                ],
                'nombre_tipo' => 'required|array', // Debe ser un array
                'nombre_tipo.*' => 'integer|exists:tipo_usuario,id_tipo', // Cada tipo debe existir en la tabla `tipo_usuario`
            ]);
        
        }
        
    }
?>