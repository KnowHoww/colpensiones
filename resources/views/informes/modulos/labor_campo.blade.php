@if (!empty(optional($laborCampo)->laborCampo))
  <table>
    <thead>
      <tr>
        <th class="bg-primary roboto-bold text-left titulos">LABOR DE CAMPO</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-break text-justify">
          {!! (optional($laborCampo)->laborCampo ?? '') !!}
        </td>
      </tr>
    </tbody>
  </table>
@endif
