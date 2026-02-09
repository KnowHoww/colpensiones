<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPension extends Model
{
    use HasFactory;
    protected $table = 'tipo_pension';
    protected $fillable = [
        'nombre',
    ];
}
