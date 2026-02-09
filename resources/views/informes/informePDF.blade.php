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
/* if (!function_exists('clean_text1')) {
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
    
} */

if (!function_exists('clean_text1')) {
  function clean_text1($text) {
      // Asegurar codificación UTF-8
      if (!mb_check_encoding($text, 'UTF-8')) {
          $text = mb_convert_encoding($text, 'UTF-8', 'auto');
      }

      // Eliminar atributos no deseados
      $text = preg_replace([
          '/(<[^>]*?)style\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i',
          '/(<[^>]*?)class\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i',
          '/(<[^>]*?)size\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i',
          '/(<[^>]*?)face\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i',
          '/(<[^>]*?)color\s*=\s*["\'][^"\']*?["\']([^>]*?>)/i',
      ], '$1$2', $text);

      // Eliminar etiquetas de tabla pero mantener contenido interno
      $text = preg_replace([
          '/<table\b[^>]*>/i', '/<\/table>/i',
          '/<tbody\b[^>]*>/i', '/<\/tbody>/i',
          '/<tr\b[^>]*>/i', '/<\/tr>/i',
          '/<td\b[^>]*>/i', '/<\/td>/i',
      ], '', $text);

      // Eliminar elementos <span> y <font> pero conservar contenido
      $text = preg_replace([
          '/<span[^>]*>(.*?)<\/span>/is',
          '/<font[^>]*>(.*?)<\/font>/is',
      ], '$1', $text);

      // Reemplazos para limpieza y estandarización
      $text = preg_replace('/●/', '<li>', $text); // Bullet personalizado
      $text = preg_replace('/<\/span><\/p>/', '</li></span></p>', $text); // Cierre de <li>

      // Agrupar <li> sueltos en <ul>
      $text = preg_replace('/(<li>.*?<\/li>)/is', '<ul>$1</ul>', $text);
      $text = preg_replace('/<\/ul>\s*<ul>/i', '', $text); // Fusionar listas consecutivas

      // Eliminar caracteres invisibles
      $text = preg_replace('/[\x00-\x1F\x7F\xA0\xAD\x{200B}-\x{200D}\x{FEFF}]/u', ' ', $text);

      // Normalizar espacios
      $text = preg_replace('/\s+/u', ' ', $text);

      // Aplicar justificación de texto en párrafos
      $text = preg_replace('/<p\b([^>]*)>/i', '<p$1 style="text-align: justify;">', $text);

      // Limitar etiquetas permitidas
      $text = strip_tags($text, '<b><i><br><ul><li><p>');

      return trim($text);
  }
}


// Obtener y filtrar el texto
//$text = $entrevistaSolicitante->trabajo_campo;
//$filtered_text = clean_text1($text);

?>

  <!-- ENCABEZADO -->
    @include('informes.modulos.encabezado')

<!-- INFORMACIÓN DEL CASO -->
@include('informes.modulos.informacion')


<!-- DATOS DE VERIFICACIÓN (ejemplo) -->
@include('informes.modulos.datosVerificacion')

<!-- ValidacionDocumental -->
@include('informes.modulos.validacionDocumental')

<!-- ConsultaBaseDatos -->
@include('informes.modulos.ConsultaBaseDatos')

  <!-- TRAZABILIDAD (ejemplo) -->
  @include('informes.modulos.trazabilidad')
  <!-- TRABAJO DE INVESTIGACIÓN REALIZADO -->
  <div class="section">
    @include('informes.modulos.trabajo_campo')
    @include('informes.modulos.gastos_vivienda')
    @include('informes.modulos.labor_campo')
    @include('informes.modulos.entrevista_familiares')
    @include('informes.modulos.estudios_auxiliares')
    @include('informes.modulos.entrevista_extrajuicio')
    @include('informes.modulos.hallazgos')
  </div>
  
  <!-- ACREDITACIÓN DE LA INVESTIGACIÓN -->
  @include('informes.modulos.acreditacion')
  
  <!-- FIRMAS -->
  @include('informes.modulos.firmas')
  
</body>
</html>