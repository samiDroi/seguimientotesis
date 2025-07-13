<?php 
namespace App\Services;

use App\Models\Logs;
 /**
     * Registra un log de cambios.
     * @param int        $model_id      Id del modelo afectado
     * @param string     $clave         Clave del tipo de operacion realizada.
     * @param string     $modelo        Nombre del modelo afectado.
     * @param string     $original      Datos originales.
     * @param string     $nuevo         Nuevos datos.
     * @param string     $descripcion   Descripcion del tipo de operacion
     */
class LogService {

    public static function logRegister($model_id, $model, $clave, $descripcion, $original, $nuevo){
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


