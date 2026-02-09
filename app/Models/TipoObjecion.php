<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoObjecion extends Model
{
    use HasFactory;
    protected $table = 'tipo_objecion';
    protected $fillable = [
        'nombre',
    ];
}
