@if (!empty(optional($entrevistaSolicitante)->trabajo_campo))
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
        <td class="text-break text-justify">
          {!! (optional($entrevistaSolicitante)->trabajo_campo ?? '') !!}
        </td>
      </tr>
    </tbody>
  </table>
@endif
