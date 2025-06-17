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
}
