@if (!empty(optional($estudioAuxiliar)->hallazgos) || !empty(optional($estudioAuxiliar)->observacion))
  <div>
    <table>
      <thead>
        <tr>
          <th class="bg-primary roboto-bold text-left titulos">HALLAZGOS ADICIONALES EN EL PROCESO DE INVESTIGACIÓN</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-break text-justify">
            {!! (optional($estudioAuxiliar)->hallazgos ?? '') !!}
          </td>
        </tr>
        @if (!empty(optional($estudioAuxiliar)->observacion))
          <tr>
            <td class="text-break text-justify">
              {!! (optional($estudioAuxiliar)->observacion ?? '') !!}
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
@endif
