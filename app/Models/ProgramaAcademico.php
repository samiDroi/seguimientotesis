<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
