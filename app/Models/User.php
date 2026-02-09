<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Notificaciones;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'numberDocument',
        'phone',
        'idTypeDocument',
        'idCity',
        'estado',
        'email',
        'centroCosto',
        'municipio',
        'password',
        'ActualizarPassword',
        'coordinador',
		'firma'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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
    protected $dates = ['fechaActualizarPassword'];

    public function state()
    {
        return $this->belongsTo(States::class, 'estado', 'id');
    }

    public function centroCostos()
    {
        return $this->belongsTo(CentroCostos::class, 'centroCosto', 'id');
    }

    public function notificacion()
    {
        //$notificaciones = Notificaciones::where('idUsuario',Auth::user()->id)->where('leido',0);
        return $this->belongsTo(Notificaciones::class, 'id', 'idUsuario');
    }
    public function notificacionQ()
    {
        //$notificaciones = Notificaciones::where('idUsuario',Auth::user()->id)->where('leido',0);
        return Notificaciones::where('idUsuario',$this->id)->where('leido',0)->count();
    }
}
