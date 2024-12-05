<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tesis extends Model
{
    use HasFactory;
    protected $table = 'tesis';
    protected $fillable = [
        'nombre_tesis',
        // Agrega otros campos que también quieras permitir para asignación masiva
    ];
    protected $primaryKey = 'id_tesis';
    
    public function comites(): BelongsToMany
    {
        return $this->belongsToMany(Comite::class,"tesis_comite","id_tesis","id_comite");
    }

}
