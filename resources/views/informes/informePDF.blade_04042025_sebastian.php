<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{{ $nombre }}</title>

  <!-- Estilos básicos compatibles con TCPDF -->
  <style>
    /* Usamos helvetica de TCPDF */
    body {
      font-family: helvetica, sans-serif;
      font-size: 13px !important;
    }
    /* Asegurar que todos los elementos de texto usen el mismo tamaño */
    p, td, th, li, ul, ol, span, div, b, i, strong, em {
      font-size: 13px !important; /* Mismo tamaño para todos los elementos de texto */
    }

    /* Quitar margen y padding por defecto */
    * {
      margin: 0;
      padding: 0;
    }

    /* Para las tablas */
    table {
      border-collapse: collapse; 
      width: 100%;
    }

    /* Para las celdas y cabeceras */
    th, td {
      border: 1px solid #000; /* Borde de 1px */
      padding: 5px; /* Espacio interno */
    }

    /* Cabecera con fondo gris */
    .bg-primary {
      background-color: #dbe5f1;
    }

    /* Texto centrado */
    .text-center {
      text-align: center;
    }
    .titulos {
      text-align: center !important;
      font-weight: bold !important;
    }

    /* Texto justificado */
    .text-justify {
      text-align: justify !important;
    }

    /* Texto bold */
    .bold {
      font-weight: bold;
    }

    /* Para secciones con un poco de margen inferior */
    .section {
      margin-bottom: 10px;
    }
    tr.titulos th {
      text-align: center !important;
      font-weight: bold !important;
    }

    th {
      font-weight: normal; /* Quita la negrita */
      text-align: left;    /* Alinea a la izquierda */
    }

    /* Si deseas un tamaño de letra global más grande o pequeño */
    /* body { font-size: 10px; } */
  </style>
</head>
<body>
    
