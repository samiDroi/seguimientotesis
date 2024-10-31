<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComiteRolUsusario extends Model
{
    use HasFactory;
    protected $table = 'comite_rol_usuario';
    protected $primaryKey = 'id_comite_rol';

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuarios::class, 'usuarios_comite', 'id_comite_rol', 'id_user');
    }
}
