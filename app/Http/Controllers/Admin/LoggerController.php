<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use Illuminate\Http\Request;

class LoggerController extends Controller
{
    public function logRegister($model_id, $model, $clave, $descripcion, $original, $nuevo){
        Logs::create([
            'model_id' => $model_id,
            'model' => $model,
            'clave' => $clave,
            'descripcion' => $descripcion,
            'original' => $original,
            'nuevo' => $nuevo,
        ]);
    }
}
