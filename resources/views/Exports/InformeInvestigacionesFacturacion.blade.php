<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr>
			<th >Id</th>
			<th>Número Caso</th>
			<th>Excluir</th>
			<th>Fecha Facturación</th>
			<th >Documento</th>
			<th >Causante</th>
			<th >Radicado</th>
			<th>Estado</th>
			<th>Analista</th>
			<th>Investigador</th>
			<th>Tipo de Investigación</th>
			<th>Región</th>
			<th>Fecha Finalizacion</th>
			<th>Fecha de Facturación</th>
			<th>Tarifa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
         <tr>
			<td class="align-middle">{{ $item->idInvestigacion }}</td>
			<td class="align-middle">{{ $item->NumeroRadicacionCaso }}</td>
			<td class="align-middle"></td>
			<td class="align-middle">{{ $item->FechaFacturacion }}</td>
			<td class="align-middle">{{ $item->TipoDocumento }} {{ $item->NumeroDeDocumento }}</td>
			<td class="align-middle">{{ $item->PrimerNombre }} {{ $item->PrimerApellido }}</td>
			<td class="align-middle">{{ $item->FechaRadicacion }}</td>
			<td class="align-middle">{{ $item->estado }}</td>
			<td class="align-middle">{{ $item->Analista }}</td>
			<td class="align-middle">{{ $item->Investigador }}</td>
			<td class="align-middle">{{ $item->tipoInvestigacion }}</td>
			<td class="align-middle">{{ $item->region }}</td>
			<td class="align-middle">{{ $item->FechaFinalizacion }}</td>
			<td class="align-middle">{{ $item->FechaFacturacion }}</td>			
			<td class="align-middle">{{ $item->tarifa }}</td>
		</tr>
        @endforeach
    </tbody>

</table>
