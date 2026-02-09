<?php

namespace App\Http\Controllers;

use App\Models\Investigaciones;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ViewController implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Simplemente devuelve una colección de ejemplo
        $data = Investigaciones::select(
            'investigaciones.IdCase',
            'investigaciones.MarcaTemporal',
            'investigaciones.TipoInvestigacion',
            'investigaciones.TipoRiesgo',
            'investigaciones.TipoTramite',
            'investigaciones.DetalleRiesgo',
            'investigaciones.NumeroRadicacionCaso',
            'investigaciones.TipoDocumento',
            'investigaciones.NumeroDeDocumento',
            'investigaciones.PrimerNombre',
            'investigaciones.SegundoNombre',
            'investigaciones.PrimerApellido',
            'investigaciones.SegundoApellido',
            'investigaciones.DireccionCausante',
            'investigaciones.TelefonoCausante'
        )
            ->leftJoin('investigaciones_beneficiarios', 'idInvestigacion', 'IdCase')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->get();
        return collect($data);
    }

    public function headings(): array
    {
        // Define los encabezados de las columnas
        return [
            'Id',
            'Fecha Transferencia',
            'Tipo de Investigación',
            'Tipo de Riesgo',
            'Tipo de Trámite',
            'Detalle del Riesgo',
            'Número caso Padre',
            'Tipo Documento Causante',
            'Número de Documento Causante',
            'Primer Causante',
            'Segundo Causante',
            'Primer apellido Causante',
            'Segundo apellido Causante',
            'Dirección Causante',
            'Teléfono Causante',
            'Tipo documento  Beneficiario 1',
            'Número de documento  Beneficiario 1',
            'Parentesco1',
            'Nombres beneficiario 1',
            'Apellidos beneficiario1',
            'Tipo documento Beneficiario 2',
            'Número de documento Beneficiario 2',
            'Nombres Beneficiario 2',
            'Apellidos beneficiario 2',
            'Parentesco2',
            'Tipo documento Beneficiario 3',
            'Número de documento Beneficiario 3',
            'Nombres beneficiario 3',
            'Apellidos beneficiario 3',
            'Parentesco3',
            'Tipo documento Beneficiario 4',
            'Número de documento Beneficiario 4',
            'Nombres beneficiario 4',
            'Apellidos beneficiario 4',
            'Parentesco4',
            'JUNTA',
            'Número Dictamen',
            'Fecha dictamen',
            'Observaciones o Causa de la investigación',
            'INSTITUCIÓN EDUCATIVA',
            'Novedad',
            'Dir Sol',
            'Prioridad',
            'Punto De Atención',
            'punto direccion',
            'depto',
            'REGION',
            'coordinador',
            'investigador',
            'Estado',
            'fecha_limite',
        ];
    }
}
