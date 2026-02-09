<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlDiasFestivos extends Model
{
    use HasFactory;
    protected $table = 'control_dias_festivos';
    protected $fillable = [
        'fecha',
        'observacion',
    ];
    protected $dates = [
        'fecha'
    ];
}
