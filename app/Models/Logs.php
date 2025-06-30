<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'Logs';
    protected $fillable = [
        'model_id','model','clave','descripcion','original','nuevo'
    ];
}
