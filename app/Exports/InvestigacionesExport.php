<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvestigacionesExport implements FromArray, WithHeadings
{
    protected $datos;

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    public function array(): array
    {
        return $this->datos;
    }

    public function headings(): array
    {
        return [
            'Fecha Transferencia',	'Dirección de correo electrónico',	'Tipo de Investigación',	'Tipo de Riesgo',	'Tipo de Trámite',	'Detalle del Riesgo',	'Número caso Padre',	'Tipo Documento Causante',	'Número de Documento Causante',	'Nombres Causante',	'Apellidos Causante',	'Dirección Causante',	'Teléfono Causante',	'Tipo documento  Beneficiario 1',	'Número de documento  Beneficiario 1',	'Parentesco',	'Nombres beneficiario 1',	'Apellidos beneficiario1',	'Tipo documento Beneficiario 2',	'Número de documento Beneficiario 2',	'Nombres Beneficiario 2',	'Apellidos beneficiario 2',	'Parentesco2',	'Tipo documento Beneficiario 3',	'Número de documento Beneficiario 3',	'Nombres beneficiario 3',	'Apellidos beneficiario 3',	'Parentesco3',	'Tipo documento Beneficiario 4',	'Número de documento Beneficiario 4',	'Nombres beneficiario 4',	'Apellidos beneficiario 4',	'Parentesco4',	'JUNTA',	'Número Dictamen',	'Fecha dictamen',	'Observaciones o Causa de la investigación',	'INSTITUCIÓN EDUCATIVA ',	'Dir Sol.',	'Prioridad Inicial',	'Punto De Atención',	'NombreCarpeta',	'idCase',	'NuevoNombreCarpeta'
        ];
    }
}
