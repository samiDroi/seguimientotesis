<?php
namespace App\Http\Controllers\Auth;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

    class LoginController extends Controller{

        public function showLogin(){
            return view("auth/login");
        }

        public function login(Request $request){
            //echo $request->get("username");
            $credencialsTrabajador = ["username" => $request->get("username"), "password" => $request->get("password")];

            $credencialsAlumno = ["matricula" => $request->get("username"), "password" => $request->get("password")];
            if (Auth::attempt($credencialsTrabajador)) {
                $user = Auth::user();

                // Verificar si es coordinador
                if ($user->esCoordinador == 1) {
                    return redirect()->route("administrador"); // Redirigir a la vista del coordinador
                } else {
                    return redirect()->route("home"); // Redirigir a la vista general
                }
            }elseif(Auth::attempt($credencialsAlumno)){
                $user = Auth::user();
                // Verificar si es coordinador
                if ($user->esCoordinador == 1) {
                    return redirect()->route("administrador"); // Redirigir a la vista del coordinador
                } else {
                    return redirect()->route("home"); // Redirigir a la vista general
                }
            }else{
                Alert::error("Error","Los datos ingresados no pertenecen a ningun usuario, verifiquelos de nuevo y vuelva a intentarlo");
                return redirect('/login')->withErrors('auth.fail');
            }
        }

        public function logout(Request $request){
            Auth::logout(); // Cierra la sesión

            $request->session()->invalidate(); // Invalida la sesión
            $request->session()->regenerateToken(); // Regenera el token CSRF

            return redirect()->route('login'); // Redirige al login
        }

    }
?>