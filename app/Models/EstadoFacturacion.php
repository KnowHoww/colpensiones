<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoFacturacion extends Model
{
    use HasFactory;
    protected $table = 'estados_facturacion';
    protected $fillable = [
        'id',
        'name',
        'create_at'
    ];
}
