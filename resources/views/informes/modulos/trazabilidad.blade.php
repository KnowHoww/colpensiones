@if (!empty($secciones) && $secciones->contains('nombre', 'TrazabilidadActividades') && $trazabilidadActividades->isNotEmpty())
  <table class="section">
    <thead>
      <tr class="bg-primary titulos">
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
          <td class="text-break text-justify">{{ $item->actividad ?? 'N/A' }}</td>
          <td class="text-break text-justify">{{ $item->observacion ?? 'N/A' }}</td>
          <td class="text-center">
            {{ !is_null($item->fecha) ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : 'Sin fecha' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif
