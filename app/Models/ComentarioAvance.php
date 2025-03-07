<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioAvance extends Model
{
    use HasFactory;
    protected $table = 'comentario_avance';

    protected $fillable = [
        'contenido',
        'id_avance_tesis',
        'id_user'
    ];
}
