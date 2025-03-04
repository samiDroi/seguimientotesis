<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceTesis extends Model
{
    use HasFactory;
    protected $table = 'avance_tesis';
    protected $primaryKey = 'id_avance_tesis';
    protected $fillable = ['contenido', 'id_requerimiento'];

    
}
