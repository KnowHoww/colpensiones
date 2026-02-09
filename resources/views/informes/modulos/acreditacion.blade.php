@if (!empty($acreditaciones) && count($acreditaciones) > 0)
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
            <th class="roboto-bold-italic">
              {{ optional($item)->PrimerNombre }}
              {{ optional($item)->SegundoNombre }}
              {{ optional($item)->PrimerApellido }}
              {{ optional($item)->SegundoApellido }}
            </th>
          </tr>
          @if (!empty(optional($item)->resumen))
            <tr>
              <th class="bg-primary text-left titulos">RESUMEN EJECUTIVO DE INVESTIGACIÓN</th>
            </tr>
            <tr>
              <td class="text-break text-justify">
              {!! optional($item)->resumen !!}
              </td>
            </tr>
          @endif
          @if (!empty(optional($item)->estado) || !empty(optional($item)->conclusion))
            <tr>
              <th class="bg-primary text-left titulos">CONCLUSIÓN</th>
            </tr>
            <tr>
              <td class="text-justify">
                <b>{{ optional($item)->estado }}</b>
              </td>
            </tr>
            <tr>
              <td class="text-break text-justify">
              {!! optional($item)->conclusion !!}
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
@endif
