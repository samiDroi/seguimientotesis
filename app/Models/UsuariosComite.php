<?php

namespace App\Models;

use App\Enums\Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosComite extends Model
{
    use HasFactory;
    protected $table = 'usuarios_comite';

    protected $fillable = ['rol'];
    
    // Cast para convertir el valor de la base de datos en una instancia del Enum
    protected $casts = [
        'rol' => Rol::class,
    ];

}
