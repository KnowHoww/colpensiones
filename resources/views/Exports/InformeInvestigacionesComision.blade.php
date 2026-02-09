<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr >
		@php
			$line =1
		@endphp
		
			<th style="background-color:#b0bec5;" >Id</th>
			<th style="background-color:#b0bec5;">Número Caso</th>
			<th style="background-color:#b0bec5;" >Documento</th>
			<th style="background-color:#b0bec5;" >Causante</th>
			<th style="background-color:#b0bec5;" >Radicado</th>
			<th style="background-color:#b0bec5;">Estado</th>
			<th style="background-color:#b0bec5;">Investigador</th>
			<th style="background-color:#b0bec5;">Tarifa I.</th>
			<th style="background-color:#b0bec5;">Por Beneficiario.</th>
			<th style="background-color:#b0bec5;">Auxiliar</th>
			<th style="background-color:#b0bec5;">Tarifa A.</th>
			<th style="background-color:#b0bec5;">Tarifa Completa</th>
			<th style="background-color:#b0bec5;">Tipo de Investigación</th>
        </tr>
    </thead>
    <tbody>
		
        @foreach ($data as $item)
			
         
		 @if ($line==1)
		<tr	style="background-color:#eceff1;">
			@php
				$line =2
			@endphp
		 @else
		<tr	>

			@php
				$line =1
			@endphp
		 @endif
			<td class="align-middle">{{ $item->idInvestigacion }}</td>
			<td class="align-middle">{{ $item->NumeroRadicacionCaso }}</td>
			<td class="align-middle">{{ $item->TipoDocumento }} {{ $item->NumeroDeDocumento }}</td>
			<td class="align-middle">{{ $item->PrimerNombre }} {{ $item->PrimerApellido }}</td>
			<td class="align-middle">{{ $item->FechaRadicacion }}</td>
			<td class="align-middle">{{ $item->estado }}</td>
			<td class="align-middle">{{ $item->Investigador }}</td>
			@if ( $item->comision_investigador >0 )
				@if ( $item->FechaComision == null )
				<td class="align-middle" style="background-color:#f8bbd0;">
				@else
				<td class="align-middle" style="background-color:#b2dfdb;">
				@endif
			@else
				<td class="align-middle">
			@endif
	
			{{ number_format($item->comision_investigador, 2) }}</td>
			@if ( $item->porBeneficiario >0 )
			<td class="align-middle" style="background-color:#f8bbd0;">Sí</td>
			@else
			<td class="align-middle"></td>
			@endif
			
			<td class="align-middle">{{ $item->Auxiliar }}</td>
			@if ( $item->comision_auxiliar >0 )
				@if ( $item->FechaComision == null )
				<td class="align-middle" style="background-color:#f8bbd0;">
				@else
				<td class="align-middle" style="background-color:#b2dfdb;">
				@endif
			@else
				<td class="align-middle">
			@endif				
			{{ number_format($item->comision_auxiliar, 2) }}</td>
			@if ( $item->AuxiliarCompleta >0 )
			<td class="align-middle" style="background-color:#f8bbd0;">Sí</td>
			@else
			<td class="align-middle"></td>
			@endif
			
			<td class="align-middle">{{ $item->tipoInvestigacion }}</td>
		</tr>
        @endforeach
    </tbody>

</table>
