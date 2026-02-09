<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifasComision extends Model
{
    use HasFactory;
    protected $table = 'tarifa_comision';
    protected $fillable = [
        'id',
        'tipo',
        'tarifa'
    ];
}
