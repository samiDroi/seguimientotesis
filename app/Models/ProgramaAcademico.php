<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProgramaAcademico extends Model
{
  
    use HasFactory;
    protected $table = 'programa_academico';
    protected $fillable = ['nombre_programa'];
    protected $primaryKey = 'id_programa'; 

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(UnidadAcademica::class,"id_unidad","id_unidad");
    }

    public function usuarios(): BelongsToMany{
        return $this->belongsToMany(Usuarios::class,"usuarios_programa_academico","id_programa","id_user");
    }

    public function comites()
    {
        return $this->hasMany(Comite::class, 'id_programa');  // 'id_programa' es la clave foránea en la tabla Comite
    }

        public function tesis()
    {
        return $this->belongsToMany(Tesis::class, 'tesis_programa_academico', 'id_programa', 'id_tesis');
    }

}
