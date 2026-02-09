@extends('layouts.app')
@section('content')
    @can('investigaciones.view.btn-create')
        @if (session('info'))
            <div class="alert alert-success">
                {{ session('info') }}
            </div>
        @endif
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('investigacion.create') }}" class="btn btn-primary">Nueva investigación</a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-12">
            <h2>{{ isset($title) ? $title : 'Investigaciones' }}</h2>
        </div>
        <div class="col-12">
            @can('investigaciones.view.btn-upload-masivo-investigaciones')
                <div class="row justify-content-center my-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body overflow-auto">
                                {!! Form::open([
                                    'route' => 'cargarMasivoInvestigaciones',
                                    'method' => 'post',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                {!! Form::token() !!}
                                {!! Form::file('archivo', ['accept' => '.xls,.xlsx', 'required' => 'required', 'class' => 'btn btn-dark']) !!}
                                {{ Form::submit('Cargar masivo de investigaciones', ['class' => 'btn btn-primary']) }}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <div class="col-12 bg-white p-3">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                @can('investigaciones.tab.solicitado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="solicitado-tab" data-bs-toggle="tab" data-bs-target="#solicitado"
                            type="button" role="tab" aria-controls="solicitado" aria-selected="true">Solicitado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.asignado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="asignado-tab" data-bs-toggle="tab" data-bs-target="#asignado"
                            type="button" role="tab" aria-controls="asignado" aria-selected="false">Asignado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.revision')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="revision-tab" data-bs-toggle="tab" data-bs-target="#revision"
                            type="button" role="tab" aria-controls="revision" aria-selected="false">En revisión</button>
                    </li>
                @endcan
                @can('investigaciones.tab.correccion')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="correccion-tab" data-bs-toggle="tab" data-bs-target="#correccion"
                            type="button" role="tab" aria-controls="correccion" aria-selected="false">En
                            corrección</button>
                    </li>
                @endcan
                @can('investigaciones.tab.finalizado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="finalizado-tab" data-bs-toggle="tab" data-bs-target="#finalizado"
                            type="button" role="tab" aria-controls="finalizado" aria-selected="false">finalizado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.devuelto')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="devuelto-tab" data-bs-toggle="tab" data-bs-target="#devuelto"
                            type="button" role="tab" aria-controls="devuelto" aria-selected="false">Objetado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.cancelado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelado-tab" data-bs-toggle="tab" data-bs-target="#cancelado"
                            type="button" role="tab" aria-controls="cancelado" aria-selected="false">Cancelado</button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content bg-white my-3" id="myTabsContent">
                @can('investigaciones.tab.solicitado')
                    <div class="tab-pane fade show" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesSolicitadasTable" class="table datatable table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 3)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td><a
                                                    href="{{ route('investigacion.edit', $investigacion) }}"class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.asignado')
                    <div class="tab-pane active" id="asignado" role="tabpanel" aria-labelledby="asignado-tab">
                        <table id="investigacionesAsignadosTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 5)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td>
                                                <a href="{{ route('investigacion.edit', $investigacion) }}"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.revision')
                    <div class="tab-pane fade" id="revision" role="tabpanel" aria-labelledby="revision-tab">
                        <table id="investigacionesRevisionTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 6)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td>
                                                <a href="{{ route('investigacion.edit', $investigacion) }}"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.finalizado')
                    <div class="tab-pane fade" id="finalizado" role="tabpanel" aria-labelledby="finalizado-tab">
                        <table id="investigacionesFinalizadasTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    <th>Acreditación</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                    @can('investigaciones.view.btn-investigacion-pdf')
                                        <th>Informe</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 7)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        <td class="align-middle">{{ $investigacion->Acreditaciones->name ?? 'ND' }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td>
                                                <a href="{{ route('investigacion.edit', $investigacion) }}"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        @can('investigaciones.view.btn-investigacion-pdf')
                                            <td class="align-content-center">
                                                <a target="_blank" href="/investigaciones/radicado/2024_1778253/investigacion/DJT-INF-AD-{{ $investigacion->NumeroRadicacionCaso }}_{{ date('dmY') }}_{{ $investigacion->TipoDocumento }}_{{ $investigacion->NumeroDeDocumento }}_{{ $investigacion->id }}.pdf" class="btn btn-primary">Informe</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.devuelto')
                    <div class="tab-pane fade" id="devuelto" role="tabpanel" aria-labelledby="devuelto-tab">
                        <table id="investigacionesDevueltoTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 8)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">---{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td>
                                                <a href="{{ route('investigacion.edit', $investigacion) }}"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.cancelado')
                    <div class="tab-pane fade" id="cancelado" role="tabpanel" aria-labelledby="cancelado-tab">
                        <table id="investigacionesCanceladosTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-watch')
                                        <th>Editar</th>
                                    @endcan
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 9)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-watch')
                                            <td>
                                                <a href="{{ route('investigacion.show', $investigacion) }}"
                                                    class="btn btn-primary">Ver</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.correccion')
                    <div class="tab-pane fade" id="correccion" role="tabpanel" aria-labelledby="correccion-tab">
                        <table id="investigacionesCorreccionTable" class="table datatable table-responsive table-striped"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Id case</th>
                                    <th>Centro de costos</th>
                                    <th>Fraude</th>
                                    <th>Radicado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha limite</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Investigación</th>
                                    <th>Ciudad</th>
                                    <th>Regional</th>
                                    <th>Investigador</th>
                                    @can('investigaciones.view.btn-edit')
                                        <th>Editar</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investigaciones as $investigacion)
                                    @if ($investigacion->estado == 11)
                                        <td class="align-middle">{{ $investigacion->id }}</td>
                                        <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->fraude }}</td>
                                        <td class="align-middle">{{ $investigacion->CasoPadreOriginal }}</td>
                                        <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                        <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                        <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                        <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                            {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                            {{ $investigacion->SegundoApellido }}</td>
                                        <td class="align-middle">
                                            {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}
                                        </td>
                                        <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_coordinador }}
                                            {{ $investigacion->lastname_coordinador }}</td>
                                        <td class="align-middle text-capitalize">{{ $investigacion->name_investigador }}
                                            {{ $investigacion->lastname_investigador }}</td>
                                        @can('investigaciones.view.btn-edit')
                                            <td>
                                                <a href="{{ route('investigacion.edit', $investigacion) }}"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
