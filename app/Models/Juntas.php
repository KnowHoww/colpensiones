<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juntas extends Model
{
    use HasFactory;
    protected $table = 'investigacion_juntas';
    protected $fillable = [
        'nombre',
    ];
}
