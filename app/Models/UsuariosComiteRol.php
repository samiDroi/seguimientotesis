<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UsuariosComiteRol extends Model
{
    use HasFactory;

    protected $table = 'usuarios_comite_roles';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_usuario_comite',
        'id_user_creador',
        'id_rol',
    ];

    public function usuarioComite(): BelongsTo
    {
        return $this->belongsTo(UsuariosComite::class, 'id_usuario_comite', 'id_usuario_comite');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'id_user_creador', 'id_user');
    }

    public function comite(): HasOne
    {
        return $this->hasOneThrough(
            Comite::class,
            UsuariosComite::class,
            'id_usuario_comite',
            'id_comite',
            'id_usuario_comite',
            'id_comite'
        );
    }
}