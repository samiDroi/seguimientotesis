<?php

namespace App\Http\Controllers\mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{
    public function sendMailConfirmation(Request $request){
        $newPassword = $request->get('password');
        $usuario = $request->get('username');
        $email = $request->get('correo_electronico');
        Mail::send('mails/AccountConfirmed', ['usuario' => $usuario,'newPassword' => $newPassword], function($msj) use ($email){
            $msj->subject('Confirmacion de cuenta');
            $msj->to($email);});

         return $newPassword;
    }
}
