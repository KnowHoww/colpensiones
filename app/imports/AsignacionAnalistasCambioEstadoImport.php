<?php

namespace App\Imports;

use App\Models\InvestigacionAsignacion;
use App\Models\Investigaciones;
use App\Models\InvestigacionesObservacionesEstado;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AsignacionAnalistasCambioEstadoImport implements ToCollection
{
    /**
     * Almacena los errores de cada fila (si los hay).
     * @var array
     */
    private $errores = [];

    /**
     * Procesa las filas de la hoja de Excel.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Convertimos la colección a un array para manipularlo más fácilmente.
        $rowsArray = $rows->toArray();

        // 1. Verificar si hay al menos 2 filas (encabezados + 1 fila de datos).
        if (count($rowsArray) < 2) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no contiene suficientes filas para procesar.",
                'datos' => [],
            ];
            return;
        }

        // 2. Tomar la primera fila como "encabezados".
        $header = $rowsArray[0];

        // 2a. Filtrar columnas vacías o nulas del encabezado
        $header = array_filter($header, fn($value) => !is_null($value) && $value !== '');

        // 2b. Verificar cantidad de columnas (debe haber exactamente 5)
        if (count($header) !== 5) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no tiene exactamente 5 columnas como se requiere.",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 2c. Convertir cada encabezado a minúsculas (para ignorar mayúsculas/minúsculas)
        $header = array_map(fn($value) => strtolower(trim($value)), $header);

        // 2d. Validar que coincidan con lo esperado, en orden:
        //     "Id", "ANALISTA ENCARGADO", "ID ANALISTA", 
        //     "ID DE LA PERSONA QUE DEJA EL MENSAJE", "MENSAJE PARA CAMBIO DE ESTADO"
        //     (todo en minúsculas, pues convertimos arriba)
        if (
            $header[0] !== 'id' ||
            $header[1] !== 'analista encargado' ||
            $header[2] !== 'id analista' ||
            $header[3] !== 'id de la persona que deja el mensaje' ||
            $header[4] !== 'mensaje para cambio de estado'
        ) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "Los encabezados no coinciden con lo esperado "
                           ."(id, analista encargado, id analista, "
                           ."id de la persona que deja el mensaje, mensaje para cambio de estado).",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 3. Recorrer las filas de datos (desde la 2da fila, index=1)
        for ($i = 1; $i < count($rowsArray); $i++) {
            $row = $rowsArray[$i];

            // Usamos try/catch para que un error en una fila
            // no detenga la importación de las demás.
            try {
                // Verificar que existan los 5 índices
                if (!isset($row[0], $row[1], $row[2], $row[3], $row[4])) {
                    throw new \Exception("Datos incompletos: faltan columnas en la fila.");
                }

                // Extraer valores
                $idInvestigacion   = $row[0];
                $analistaEncargado = $row[1]; // texto libre, no se usa en el update
                $idAnalista        = $row[2];
                $idUsuarioObs      = $row[3];
                $observacion       = $row[4];
                $observacion       = "Se asigna investigación";
                $estado            = 5;  // Ejemplo: se fija a 5

                // Validaciones personalizadas
                if (!is_numeric($idInvestigacion)) {
                    throw new \Exception("El 'Id' debe ser un número, se recibió '$idInvestigacion'.");
                }
                if (!is_numeric($idAnalista)) {
                    throw new \Exception("El 'ID ANALISTA' debe ser un número, se recibió '$idAnalista'.");
                }
                if (!is_numeric($idUsuarioObs)) {
                    throw new \Exception("El 'ID DE LA PERSONA QUE DEJA EL MENSAJE' debe ser un número, se recibió '$idUsuarioObs'.");
                }

                // 4. Intentar los UPDATE / INSERT en la BD:

                // 4c) VERIFICAR si ya existe la observación antes de insertarla
                $observacionExistente = InvestigacionesObservacionesEstado::where('idInvestigacion', $idInvestigacion)
                ->where('observacion', $observacion)
                ->exists();

                if ($observacionExistente) {
                    throw new \Exception("Ya existe un registro con la misma observación para esta investigación (ID=$idInvestigacion).");
                }

                // 4a) UPDATE en investigacion_asignacion
                $updated = InvestigacionAsignacion::where('idInvestigacion', $idInvestigacion)
                    ->update(['Analista' => $idAnalista]);

                if (!$updated) {
                    throw new \Exception("No se encontró o no se pudo actualizar "
                                        ."la Asignación con idInvestigacion=$idInvestigacion.");
                }

                // 4b) UPDATE en investigaciones (estado=5)
                $updatedInv = Investigaciones::where('id', $idInvestigacion)
                    ->update(['estado' => $estado]);

                if (!$updatedInv) {
                    throw new \Exception("No se encontró o no se pudo actualizar la Investigación con id=$idInvestigacion.");
                }

                 

                // 4c) INSERT en investigaciones_observaciones_estados
                InvestigacionesObservacionesEstado::create([
                    'idInvestigacion' => $idInvestigacion,
                    'idUsuario'       => $idUsuarioObs,
                    'idEstado'        => $estado,
                    'observacion'     => $observacion,
                ]);

            } catch (\Exception $e) {
                // Si algo falla en esta fila, la guardamos en $this->errores
                $this->errores[] = [
                    'fila'  => $i + 1, // Index +1 para mostrar la fila real
                    'error' => $e->getMessage(),
                    'datos' => $row,
                ];
                // Seguimos con la siguiente fila (no hacemos throw global)
            }
        }
    }

    /**
     * Devuelve el listado de errores acumulados.
     */
    public function getErrores()
    {
        return $this->errores;
    }
}
