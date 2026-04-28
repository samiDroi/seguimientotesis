<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $fillable = ['nombre_rol'];

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permiso::class, 'roles_permisos', 'id_rol', 'id_permisos');
    }

    public function usuariosComite(): HasMany
    {
        return $this->hasMany(UsuariosComiteRol::class, 'id_rol', 'id_rol');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuarios::class,
            'usuarios_comite_roles',
            'id_rol',
            'id_user_creador'
        )->withPivot('id_usuario_comite');
    }
}