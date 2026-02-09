@if (!empty(optional($estudioAuxiliar)->entrevistaExtrajuicio))
  <table>
    <thead>
      <tr>
        <th class="bg-primary roboto-bold text-left titulos">ENTREVISTA A DECLARANTES EXTRAJUICIO</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-break text-justify">
          {!! strip_tags(optional($estudioAuxiliar)->entrevistaExtrajuicio, '<b><i><br>') !!}
        </td>
      </tr>
    </tbody>
  </table>
@endif
