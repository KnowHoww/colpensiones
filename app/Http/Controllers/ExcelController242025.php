<?php


namespace App\Http\Controllers;

use Exception;
use App\Models\ControlDiasFestivos;
use App\Models\DetalleRiesgo;
use App\Models\InvestigacionAsignacion;
use App\Models\InvestigacionConsultasAntecedentesBeneficiarios;
use App\Models\InvestigacionConsultasAntecedentesCausante;
use App\Models\Investigaciones;
use App\Models\InvestigacionesBeneficiarios;
use App\Models\InvestigacionesFacturacion;
use App\Models\InvestigacionEstudiosAuxiliares;
use App\Models\InvestigacionesValidacionDocumentalCausante;
use App\Models\InvestigacionValidacionDocumentalBeneficiarios;
use App\Models\InvestigacionVerificacion;
use App\Models\Parentesco;
use App\Models\CentroCostos;
use App\Models\Tarifas;
use App\Models\TipoDocumento;
use App\Models\TipoInvestigacion;
use App\Models\TipoPension;
use App\Models\TipoPrioridad;
use App\Models\TipoRiesgo;
use App\Models\TipoSolicitante;
use App\Models\TipoSolicitud;
use App\Models\TipoTramite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
// agregar estas librerias para el manejo de errores
use App\Exports\ErroresExport;
use App\Exports\InvestigacionesExport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;



use App\Imports\DataImport; 



class ExcelController extends Controller
{
    public function esFestivo($start_date, $end_date)
    {
        $festiveDates = ControlDiasFestivos::pluck('fecha')->toArray();
        $newEndDate = $end_date;
        while ($start_date->lte($end_date)) {
            if (in_array($start_date->format('Y-m-d'), $festiveDates) || $start_date->isWeekend()) {
                $newEndDate->addDay();
            }
            $start_date->addDay();
        }

        return $newEndDate;
    }

    public function index()
    {
        return view('cargar-excel');
    }

