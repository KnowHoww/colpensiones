<?php

namespace App\Exports;

use App\Models\Investigaciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class generarDocumentacion implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $ids;
    protected $plataforma;
    protected $fechaRadicacionColpensiones;
    protected $nombreCarpetaFinalizado;
    protected $nombreCarpetaObjetado;
    
    public function __construct($ids, $nombreCarpetaCargueInforme )
    {
        $this->ids = $ids;
        $this->nombreCarpetaCargueInforme = $nombreCarpetaCargueInforme;
        $this->plataforma = 'GoAnywhere';
        $this->fechaRadicacionColpensiones = Carbon::now()->format('d/m/Y');
    }

    public function collection()
    {
        // Obtener los datos necesarios para el Excel
        return Investigaciones::whereIn('id', $this->ids)->get();
    }
    
    public function headings(): array
    {
        return [
            'Radicado',
            'ID',
            'Tipo Investigacion',
            'DOC Causante',
            'Fecha Finalización Inv',
            'Nombre Carpeta Cargue Informe / M. Probatorios',
            'Fecha Radicado Colpensiones Carpeta Finalizados GoAnywhere',
            'Plataforma',
            'Dirección Solicito Inv.',
            'Conclusión',
            'Objetado'
        ];
    }

    public function map($investigacion): array
    {

        $nombreCarpetaCargueInforme = $this->nombreCarpetaCargueInforme[$investigacion->id] ?? 'No Tiene Carpeta';
        
        // Obtener la conclusión desde la tabla investigacion_acreditacion
        $conclusiones = DB::table('investigacion_acreditacion')
                ->where('idInvestigacion', $investigacion->id)
                ->pluck('acreditacion');

        if ($conclusiones->isEmpty()) {
            $conclusionfinal = "ERROR";
            //Log::error("No se encontraron acreditaciones para la investigación con ID: " . $investigacion->id);
        } else {
            $conclusionfinal = $conclusiones->contains(14) ? 'Acredita' : 'No acredita';
        }

        $objetado = ($investigacion->estado == 7) ? "No" : "Si";

        // $nombreCarpetaCargue = ($investigacion->estado == 7) 
        //     ? $this->nombreCarpetaFinalizado
        //     : $this->nombreCarpetaObjetado;        
        
        if($investigacion->estado == 16){
            $fechaFinalizacion = Carbon::parse($investigacion->FechaFinalizacionObjecion)->format('d/m/Y');
        }else{
            $fechaFinalizacion =Carbon::parse($investigacion->FechaFinalizacion)->format('d/m/Y');
        }

        $tipoInvestigacion = DB::table('tipo_investigacion')
            ->where('codigo', $investigacion->TipoInvestigacion)
            ->value('nombre');

        return [
            $investigacion->NumeroRadicacionCaso,
            $investigacion->id,
            $tipoInvestigacion,
            $investigacion->NumeroDeDocumento,
            $fechaFinalizacion,
            $nombreCarpetaCargueInforme,
            $this->fechaRadicacionColpensiones,
            $this->plataforma,
            $investigacion->CentroCosto,
            $conclusionfinal ,
            $objetado,
        ];
    }
}
