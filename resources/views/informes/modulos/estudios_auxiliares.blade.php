@if (!empty(optional($estudioAuxiliar)->labor))
  <div>
    <table>
      <thead>
        <tr>
          <th class="bg-primary roboto-bold text-left titulos">ESTUDIOS AUXILIARES (GRAFOLOGÍA, DACTILOSCOPIA, BIOMETRÍA)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-break text-justify">
            {!! strip_tags(optional($estudioAuxiliar)->labor, '<b><i><br>') !!}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@endif
