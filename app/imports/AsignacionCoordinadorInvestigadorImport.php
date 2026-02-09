<?php

namespace App\Imports;

use App\Models\InvestigacionAsignacion;
use App\Models\Investigaciones;
use App\Models\InvestigacionesObservacionesEstado;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AsignacionCoordinadorInvestigadorImport implements ToCollection
{
    /**
     * Aquí se almacenan los errores de cada fila.
     */
    private $errores = [];

    /**
     * Procesa todas las filas del Excel.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Convertir la colección a arreglo para manipularlo con más facilidad
        $rowsArray = $rows->toArray();

        // 1. Verificar filas suficientes (encabezado + al menos 1 fila de datos)
        if (count($rowsArray) < 2) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no contiene suficientes filas para procesar (se requieren encabezado y datos).",
                'datos' => [],
            ];
            return;
        }

        // 2. Tomar la primera fila como "encabezados"
        $header = $rowsArray[0];

        // 2a. Eliminar columnas vacías o nulas del encabezado
        $header = array_filter($header, fn($value) => !is_null($value) && $value !== '');

        // 2b. Verificar que haya exactamente 8 columnas
        if (count($header) !== 8) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "El archivo no tiene exactamente 8 columnas como se requiere.",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 2c. Convertir cada encabezado a minúsculas / trim para comparar sin importar mayúsculas
        $header = array_map(fn($col) => strtolower(trim($col)), $header);

        // 2d. Validar que los encabezados sean los esperados
        //     ("id", "id municipio o ciudad", "id departamento", "id coordinador", 
        //      "region en número", "id investigador", "persona que deja el mensaje", "mensaje")
        if (
            $header[0] !== 'id' ||
            $header[1] !== 'id_municipio_o_ciudad' ||
            $header[2] !== 'id_departamento' ||
            $header[3] !== 'id_coordinador' ||
            $header[4] !== 'region_en_numero' ||
            $header[5] !== 'id_investigador' ||
            $header[6] !== 'persona_que_deja_el_mensaje' ||
            $header[7] !== 'mensaje'
        ) {
            $this->errores[] = [
                'fila'  => 1,
                'error' => "Los encabezados no coinciden con lo esperado (ID, ID MUNICIPIO O CIUDAD, "
                         . "ID DEPARTAMENTO, ID COORDINADOR, REGION EN NÚMERO, ID INVESTIGADOR, "
                         . "PERSONA QUE DEJA EL MENSAJE, MENSAJE).",
                'datos' => $rowsArray[0],
            ];
            return;
        }

        // 3. Recorrer las filas de datos (index=1 en adelante)
        for ($i = 1; $i < count($rowsArray); $i++) {
            $row = $rowsArray[$i];

            // Usamos try/catch para que un error en esta fila no detenga todo.
            try {
                // Verificar que existan las 8 posiciones
                if (!isset($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7])) {
                    throw new \Exception("Datos incompletos: faltan columnas en la fila.");
                }

                // Extraer valores
                $idInvestigacion   = $row[0];
                $idCiudad          = $row[1];
                $idDepartamento    = $row[2];
                $idCoordinador     = $row[3];
                $regionNumero      = $row[4];
                $idInvestigador    = $row[5];
                $idUsuarioObs      = $row[6];  // Persona que deja el mensaje
                $observacion       = $row[7];  // Mensaje
                $estadoFijo        = 5;        // Se setea a 5

                // Validaciones sencillas de tipos numéricos
                if (!is_numeric($idInvestigacion)) {
                    throw new \Exception("El 'ID' debe ser numérico, se recibió '$idInvestigacion'.");
                }
                if (!is_numeric($idCiudad)) {
                    throw new \Exception("El 'ID MUNICIPIO O CIUDAD' debe ser numérico, se recibió '$idCiudad'.");
                }
                if (!is_numeric($idDepartamento)) {
                    throw new \Exception("El 'ID DEPARTAMENTO' debe ser numérico, se recibió '$idDepartamento'.");
                }
                if (!is_numeric($idCoordinador)) {
                    throw new \Exception("El 'ID COORDINADOR' debe ser numérico, se recibió '$idCoordinador'.");
                }
                if (!is_numeric($regionNumero)) {
                    throw new \Exception("El 'REGION EN NÚMERO' debe ser numérico, se recibió '$regionNumero'.");
                }
                if (!is_numeric($idInvestigador)) {
                    throw new \Exception("El 'ID INVESTIGADOR' debe ser numérico, se recibió '$idInvestigador'.");
                }
                if (!is_numeric($idUsuarioObs)) {
                    throw new \Exception("La 'PERSONA QUE DEJA EL MENSAJE' debe ser numérico (ID usuario), se recibió '$idUsuarioObs'.");
                }

                // === A) UPDATES en "investigaciones" ===
                // A1) ciudadRegion
                $updatedCity = Investigaciones::where('id', $idInvestigacion)
                    ->update(['ciudadRegion' => $idCiudad]);
                if (!$updatedCity) {
                    throw new \Exception("No se pudo actualizar 'ciudadRegion' en investigaciones con id=$idInvestigacion.");
                }

                // A2) departamentoRegion
                $updatedDepto = Investigaciones::where('id', $idInvestigacion)
                    ->update(['departamentoRegion' => $idDepartamento]);
                if (!$updatedDepto) {
                    throw new \Exception("No se pudo actualizar 'departamentoRegion' en investigaciones con id=$idInvestigacion.");
                }

                // A3) region (REGION EN NÚMERO)
                $updatedRegion = Investigaciones::where('id', $idInvestigacion)
                    ->update(['region' => $regionNumero]);
                if (!$updatedRegion) {
                    throw new \Exception("No se pudo actualizar 'region' en investigaciones con id=$idInvestigacion.");
                }

                // === B) UPDATES en "investigacion_asignacion" ===
                // B1) coordinadorRegional
                $updatedCoord = InvestigacionAsignacion::where('idInvestigacion', $idInvestigacion)
                    ->update(['CoordinadorRegional' => $idCoordinador]);
                if (!$updatedCoord) {
                    throw new \Exception("No se pudo actualizar 'CoordinadorRegional' en investigacion_asignacion con idInvestigacion=$idInvestigacion.");
                }

                // B2) investigador
                $updatedInves = InvestigacionAsignacion::where('idInvestigacion', $idInvestigacion)
                    ->update(['Investigador' => $idInvestigador]);
                if (!$updatedInves) {
                    throw new \Exception("No se pudo actualizar 'Investigador' en investigacion_asignacion con idInvestigacion=$idInvestigacion.");
                }

                // === C) INSERT en "investigaciones_observaciones_estados" ===
                InvestigacionesObservacionesEstado::create([
                    'idInvestigacion' => $idInvestigacion,
                    'idUsuario'       => $idUsuarioObs,
                    'idEstado'        => $estadoFijo, // 5
                    'observacion'     => $observacion,
                ]);

                // === D) UPDATE estado=5 en "investigaciones" ===
                $updatedState = Investigaciones::where('id', $idInvestigacion)
                    ->update(['estado' => $estadoFijo]);
                if (!$updatedState) {
                    throw new \Exception("No se pudo actualizar 'estado=5' en investigaciones con id=$idInvestigacion.");
                }

            } catch (\Exception $e) {
                // Si algo falla en esta fila, lo registramos y continuamos
                $this->errores[] = [
                    'fila'  => $i + 1,   // index +1 = n° de fila "real" en Excel
                    'error' => $e->getMessage(),
                    'datos' => $row,
                ];
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
