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
                @can('investigaciones.tab.Pendientedeaprobacion')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="Pendientedeaprobacion-tab" data-bs-toggle="tab"
                            data-bs-target="#Pendientedeaprobacion" type="button" role="tab"
                            aria-controls="Pendientedeaprobacion" aria-selected="true">Pendiente de aprobación</button>
                    </li>
                @endcan
                @can('investigaciones.tab.CanceladoColpensiones')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="CanceladoColpensiones-tab" data-bs-toggle="tab"
                            data-bs-target="#CanceladoColpensiones" type="button" role="tab"
                            aria-controls="CanceladoColpensiones" aria-selected="false">Cancelado Colpensiones</button>
                    </li>
                @endcan
                @can('investigaciones.tab.Aprobado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Aprobado-tab" data-bs-toggle="tab" data-bs-target="#Aprobado"
                            type="button" role="tab" aria-controls="Aprobado" aria-selected="false">Aprobado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.finalizado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="finalizado-tab" data-bs-toggle="tab" data-bs-target="#finalizado"
                            type="button" role="tab" aria-controls="finalizado" aria-selected="false">finalizado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.Objetado')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Objetado-tab" data-bs-toggle="tab" data-bs-target="#Objetado"
                            type="button" role="tab" aria-controls="Objetado" aria-selected="false">Objetado</button>
                    </li>
                @endcan
                @can('investigaciones.tab.CanceladoCliente')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="CanceladoCliente-tab" data-bs-toggle="tab"
                            data-bs-target="#CanceladoCliente" type="button" role="tab" aria-controls="CanceladoCliente"
                            aria-selected="false">Cancelado Cliente</button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content bg-white my-3" id="myTabsContent">
                @can('investigaciones.tab.Pendientedeaprobacion')
                    <div class="tab-pane fade show active" id="Pendientedeaprobacion" role="tabpanel"
                        aria-labelledby="Pendientedeaprobacion-tab">
                        <table id="investigacionesPendientedeaprobacionTable"
                            class="table datatable table-responsive table-striped">
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
                                    @if ($investigacion->estado == 17)
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
                @can('investigaciones.tab.CanceladoCliente')
                    <div class="tab-pane fade" id="CanceladoCliente" role="tabpanel" aria-labelledby="CanceladoCliente-tab">
                        <table id="investigacionesCanceladoClienteTable"
                            class="table datatable table-responsive table-striped" style="width:100%">
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
                                    @if ($investigacion->estado == 20)
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
                @can('investigaciones.tab.Aprobado')
                    <div class="tab-pane fade" id="Aprobado" role="tabpanel" aria-labelledby="Aprobado-tab">
                        <table id="investigacionesAprobadoTable" class="table datatable table-responsive table-striped"
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
                                    @if ($investigacion->estado == 18)
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
                                                <a target="_blank"
                                                    href="/investigaciones/radicado/2024_1778253/investigacion/DJT-INF-AD-{{ $investigacion->NumeroRadicacionCaso }}_{{ date('dmY') }}_{{ $investigacion->TipoDocumento }}_{{ $investigacion->NumeroDeDocumento }}_{{ $investigacion->id }}.pdf"
                                                    class="btn btn-primary">Informe</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endcan
                @can('investigaciones.tab.Objetado')
                    <div class="tab-pane fade" id="Objetado" role="tabpanel" aria-labelledby="Objetado-tab">
                        <table id="investigacionesObjetadoTable" class="table datatable table-responsive table-striped"
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
                @can('investigaciones.tab.CanceladoColpensiones')
                    <div class="tab-pane fade" id="CanceladoColpensiones" role="tabpanel"
                        aria-labelledby="CanceladoColpensiones-tab">
                        <table id="investigacionesCanceladoColpensionessTable"
                            class="table datatable table-responsive table-striped" style="width:100%">
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
                @can('investigaciones.tab.FinalidoObjetado')
                    <div class="tab-pane fade" id="FinalidoObjetado" role="tabpanel"
                        aria-labelledby="FinalidoObjetado-tab">
                        <table id="investigacionesFinalidoObjetadosTable"
                            class="table datatable table-responsive table-striped" style="width:100%">
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
            </div>
        </div>
    </div>
@endsection
