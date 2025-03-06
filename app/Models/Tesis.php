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

    public function usuarios(): BelongsToMany{
        return $this->belongsToMany(Usuarios::class,"tesis_usuarios","id_tesis","id_user");
    }

        public function programas()
    {
        return $this->belongsToMany(ProgramaAcademico::class, 'tesis_programa_academico', 'id_tesis', 'id_programa');
    }

}
