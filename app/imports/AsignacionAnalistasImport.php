<?php

namespace App\Imports;

use App\Models\InvestigacionAsignacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AsignacionAnalistasImport implements ToCollection
{
    /**
     * Almacena los errores de cada fila (si los hay).
     *
     * @var array
     */
    private $errores = [];

    /**
     * Procesa todas las filas del Excel.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Convertimos la colección en un array puro para manipularlo fácilmente.
        $rowsArray = $rows->toArray();

        // 1. Verificar si hay al menos 2 filas (encabezado + 1 fila de datos).
        if (count($rowsArray) < 2) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no contiene suficientes filas para procesar.",
                'datos' => [],
            ];
            return; // No procesamos más
        }

        // 2. Tomar la primera fila como "encabezados"
        $header = $rowsArray[0];

        // 2a. Filtrar columnas vacías o nulas del encabezado
        $header = array_filter($header, fn($value) => !is_null($value) && $value !== '');

        // 2b. Verificar cantidad de columnas (debe haber exactamente 3)
        if (count($header) !== 3) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no tiene exactamente 3 columnas como se requiere.",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 2c. Convertir cada encabezado a minúsculas (para que 'ID' ~ 'id' ~ 'Id')
        $header = array_map(fn($value) => strtolower(trim($value)), $header);

        // 2d. Validar que sean: id, nombre, id (en ese orden)
        //    Si quisieras permitir distinto orden, habría que reorganizar la lógica. 
        if (
            $header[0] !== 'id' ||
            $header[1] !== 'nombre' ||
            $header[2] !== 'id'
        ) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "Los encabezados no coinciden con lo esperado (id, nombre, id).",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 3. Recorrer las filas de datos desde la 2da (index=1 en adelante)
        for ($i = 1; $i < count($rowsArray); $i++) {
            $row = $rowsArray[$i];

            // Procesamos dentro de un try/catch para capturar cada fila por separado
            try {
                // Verificar que existan al menos 3 índices
                if (!isset($row[0], $row[1], $row[2])) {
                    throw new \Exception("Datos incompletos, faltan columnas en la fila.");
                }

                // Extraer valores
                $idInvestigacion = $row[0]; // Columna A
                $nombreAnalista  = $row[1]; // Columna B (no lo usamos en el UPDATE)
                $idAnalista      = $row[2]; // Columna C

                // Validaciones de ejemplo
                if (!is_numeric($idInvestigacion)) {
                    throw new \Exception("El 'Id' debe ser un número, se recibió '$idInvestigacion'.");
                }
                if (!is_numeric($idAnalista)) {
                    throw new \Exception("El 'ID' (Analista) debe ser un número, se recibió '$idAnalista'.");
                }

                // 4. Intentar hacer el UPDATE en la BD
                $updated = InvestigacionAsignacion::where('idInvestigacion', $idInvestigacion)
                    ->update(['Analista' => $idAnalista]);

                if (!$updated) {
                    // Si no se actualizó, podría ser que no exista el registro en BD
                    throw new \Exception(
                        "No se pudo actualizar la asignación con idInvestigacion=$idInvestigacion."
                    );
                }

                // Si todo va bien, la fila se procesó correctamente
                // No hacemos nada adicional aquí.

            } catch (\Exception $e) {
                // Capturamos el error de esta fila
                $this->errores[] = [
                    'fila'  => $i + 1, // index+1 para reflejar la fila "real" en el Excel
                    'error' => $e->getMessage(),
                    'datos' => $row,
                ];
                // No lanzamos un throw global, seguimos con la siguiente fila.
            }
        }
    }

    /**
     * Devuelve el listado de errores acumulados.
     * El controlador lo usará para generar el archivo de reporte.
     */
    public function getErrores()
    {
        return $this->errores;
    }
}
