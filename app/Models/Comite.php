<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comite extends Model
{
    use HasFactory;
    protected $table = 'comite';
    protected $primaryKey = 'id_comite';

    public function usuarios():BelongsToMany {
        return $this->belongsToMany(Usuarios::class,"usuarios_comite","id_comite","id_user");
    }

    public function roles()
    {
        return $this->hasMany(ComiteRolUsusario::class,"id_comite","id_comite_rol");
    }

    public function tesis(): BelongsToMany
    {
        return $this->belongsToMany(Tesis::class,"tesis_comite","id_comite","id_tesis");
    }
}
