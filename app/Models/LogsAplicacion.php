<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsAplicacion extends Model
{
    use HasFactory;
    protected $table = 'logs_aplicacion';
    protected $fillable = [
        'idUsuario',
        'Observacion',
    ];
}
