<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionRegion extends Model
{
    use HasFactory;
    protected $table = 'investigacion_region';
    protected $fillable = [
        'nombre',
    ];
}
