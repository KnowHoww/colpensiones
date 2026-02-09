@extends('layouts.app')
@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
    @endif
    @can('investigaciones.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('investigacion.create') }}" class="btn btn-primary">Nueva investigación</a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ $title }} @isset($estado->name)
                        {{ $estado->name }}
                    @endisset </h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 my-3">
            @can('invetigacion.filtro.todas')
                <a href="/migrupo/" class="btn btn-dark m-1" rel="noopener noreferrer">Todas
                @endcan
                @can('invetigacion.filtro.pendiente')
                    <a href="/migrupo/17" class="btn btn-dark m-1" rel="noopener noreferrer">Pendiente de
                        aprobación</a>
                @endcan
                @can('invetigacion.filtro.devuelto')
                    <a href="/migrupo/19" class="btn btn-dark m-1" rel="noopener noreferrer">Devuelto</a>
                @endcan
                @can('invetigacion.filtro.canceladoColpensiones')
                    <a href="/migrupo/20" class="btn btn-dark m-1" rel="noopener noreferrer">Cancelado
                        colpensiones</a>
                @endcan
                @can('invetigacion.filtro.solicitado')
                    <a href="/migrupo/3" class="btn btn-dark m-1" rel="noopener noreferrer">Solicitado</a>
                @endcan
                @can('invetigacion.filtro.asignado')
                    <a href="/migrupo/5" class="btn btn-dark m-1" rel="noopener noreferrer">Asignado</a>
                @endcan
                @can('invetigacion.filtro.revision')
                    <a href="/migrupo/6" class="btn btn-dark m-1" rel="noopener noreferrer">En
                        revisión</a>
                @endcan
                @can('invetigacion.filtro.finalizado')
                    <a href="/migrupo/7" class="btn btn-dark m-1" rel="noopener noreferrer">Finalizado</a>
                @endcan
                @can('invetigacion.filtro.objetado')
                    <a href="/migrupo/8" class="btn btn-dark m-1" rel="noopener noreferrer">Objetado</a>
                @endcan
                @can('invetigacion.filtro.canceladoJavh')
                    <a href="/migrupo/9" class="btn btn-dark m-1" rel="noopener noreferrer">Cancelado
                        javh</a>
                @endcan
                @can('invetigacion.filtro.correccion')
                    <a href="/migrupo/11" class="btn btn-dark m-1" rel="noopener noreferrer">En
                        Corrección</a>
                @endcan
                @can('invetigacion.filtro.aprobadoObjetado')
                    <a href="/migrupo/21" class="btn btn-dark m-1" rel="noopener noreferrer">Aprobado
                        Objetado</a>
                @endcan
                @can('invetigacion.filtro.objetadoFinalizado')
                    <a href="/migrupo/16" class="btn btn-dark m-1" rel="noopener noreferrer">Objetado
                        Finalizado</a>
                @endcan
        </div>
        <div class="col-12 bg-white p-3">
            <div class="col-12 col-md-5 col-xl-12 d-flex justify-content-between">
                <a class="btn btn-outline-primary rounded-0" href="/investigacionesTodas">Limpiar filtro</a>
                <form class="d-flex" action="{{ route('buscarInvestigacion') }}">
                    <input class="form-control rounded-0" name="filtro" type="search" placeholder="Consultar"
                        aria-label="Search" required>
                    <button class="btn btn-outline-primary rounded-0" type="submit">Consultar</button>
                </form>
            </div>
            <div class="col-12 mt-3">
                {{-- <b>Registros: {{ $cantidad }}</b> --}}
            </div>
            <div class="tab-content bg-white my-3" id="myTabsContent">
                <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                    <table id="investigacionesTable" class="table  table-responsive table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th title="ID Investigación">Id</th>
                                <th>Objetada</th>
                                <th title="Tipo de documento y número de documento causante">Documento</th>
                                <th title="">Causante</th>
                                <th title="Radicado Bizagi">Radicado</th>
                                <th>Estado</th>
                                <th>Fecha solicitud</th>
                                @if ($fechaAprobacion)
                                    <th>Fecha aprobación</th>
                                @endif
                                @if ($fechaLimite)
                                    <th>Fecha limite</th>
                                @endif
                                @if ($fechaObjecion)
                                    <th>Fecha objeción</th>
                                @endif
                                @if ($fechaFinalizacion)
                                    <th>Fecha finalización</th>
                                @endif
                                @if ($fechaCancelacion)
                                    <th>Fecha cancelación</th>
                                @endif
                                <th>Creador</th>
                                <th>Aprobador</th>
                                <th>Tipo</th>
                                <th>Riesgo</th>
                                <th>centro de costos</th>
                                <th>Fraude</th>
                                <th>Prioridad</th>
                                <th>Ciudad</th>
                                <th>Coor. Regional</th>
                                <th>Investigador</th>
                                <th>Analista</th>
                                <th>Acreditación</th>
                                @if ($btneditar)
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                @endif
                                @if ($btnver)
                                    @can('investigaciones.view.btn-watch')
                                        <th>Ver</th>
                                    @endcan
                                @endif
                                @if ($btnrevision)
                                    @can('investigaciones.view.btn-revision')
                                        <th>Revisar</th>
                                    @endcan
                                @endif
                                @if ($btninforme)
                                    @can('investigaciones.view.btn-investigacion-pdf')
                                        <th>Informe</th>
                                    @endcan
                                @endif
                                @if ($btninformeObjetado)
                                    @can('investigaciones.view.btn-investigacion-pdf')
                                        <th>Informe objetado</th>
                                    @endcan
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investigaciones as $investigacion)
                                <td class="align-middle">{{ $investigacion->id }}</td>
                                <td class="align-middle">{{ $investigacion->esObjetado == 1 ? 'Si' : 'No' }}</td>
                                <td class="align-middle">{{ $investigacion->TipoDocumento }}
                                    {{ $investigacion->NumeroDeDocumento }}</td>
                                <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                    {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                    {{ $investigacion->SegundoApellido }}</td>
                                <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                <td class="align-middle">{{ optional($investigacion->estados)->name }}</td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->MarcaTemporal))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->MarcaTemporal)) : '' }}
                                </td>
                                @if ($fechaLimite)
                                    <td class="align-middle">
                                        {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaLimite))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaLimite)) : '' }}
                                    </td>
                                @endif
                                @if ($fechaAprobacion)
                                    <td class="align-middle">
                                        {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaAprobacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaAprobacion)) : '' }}
                                    </td>
                                @endif
                                @if ($fechaObjecion)
                                    <td class="align-middle">
                                        {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaObjecion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaObjecion)) : '' }}
                                    </td>
                                @endif
                                @if ($fechaFinalizacion)
                                    <td class="align-middle">
                                        {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacion)) : '' }}
                                    </td>
                                @endif
                                @if ($fechaCancelacion)
                                    <td class="align-middle">
                                        {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaCancelacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaCancelacion)) : '' }}
                                    </td>
                                @endif
                                <td class="align-middle text-capitalize">
                                    {{ $investigacion->name_analistaColpensiones }}
                                    {{ $investigacion->lastname_analistaColpensiones }}</td>
                                <td class="align-middle text-capitalize">
                                    {{ $investigacion->name_aprobadorColpensiones }}
                                    {{ $investigacion->lastname_aprobadorColpensiones }}</td>
                                <td class="align-middle">
                                    {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}</td>
                                <td class="align-middle">{{ optional($investigacion->DetalleRiesgos)->nombre }}</td>
                                <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->fraude }}</td>
                                <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                <td class="align-middle text-capitalize">
                                    {{ $investigacion->name_coordinador ? $investigacion->name_coordinador : 'No asignado' }}
                                    {{ $investigacion->lastname_coordinador }}</td>
                                <td class="align-middle text-capitalize">
                                    {{ $investigacion->name_investigador ? $investigacion->name_investigador : 'No asignado' }}
                                    {{ $investigacion->lastname_investigador }}</td>
                                <td class="align-middle text-capitalize">
                                    {{ $investigacion->name_analista ? $investigacion->name_analista : 'No asignado' }}
                                    {{ $investigacion->lastname_analista }}</td>
                                <td>
                                    @if ($investigacion->acreditacion == 14)
                                        Acredita
                                    @elseif($investigacion->acreditacion == 15)
                                        No acredita
                                    @else
                                        Pendiente
                                    @endif
                                </td>
                                @if ($btneditar)
                                    @can('investigaciones.view.btn-edit')
                                        <td class="align-content-center"><a
                                                href="{{ route('investigacion.edit', $investigacion) }}"class="btn btn-primary">Editar</a>
                                        </td>
                                    @endcan
                                @endif
                                @if ($btnver)
                                    @can('investigaciones.view.btn-watch')
                                        <td class="align-content-center">
                                            @if ($investigacion->estado != 17 && $investigacion->estado != 19)
                                                <a
                                                    href="{{ route('investigacion.show', $investigacion) }}"class="btn btn-primary">Ver</a>
                                            @endif
                                        </td>
                                    @endcan
                                @endif
                                @if ($btnrevision)
                                    @can('investigaciones.view.btn-revision')
                                        <td class="align-content-center">
                                            @if ($investigacion->estado == 17 || $investigacion->estado == 19)
                                                <a target="_blank"
                                                    href="{{ route('revisioninvestigacion', $investigacion) }}"
                                                    class="btn btn-primary">Revisar</a>
                                            @endif
                                        </td>
                                    @endcan
                                @endif
                                @if ($btninforme)
                                    @can('investigaciones.view.btn-investigacion-pdf')
                                        <td class="align-content-center">
                                            @if ($investigacion->estado == 7)
                                                <a target="_blank" href="{{ route('verinformepdf', $investigacion) }}"
                                                    class="btn btn-primary">Informe</a>
                                            @endif
                                        </td>
                                    @endcan
                                @endif
                                @if ($btninformeObjetado)
                                    @can('investigaciones.view.btn-investigacion-pdf')
                                        <td class="align-content-center">
                                            @if ($investigacion->estado == 16)
                                                <a target="_blank" href="{{ route('verinformepdf', $investigacion) }}"
                                                    class="btn btn-primary">Informe objetado</a>
                                            @endif
                                        </td>
                                    @endcan
                                @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="30" class="text-center">No se encontraron registros con el parametro de
                                        busqueda</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $investigaciones->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
