<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanesTrabajo extends Model
{
    use HasFactory;

    protected $table = 'plan_trabajo';
    protected $primaryKey = 'id_plan';
    protected $fillable = [
        'objetivos','metas','criterios','compromisos','fecha_creacion','estado'
    ];
    protected $casts = [
    'metas' => 'array',
    'criterios' => 'array',
    'compromisos' => 'array',
];

  public function actividades()
    {
        return $this->hasMany(PlanesTrabajoActividades::class, 'id_plan', 'id_plan');   
    }
}
