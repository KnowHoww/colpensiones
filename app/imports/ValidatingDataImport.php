<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\exportarFinalizadasHoy as exportarFinalizadasHoyExport;

class ValidatingDataImport extends DataImport
{
    public function array(array $array)
    {
        $errors = [];
        $idsConsulta = [];
        // Iterar sobre las filas (excepto la cabecera)
        foreach ($array as $rowIndex => $row) {
            if ($rowIndex === 0) {
                continue; // Saltar la cabecera
            }

            $id = $row[0];
            $idsConsulta[] = $id;
            $radicado = $row[1];
            $estado = $row[2];
            $tipoInvestigacion = $row[3];

            
            if (is_null($id) || is_null($radicado) || is_null($estado) ||
                trim($id) === '' || trim($radicado) === '' || trim($estado) === ''  ||
                trim($id) === 'null' || trim($radicado) === 'null' || trim($estado) === 'null') {
                $errors[] = "Por favor agregar el campo faltante en el ID {$id}.";
            }
             
            if (is_null($tipoInvestigacion) || trim($tipoInvestigacion) === '' || trim($tipoInvestigacion) === 'null') {
                $errors[] = "Por favor agregar tipo de investigación del id: {$id}. \n Recuerde que Reconocimiento=22, Nomina=23";
            }
            
            $estadoInvestigacion = DB::table('investigaciones_estados_radicacion')
            ->where('idInvestigacion', $id)
            ->exists();

            if ($estadoInvestigacion) {
                $errors[] = "La investigacion con el ID {$id} ya se encuentra RADICADA.";
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::put('idsConsulta', $idsConsulta);
            return;
        }

    }
}
