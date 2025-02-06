<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Usuarios extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'id_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];
    protected $table = 'usuarios';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function tipos(): BelongsToMany
    {
        return $this->belongsToMany(TipoUsuario::class,"usuario_tipo_usuario","id_usuario","id_tipo");
    }

    public function comites():BelongsToMany {
        return $this->belongsToMany(Comite::class,"usuarios_comite","id_user","id_comite");
    }
    
    public function programas():BelongsToMany{
        return $this->belongsToMany(ProgramaAcademico::class,"usuarios_programa_academico","id_user","id_programa");
    }

    public function roles():BelongsToMany{
        return $this->belongsToMany(ComiteRolUsusario::class,"usuarios_comite","id_user","id_comite_rol")->withPivot('id_comite');;
    }

    public function tesis():BelongsToMany{
        return $this->belongsToMany(Tesis::class,"tesis_usuarios","id_user","id_tesis");
    }
}
