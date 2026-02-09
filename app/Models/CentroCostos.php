<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCostos extends Model
{
    use HasFactory;
    protected $table = 'centro_costos';
    protected $fillable = [
        'nombre',
        'codigo',
    ];
}
