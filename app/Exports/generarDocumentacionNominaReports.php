<?php

namespace App\Exports;

use App\Models\GenerarDocumentacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;
class generarDocumentacionNominaReports implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $ids;
    protected $contador;
    protected $reconocimiento;

    public function __construct($ids,  $reconocimiento){
        $this->ids = $ids;
        $this->contador = 0;
        $this -> reconocimiento = $reconocimiento;
    }

    public function collection()
    {
        
        $documentaciones = GenerarDocumentacion::whereIn('idInvestigacion', $this->ids)
            ->where('CodigoDocumental', 'DJT-INF-AD') // Condición modificada
            ->get();
       

        return $documentaciones;

    }
    public function map($generarDocumentacion):array 
    {

        $this->contador++;
        
        $codigoTramite = "GNP";
        $tipoIdentifacion = DB::table('investigaciones')
            ->where('id', $generarDocumentacion->idInvestigacion)
            ->first(['TipoInvestigacion','estado','FechaFinalizacionObjecion','FechaFinalizacion','TipoDocumento','TipoTramite','NumeroDeDocumento','NumeroRadicacionCaso']);
        
        $nombreTramite = "Gestión de nómina pensionados";
        $codigoSubTramite = "GNPESC";
        $nombreSubTramite = "Actualización de escolaridad";
        $codigoSerire = "500.520.580";
        $nombreSerieDocumental ="Nomina Pensional";
        $codigoSubserie ="500.520.580.2";
        $nombreSubserieDocumental = "Novedades nómina de prestaciones económicas pensionales";
        $nombreTipoDocumental = ($generarDocumentacion->CodigoDocumental == "DJT-INF-AD")? "Informe de investigación administrativa":"Documento Probatorio Investigación Administrativa";
        $claseDocumental = "ClaseDocumentalColpensiones";
        
        $claseDocumental1 ="";
        $tipoIdentifacion1 = "";
        $nombreTipoDocumental1 ="";
        $numeroIdentificacion1 ="";
        $nombreExpediente = "";
        // $fechaDateTime = new DateTime($tipoIdentifacion->FechaFinalizacion);

        if($tipoIdentifacion->estado == 16){
            $fechaDocumento = Carbon::parse($tipoIdentifacion->FechaFinalizacionObjecion)->format('d/m/Y');
        }else{
            $fechaDocumento =Carbon::parse($tipoIdentifacion->FechaFinalizacion)->format('d/m/Y');
        }

        // $fechaDocumento = $fechaDateTime->format('d/m/Y');
        $numeroFolios = ($generarDocumentacion->folios !== NULL) ? $generarDocumentacion->folios : 1;
        $soporte = "";

        $filename = $generarDocumentacion->NombreNemotecnia;
        $fileInfo = pathinfo($filename);
        $extension = $fileInfo['extension'];

        return [
            $this->contador,
            $codigoTramite,
            $nombreTramite,
            $codigoSubTramite,
            $nombreSubTramite,
            $codigoSerire,
            $nombreSerieDocumental,
            $codigoSubserie,
            $nombreSubserieDocumental,
            $generarDocumentacion -> CodigoDocumental,
            $nombreTipoDocumental,
            $claseDocumental,
            $tipoIdentifacion->TipoDocumento,
            $tipoIdentifacion->NumeroDeDocumento,
            $nombreTipoDocumental1,
            $claseDocumental1,
            $tipoIdentifacion1,
            $numeroIdentificacion1,
            $nombreExpediente,
            $generarDocumentacion->NombreNemotecnia,
            $tipoIdentifacion -> NumeroRadicacionCaso,
            $fechaDocumento,
            $numeroFolios,
            $soporte,
            $extension,
            '',
            '',
            '',
            $numeroFolios,
            '',
            '',
            '',
            $generarDocumentacion->idInvestigacion,
            $generarDocumentacion->observacion
        ];
    }

    public function headings(): array
    {
        return [
            'Ítem No.',
            'Código Trámite',
            'Nombre Tramite',
            'Código de SubTrámite',
            'Nombre SubTrámite',
            'Código Serie',
            'Nombre Serie Documental',
            'Código Subserie',
            'Nombre Subserie Documental',
            'Código Documental',
            'Nombre Tipo Documental',
            'Clase Documental',
            'Tipo de Identificación',
            'Numero de Identificación',
            'Nombre Tipo Documental',
            'Clase Documental',
            'Tipo de Identificación',
            'Numero de Identificación',
            'Nombre del expediente (Agrupador)',
            'Nombre del Archivo',
            'Número de Radicación',
            'Fecha del Documento',
            'Número de Folios',
            'Soporte',
            'Formato',
            'Ubicación Documento Electrónico',
            'Posición del Archivo',
            'Tamaño/Peso',
            'Número de Imágenes',
            'Número de Caja/ Tula',
            'No. Precinto',
            'Serie/Subserie con documentos Vitales',
            'Observaciones',
            'Errors'
        ];
    }
}
