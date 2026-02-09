<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr >

			<th style="background-color:#b0bec5;" >Investigador</th>
			<th style="background-color:#b0bec5;"># Informe</th>
			<th style="background-color:#b0bec5;" >Valor</th>
        </tr>
    </thead>
    <tbody>
		
        @foreach ($data as $item)
		<tr	>
			
			<td class="align-middle"> {{ $item->nombre }}</td>
			<td  class="align-middle" style="text-align: center">{{ $item->idInforme }}</td>
			<td class="align-middle" style="text-align: right">{{ str_replace(',','',number_format($item->valor, 2)) }} </td>
		</tr>
        @endforeach
		<tr>
			
			<td class="align-middle" rowspan="2"  colspan="8"  style="text-align: center"> DETALLE</td>
		</tr>
			<tr>
			</tr>
        <tr>
			<th style="background-color:#b0bec5;" >Investigador</th>
			<th style="background-color:#b0bec5;">Radicado</th>
			<th style="background-color:#b0bec5;" >Id</th>
			<th style="background-color:#b0bec5;" >Por Beneficiario</th>
			<th style="background-color:#b0bec5;">Caso Doble</th>
			<th style="background-color:#b0bec5;" >Apoyo Completo</th>
			<th style="background-color:#b0bec5;" >Valor Investigación</th>
			<th style="background-color:#b0bec5;" >Valor Apoyo</th>
        </tr>
		 @foreach ($data2 as $item)
		<tr	>
			
			<td class="align-middle"> {{ $item->nombre }}</td>
			<td class="align-middle"> {{ $item->CasoPadreOriginal }}</td>
			<td class="align-middle"> {{ $item->id }}</td>
			<td class="align-middle"> 
			@if ( $item->porBeneficiario >0)
				Sí
			@endif
			</td>
			<td  class="align-middle" style="text-align: center">			
			@if ( $item->doble >0)
				Sí
			@endif
			</td>
			<td  class="align-middle" style="text-align: center">			
			@if ( $item->AuxiliarCompleta >0)
				Sí
			@endif
			</td>
			<td class="align-middle" style="text-align: right">{{ str_replace(',','',number_format($item->ValorInvestigador, 2)) }} </td>
			<td class="align-middle" style="text-align: right">{{ str_replace(',','',number_format($item->ValorAuxiliar, 2)) }} </td>
		</tr>
        @endforeach
    </tbody>
</table>

