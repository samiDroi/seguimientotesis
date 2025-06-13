<?php

namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Models\Usuarios;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Controllers\mail\MailController;
    use App\Models\ProgramaAcademico;
use App\Models\TipoUsuario;
use App\Models\UnidadAcademica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

    class RegisterController extends Controller{
       
        public function showRegister(){
            $unidades = UnidadAcademica::all();
            $programas = ProgramaAcademico::all();
            $tiposUsuario = TipoUsuario::all();
            return view("auth/register",compact("unidades","programas","tiposUsuario"));
        }

        public function register(Request $request){
            //Retornar true o false si la validación fue exitosa o no
            $validator = $this->validateUser($request);

            
            if (!$validator->fails()) {
                $user = new Usuarios;
                $user->username = $request->get('username');

                $MailController = new MailController;
    
                $newPassword = $MailController->sendMailConfirmation($request);
    
                $user->password = Hash::make($newPassword); 
                $user->nombre = $request->get('nombre');
                $user->apellidos = $request->get('apellidos');
                $user->correo_electronico = $request->get('correo_electronico');
                $user->generacion = $request->get('generacion');
                // Obtener el ID del tipo "Coordinador"
                $coordinadorId = DB::table('tipo_usuario')->where('nombre_tipo', 'Coordinador')->value('id_tipo');

                // Verificar si el ID del coordinador está en el array enviado
                $user->esCoordinador = in_array($coordinadorId, $request->nombre_tipo) ? 1 : 0;
                $user->save();
                //asignar los programas academicos a los usuarios
                $user->programas()->attach($request->input('id_programa'));
                //asignar los tipos de usuario en la tabla
                $user->tipos()->sync($request->nombre_tipo);
                alert()->success("El usuario se ha registrado satisfactoriamente")->persistent(true,false);
                return redirect()->route('login');
            } else {
                Alert::error("Error","este usuario ya existe en el sistema, favor de verificar los datos");
                return redirect()->route('register.index');
                // foreach ($validator->errors()->all() as $error) {
                //     echo "<p style='color: red;'>$error</p>";
                // }
            }
    
                // $user = new Usuarios;
                // $user->username = $request->get('username');

                // $MailController = new MailController;
    
                // $newPassword = $MailController->sendMailConfirmation($request);
    
                // $user->password = Hash::make($newPassword); 
                // $user->nombre = $request->get('nombre');
                // $user->apellidos = $request->get('apellidos');
                // $user->correo_electronico = $request->get('correo_electronico');
               
                // $user->save();
                // //asignar los programas academicos a los usuarios
                // $user->programas()->attach($request->input('id_programa'));
                // //asignar los tipos de usuario en la tabla
                // $user->tipos()->sync($request->nombre_tipo);
               
                // return redirect()->route('login');
           
        }

        public function validateUser(Request $request){
           return $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:usuarios,username',
                'correo_electronico' => 'required|string|email|max:255|unique:usuarios,correo_electronico',]);
        }

    }
?>

