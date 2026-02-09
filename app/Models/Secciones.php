<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secciones extends Model
{
    use HasFactory;
    protected $table = 'Secciones';
    protected $fillable = [
        'id',
        'nombre',
    ];
}
