@if (!empty(optional($entrevistaFamiliares)->laborCampo))
  <table>
    <thead>
      <tr>
        <th class="bg-primary roboto-bold text-left titulos">ENTREVISTA A FAMILIARES DEL CAUSANTE</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-break text-justify" style="font-size: 12px !important;">
          {!! (optional($entrevistaFamiliares)->laborCampo) !!}
        </td>
      </tr>
    </tbody>
  </table>
@endif
