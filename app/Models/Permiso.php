<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;
    protected $table = 'permisos';

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'roles_permisos', 'id_permisos', 'id_rol');
    }
}
