<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosComiteRol extends Model
{
    use HasFactory;
    protected $table = 'usuarios_comite_roles';
    protected $fillable = [
        'id_usuario_comite',
        'id_user_creador',
        'id_rol',
        'rol_personalizado', // Si tienes otros campos, agrégales aquí también
    ];
}
