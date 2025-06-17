<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanesTrabajoActividades extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_actividad';
    protected $fillable = [
        'tema','descripcion','fecha_entrega'
    ];

    public function responsables(){
        return $this->belongsToMany(Usuarios::class, 'responsables', 'id_actividad','id_user' );
    }
}
