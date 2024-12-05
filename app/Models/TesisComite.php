<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesisComite extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_tesis_comite';
    protected $table = 'tesis_comite';
    protected $fillable = [
        'id_tesis',
        'id_comite', // Si también usas este campo en asignación masiva
        // Agrega otros campos necesarios
    ];

    public function tesis()
    {
        return $this->belongsTo(Tesis::class, 'id_tesis');
    }

    // Relación con Comite
    public function comite()
    {
        return $this->belongsTo(Comite::class, 'id_comite');
    }

    // Relación con Requerimientos (si es necesario)
    public function requerimientos()
    {
        return $this->hasMany(ComiteTesisRequerimientos::class, 'id_tesis_comite');
    }
}
