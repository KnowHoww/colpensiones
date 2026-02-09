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
            <h2>{{ $title }}</h2>
        </div>
        <div class="col-12 bg-white p-3">
            <div class="tab-content bg-white my-3" id="myTabsContent">
                <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                    <table id="investigacionesTable" class="table datatable table-responsive table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Id case</th>
                                <th>centro de costos</th>
                                <th>Fraude</th>
                                <th>Estado</th>
                                <th>Radicado</th>
                                <th>Prioridad</th>
                                <th>Fecha solicitud</th>
                                <th>Fecha limite</th>
                                <th>Nombres y apellidos</th>
                                <th>Tipo y número de documento</th>
                                <th>Investigación</th>
                                <th>Riesgo</th>
                                <th>Ciudad</th>
                                @can('investigaciones.view.btn-watch')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($investigaciones as $investigacion)
                                <td class="align-middle">{{ $investigacion->id }}</td>
                                <td class="align-middle">{{ optional($investigacion->CentroCostos)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->fraude }}</td>
                                <td class="align-middle">{{ optional($investigacion->estados)->name }}</td>
                                <td class="align-middle">{{ $investigacion->NumeroRadicacionCaso }}</td>
                                <td class="align-middle">{{ optional($investigacion->Prioridades)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->MarcaTemporal }}</td>
                                <td class="align-middle">{{ $investigacion->FechaLimite }}</td>
                                <td class="align-middle">{{ $investigacion->PrimerNombre }}
                                    {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }}
                                    {{ $investigacion->SegundoApellido }}</td>
                                <td class="align-middle">{{ $investigacion->TipoDocumento }}
                                    {{ $investigacion->NumeroDeDocumento }}</td>
                                <td class="align-middle">
                                    {{ optional($investigacion->TipoInvestigaciones)->nombre ?? 'ND' }}</td>
                                <td class="align-middle">{{ optional($investigacion->DetalleRiesgos)->nombre }}</td>
                                <td class="align-middle">{{ $investigacion->Ciudad ?? 'ND' }}</td>
                                @can('investigaciones.view.btn-watch')
                                    <td><a
                                            href="{{ route('investigacion.show', $investigacion) }}"class="btn btn-primary">Ver</a>
                                    </td>
                                @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
