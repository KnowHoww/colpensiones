<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ErroresImportacionExport implements FromCollection
{
    protected $errores;

    /**
     * Recibimos un array con los errores en el constructor.
     */
    public function __construct(array $errores)
    {
        $this->errores = $errores;
    }

    /**
     * Devuelve la colección de filas que se escribirán en el Excel.
     */
    public function collection()
    {
        // Cabecera del reporte (puedes ajustarla)
        $reporte = collect([
            ['Fila', 'Error', 'Datos']
        ]);

        foreach ($this->errores as $error) {
            // Convertimos los datos de la fila a string, si existen
            $datosStr = '';
            if (isset($error['datos'])) {
                // $error['datos'] es un array o Collection con las celdas
                // Lo convertimos en un string con comas
                if (is_array($error['datos'])) {
                    $datosStr = implode(', ', $error['datos']);
                } elseif (method_exists($error['datos'], 'toArray')) {
                    $datosStr = implode(', ', $error['datos']->toArray());
                }
            }

            // Agregamos la fila de error al reporte
            $reporte->push([
                $error['fila'],     // Número de fila en Excel
                $error['error'],    // Mensaje de error
                $datosStr           // Datos crudos de la fila
            ]);
        }

        return $reporte;
    }
}