<?php
if (!function_exists('clean_text1')) {
    function clean_text1($text) {
    if (!mb_check_encoding($text, 'UTF-8')) {
        $text = mb_convert_encoding($text, 'UTF-8', 'auto');
    }
        // Primero eliminar todos los atributos de estilo de cualquier etiqueta
        $text = preg_replace('/(<[^>]*?)style\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
        $text = preg_replace('/(<[^>]*?)class\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
        $text = preg_replace('/(<[^>]*?)size\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
        $text = preg_replace('/(<[^>]*?)face\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
        $text = preg_replace('/(<[^>]*?)color\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
        

    $text = strip_tags($text,"<b><i><br><ul><li><p>");
    // Eliminar caracteres especiales invisibles o no imprimibles
    //$text = preg_replace('/[\x00-\x1F\x7F\xA0\xAD\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
    $text = preg_replace('/[\x00-\x1F\x7F\xA0\xAD\x{200B}-\x{200D}\x{FEFF}]/u', ' ', $text);

    // Reemplazar espacios no estándar por espacios normales
    $text = preg_replace('/\s+/u', ' ', $text);

    // Reemplazar el carácter ● con un bullet estándar en HTML
    $text = preg_replace('/●/', '<li>', $text);
    
    // Cerrar los bullets reemplazados
    $text = preg_replace('/<\/span><\/p>/', '</li></span></p>', $text);

        // Eliminar solo las etiquetas de table, mantener el contenido interno
           // Eliminar etiquetas de apertura y cierre de tabla, pero conservar el contenido interno
           $text = preg_replace('/<table\b[^>]*>/i', '', $text);
           $text = preg_replace('/<\/table>/i', '', $text);
           $text = preg_replace('/<tbody\b[^>]*>/i', '', $text);
           $text = preg_replace('/<\/tbody>/i', '', $text);
           $text = preg_replace('/<tr\b[^>]*>/i', '', $text);
           $text = preg_replace('/<\/tr>/i', '', $text);
           $text = preg_replace('/<td\b[^>]*>/i', '', $text);
           $text = preg_replace('/<\/td>/i', '', $text);

               // Eliminar TODOS los atributos de estilo, incluyendo inline CSS
    $text = preg_replace('/(<[^>]*?)style\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
    $text = preg_replace('/(<[^>]*?)class\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i', '$1$2', $text);
    
    // Eliminar elementos span y font completamente (muy importante)
    $text = preg_replace('/<span[^>]*>(.*?)<\/span>/is', '$1', $text);
    $text = preg_replace('/<font[^>]*>(.*?)<\/font>/is', '$1', $text);
    

                // Eliminar solo los atributos de estilo y clase, pero conservar las etiquetas <span> y <font>
            $text = preg_replace('/(<span\b[^>]*)style=["\'][^"\']*["\']([^>]*>)/i', '$1$2', $text);
            $text = preg_replace('/(<span\b[^>]*)class=["\'][^"\']*["\']([^>]*>)/i', '$1$2', $text);
            $text = preg_replace('/(<font\b[^>]*)face=["\'][^"\']*["\']([^>]*>)/i', '$1$2', $text);
            $text = preg_replace('/(<font\b[^>]*)color=["\'][^"\']*["\']([^>]*>)/i', '$1$2', $text);
            $text = preg_replace('/(<p\b[^>]*)style=["\'][^"\']*["\']([^>]*>)/i', '$1$2', $text);

             // Agrupar los <li> en una lista <ul> si no están agrupados
    $text = preg_replace('/(<li>.*?<\/li>)/is', '<ul>$1</ul>', $text);

            // Corregir posibles problemas con múltiples listas
        $text = preg_replace('/<\/ul>\s*<ul>/i', '', $text);

        // Añadir estilo de justificación a los párrafos
        $text = preg_replace('/<p\b([^>]*)>/i', '<p$1 style="text-align: justify;">', $text);

        return $text;
    }
    
}

// Obtener y filtrar el texto
$text = $entrevistaSolicitante->trabajo_campo;
$filtered_text = clean_text1($text);

?>

  <!-- ENCABEZADO -->
  <table style="margin-bottom: 10px;">
    <tbody>
      <tr>
        <!-- LOGO -->
        <td rowspan="2" style="width: 15%; text-align: center;">
          <img src="data:image/jpeg;base64,{{ $logoBase64 }}" style="height: 20px;" />
        </td>
        <td style="width: 60%; text-align: center; font-weight: bold;">Gestión de proyecto</td>
        <td style="width: 25%; text-align: center; font-weight: bold;">Página de</td>
      </tr>
      <tr>
        <td style="text-align: center; font-weight: bold;">Informe Técnico Investigaciones Administrativas</td>
        <td style="text-align: center; font-weight: bold;">Versión 01</td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold;">
          Nombre del Proyecto: Investigaciones Administraciones Colpensiones
        </td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold;">
          Contrato 004 de 2024 celebrado entre La Administradora Colombiana de Pensiones - Colpensiones y Jahv McGregor
        </td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold;">
          Título: Informe Técnico Investigaciones Administrativas
        </td>
      </tr>
    </tbody>
  </table>

  <!-- INFORMACIÓN DEL CASO -->
  <div class="section">
    <table class="table-body">
      <tbody>
        <tr>
          <th class="bg-primary titulos" colspan="4">INFORMACIÓN DEL CASO</th>
        </tr>
        @if ($investigacion->esObjetado == 1)
          <tr>
            <th>Versión objeción</th>
            <th>Fecha objeción</th>
            <th>Fecha aprobación objeción</th>
            <th>Fecha finalización objeción</th>
          </tr>
          <tr>
            <td>{{ $investigacion->cantidadObjeciones }}</td>
            <td>{{ date('Y-m-d H:i:s', strtotime($investigacion->FechaObjecion)) }}</td>
            <td>{{ date('Y-m-d H:i:s', strtotime($investigacion->FechaAprobacionObjecion)) }}</td>
            <td>{{ date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacionObjecion)) }}</td>
          </tr>
        @endif
        <tr class="text-justify">
          <th colspan="2">Centro de costos</th>
          <td colspan="2">{{ optional($investigacion->CentroCostos)->nombre }}</td>
        </tr>
        <tr class="text-justify">
          <th>Número de radicado</th>
          <td>{{ $investigacion->CasoPadreOriginal }}</td>
          <th>ID del caso</th>
          <td>{{ $investigacion->IdCase }}</td>
        </tr>
        <tr class="text-justify">
          <th>Departamento de verificación</th>
          <td>{{ optional($investigacion->departamentos)->departamento }}</td>
          <th>Municipio de verificación</th>
          <td>{{ optional($investigacion->municipios)->municipio }}</td>
        </tr>
        <tr class="text-justify">
          <th>Fecha de resultado</th>
          <td>{{ date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacion)) }}</td>
          <th>Fecha de recibo de la solicitud</th>
          <td>{{ $investigacion->FechaAprobacion}}</td>
        </tr>
        <tr class="text-justify">
          <th>Nombre del causante</th>
          <td>
            {{ ucfirst($investigacion->PrimerNombre ?? '') }}
            {{ ucfirst($investigacion->SegundoNombre ?? '') }}
            {{ ucfirst($investigacion->PrimerApellido ?? '') }}
            {{ ucfirst($investigacion->SegundoApellido ?? '') }}
          </td>
          <th>Identificación</th>
          <td>{{ $investigacion->NumeroDeDocumento }}</td>
        </tr>
        <tr class="text-justify">
          <th>Tipo de investigación</th>
          <td>{{ optional($investigacion->TipoInvestigaciones)->nombre }}</td>
          <th>Tipo de riesgo</th>
          <td>{{ optional($investigacion->TipoRiesgos)->nombre }}</td>
        </tr>
        <tr class="text-justify">
          <th>Objeto de investigación</th>
          <td colspan="3" class="text-break text-justify">{{ $investigacion->Observacion }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- DATOS DE VERIFICACIÓN (ejemplo) -->
  @if ($secciones->contains('nombre', 'DatosVerificacion'))
    <table class="section">
      <thead>
        <tr class="bg-primary titulos">
          <th>Nombre de solicitantes</th>
          <th>Tipo de Documento</th>
          <th>Número de Documento</th>
          <th>Ciudad</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Parentesco</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($investigacionVerificacion as $item)
          <tr>
            <td>{{ $item->PrimerNombre }} {{ $item->SegundoNombre }} {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</td>
            <td>{{ $item->TipoDocumento }}</td>
            <td class="text-break text-justify">{{ $item->NumeroDocumento }}</td>
            <td class="text-break text-justify">{{ $item->ciudad }}</td>
            <td class="text-break text-justify">{{ $item->direccion }}</td>
            <td class="text-break text-justify">{{ $item->telefono }}</td>
            <td class="text-break text-justify">{{ $item->parentesco }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- ValidacionDocumental -->
  @if ($secciones->contains('nombre', 'ValidacionDocumental'))
    <div class="section">
      <table class="table-body">
        <tbody>
          <tr>
            <th colspan="2" class="bg-primary titulos">VALIDACIÓN DOCUMENTAL</th>
          </tr>
          <tr>
            <th colspan="2" class="roboto-bold titulos">VALIDACIÓN DOCUMENTAL DEL CAUSANTE</th>
          </tr>
          @if ($validacionDocumentalCausante->cedula != '')
            <tr>
              <td>CÉDULA DE CIUDADANÍA</td>
              <td class="text-break text-justify"><?php echo strip_tags($validacionDocumentalCausante->cedula); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->defuncion != '')
            <tr>
              <td>REGISTRO CIVIL DE DEFUNCIÓN</td>
              <td class="text-break text-justify"><?php echo strip_tags($validacionDocumentalCausante->defuncion); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->matrimonio != '')
            <tr>
              <td>REGISTRO CIVIL DE MATRIMONIO </td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->matrimonio); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->gastos_funebre != '')
            <tr>
              <td>EVIDENCIA GASTOS FÚNEBRES</td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->gastos_funebre); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->gastos_funerarios != '')
            <tr>
              <td>EVIDENCIA GASTOS FUNERARIOS</td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->gastos_funerarios); ?></td>
            </tr>
          @endif
          <tr>
            <th colspan="2" class="roboto-bold titulos">VALIDACIÓN DOCUMENTAL DEL SOLICITANTE</th>
          </tr>
          @foreach ($validacionDocumentalBeneficiarios as $item)
            <tr>
              <th class="roboto-bold-italic" colspan="2">{{ $item->PrimerNombre }}
                {{ $item->SegundoNombre }}
                {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</th>
            </tr>
            @if ($item->cedula != '')
              <tr>
                <th class="titulos">CÉDULA DE CIUDADANÍA</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->cedula); ?></td>
              </tr>
            @endif
            @if ($item->nacimiento != '')
              <tr>
                <th class="titulos">REGISTRO CIVIL DE NACIMIENTO</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->nacimiento); ?></td>
              </tr>
            @endif
            @if ($item->incapacidad != '')
              <tr>
                <th class="titulos">DICTAMEN MÉDICO DE INCAPACIDAD LABORAL </th>
                <td class="text-break text-justify"><?php echo clean_text1($item->incapacidad); ?></td>
              </tr>
            @endif
            @if ($item->escolaridad != '')
              <tr>
                <th class="titulos">CERTIFICADO DE ESCOLARIDAD</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->escolaridad); ?></td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <!-- ConsultaBaseDatos -->
  @if ($secciones->contains('nombre', 'ConsultaBaseDatos'))
    <div class="section">
      <table class="table-body">
        <tbody>
          <tr>
            <th colspan="2" class="bg-primary titulos">CONSULTA BASES DE DATOS</th>
          </tr>
          <tr>
            <th colspan="2" class="roboto-bold titulos">CAUSANTE</th>
          </tr>
          @if ($AntecedentesCausante->adres == 12)
            <tr>
              <th>ADRES</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_adres }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->ruaf == 12)
            <tr>
              <th>RUAF</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_ruaf }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rues == 12)
            <tr>
              <th>RUES</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rues }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rnec == 12)
            <tr>
              <th>RNEC</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rnec }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->cufe == 12)
            <tr>
              <th>CUFE</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_cufe }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->sispro == 12)
            <tr>
              <th>SISPRO</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_sispro }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rama_judicial == 12)
            <tr>
              <th>RAMA JUDICIAL</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rama_judicial }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->samai == 12)
            <tr>
              <th>SAMAI</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_samai }}</td>
            </tr>
          @endif
          <tr>
            <th colspan="2" class="roboto-bold titulos">SOLICITANTE(S)</th>
          </tr>
          @foreach ($antecedentesBeneficiarios as $item)
            <tr>
              <th class="roboto-bold-italic" colspan="2">{{ $item->PrimerNombre }}
                {{ $item->SegundoNombre }}
                {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</th>
            </tr>
            @if ($item->adres == 12)
              <tr>
                <th class="titulos">ADRES</th>
                <td class="text-break text-justify">{{ $item->observacion_adres }}</td>
              </tr>
            @endif
            @if ($item->ruaf == 12)
              <tr>
                <th class="titulos">RUAF</th>
                <td class="text-break text-justify">{{ $item->observacion_ruaf }}</td>
              </tr>
            @endif
            @if ($item->rues == 12)
              <tr>
                <th class="titulos">RUES</th>
                <td class="text-break text-justify">{{ $item->observacion_rues }}</td>
              </tr>
            @endif
            @if ($item->rnec == 12)
              <tr>
                <th class="titulos">RNEC</th>
                <td class="text-break text-justify">{{ $item->observacion_rnec }}</td>
              </tr>
            @endif
            @if ($item->cufe == 12)
              <tr>
                <th class="titulos">CUFE</th>
                <td class="text-break text-justify">{{ $item->observacion_cufe }}</td>
              </tr>
            @endif
            @if ($item->sispro == 12)
              <tr>
                <th class="titulos">SISPRO</th>
                <td class="text-break text-justify">{{ $item->observacion_sispro }}</td>
              </tr>
            @endif
            @if ($item->rama_judicial == 12)
              <tr>
                <th class="titulos">RAMA JUDICIAL</th>
                <td class="text-break text-justify">{{ $item->observacion_rama_judicial }}</td>
              </tr>
            @endif
            @if ($item->samai == 12)
              <tr>
                <th class="titulos">SAMAI</th>
                <td class="text-break text-justify">{{ $item->observacion_samai }}</td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <!-- TRAZABILIDAD (ejemplo) -->
  @if ($secciones->contains('nombre', 'TrazabilidadActividades'))
    <table class="section">
      <thead>
        <tr class="bg-primary titulos titulos">
          <th colspan="3">TRAZABILIDAD DE LAS ACTIVIDADES REALIZADAS</th>
        </tr>
        <tr class="bg-primary bold text-center titulos">
          <th>Actividad Realizada</th>
          <th>Observación</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($trazabilidadActividades as $item)
          <tr>
            <td class="text-break text-justify">{{ $item->actividad }}</td>
            <td class="text-break text-justify">{{ $item->observacion }}</td>
            <td>
              @if (!is_null($item->fecha))
                {{ $item->fecha }}
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- TRABAJO DE INVESTIGACIÓN REALIZADO -->
  <div class="section">
    @if ($entrevistaSolicitante->trabajo_campo != '')
      <table class="table-body">
        <thead>
          <tr>
            <th class="bg-primary titulos">TRABAJO DE INVESTIGACIÓN REALIZADO</th>
          </tr>
          <tr>
            <th class="bg-primary roboto-bold text-left titulos">ENTREVISTA A SOLICITANTE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-break text-justify"><?php echo $filtered_text; ?></td>
          </tr>
        </tbody>
      </table>
    @endif
            
    @if ($gastosVivienda->totalValor != 0 && $gastosVivienda->totalValor != null)
      <div>
        <table>
          <tbody>
            <tr>
              <th class="bg-primary titulos" colspan="3">GASTOS DE LA VIVIENDA</th>
            </tr>
            <tr>
              <th class="bg-primary" colspan="2">Total gastos del hogar</th>
              <th class="bg-primary">Aportes del afiliado a los gastos del hogar</th>
            </tr>
            <tr>
              <th class="text-center">Concepto</th>
              <th class="text-center">Valor</th>
              <th class="text-center">Valor</th>
            </tr>
            <tr>
              <th class="">Servicios Públicos</th>
              <th class="text-center">{{ number_format($gastosVivienda->serviciosPublicosValor) }}</th>
              <th class="text-center">{{ number_format($gastosVivienda->serviciosPublicosValorAporte) }}</th>
            </tr>
            <tr>
              <th class="">Arriendo</th>
              <th class="text-center">{{ number_format($gastosVivienda->arriendoValor) }}</th>
              <th class="text-center">{{ number_format($gastosVivienda->arriendoValorAporte) }}</th>
            </tr>
            <tr>
              <th class="">Mercado</th>
              <th class="text-center">{{ number_format($gastosVivienda->mercadoValor) }}</th>
              <th class="text-center">{{ number_format($gastosVivienda->mercadoValorAporte) }}</th>
            </tr>
            <tr>
              <th class="">Otros</th>
              <th class="text-center">{{ number_format($gastosVivienda->otrosValor) }}</th>
              <th class="text-center">{{ number_format($gastosVivienda->otrosValorAporte) }}</th>
            </tr>
            <tr>
              <th class="text-center bg-primary">Total</th>
              <th class="text-center bg-primary">{{ number_format($gastosVivienda->totalValor) }}</th>
              <th class="text-center bg-primary">{{ number_format($gastosVivienda->totalValorAporte) }}</th>
            </tr>
            <tr>
              <td colspan="3"><?php echo strip_tags($gastosVivienda->observacion,"<b><i><br>"); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    @endif

    @if (!empty($laborCampo->laborCampo))
      <table>
        <thead>
          <tr>
            <th class="bg-primary roboto-bold text-left titulos">LABOR DE CAMPO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-break text-justify"><?php echo clean_text1($laborCampo->laborCampo); ?></td>
          </tr>
        </tbody>
      </table>
    @endif

    @if ($entrevistaFamiliares->laborCampo != '')
      <table>
        <thead>
          <tr>
            <th class="bg-primary roboto-bold text-left titulos">ENTREVISTA A FAMILIARES DEL CAUSANTE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-break text-justify" style="font-size: 12px !important;"><?php echo clean_text1($entrevistaFamiliares->laborCampo,"<b><i><br>"); ?></td>
          </tr>
        </tbody>
      </table>
    @endif

    @if ($estudioAuxiliar->labor != '')
      <div>
        <table>
          <thead>
            <tr>
              <th class="bg-primary roboto-bold text-left titulos">ESTUDIOS AUXILIARES (GRAFOLOGÍA, DACTILOSCOPIA, BIOMETRÍA)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-break text-justify"><?php echo strip_tags($estudioAuxiliar->labor,"<b><i><br>"); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    @endif

    @if ($estudioAuxiliar->entrevistaExtrajuicio != '')
      <table>
        <thead>
          <tr>
            <th class="bg-primary roboto-bold text-left titulos">ENTREVISTA A DECLARANTES EXTRAJUICIO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-break text-justify"><?php echo strip_tags($estudioAuxiliar->entrevistaExtrajuicio,"<b><i><br>"); ?></td>
          </tr>
        </tbody>
      </table>
    @endif

    @if ($estudioAuxiliar->hallazgos != '' || $estudioAuxiliar->observacion != '')
      <div>
        <table>
          <thead>
            <tr>
              <th class="bg-primary roboto-bold text-left titulos">HALLAZGOS ADICIONALES EN EL PROCESO DE INVESTIGACIÓN</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-break text-justify"><?php echo clean_text1($estudioAuxiliar->hallazgos,"<b><i><br><ul><li>"); ?></td>
            </tr>
            @if ($estudioAuxiliar->observacion != '')
              <tr>
                <td class="text-break text-justify"><?php echo clean_text1($estudioAuxiliar->observacion,"<b><i><br><ul><li>"); ?></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- ACREDITACIÓN DE LA INVESTIGACIÓN -->
  <div class="section">
    <table class="table-body">
      <thead>
        <tr>
          <th class="bg-primary titulos">ACREDITACIÓN DE LA INVESTIGACIÓN</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($acreditaciones as $item)
          <tr>
            <th class="roboto-bold-italic">{{ $item->PrimerNombre }}
              {{ $item->SegundoNombre }}
              {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</th>
          </tr>
          <tr>
            <th class="bg-primary text-left titulos">RESUMEN EJECUTIVO DE INVESTIGACIÓN</th>
          </tr>
          <tr>
            <td class="text-break text-justify"><?php echo strip_tags($item->resumen,"<b><i><br><ul><li>"); ?></td>
          </tr>
          <tr>
            <th class="bg-primary text-left titulos">CONCLUSIÓN</th>
          </tr>
          <tr>
            <td class="text-justify"><b><?php echo $item->estado; ?></b></td>
          </tr>
          <tr>
            <td class="text-break text-justify"><?php echo strip_tags($item->conclusion,"<b><i><br><ul><li>"); ?></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- FIRMAS -->
  <div class="section">
    <table class="table-body">
      <thead>
        <tr>
          <td width="10%">Analista</td>
          <td width="23%">{{ ucwords(strtolower(optional($analista)->full_name)) }}</td>
          <td width="12%">Investigador</td>
          <td width="21%">{{ ucwords(strtolower(optional($investigador)->full_name)) }}</td>
          <td width="12%">Coordinador</td>
          <td width="21%">{{ ucwords(strtolower(optional($coordinador)->full_name)) }}</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Gerente del proyecto</td>
          <td>Gildardo Tijaro Galindo</td>
          <td colspan="4"><img src="data:image/jpeg;base64,{{ $firmaBase64 }}" /></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>