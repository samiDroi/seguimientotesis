<?php

namespace App\Http\Controllers\mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\Usuarios;


class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request){
        $usuario = Usuarios::where('correo_electronico', $request->correo_electronico)->first();
        $email = $request->get('correo_electronico');
        
        Mail::send('mails/ResetPassword', ['usuario' => $usuario->nombre], function($msj) use ($email){
            $msj->subject('Recuperacion de contraseña');
            $msj->to($email);
        });
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }
}
