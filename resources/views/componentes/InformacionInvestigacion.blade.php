<div class="accordion-item my-2">
    <h2 class="accordion-header" id="heading1">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1"
            aria-expanded="false" aria-controls="collapse1">
            Información de la investigación
        </button>
    </h2>
    <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table class="table datatable table-bordered" style="width: 100%">
                <tr>
                    <th>estado</th>
                    <th>idCase</th>
                    <th>Prioridad</th>
                    <th>Fraude</th>
                    @if ($investigacion->TipoInvestigacion != '')
                        <th>Investigacion</th>
                    @endif
                    @if ($investigacion->TipoRiesgo != '')
                        <th>Riesgo</th>
                    @endif
                    @if ($investigacion->DetalleRiesgo != '')
                        <th>Detalle de riesgo</th>
                    @endif
                    @if ($investigacion->TipoTramite != '')
                        <th>Tramite</th>
                    @endif
                    @if ($investigacion->TipoSolicitud != '')
                        <th>Solicitud</th>
                    @endif
                    @if ($investigacion->TipoSolicitante != '')
                        <th>Solicitante</th>
                    @endif
                    @if ($investigacion->TipoPension != '')
                        <th>Pensión</th>
                    @endif
                </tr>
                <tr>
                    <td>{{ optional($investigacion->estados)->name }}</td>
                    <td>{{ $investigacion->id }}</td>
                    <td>{{ optional($investigacion->Prioridades)->nombre }}</td>
                    <td>{{ $esFraude->fraudes->name }}</td>
                    @if ($investigacion->TipoInvestigacion != '')
                        <td>{{ optional($investigacion->TipoInvestigaciones)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->TipoRiesgo != '')
                        <td>{{ optional($investigacion->TipoRiesgos)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->DetalleRiesgo != '')
                        <td>{{ optional($investigacion->DetalleRiesgos)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->TipoTramite != '')
                        <td>{{ optional($investigacion->TipoTramites)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->TipoSolicitud != '')
                        <td>{{ optional($investigacion->TipoSolicitudes)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->TipoSolicitante != '')
                        <td>{{ optional($investigacion->TipoSolicitantes)->nombre ?? '' }}</td>
                    @endif
                    @if ($investigacion->TipoPension != '')
                        <td>{{ optional($investigacion->TipoPensiones)->nombre ?? '' }}</td>
                    @endif
                </tr>
            </table>
            <table class="table datatable table-bordered" style="width: 100%">
                <tr>
                    <th>Número de identificación</th>
                    <th>Nombres y apellidos</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Direccion</th>
                    <th>Región</th>
                </tr>
                <tr>
                    <td>{{ optional($investigacion->TipoDocumentos)->nombre ?? 'ND' }}-{{ $investigacion->NumeroDeDocumento }}
                    </td>
                    <td>{{ $investigacion->PrimerNombre }} {{ $investigacion->SegundoNombre }}
                        {{ $investigacion->PrimerApellido }} {{ $investigacion->SegundoApellido }}
                    </td>
                    <td>{{ $investigacion->TelefonoCausante }}</td>
                    <td>{{ $investigacion->CiudadCausante }}</td>
                    <td>{{ $investigacion->DireccionCausante }}</td>
                    <td>{{ optional($investigacion->Regiones)->nombre ? optional($investigacion->Regiones)->nombre : 'Pendiente' }}
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table datatable table-bordered" style="width: 100%">
                <tr>
                    @if ($investigacion->Junta != 0)
                        <th>Junta</th>
                    @endif
                    @if ($investigacion->FechaDictamen != '')
                        <th>Fecha dictamen</th>
                    @endif
                    @if ($investigacion->PuntoAtencion != '')
                        <th>PuntoAtencion</th>
                    @endif
                    @if ($investigacion->DireccionPunto != '')
                        <th>DireccionPunto</th>
                    @endif
                </tr>
                <tr>
                    <td>{{ optional($investigacion->juntas)->nombre }}
                    </td>
                    @if ($investigacion->NumeroDictamen != '')
                        <td>{{ $investigacion->NumeroDictamen }}</td>
                    @endif
                    @if ($investigacion->FechaDictamen != '')
                        <td>{{ $investigacion->FechaDictamen }}</td>
                    @endif
                    @if ($investigacion->PuntoAtencion != '')
                        <td>{{ $investigacion->PuntoAtencion }}</td>
                    @endif
                    @if ($investigacion->DireccionPunto != '')
                        <td>{{ $investigacion->DireccionPunto }}</td>
                    @endif
                </tr>
                <tr>
                    <th>Observación</th>
                    <td colspan="19">{{ $investigacion->Observacion }}</td>
                </tr>
            </table>
            <table class="table datatable table-bordered" style="width: 100%">
                <tr>
                    <th>Creador/Analista</th>
                    <th>Aprobador</th>
                </tr>
                <tr>
                    <td class="text-capitalize">
                        {{ optional($creador)->full_name }}
                    </td>

                    <td class="text-capitalize">
                        {{ optional($aprobador)->full_name }}
                    </td>
                </tr>
            </table>
            <table class="table datatable table-bordered" style="width: 100%">
                <tr>
                    @if ($coordinador)
                        <th>Coordinador</th>
                    @endif
                    @if ($investigador)
                        <th>Investigador</th>
                    @endif
                    @if ($auxiliar)
                        <th>Auxiliar</th>
                    @endif
                    @if ($analista)
                        <th>Analista</th>
                    @endif
                </tr>
                <tr>
                    @if ($coordinador)
                        <td class="text-capitalize">
                            {{ optional($coordinador)->full_name }}
                        </td>
                    @endif
                    @if ($investigador)
                        <td class="text-capitalize">
                            {{ optional($investigador)->full_name }}
                        </td>
                    @endif
                    @if ($auxiliar)
                        <td class="text-capitalize">
                            {{ optional($auxiliar)->full_name }}
                        </td>
                    @endif
                    @if ($analista)
                        <td class="text-capitalize">
                            {{ optional($analista)->full_name }}
                        </td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
</div>
