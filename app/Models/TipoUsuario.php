<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class TipoUsuario extends Model
{
    use HasFactory;
    protected $table = 'tipo_usuario';
    protected $primaryKey = 'id_tipo';

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuarios::class,"usuario_tipo_usuario","id_tipo","id_usuario");
    }
}
