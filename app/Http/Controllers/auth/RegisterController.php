<?php

namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Models\Usuarios;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Controllers\mail\MailController;
    use Illuminate\Support\Facades\Validator;

    class RegisterController extends Controller{
       
        public function showRegister(){
            return view("auth/register");
        }

        public function register(Request $request){
            // Retornar true o false si la validación fue exitosa o no
            $validator = $this->validateUser($request);
            if ($validator->passes()) {
                $user = new Usuarios;
                $user->username = $request->get('username');
                $MailController = new MailController;
    
                $newPassword = $MailController->sendMailConfirmation($request);
    
                $user->password = Hash::make($newPassword); 
                $user->nombre = $request->get('nombre');
                $user->apellidos = $request->get('apellidos');
                $user->correo_electronico = $request->get('correo_electronico');
                $user->save();
    
                return redirect('login')->with('success', 'Registro exitoso. Revisa tu correo para la confirmación.');
            } else {
                foreach ($validator->errors()->all() as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            }
    
        }

        public function validateUser(Request $request){
           return $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:usuarios,username',
                'correo_electronico' => 'required|string|email|max:255|unique:usuarios,correo_electronico',]);
        }
     
    }

    
    //password to send email
    

    
?>

