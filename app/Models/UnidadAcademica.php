<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadAcademica extends Model
{
    use HasFactory;
    protected $table = 'unidad_academica';
    protected $fillable = ['nombre_unidad'];
    protected $primaryKey = 'id_unidad';

    public function programas()
    {
        return $this->hasMany(ProgramaAcademico::class,"id_unidad","id_unidad");
    }
}
