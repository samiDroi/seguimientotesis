<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsuariosComite extends Model
{
    use HasFactory;

    protected $table = 'usuarios_comite';
    protected $primaryKey = 'id_usuario_comite';
    protected $fillable = ['id_user', 'id_comite'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'id_user');
    }

    public function comite(): BelongsTo
    {
        return $this->belongsTo(Comite::class, 'id_comite');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(UsuariosComiteRol::class, 'id_usuario_comite');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}