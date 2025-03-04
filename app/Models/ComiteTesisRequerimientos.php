<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComiteTesisRequerimientos extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_requerimiento';
    protected $table = 'comite_tesis_requerimientos';
    protected $fillable = [
        'nombre_requerimiento', // Agregar el campo 'nombre_requerimiento'
        'descripcion',          // Si 'descripcion' también es asignada masivamente
        'id_tesis_comite',      // También asegurarse de incluir este campo si es necesario
    ];
    // public function requerimientos()
    // {
    //     return $this->belongsTo(ComiteTesisRequerimientos::class, 'id_tesis_comite');
    // }
    public function tesisComite()
    {
        return $this->belongsTo(TesisComite::class, 'id_tesis_comite','id_tesis_comite');
    }

 
    
    
    public function avances()
    {
        return $this->hasMany(AvanceTesis::class, 'id_requerimiento', 'id_requerimiento');
    }

}
