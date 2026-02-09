<div class="section">
  <table class="table-body">
    <thead>
      <tr>
        <td width="10%">Analista</td>
        <td width="23%">{{ ucwords(strtolower(optional($analista)->full_name ?? '')) }}</td>
        <td width="12%">Investigador</td>
        <td width="21%">{{ ucwords(strtolower(optional($investigador)->full_name ?? '')) }}</td>
        <td width="12%">Coordinador</td>
        <td width="21%">{{ ucwords(strtolower(optional($coordinador)->full_name ?? '')) }}</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Gerente del proyecto</td>
        <td>Gildardo Tijaro Galindo</td>
        <td colspan="4">
          @if (!empty($firmaBase64))
            <img src="data:image/jpeg;base64,{{ $firmaBase64 }}" />
          @endif
        </td>
      </tr>
    </tbody>
  </table>
</div>
