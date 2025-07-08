<?php

namespace App\Models;

use App\Enums\Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosComite extends Model
{
    use HasFactory;
    protected $table = 'usuarios_comite';
    protected $primaryKey = 'id_usuario_comite';
    protected $fillable = ['rol','id_user','id_comite'];
    
    // Cast para convertir el valor de la base de datos en una instancia del Enum
    protected $casts = [
        'rol' => Rol::class,
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
