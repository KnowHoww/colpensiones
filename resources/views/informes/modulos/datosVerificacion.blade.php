@if ($secciones->contains('nombre', 'DatosVerificacion'))
    <table class="section">
      <thead>
        <tr class="bg-primary titulos">
          <th>Nombre de solicitantes</th>
          <th>Tipo de Documento</th>
          <th>Número de Documento</th>
          <th>Ciudad</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Parentesco</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($investigacionVerificacion as $item)
          <tr>
            <td>{{ $item->PrimerNombre }} {{ $item->SegundoNombre }} {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</td>
            <td>{{ $item->TipoDocumento }}</td>
            <td class="text-break text-justify">{{ $item->NumeroDocumento }}</td>
            <td class="text-break text-justify">{{ $item->ciudad }}</td>
            <td class="text-break text-justify">{{ $item->direccion }}</td>
            <td class="text-break text-justify">{{ $item->telefono }}</td>
            <td class="text-break text-justify">{{ $item->parentesco }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif