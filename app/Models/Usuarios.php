<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasThrough;

class Usuarios extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    protected $primaryKey = 'id_user';
    protected $table = 'usuarios';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function comites(): BelongsToMany
    {
        return $this->belongsToMany(Comite::class, 'usuarios_comite', 'id_user', 'id_comite')
            ->withPivot('id_usuario_comite', 'rol')
            ->withTimestamps();
    }

    public function tipos(): BelongsToMany
    {
        return $this->belongsToMany(TipoUsuario::class, "usuario_tipo_usuario", "id_usuario", "id_tipo");
    }

    public function programas(): BelongsToMany
    {
        return $this->belongsToMany(ProgramaAcademico::class, "usuarios_programa_academico", "id_user", "id_programa");
    }

    public function tesis(): BelongsToMany
    {
        return $this->belongsToMany(Tesis::class, "tesis_usuarios", "id_user", "id_tesis");
    }

    public function usuariosComite(): HasMany
    {
        return $this->hasMany(UsuariosComite::class, 'id_user');
    }

    public function usuariosComiteRol(): HasMany
    {
        return $this->hasMany(UsuariosComiteRol::class, 'id_user_creador');
    }

    public function rolesEnComites(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'usuarios_comite_roles',
            'id_user_creador',
            'id_rol'
        )->withPivot('id_usuario_comite', 'id_user_creador')
         ->distinct();
    }

    public function getRolesAttribute()
    {
        return $this->rolesEnComites()->get();
    }

    public function obtenerRolesUnicos()
    {
        return $this->rolesEnComites()
            ->select('roles.id_rol', 'roles.nombre_rol')
            ->distinct()
            ->get();
    }

    public function obtenerTesisPorRol($idRol)
    {
        $comitesDelUsuario = $this->comites()
            ->whereHas('usuariosComite', function ($query) use ($idRol) {
                $query->whereHas('roles', function ($q) use ($idRol) {
                    $q->where('roles.id_rol', $idRol);
                });
            })
            ->get();

        $tesisIds = collect();

        foreach ($comitesDelUsuario as $comite) {
            $tesisComite = $comite->tesis()->get();
            $tesisIds = $tesisIds->merge($tesisComite->pluck('id_tesis'));
        }

        return Tesis::whereIn('id_tesis', $tesisIds->unique())->get();
    }

    public function actividades()
    {
        return $this->belongsToMany(Usuarios::class, 'responsables', 'id_user', 'id_actividad');
    }
}