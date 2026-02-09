@extends('layouts.app')
@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
    @endif
    <!-- @can('investigaciones.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('investigacion.create') }}" class="btn btn-primary">Nueva investigación</a>
            </div>
        </div>
    @endcan -->
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 my-3">
            @can('invetigacion.filtro.pendiente')
                <a href="/investigacionesLista/{{ $filtro }}&est=17" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Pendiente de
                    aprobación</a>
            @endcan
            @can('invetigacion.filtro.devuelto')
                <a href="/investigacionesLista/{{ $filtro }}&est=19" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Devuelto</a>
            @endcan
            @can('invetigacion.filtro.canceladoColpensiones')
                <a href="/investigacionesLista/{{ $filtro }}&est=20" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Cancelado
                    colpensiones</a>
            @endcan
            @can('invetigacion.filtro.solicitado')
                <a href="/investigacionesLista/{{ $filtro }}&est=3" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Solicitado</a>
            @endcan
            @can('invetigacion.filtro.asignado')
                <a href="/investigacionesLista/{{ $filtro }}&est=5" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Asignado</a>
            @endcan
            @can('invetigacion.filtro.revision')
                <a href="/investigacionesLista/{{ $filtro }}&est=6" class="btn btn-dark m-1" rel="noopener noreferrer">En
                    revisión</a>
            @endcan
            @can('invetigacion.filtro.finalizado')
                <a href="/investigacionesLista/{{ $filtro }}&est=7" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Finalizado</a>
            @endcan
            @can('invetigacion.filtro.objetado')
                <a href="/investigacionesLista/{{ $filtro }}&est=8" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Objetado</a>
            @endcan
            @can('invetigacion.filtro.canceladoJavh')
                <a href="/investigacionesLista/{{ $filtro }}&est=9" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Cancelado
                    javh</a>
            @endcan
            @can('invetigacion.filtro.correccion')
                <a href="/investigacionesLista/{{ $filtro }}&est=11" class="btn btn-dark m-1"
                    rel="noopener noreferrer">En
                    Corrección</a>
            @endcan
            @can('invetigacion.filtro.aprobadoObjetado')
                <a href="/investigacionesLista/{{ $filtro }}&est=21" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Aprobado
                    Objetado</a>
            @endcan
            @can('invetigacion.filtro.objetadoFinalizado')
                <a href="/investigacionesLista/{{ $filtro }}&est=16" class="btn btn-dark m-1"
                    rel="noopener noreferrer">Objetado
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
            {{-- <div class="col-12 mt-3">
                <b>Registros: {{ count($investigaciones) }}</b>
            </div> --}}
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
                                <th>Fecha aprobación</th>
                                <th>Fecha limite</th>
                                <th>Fecha finalización</th>
                                <th>Fecha cancelación</th>
                                @if (Auth::user()->centroCosto != 1)
                                    <th>Creador</th>
                                    <th>Aprobador</th>
                                @endif
                                <th>Tipo</th>
                                <th>Riesgo</th>
                                <th>centro de costos</th>
                                <th>Fraude</th>
                                <th>Prioridad</th>
                                <th>Ciudad</th>
                                @if (Auth::user()->centroCosto == 1)
                                    <th>Coor. Regional</th>
                                    <th>Investigador</th>
                                @endif
                                <th>Acreditación</th>
                                @can('investigaciones.view.btn-edit')
                                    <th>Editar</th>
                                @endcan
                                @can('investigaciones.view.btn-watch')
                                    <th>Ver</th>
                                @endcan
                                @can('investigaciones.view.btn-investigacion-pdf')
                                    <th>Informe</th>
                                @endcan
                                @can('investigaciones.view.btn-investigacion-pdf')
                                    <th>Informe objetado</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investigaciones as $investigacion)
                                <td class="align-middle">{{ $investigacion->id }}</td>
                                <td class="align-middle">{{ $investigacion->estado == 8 ? 'Si' : 'No' }}</td>
                                <td class="align-middle">{{ $investigacion->TipoDocumento }}
                                    {{ $investigacion->NumeroDeDocumento }}</td>
                                <td class="align-middle">
                                    {{ $investigacion->PrimerNombre }}{{ $investigacion->SegundoNombre }}
                                    {{ $investigacion->PrimerApellido }}{{ $investigacion->SegundoApellido }}</td>
                                <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                <td class="align-middle">{{ optional($investigacion->estados)->name }}</td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->MarcaTemporal))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->MarcaTemporal)) : '' }}
                                </td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaLimite))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaLimite)) : '' }}
                                </td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaAprobacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaAprobacion)) : '' }}
                                </td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaFinalizacion)) : '' }}
                                </td>
                                <td class="align-middle">
                                    {{ date('Y-m-d H:i:s', strtotime($investigacion->FechaCancelacion))> '1970-01-01' ? date('Y-m-d H:i:s', strtotime($investigacion->FechaCancelacion)) : '' }}
                                </td>
                                @if (Auth::user()->centroCosto != 1)
                                    <td class="align-middle text-capitalize">
                                        {{ $investigacion->name_analistaColpensiones }}
                                        {{ $investigacion->lastname_analistaColpensiones }}</td>
                                    <td class="align-middle text-capitalize">
                                        {{ $investigacion->name_aprobadorColpensiones }}
                                        {{ $investigacion->lastname_aprobadorColpensiones }}</td>
                                @endif
                                <td class="align-middle">
                                    {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}</td>
                                <td class="align-middle">{{ optional($investigacion->DetalleRiesgos)->nombre }}</td>
                                <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->fraude }}</td>
                                <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                @if (Auth::user()->centroCosto == 1)
                                    <td class="align-middle text-capitalize">
                                        {{ $investigacion->name_coordinador ? $investigacion->name_coordinador : 'No asignado' }}
                                        {{ $investigacion->lastname_coordinador }}</td>
                                    <td class="align-middle text-capitalize">
                                        {{ $investigacion->name_investigador ? $investigacion->name_investigador : 'No asignado' }}
                                        {{ $investigacion->lastname_investigador }}</td>
                                @endif
                                <td>
                                    @if ($investigacion->acreditacion == 14)
                                        Acredita
                                    @elseif($investigacion->acreditacion == 15)
                                        No acredita
                                    @else
                                        Pendiente
                                    @endif
                                </td>
                                @can('investigaciones.view.btn-edit')
                                    <td class="align-content-center"><a
                                            href="{{ route('investigacion.edit', $investigacion) }}"class="btn btn-primary">Editar</a>
                                    </td>
                                @endcan
                                @can('investigaciones.view.btn-watch')
                                    <td class="align-content-center"><a
                                            href="{{ route('investigacion.show', $investigacion) }}"class="btn btn-primary">Ver</a>
                                    </td>
                                @endcan
                                @can('investigaciones.view.btn-investigacion-pdf')
                                    <td class="align-content-center">
                                        @if ($investigacion->estado == 7)
                                            <a target="_blank" href="{{ route('verinformepdf', $investigacion) }}"
                                                class="btn btn-primary">Informe</a>
                                        @endif
                                    </td>
                                @endcan

                                @can('investigaciones.view.btn-investigacion-pdf')
                                    <td class="align-content-center">
                                        @if ($investigacion->estado == 16)
                                            <a target="_blank" href="{{ route('verinformepdf', $investigacion) }}"
                                                class="btn btn-primary">Informe objetado</a>
                                        @endif
                                    </td>
                                @endcan

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="30" class="text-center">No se encontraron registros con el parametro de busqueda</td>
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
