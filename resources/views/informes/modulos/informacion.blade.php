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