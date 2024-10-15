<?php
namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Controllers\mail\MailController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Http\Controllers\mail\ResetPasswordController;
use RealRashid\SweetAlert\Facades\Alert;

class ResetPwsdController extends Controller{
    public function index(){
        return view("auth.ForgotPassword");
    }


    public function resetPassword(Request $request){
        $request->validate([
            'correo_electronico' => 'required|email',
        ]);
        $usuario = Usuarios::where('correo_electronico', $request->correo_electronico)->first();
        if (!$usuario) {
            //Alert::error('Error', 'Este correo electrónico no está registrado, Por favor, verifica el correo e intenta de nuevo');
            return back()->withErrors(['correo_electronico' => 'Este correo electrónico no está registrado.']); 
        }
        
        $MailController = new ResetPasswordController;
        $MailController->resetPassword($request);
        //Alert::success('Correo confirmado','se ha enviado un correo de recuperacion de contraseña a tu email');
        return redirect('login');
    }
}
?>