    private function convertirFecha($fecha_original) {
        $fecha_original = trim($fecha_original); // Eliminar espacios en blanco adicionales

        // Comprobar si la fecha es un número de serie de Excel
        if (is_numeric($fecha_original)) {
            // Convertimos el número de serie de Excel a un objeto DateTime
            $fecha_objeto = Date::excelToDateTimeObject($fecha_original);
            if ($fecha_objeto) {
                // Reformateamos la fecha al formato deseado "Y-m-d"
                return [
                    'fecha_formateada' => $fecha_objeto->format('Y-m-d'),
                    'error' => null
                ];
            } else {
                return [
                    'fecha_formateada' => null,
                    'error' => "Error al convertir el número de serie de Excel."
                ];
            }
        } 

        // Validar que la fecha tenga el formato correcto usando una expresión regular
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fecha_original)) {
            // Convertimos la fecha original a un objeto DateTime
            $fecha_objeto = \DateTime::createFromFormat('n/j/Y', $fecha_original);

            if ($fecha_objeto && $fecha_objeto->format('n/j/Y') === $fecha_original) {
                // Reformateamos la fecha al formato deseado "Y-m-d"
                $fecha_reformateada = $fecha_objeto->format('Y-m-d');
                
                // Devolvemos la fecha formateada y el estado de error como null
                return [
                    'fecha_formateada' => $fecha_reformateada,
                    'error' => null
                ];
            } else {
                // Devolver error si la conversión falla
                return [
                    'fecha_formateada' => null,
                    'error' => "Error al convertir la fecha."
                ];
            }
        } else {
            // Devolver error si el formato no es válido
            return [
                'fecha_formateada' => null,
                'error' => "Formato de fecha no válido. Debe ser en formato 'm/d/Y' o 'mm/dd/yyyy'."
            ];
        }
    }

    private function normalizarValor($valor) {
        // Convertir el valor a minúsculas para la comparación insensible a mayúsculas y minúsculas
        $valor = iconv('UTF-8', 'ASCII//TRANSLIT', $valor);
        $valor = mb_strtolower($valor, 'UTF-8');

        // Reemplazar caracteres especiales
        $valor = str_replace(['@'], 'o', $valor);
        //$valor = str_replace(['hij@', 'hijo', 'hija'], 'hijo(a)', $valor);
        $valor = str_replace(['compañer@', 'compañero', 'compañera'], 'conyuge/ compañero(a)', $valor);
        $valor = str_replace(['hij@ invalido'], 'hijo invalido(a)', $valor);
        $valor = str_replace(['padres'], 'padre', $valor);
        $valor = str_replace(['madres'], 'madre', $valor);

        return $valor;
    }
    

    private function obtenerCodigo($tabla, $valor, $columnaNombre, $columnaCodigo, $columnaEquivalencia = null, $codigoPorDefecto = null) {
        // Convertir el valor a minúsculas para la comparación insensible a mayúsculas y minúsculas
        $valor = $this->normalizarValor($valor);

        foreach ($tabla as $fila) {
            $nombre = mb_strtolower($fila[$columnaNombre], 'UTF-8');
            $nombre = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
            if ($nombre === $valor || ($columnaEquivalencia && mb_strtolower($fila[$columnaEquivalencia], 'UTF-8') === $valor)) {
                return $fila[$columnaCodigo];
            }
        }

        // Buscar coincidencias parciales si no se encontró una coincidencia exacta
        foreach ($tabla as $fila) {
            $nombre = mb_strtolower($fila[$columnaNombre], 'UTF-8');
            $nombre = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
            if (mb_stripos($nombre, $valor) !== false || ($columnaEquivalencia && mb_stripos(mb_strtolower($fila[$columnaEquivalencia], 'UTF-8'), $valor) !== false)) {
                return $fila[$columnaCodigo];
            }
        }
            // Si no se encuentra ninguna coincidencia, devolver el código por defecto si está definido
            if ($codigoPorDefecto !== null) {
                return $codigoPorDefecto;
            }

        // Si no se encuentra ninguna coincidencia, devolver null
        return null;
    }

    private function obtenerTablaTipoInvestigacion() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['nombre' => 'Convivencia', 'codigo' => 'CV'],
            ['nombre' => 'Dependencia Económica', 'codigo' => 'DE'],
            ['nombre' => 'Validación Documental', 'codigo' => 'VD'],
            ['nombre' => 'Escolaridad', 'codigo' => 'ES'],
            ['nombre' => 'Auxilio funerario', 'codigo' => 'AF'],
            ['nombre' => 'Auxilios funerarios', 'codigo' => 'AF'],
            ['nombre' => 'Conservación al derecho pensional', 'codigo' => 'CP']
        ];
    }

    private function obtenerTablaTipoRiesgo() {
        return [
            ['Tipo Riesgo' => 'Sustitución invalidez', 'cod' => '200', 'riesgo_equiv' => 'Sustitución invalidez'],
            ['Tipo Riesgo' => 'Sustitución vejez', 'cod' => '400', 'riesgo_equiv' => 'Sustitución vejez'],
            ['Tipo Riesgo' => 'Sobrevivientes', 'cod' => '500', 'riesgo_equiv' => 'Sobrevivientes'],
            ['Tipo Riesgo' => 'Sustitución jubilación', 'cod' => '700', 'riesgo_equiv' => 'Sustitución jubilación'],
            ['Tipo Riesgo' => 'Sustitución pensión sanción', 'cod' => '900', 'riesgo_equiv' => 'Sustitución pensión sanción'],
            ['Tipo Riesgo' => 'Pensión de Vejez', 'cod' => 'RC01', 'riesgo_equiv' => 'Vejez'],
            ['Tipo Riesgo' => 'Pensión de Invalidez', 'cod' => 'RC02', 'riesgo_equiv' => 'Invalidez'],
            ['Tipo Riesgo' => 'Pensión Por Muerte', 'cod' => 'RC03', 'riesgo_equiv' => 'Muerte'],
            ['Tipo Riesgo' => 'Indemnización Sustitutiva', 'cod' => 'RC04', 'riesgo_equiv' => 'Indemnización Sustitutiva'],
            ['Tipo Riesgo' => 'Indemnización Sustitutiva sobrevivientes', 'cod' => 'RC04', 'riesgo_equiv' => 'Indemnización Sustitutiva'],
            ['Tipo Riesgo' => 'Auxilio Funerario', 'cod' => 'RC05', 'riesgo_equiv' => 'Auxilio Funerario'],
            ['Tipo Riesgo' => 'Piloto', 'cod' => '02', 'riesgo_equiv' => 'Piloto'],
            ['Tipo Riesgo' => 'Auxilios Funerarios', 'cod' => 'RC05', 'riesgo_equiv' => 'Auxilio Funerario']
        ];
    }

    private function obtenerTablaDetalleRiesgo() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['Detalle de Riesgo' => 'Pensión de vejez tiempos privados', 'cod' => '8'],
            ['Detalle de Riesgo' => 'Pensión de vejez tiempos públicos – regímenes especiales', 'cod' => '9'],
            ['Detalle de Riesgo' => 'Pensión de vejez compartida', 'cod' => '10'],
            ['Detalle de Riesgo' => 'Pensión vejez para madre o padre trabajador de hijo inválido', 'cod' => '11'],
            ['Detalle de Riesgo' => 'Pensión especial de vejez anticipada por invalidez', 'cod' => '12'],
            ['Detalle de Riesgo' => 'Pensión vejez alto riesgo', 'cod' => '13'],
            ['Detalle de Riesgo' => 'Indemnización vejez', 'cod' => '14'],
            ['Detalle de Riesgo' => 'Pensión Sobreviviente', 'cod' => '15'],
            ['Detalle de Riesgo' => 'Pensión Sobrevivientes', 'cod' => '15'],
            ['Detalle de Riesgo' => 'Pensión de invalidez', 'cod' => '16'],
            ['Detalle de Riesgo' => 'Indemnización invalidez', 'cod' => '17'],
            ['Detalle de Riesgo' => 'Auxilio Funerario', 'cod' => '18'],
            ['Detalle de Riesgo' => 'Auxilios Funerarios', 'cod' => '18'],
            ['Detalle de Riesgo' => 'Pensión de vejez convenios internacionales', 'cod' => '22'],
            ['Detalle de Riesgo' => 'Interposición de Recursos', 'cod' => '59'],
            ['Detalle de Riesgo' => 'Sustitución Provisional Ley 1204 de 2008', 'cod' => '75'],
            ['Detalle de Riesgo' => 'Pensión vejez periodista', 'cod' => '94'],
            ['Detalle de Riesgo' => 'Indemnización Sobreviviente', 'cod' => '96'],
            ['Detalle de Riesgo' => 'Indemnización Sobrevivientes', 'cod' => '96'],
            ['Detalle de Riesgo' => 'Indemnización sustitutiva Sobrevivientes', 'cod' => '96'],
            ['Detalle de Riesgo' => 'Sustitución Pensional', 'cod' => '97'],
            ['Detalle de Riesgo' => 'Pensión de invalidez convenios internacionales', 'cod' => '178'],
            ['Detalle de Riesgo' => 'Pensión de sobrevivientes convenios internacionales', 'cod' => '179'],
            ['Detalle de Riesgo' => 'Pensión Familiar', 'cod' => '359'],
            ['Detalle de Riesgo' => 'Piloto', 'cod' => '22'],
            ['Detalle de Riesgo' => 'Pensión de Invalidez Victimas de la Violencia', 'cod' => '414']
        ];
    }

    private function obtenerTablaTipoTramite() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['nombre' => 'Incapacidades superiores a 180 días', 'codigo' => '19'],
            ['nombre' => 'Calificación de pérdida de capacidad laboral en primera oportunidad', 'codigo' => '20'],
            ['nombre' => 'Revisión del estado de invalidez', 'codigo' => '21'],
            ['nombre' => 'Reconocimiento', 'codigo' => '22'],
            ['nombre' => 'Nomina de Pensionados', 'codigo' => '23'],
            ['nombre' => 'Piloto', 'codigo' => '08'],
            ['nombre' => 'Validación documental', 'codigo' => '24']
        ];
    }

    private function obtenerTablaTipoDocumento() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['nombre' => 'Tarjeta de identidad', 'codigo' => 'TI'],
            ['nombre' => 'TI', 'codigo' => 'TI'],
            ['nombre' => 'Registro civil', 'codigo' => 'RC'],
            ['nombre' => 'RC', 'codigo' => 'RC'],
            ['nombre' => 'Pasaporte', 'codigo' => 'PA'],
            ['nombre' => 'PA', 'codigo' => 'PA'],
            ['nombre' => 'Número único de identificación personal', 'codigo' => 'NU'],
            ['nombre' => 'NU', 'codigo' => 'NU'],
            ['nombre' => 'NIT', 'codigo' => 'NI'],
            ['nombre' => 'NI', 'codigo' => 'NI'],
            ['nombre' => 'Menor sin Identificación', 'codigo' => 'MS'],
            ['nombre' => 'MS', 'codigo' => 'MS'],
            ['nombre' => 'Documento Extranjero', 'codigo' => 'F'],
            ['nombre' => 'F', 'codigo' => 'F'],
            ['nombre' => 'Carné Diplomático', 'codigo' => 'CF'],
            ['nombre' => 'CF', 'codigo' => 'CF'],
            ['nombre' => 'Cédula de extranjería', 'codigo' => 'CE'],
            ['nombre' => 'CE', 'codigo' => 'CE'],
            ['nombre' => 'Cédula de ciudadanía', 'codigo' => 'CC'],
            ['nombre' => 'CC', 'codigo' => 'CC'],
            ['nombre' => 'Adulto sin Identificación', 'codigo' => 'AS'],
            ['nombre' => 'AS', 'codigo' => 'AS']
        ];
    }

    private function obtenerTablaPrioridad() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['nombre' => 'Normal', 'codigo' => '1'],
            ['nombre' => 'Objetivo Meta', 'codigo' => '2'],
            ['nombre' => 'Tutelas', 'codigo' => '3'],
            ['nombre' => 'Desacato', 'codigo' => '4'],
            ['nombre' => 'Sanción', 'codigo' => '5'],
            ['nombre' => 'Objeción', 'codigo' => '6']
        ];
    }
    
    private function obtenerTablaCentroCosto() {
        // Simulando la obtención de datos desde la base de datos
        return [
            ['nombre' => 'Javh', 'codigo' => 'JA'],
            ['nombre' => 'Dirección de Estandarización', 'codigo' => 'DES'],
            ['nombre' => 'Dirección de Prestaciones Económicas', 'codigo' => 'DPE'],
            ['nombre' => 'Gerencia de Prevención del Fraude', 'codigo' => 'GPF'],
            ['nombre' => 'Dirección de nomina de pensionados', 'codigo' => 'DNP'],
            ['nombre' => 'Javhl', 'codigo' => 'Javhl'],
            ['nombre' => 'DES', 'codigo' => 'DES'],
            ['nombre' => 'DPE', 'codigo' => 'DPE'],
            ['nombre' => 'GPF', 'codigo' => 'GPF'],
            ['nombre' => 'DNP', 'codigo' => 'DNP']
        ];
    }

    private function obtenerTablaParentesco() {
        return [
            ['Parentesco' => 'Hijo invalido(a)', 'cod' => '1'],
            ['Parentesco' => 'Conyuge/ Compañero(a)', 'cod' => '2'],
            ['Parentesco' => 'Compañero', 'cod' => '2'],
            ['Parentesco' => 'Cónyuge', 'cod' => '2'],
            ['Parentesco' => 'Hijo Menor Edad', 'cod' => '3'],
            ['Parentesco' => 'Hijo Mayor Estudios', 'cod' => '4'],
            ['Parentesco' => 'Hijo', 'cod' => '4'],
            ['Parentesco' => 'Madre/Padre', 'cod' => '5'],
            ['Parentesco' => 'Padre', 'cod' => '5'],
            ['Parentesco' => 'Padres', 'cod' => '5'],
            ['Parentesco' => 'Madre', 'cod' => '5'],
            ['Parentesco' => 'Hermano invalido(a)', 'cod' => '6'],
            ['Parentesco' => 'Otro', 'cod' => '7'],
            ['Parentesco' => 'Hijo estudiante', 'cod' => '8']
            
        ];
    }
    
    private function validarFila($fila)
    {
        try {
            $tablaTipoInvestigacion = $this->obtenerTablaTipoInvestigacion();
            $tablaTipoRiesgo = $this->obtenerTablaTipoRiesgo();
            $tablaDetalleRiesgo = $this->obtenerTablaDetalleRiesgo();
            $tablaTipoTramite = $this->obtenerTablaTipoTramite();
            $tablaTipoDocumento = $this->obtenerTablaTipoDocumento();
            $tablaPrioridad = $this->obtenerTablaPrioridad();
            $tablaCentroCosto = $this->obtenerTablaCentroCosto();
            $tablaParentesco = $this->obtenerTablaParentesco();
            
            //Validar Fecha
            $resultado = $this->convertirFecha($fila[0]);

            if ($resultado['error']) {
                throw new Exception($resultado['error']);
            }
            $fecha_transferencia = $resultado['fecha_formateada'];

            //Numero de documento
            if (!ctype_digit(trim($fila[8]))) {
                throw new Exception("El documento " . $fila[8] . " debe contener solo números");
            }
            
            $documentoCausante = trim($fila[8]);

            //Tipo de Investigacion
            $tipoInvestigacionNombre = trim($fila[2]);
            $tipoInvestigacionCodigo = $this->obtenerCodigo($tablaTipoInvestigacion, $tipoInvestigacionNombre, 'nombre', 'codigo');
            if ($tipoInvestigacionCodigo === null) {
                throw new Exception("Tipo de investigación no encontrado: $tipoInvestigacionNombre");
            }
            //Tipo Riesgo
            $tipoRiesgoNombre = trim($fila[3]);
            $tipoRiesgoCodigo = $this->obtenerCodigo($tablaTipoRiesgo, $tipoRiesgoNombre, 'Tipo Riesgo', 'cod', 'riesgo_equiv');
            if ($tipoRiesgoCodigo === null) {
                throw new Exception("Tipo de riesgo no encontrado: $tipoRiesgoNombre");
            }
            //Detalle de riesgo
            $detalleRiesgoNombre = trim(Str::after(trim($fila[5]), "Recurso"));
            $detalleRiesgoCodigo = $this->obtenerCodigo($tablaDetalleRiesgo, $detalleRiesgoNombre, 'Detalle de Riesgo', 'cod');
            if ($detalleRiesgoCodigo === null) {
                throw new Exception("Detalle de riesgo no encontrado: $detalleRiesgoNombre");
            }
            //Tipo Tramite
            $tipoTramiteNombre = trim($fila[4]);
            $tipoTramiteCodigo = $this->obtenerCodigo($tablaTipoTramite, $tipoTramiteNombre, 'nombre', 'codigo');
            if ($tipoTramiteCodigo === null) {
                throw new Exception("Tipo de trámite no encontrado: $tipoTramiteNombre");
            }
            //TipoDocumento
            $tipoDocumentoNombre = trim($fila[7]);
            $tipoDocumentoCodigo = $this->obtenerCodigo($tablaTipoDocumento, $tipoDocumentoNombre, 'nombre', 'codigo');
            if ($tipoDocumentoCodigo === null) {
                throw new Exception("Tipo de documento no encontrado: $tipoDocumentoNombre");
            }
            //Prioridad
            $prioridadNombre = trim($fila[39]);
            $prioridadCodigo = $this->obtenerCodigo($tablaPrioridad, $prioridadNombre, 'nombre', 'codigo');
            if ($prioridadCodigo === null) {
                throw new Exception("Prioridad no encontrada: $prioridadNombre");
            }
            //Codigo CentroCosto
            $centroCostoNombre = trim($fila[38]);
            $centroCostoCodigo = $this->obtenerCodigo($tablaCentroCosto, $centroCostoNombre, 'nombre', 'codigo');
            if ($centroCostoCodigo === null) {
                throw new Exception("Centro de costo no encontrado: $centroCostoNombre");
            }

            // Validaciones para los beneficiarios
            
            $beneficiarioCampos = [
                ['documento' => 14, 'tipo_documento' => 13, 'parentesco' => 15, 'primer_nombre' => 16, 'primer_apellido' => 17, 'institucion_educativa' => 27],
                ['documento' => 19, 'tipo_documento' => 18, 'parentesco' => 22, 'primer_nombre' => 20, 'primer_apellido' => 21, 'institucion_educativa' => 27],
                ['documento' => 24, 'tipo_documento' => 23, 'parentesco' => 27, 'primer_nombre' => 25, 'primer_apellido' => 26, 'institucion_educativa' => 27],
                ['documento' => 29, 'tipo_documento' => 28, 'parentesco' => 32, 'primer_nombre' => 30, 'primer_apellido' => 31, 'institucion_educativa' => 27]
            ];

            $beneficiarios = [];
            $isBeneficiarioExisting = FALSE;

            foreach ($beneficiarioCampos as $beneficiarioCampo) {
                if (!empty(trim($fila[$beneficiarioCampo['documento']]))) {
                    $isBeneficiarioExisting = TRUE;
                    $tipoDocumentoNombre = trim($fila[$beneficiarioCampo['tipo_documento']]);
                    $tipoDocumentoCodigo = $this->obtenerCodigo($tablaTipoDocumento, $tipoDocumentoNombre, 'nombre', 'codigo');
                    if ($tipoDocumentoCodigo === null) {
                        throw new Exception("Tipo de documento Beneficiario no encontrado: $tipoDocumentoNombre");
                    }
                    $parentescoNombre = trim($fila[$beneficiarioCampo['parentesco']]);
                    $parentescoCodigo = $this->obtenerCodigo($tablaParentesco, $parentescoNombre, 'Parentesco', 'cod', null, '7');
                    if ($parentescoCodigo === null) {
                        throw new Exception("Parentesco no encontrado: $parentescoNombre");
                    }
                    $beneficiarios[] = [
                        'tipoDocumentoCodigo' => $tipoDocumentoCodigo,
                        'parentescoCodigo' => $parentescoCodigo,
                        'NumeroDocumento' => trim($fila[$beneficiarioCampo['documento']]),
                        'PrimerNombre' => trim($fila[$beneficiarioCampo['primer_nombre']]),
                        'PrimerApellido' => trim($fila[$beneficiarioCampo['primer_apellido']]),
                        'InstitucionEducativa' => trim($fila[$beneficiarioCampo['institucion_educativa']])
                    ];
                }
            }

            return [
                'fecha_transferencia' => $fecha_transferencia,
                'documentoCausante' => $documentoCausante,
                'tipoInvestigacionCodigo' => $tipoInvestigacionCodigo,
                'tipoRiesgoCodigo' => $tipoRiesgoCodigo,
                'detalleRiesgoCodigo' => $detalleRiesgoCodigo,
                'tipoTramiteCodigo' => $tipoTramiteCodigo,
                'tipoDocumentoCodigo' => $tipoDocumentoCodigo,
                'prioridadCodigo' => $prioridadCodigo,
                'centroCostoCodigo' => $centroCostoCodigo,
                'beneficiarios' => $beneficiarios,
                'isBeneficiarioExisting' => $isBeneficiarioExisting,
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function cargarMasivoInvestigaciones(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xls,xlsx',
        ]);
        $archivo = $request->file('archivo');
         // Define las primeras cabeceras esperadas
        $cabecerasEsperadas = [
            'Fecha Transferencia',	'Dirección de correo electrónico',	'Tipo de Investigación',	'Tipo de Riesgo'
        ];
            // Añade depuración para ver el contenido de $datos1
        try{
            //Leemos el archivo excel de colpenciones
            //$datos1 = Excel::toArray([], $archivo);
            $datos1 = Excel::toArray(new DataImport, $archivo);
            $cabecerasArchivo = null;
            $datos2 = null;
            
            foreach ($datos1 as $hoja) {
                $cabecerasArchivo = $hoja[0];
                if (!empty($hoja) && isset($cabecerasArchivo)) {
                    // Verificar si todas las cabeceras son null y saltar si es el caso
                    if (array_filter($cabecerasArchivo) === []) {
                        continue;
                    }
                    $cabecerasArchivo = array_slice($hoja[0],0,4);
                    // Comparar las cabeceras encontradas con las esperadas
                    if(count($hoja[0])!=43){
                        throw new Exception("El número de cabeceras es: ". count($hoja[0]));
                    }
                    if ($cabecerasArchivo === $cabecerasEsperadas) {
                        $datos2 = $hoja; // Guardar la hoja correcta en $datos2
                        break;
                    }
                }
            }
            if ($datos2 !== null) {
                array_shift($datos2);                        // Elimina las cabeceras del array de datos
                
                DB::beginTransaction();
                set_time_limit(120);  // Extiende el límite de tiempo de ejecución

                try{

                    $i = 0;
                    $nuevosIds = [];
                    $errores = [];
                    $duplicados = [];
                    $idDuplicado = [];
                    $idNormal = [];
                    $viejoNombreCarpeta =[];
                    

                    foreach ($datos2 as $index => &$fila){

                        $validacion = $this->validarFila($fila);

                        if (!is_array($validacion)) {
                            $errores[] = [
                                'fila' => $index + 1,
                                'error' => $validacion
                            ];
                            continue;
                        }

                        try {
                            
                            $campos = $validacion;
                            
                            $idInvestigacion = Investigaciones::create([
                                "analista" => 230,
                                "aprobador" => 230,
                                "estado" => 3,
                                "MarcaTemporal" => $campos['fecha_transferencia'],
                                "NumeroRadicacionCaso" => trim($fila[6]),
                                "CasoPadreOriginal" => trim($fila[6]),
                                "NumeroDeDocumento" => $campos['documentoCausante'],
                                "PrimerNombre" => trim($fila[9]),
                                "PrimerApellido" => trim($fila[10]),
                                "TipoInvestigacion" => $campos['tipoInvestigacionCodigo'],
                                "TipoRiesgo" => $campos['tipoRiesgoCodigo'],
                                "DetalleRiesgo" => $campos['detalleRiesgoCodigo'],
                                "TipoTramite" => $campos['tipoTramiteCodigo'],
                                "TipoDocumento" => $campos['tipoDocumentoCodigo'],
                                "DireccionCausante" => trim($fila[11]),
                                "TelefonoCausante" => trim($fila[12]),
                                "Junta" => trim($fila[33]),
                                "NumeroDictamen" => trim($fila[34]),
                                "FechaDictamen" => trim($fila[35]),
                                "Observacion" => trim($fila[36]),
                                "PuntoAtencion" => trim($fila[40]),
                                "Prioridad" => $campos['prioridadCodigo'],
                                "CentroCosto" => $campos['centroCostoCodigo'],
                            ]);
                            $viejoNombreCarpeta[] = trim($fila[41]);         
                            // Actualiza el registro de investigación con el ID y el nombre de la carpeta
                            $idInvestigacion ->update([
                                "IdCase" => $idInvestigacion->id,
                                "nombreCarpeta" => $idInvestigacion->NumeroRadicacionCaso . "_" . $idInvestigacion->id,
                            ]);
                            // Añade el ID de la nueva investigación a la lista de IDs nuevos
                            $nuevosIds[] = $idInvestigacion->id;
                            //Cuando existen una carpeta Duplicada se debe colocar en la fila 42 la 
                            //palabra duplicada con el indice de las veces que se encuentre duplicada
                            //es decir, duplicada1, duplicada2, duplicada3
                            //a continuacion las carpetas duplicadas se colocan dentro del directorio
                            //masivos/duplicadas, agregandole al final el indice de su duplicidad, es decir
                            //duplicada1 -> /masivos/duplicadas/NoRadicado1/
                            //duplicada2 -> /masivos/duplicadas/NoRadicado2/
                            //Y asi sucesivamente
                            // Si el indice i de la columna  42 contiene 'duplicada', marca como duplicado
                            if (strpos($fila[42], 'duplicada') !== false) {
                                $duplicados[] = [
                                    'NumeroRadicacionCaso' => trim($fila[6]),
                                    'duplicado' => $fila[42],
                                    'id' => $idInvestigacion->id
                                ];
                                $idDuplicado[] = $idInvestigacion->id;
                                continue;
                            } else {
                                $idNormal[] = $idInvestigacion->id;
                            }
                            //Como ya hemos procesado la informacion de si la carpeta esta duplicada,
                            //usamos la columna 42 para guardar los nuevos ids                                
                            // Agregar la fila con las actualizaciones
                            $datos2[$index][42] = $idInvestigacion -> id;
                            //Utilizaremos la columna 43 para guardar los nuevos nombres de las carpetas
                            $datos2[$index][43] = $idInvestigacion -> NumeroRadicacionCaso . "_" . $idInvestigacion -> id;

                            // Actualiza la fecha límite de la investigación
                            $actualizacion = Investigaciones::find($idInvestigacion->id);
                            if ($actualizacion->Prioridad != null && $actualizacion->Prioridad != '') {
                                $prioridad = TipoPrioridad::find($actualizacion->Prioridad);
                            }

                            $fechaSolicitud = Carbon::parse($actualizacion->MarcaTemporal);
                            $tipo = TipoInvestigacion::where('codigo', $actualizacion->TipoInvestigacion)->first();
                            $tiempoEntrega = $tipo->TiempoEntrega;

                            if ($prioridad->TiempoEntrega != null) {
                                $TiempoEntrega = $prioridad->TiempoEntrega;
                            }

                            $fechaEntrega = $fechaSolicitud->copy()->addDays($tiempoEntrega);
                            $fechaLimite = $this->esFestivo(Carbon::createFromFormat('Y-m-d', $fechaSolicitud->toDateString()), Carbon::createFromFormat('Y-m-d', $fechaEntrega->toDateString()));
                            $actualizacion->update(['FechaLimite' => $fechaLimite]);

                            // Crea registros asociados a la investigación
                            InvestigacionesValidacionDocumentalCausante::create([
                                'idInvestigacion' => $idInvestigacion->id,
                            ]);

                            // Crear registros de beneficiarios asociados a la investigación
                            if($campos['isBeneficiarioExisting'] == TRUE){
                                foreach ($campos['beneficiarios'] as $beneficiario) {
                                    
                                    $ben = InvestigacionesBeneficiarios::create([
                                        'IdInvestigacion' => $idInvestigacion->id,
                                        'TipoDocumento' => $beneficiario['tipoDocumentoCodigo'],
                                        'NumeroDocumento' => $beneficiario['NumeroDocumento'],
                                        'PrimerNombre' => $beneficiario['PrimerNombre'],
                                        'PrimerApellido' => $beneficiario['PrimerApellido'],
                                        'Parentesco' => $beneficiario['parentescoCodigo'],
                                        'InstitucionEducativa' => $beneficiario['InstitucionEducativa']
                                    ]);

                                    InvestigacionValidacionDocumentalBeneficiarios::create([
                                        'idInvestigacion' => $idInvestigacion->id,
                                        'idBeneficiario' => $ben->id
                                    ]);

                                    InvestigacionConsultasAntecedentesBeneficiarios::create([
                                        'idInvestigacion' => $idInvestigacion->id,
                                        'idBeneficiario' => $ben->id
                                    ]);
                                }
                            }else {
                                $ben = InvestigacionesBeneficiarios::create([
                                    'IdInvestigacion' => $idInvestigacion->id,
                                    //'TipoDocumento' => $beneficiario['tipoDocumentoCodigo'],
                                    "TipoDocumento" => $campos['tipoDocumentoCodigo'],
                                    //'NumeroDocumento' => $beneficiario['NumeroDocumento'],
                                    "NumeroDeDocumento" => $campos['documentoCausante'],
                                    //'PrimerNombre' => $beneficiario['PrimerNombre'],
                                    "PrimerNombre" => trim($fila[9]),
                                    //'PrimerApellido' => $beneficiario['PrimerApellido'],
                                    "PrimerApellido" => trim($fila[10]),
                                    "Parentesco" => "8",

                                    
                                ]);
                                InvestigacionValidacionDocumentalBeneficiarios::create([
                                    'idInvestigacion' => $idInvestigacion->id,
                                    'idBeneficiario' => $ben->id
                                ]);

                                InvestigacionConsultasAntecedentesBeneficiarios::create([
                                    'idInvestigacion' => $idInvestigacion->id,
                                    'idBeneficiario' => $ben->id
                                ]);
                            }

                            //Agrega un registro a la tabla InvestigacionAsignacion, con el coordinadorRegional y el Investigador
                            InvestigacionAsignacion::create([
                                'idInvestigacion' => $idInvestigacion->id,
                                //'CoordinadorRegional' => $fila[62],
                                //'Investigador' => $fila[63],
                            ]);
                            //Agrega un registro a la tabla InvestigacionConsultasAntecedentesCausante,con el id de la investigacion
                            InvestigacionConsultasAntecedentesCausante::create([
                                'idInvestigacion' => $idInvestigacion->id,
                            ]);
                            //Agrega un registro a la tabla InvestigacionVerificacion con el id de la investigacion
                            InvestigacionVerificacion::create(
                                [
                                    'idInvestigacion' => $idInvestigacion->id,
                                ]
                            );
                            //Agrega un registro a la tabla InvestigacionEstudiosAuxiliares con el id de la investigacion
                            InvestigacionEstudiosAuxiliares::create(
                                [
                                    'idInvestigacion' => $idInvestigacion->id,
                                ]
                            );



                            $i++;
                            
                        } catch (\Exception $e) {
                            // Capturar error específico de esta fila
                            $errores[] = [
                                'fila' => json_encode($fila),
                                'error' => $e->getMessage()
                            ];
                            throw $e;
                        }

                    }

                    

                    DB::commit();                               // Confirmar la transacción
                    
                    // Procesar datos con errores antes de guardarlos
                    $investigaciones = Investigaciones::whereIn('id', $nuevosIds)->get()->keyBy('NumeroRadicacionCaso');
                    
                    //$datos3 = $this->procesarDatosConErrores($datos2, $errores, $investigaciones);
                    if(isset($errores)){
                        foreach ($datos2 as &$fila) {
                            $fila = array_pad($fila, 44, null);
                        }
                        foreach($errores as $error){

                            $datos2[$error['fila']-1][44]= strval($error['error']);
                        }
                    }
                    
                    $filename = 'investigaciones/masivo/historiadeMasivos/informe_' . date('Ymd_His') . '.xlsx'; // Generar el informe con los datos actualizados
                    //dd($datos2);
                    Excel::store(new InvestigacionesExport($datos2), $filename);
                    
                    session(['datosExcel' => $datos2]);         // Guarda los datos del Excel en la sesión
                    
                    // Dentro del método cargarMasivoInvestigaciones Guarda los IDs y otros datos en la sesión
                    session([ 'nuevosIds' => $nuevosIds, 'duplicados' => $duplicados, 'idNormal' => $idNormal, 'idDuplicado' => $idDuplicado, 'errores' => $errores, 'viejoNombreCarpeta' => $viejoNombreCarpeta]);
                    
                    return redirect()->route('masivoinvestigaciones')->with('success', 'Se han cargado ' . $i . ' de '. count($datos2). ' registros exitosamente.');

                } catch (\Exception $e) {
                    DB::rollback();
                    // Manejar el error, registrar o notificar según sea necesario
                    return redirect()->route('masivoinvestigaciones')->with('error', 'Error al procesar los datos: ' . $e->getMessage());
            
                }
            } else {
                // Si las cabeceras no son válidas, redirigir con un mensaje de error y las cabeceras esperadas
                return redirect()->route('masivoinvestigaciones')
                    ->with('error', 'Las cabeceras del archivo no son válidas.');
            }
        } catch (\Exception $e) {
            return redirect()->route('masivoinvestigaciones')->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
        
    }

    


    // Metodo creado por wilmer contreras
    public function mostrarMasivo()
    {

        // Obtener los nuevos IDs después de limpiar la sesión
        $nuevosIds = session('nuevosIds', []);

        // Obtener las investigaciones correspondientes a los nuevos IDs
        $investigaciones = Investigaciones::whereIn('id', $nuevosIds)->get();
        // Limpiar los datos de la sesión
        
        return view('investigaciones.masivo', compact('investigaciones'));
    }


    
    public function descargarErrores()
    {
        
        $datos = session('datosExcel', []); // invoca los datos guardados en la sesion

        return Excel::download(new InvestigacionesExport($datos), 'investigaciones_con_errores.xlsx');
    }

    


    public function cargarMasivoUsuarios(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xls,xlsx',
        ]);

        $archivo = $request->file('archivo');

        try {
            // Leer el archivo Excel y obtener los datos
            $datos = Excel::toArray([], $archivo)[0];

            // Verificar si el array de datos no está vacío antes de procesarlo
            if (!empty($datos)) {
                // Omitir la primera línea (cabecera)
                array_shift($datos);
                // Iniciar la transacción de la base de datos
                DB::beginTransaction();
                set_time_limit(120);
                $i = 0;
                try {
                    // Guardar los datos en la base de datos
                    foreach ($datos as $fila) {
                        $validacion =  DB::table('users')->where('email', strtolower($fila[6]))->where('numberDocument', $fila[3])->get()->count();
                        if ($fila[3] !== '' && $fila[3] != null) {
                            if ($validacion == 0) {
                                $user = User::create([
                                    'name' => trim($fila[0]),
                                    'lastname' => trim($fila[1]),
                                    'idTypeDocument' => $fila[2],
                                    'numberDocument' => $fila[3],
                                    'idCity' => 6881,
                                    'phone' => $fila[4],
                                    'estado' => $fila[5],
                                    'email' => strtolower($fila[6]),
                                    'centroCosto' => $fila[7],
                                    'municipio' => $fila[10],
                                    'coordinador' => $fila[8],
                                    'email_verified_at' => now(),
                                    'password' => Hash::make($fila[3]),
                                    'ActualizarPassword' => 0,
                                    'fechaActualizarPassword' => now(),
                                ]);
                                $user->roles()->sync($fila[9]);
                                echo 'Usuario creado: '.$user->numberDocument . '<br>';
                            }else {
                                echo 'Usuario no creado: '.$fila[3]. '<br>';
                            }
                        } 
                    }
                    // Confirmar la transacción
                    DB::commit();
                    //return redirect('/usuarios')->with('info', 'Carga realizada con exito.');
                } catch (\Exception $e) {
                    // Revertir la transacción en caso de error
                    DB::rollback();

                    // Manejar el error, registrar o notificar según sea necesario
                    throw $e;
                }
            } else {
                //return redirect()->route('/usuarios')->with('error', 'El archivo no contiene datos válidos');
            }
        } catch (\Exception $e) {
            return $e;
            // Manejar cualquier excepción durante la lectura del archivo
            //return redirect()->route('/usuarios')->with('error', 'Error al procesar el archivo Excel');
        }
    }
	
	  public function cargarMasivoFacturacion(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xls,xlsx',
        ]);
		$actualizarOK=true;
		$errores = '';
		$i = 0;
        $archivo = $request->file('archivo');
         // Define las primeras cabeceras esperadas
        $cabecerasEsperadas = [
            'Id',	'Caso Padre',	'Excluir',	'Fecha Facturacion'
        ];
            // Añade depuración para ver el contenido de $datos1
        try{
            //Leemos el archivo excel de colpenciones
            //$datos1 = Excel::toArray([], $archivo);
            $datos1 = Excel::toArray(new DataImport, $archivo);
            $cabecerasArchivo = null;
            $datos2 = null;
            
			if (!empty($datos1)) {
                // Omitir la primera línea (cabecera)
               
				foreach ($datos1 as $hoja) {
					 array_shift($hoja);
					$datos2 = $hoja; 
			
						   
					if ($datos2 !== null) {
					//	array_shift($datos2);                        // Elimina las cabeceras del array de datos
						
						
						set_time_limit(120);  // Extiende el límite de tiempo de ejecución

						try{
						//	DB::beginTransaction();
							
							

							foreach ($datos2 as $index => &$fila){

								if ($fila[2]!= 'Si' && $fila[2]!= 'Sí'  && $fila[2]!= 'SI'  && $fila[2]!= 'SÍ' && $fila[2]!= 'x'  && $fila[2]!= '1'){ 
									$investiga = Investigaciones::find($fila[0]);
									$estado = ($fila[3] == '' ?  3 : 2);
									if ($estado ==2 ) {
										$fechaActualizacion = $fila[3];
									}
									else
									{
										$fechaActualizacion = null;
									}
									$tarifa = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->where('idRegion', $investiga->region)->first();
									$tarifac = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->where('idRegion', $investiga->region)->count();
									if ($tarifac == 0){
										$tarifa = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->whereNull('idRegion')->first();
									}
									
									if ( $tarifa !=null){
										if ((($tarifa->region == $investiga->region) && ($tarifa->TipoInvestigacion == $investiga->TipoInvestigacion)||($tarifa->region == null) && ($tarifa->TipoInvestigacion == $investiga->TipoInvestigacion))){
											$InvestigacionFacturacion = InvestigacionesFacturacion::where('idInvestigacion', $fila[0])->first();
											if ($InvestigacionFacturacion==null){
												$InvestigacionFacturacion = InvestigacionesFacturacion::create ([
													'idInvestigacion' => ($fila[0]),
													'idTarifa'=>$tarifa->id,
													'FechaFacturacion' => $fechaActualizacion ,
													'facturador' =>  Auth::user()->id,
													'idEstadoFacturacion' =>  $estado
												]);
												
											}
											else {
												$InvestigacionFacturacion::find($fila[0]);
												$InvestigacionFacturacion->FechaFacturacion =  $fechaActualizacion ;
												$InvestigacionFacturacion->facturador = Auth::user()->id ;
												$InvestigacionFacturacion->idEstadoFacturacion = $estado ;
												$InvestigacionFacturacion->idTarifa =  $tarifa->id;
												$InvestigacionFacturacion->update(['FechaFacturacion' => $fechaActualizacion  ,'facturador' => Auth::user()->id ,'idEstadoFacturacion' => $estado ,'idTarifa' => $tarifa->id ]);
												if( !$InvestigacionFacturacion->save()){
													$actualizarOK=false;
												}
											}
										}
										//$errores = $errores . $investigacion .'.' ; 
									}
									else {
										$actualizarOK=false;
										$errores = $errores . ' Tarifa No encontrada - ' . $fila[0] . '\\' ; 
									}
							
								}
							}
							//DB::commit();       
			
							// Confirmar la transacción
							session(['success' => 'success: Se han cargado ' . $i . ' de '. count($datos1). ' registros exitosamente.']); 
							return redirect()->route('masivofacturacion')->with('success', 'Se han cargado ' . $i . ' de '. count($datos1). ' registros exitosamente.');

						} catch (\Exception $e) {
	//DB::rollback();
							// Manejar el error, registrar o notificar según sea necesario
							session(['infoError' => 'error:'. $e]); 
							
							return redirect()->route('masivofacturacion')->with('error', 'Error al procesar los datos--: ' . $e);
					
						}
					} else {
						// Si las cabeceras no son válidas, redirigir con un mensaje de error y las cabeceras esperadas
						return redirect()->route('masivofacturacion')
							->with('error', 'Las cabeceras del archivo no son válidas.');
					}
				}
				if ($actualizarOK==true){
					session(['success' => 'success: Se han cargado ' . $i . ' de '. count($datos1). ' registros exitosamente.']); 
					return redirect()->route('masivofacturacion')->with('success', 'Se han cargado ' . $i . ' de '. count($datos1). ' registros exitosamente.');
				}
				else {
						// Si las cabeceras no son válidas, redirigir con un mensaje de error y las cabeceras esperadas
						session(['infoError' => 'error:'. $errores]); 
						return redirect()->route('masivofacturacion')->with('error', 'Error al procesar los datos-: ' . $errores);
				}
				
				
				
			}
			 else {
						// Si las cabeceras no son válidas, redirigir con un mensaje de error y las cabeceras esperadas
						return redirect()->route('masivofacturacion')
							->with('error', 'Archivo no valido.');
					}
        } catch (\Exception $e) {
            return redirect()->route('masivofacturacion')->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
        
    }

     public function mostrarMasivoFacturacion()
    {

        // Obtener los nuevos IDs después de limpiar la sesión
         $centroCosto = CentroCostos::where('id','!=',1)->get();      
        return view('investigaciones.masivoFacturacion', compact('centroCosto'));
    }


	
}
