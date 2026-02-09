<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Número caso Padre</th>
            <th>Estado</th>
            <th>Objetada</th>
            <th>Fecha objetada</th>
            <th>Fecha aprobacion objetada</th>
            <th>Creador</th>
            <th>Aprobador</th>
            <th>Centro de costos</th>
            <th>Prioridad</th>
            <th>Fecha solicitud</th>
            <th>Fecha aprobación</th>
            <th>Fecha limite</th>
            <th>Fecha finalización</th>
            <th>Fecha finalización objetada</th>
            <th>Fecha cancelación</th>
            <th>Fecha Revision</th>
            <th>Tipo de Investigación</th>
            <th>Tipo de Riesgo</th>
            <th>Tipo de Trámite</th>
            <th>Detalle del Riesgo</th>
            <th>Tipo Documento Causante</th>
            <th>Número de Documento Causante</th>
            <th>Primer Causante</th>
            <th>Segundo Causante</th>
            <th>Primer apellido Causante</th>
            <th>Segundo apellido Causante</th>
            <th>Dirección Causante</th>
            <th>Teléfono Causante</th>
            <th>Coordinador</th>
            <th>Investigador</th>
            <th>Analista</th>
            <th>Auxiliar</th>
            <th>Junta</th>
            <th>Número Dictamen</th>
            <th>Fecha dictamen</th>
            <th>Causal de la investigación</th>
            <th>Región</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Acredita</th>
            <th>¿Es fraude?</th>
            @if ($mostrarBeneficiarios == 1)
                <th>Tipo documento Beneficiario 1</th>
                <th>Número de documento Beneficiario 1</th>
                <th>Parentesco 1</th>
                <th>Nombres beneficiario 1</th>
                <th>Apellidos beneficiario1</th>
                <th>Nit1</th>
                <th>Institución educativa1</th>
                <th>Acreditación 1</th>
                <th>resumen de acreditacion 1</th>
                <th>conclusion de acreditacion 1</th>
                <th>Tipo documento Beneficiario 2</th>
                <th>Número de documento Beneficiario 2</th>
                <th>Parentesco 2</th>
                <th>Nombres beneficiario 2</th>
                <th>Apellidos beneficiario2</th>
                <th>Nit2</th>
                <th>Institución educativa2</th>
                <th>Acreditación 2</th>
                <th>resumen de acreditacion 2</th>
                <th>conclusion de acreditacion 2</th>
                <th>Tipo documento Beneficiario 3</th>
                <th>Número de documento Beneficiario 3</th>
                <th>Parentesco 3</th>
                <th>Nombres beneficiario 3</th>
                <th>Apellidos beneficiario 3</th>
                <th>Nit3</th>
                <th>Institución educativa 3</th>
                <th>Acreditación 3</th>
                <th>resumen de acreditacion 3</th>
                <th>conclusion de acreditacion 3</th>
                <th>Tipo documento Beneficiario 4</th>
                <th>Número de documento Beneficiario 4</th>
                <th>Parentesco 4</th>
                <th>Nombres beneficiario 4</th>
                <th>Apellidos beneficiario4</th>
                <th>Nit4</th>
                <th>Institución educativa4</th>
                <th>Acreditación 4</th>
                <th>resumen de acreditacion 4</th>
                <th>conclusion de acreditacion 4</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->IdCase }}</td>
                <td>{{ $item->NumeroRadicacionCaso }}</td>
                <td>{{ $item->iEstado }}</td>
                <td class="align-middle">{{ $item->esObjetado == 1 ? 'Si' : 'No' }}</td>
                <th>{{$item->FechaObjecion}}</th>
                <th>{{$item->FechaAprobacionObjecion}}</th>
                <td class="align-middle text-capitalize">
                    {{ $item->name_analistaColpensiones ? $item->name_analistaColpensiones : 'No asignado' }}
                    {{ $item->lastname_analistaColpensiones }}</td>
                <td class="align-middle text-capitalize">
                    {{ $item->name_aprobadorColpensiones ? $item->name_aprobadorColpensiones : 'No asignado' }}
                    {{ $item->lastname_aprobadorColpensiones }}</td>
                <td>{{ $item->iCentroCosto }}</td>
                <td>{{ $item->iPrioridad }}</td>
                 <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->MarcaTemporal))> '1970-01-01' ? date('Y-m-d', strtotime($item->MarcaTemporal)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->MarcaTemporal))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->MarcaTemporal)) : '' }}
                    @endif
                </td>
                <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->FechaAprobacion))> '1970-01-01' ? date('Y-m-d', strtotime($item->FechaAprobacion)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->FechaAprobacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->FechaAprobacion)) : '' }}
                    @endif
                </td>
                <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->FechaLimite))> '1970-01-01' ? date('Y-m-d', strtotime($item->FechaLimite)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->FechaLimite))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->FechaLimite)) : '' }}
                    @endif              
                </td>
                <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->FechaFinalizacion))> '1970-01-01' ? date('Y-m-d', strtotime($item->FechaFinalizacion)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->FechaFinalizacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->FechaFinalizacion)) : '' }}
                    @endif              
                </td>
                <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->FechaFinalizacionObjecion))> '1970-01-01' ? date('Y-m-d', strtotime($item->FechaFinalizacionObjecion)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->FechaFinalizacionObjecion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->FechaFinalizacionObjecion)) : '' }}
                    @endif              
                
                </td>
                <td class="align-middle">
                    @if ($solo_fecha)
                        {{ date('Y-m-d', strtotime($item->FechaCancelacion))> '1970-01-01' ? date('Y-m-d', strtotime($item->FechaCancelacion)) : '' }}
                    @else
                        {{ date('Y-m-d H:i:s', strtotime($item->FechaCancelacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($item->FechaCancelacion)) : '' }}
                    @endif              
                </td>
                <td>{{ $item -> fechaRevision }}</td>
                <td>{{ $item->iTipoInvestigacion}}</td>
                <td>{{ $item->iTipoRiesgo }}</td>
                <td>{{ $item->iTipoTramite  }}</td>
                <td>{{ $item->iDetalleRiesgo }}</td>
                <td>{{ $item->iTipoDocumento}}</td>
                <td>{{ $item->NumeroDeDocumento }}</td>
                <td>{{ $item->PrimerNombre }}</td>
                <td>{{ $item->SegundoNombre }}</td>
                <td>{{ $item->PrimerApellido }}</td>
                <td>{{ $item->SegundoApellido }}</td>
                <td>{{ $item->DireccionCausante }}</td>
                <td>{{ $item->TelefonoCausante }}</td>
                <td>{{ $item->name_coordinador ? $item->name_coordinador : 'No asignado' }}
                    {{ $item->lastname_coordinador }}</td>
                <td>{{ $item->name_investigador ? $item->name_investigador : 'No asignado' }}
                    {{ $item->lastname_investigador }}</td>
                <td>{{ $item->name_analista ? $item->name_analista : 'No asignado' }} {{ $item->lastname_analista }}
                </td>
                <td>{{ $item->name_auxiliar ? $item->name_auxiliar : 'No asignado' }} {{ $item->lastname_auxiliar }}
                </td>
                <td>{{ $item->Junta }}</td>
                <td>{{ $item->NumeroDictamen }}</td>
                <td>{{ $item->FechaDictamen }}</td>
                <td>{{ $item->Observacion }}</td>
                <td>{{ $item->Iregion }}</td>
                <td>{{ $item->departamento }}</td>
                <td>{{ $item->municipio }}</td>
                <td>{{ $item->acreditados }}</td>
                <td>{{ $item->estadoFraude ?? 'No definido' }}</td>
                @if ($mostrarBeneficiarios == 1)
                    @foreach ($item->beneficiarios as $beneficiario)
                        <td>{{ $beneficiario->TipoDocumento }}</td>
                        <td>{{ $beneficiario->NumeroDocumento }}</td>
                        <td>{{ $beneficiario->iParentesco }}</td>
                        <td>{{ htmlspecialchars($beneficiario->PrimerNombre) }} {{ htmlspecialchars($beneficiario->SegundoNombre) }}</td>
                        <td>{{ htmlspecialchars($beneficiario->PrimerApellido) }} {{ htmlspecialchars($beneficiario->SegundoApellido) }}</td>
                        <td>{{ $beneficiario->Nit }}</td>
                        <td>{{ $beneficiario->InstitucionEducativa }}</td>
                        <td>
                            @if ($beneficiario->acreditacion == 14)
                                Acredita
                            @elseif($beneficiario->acreditacion == 15)
                                No acredita
                            @else
                                Pendiente
                            @endif
                        </td>
                        <td>{{ substr(strip_tags($beneficiario->resumen_acreditacion),0,200) }}</td>
                        <td>{{ substr(strip_tags($beneficiario->resumen_conclusion),0,200)  }}</td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    </tbody>

</table>
