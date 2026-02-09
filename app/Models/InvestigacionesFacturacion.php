<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionesFacturacion extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_facturacion';
    protected $fillable = [
        'id',
        'idInvestigacion',
        'FechaRadicacion',
        'FechaFacturacion',
        'idTarifa',
        'radicador',
        'facturador',
        'FechaCorrecionRadicacion',
        'idEstadoRadicacion',
        'idEstadoFacturacion',
        'FechaFinalizacion',
        'extendida'
    ];

    

    public function radicador()
    {
        return $this->belongsTo(User::class, 'radicador', 'id');
    }
    public function facturador()
    {
        return $this->belongsTo(User::class, 'facturador', 'id');
    }

    public function estadoRadicacion()
    {
        return $this->belongsTo(EstadoRadicacion::class, 'idEstadoRadicacion', 'id');
    }
    
    public function estadoFacturacion()
    {
        return $this->belongsTo(EstadoFacturacion::class, 'idEstadoFacturacion', 'id');
    }
	
    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class, 'idTarifa', 'id');
    }
	
}
