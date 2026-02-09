@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-12">
            @if (session('info'))
                <div class="alert alert-success">
                    {{ session('info') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Se muestran las investigaciones Asociadas -->
            <div class="card my-2">
                <div class="card-body">
                    <table class="table" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Número radicación caso</th>
                                <th>Estado</th>
                                <th colspan="2">Documento</th>
                                <th>Tipo investigación</th>
                                <th>Tipo riesgo</th>
                                <th>Detalle riesgo</th>
                                <th>Primer Nombre</th>
                                <th>Segundo Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Ver investigación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($Historial as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->NumeroRadicacionCaso }}</td>
                                    <td>{{ $item->Estados->name }}</td>
                                    <td>{{ $item->TipoDocumento }}</td>
                                    <td id="tipo_{{ $item->id }}">{{ $item->NumeroDeDocumento }}</td>
                                    <td>{{ $item->TipoInvestigaciones->nombre }}</td>
                                    <td>{{ $item->TipoRiesgos->nombre }}</td>
                                    <td>{{ optional($item->DetalleRiesgos)->nombre ?? 'No especificado' }}</td>
                                    <td id="nombre1_{{ $item->id }}">{{ $item->PrimerNombre }}</td>
                                    <td id="nombre2_{{ $item->id }}">{{ $item->SegundoNombre }}</td>
                                    <td id="apellido1_{{ $item->id }}">{{ $item->PrimerApellido }}</td>
                                    <td id="apellido2_{{ $item->id }}">{{ $item->SegundoApellido }}</td>
                                    <td><a target="_blank" href="{{ route('investigacion.show', $item->id) }}"
                                            class="btn btn-primary usarInformacion">Ver</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center">No se encontraron investigaciones para el
                                        documento
                                        <u><b>{{ $informacion?->NumeroDeDocumento }}</b></u>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- SECCION INFORMACION DEL CAUSANTE -->
            <div class="card mt-2">
                <div class="card-body">
                    {!! Form::model($investigacion, [
                        'route' => ['investigacion.update', $investigacion->id],
                        'method' => 'put',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    {{ Form::token() }}
                        <div class="row">
                            <!-- Se inicia el apartado de INFORMACION DEL CAUSANTE -->
                            <div class="col-12 d-flex justify-content-between mt-2">
                                <h3 class="my-2">Información del causante</h3>
                            </div>
                            <!-- Campo para Tipo de documento -->
                            <div class="form-group col-4">
                                <div>
                                    <label>
                                        {!! Form::label('TipoDocumento', 'Tipo de documento*', ['class' => 'form-label']) !!}
                                        {!! Form::select(
                                            'TipoDocumento',
                                            collect($TipoDocumento)->pluck('nombre', 'codigo')->toArray(),
                                            $investigacion->TipoDocumento,
                                            [
                                                'class' => 'form-control',
                                                'required',
                                                'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,

                                            ],
                                        ) !!}

                                    </label>
                                </div>
                            </div>
                            <!-- Campo para NUMERO de documento -->
                            <div class="form-group col-4">
                                {!! Form::label('NumeroDeDocumento', 'Número de documento*', ['class' => 'form-label']) !!}
                                {!! Form::text('NumeroDeDocumento', $investigacion->NumeroDeDocumento, [
                                    'class' => 'form-control',
                                    'required',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <!-- Campo deshabilitado (Numero de CasoPadre) -->
                            {{-- 
                                <div class="form-group col-4">
                                    {!! Form::label('CasoPadreOriginal', 'Numero Radicacion Padre*', ['class' => 'form-label']) !!}
                                    {!! Form::text('CasoPadreOriginal', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> 
                            --}}
                            <!-- Campo para NUMERO de Radicado del caso -->
                            <div class="form-group col-4">
                                {!! Form::label('NumeroRadicacionCaso', 'Numero Radicacion Caso*', ['class' => 'form-label']) !!}
                                {!! Form::text('NumeroRadicacionCaso', null, [
                                    'class' => 'form-control',
                                    'required',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Campo para tipo de Investigacion -->
                            <div class="form-group col-4">
                                <div>
                                    <label>
                                        {!! Form::label('TipoInvestigacion', 'Tipo de investigación*', ['class' => 'form-label']) !!}
                                        {!! Form::select('TipoInvestigacion', collect($TipoInvestigacion)->pluck('nombre', 'codigo')->toArray(), null, [
                                            'class' => 'form-control',
                                            'required',
                                            'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            'id' => 'TipoInvestigacionSelect'
                                            ]) !!}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Campo para tipo de Riesgo -->
                            <div class="form-group col-4">
                                <div>
                                    <label>
                                        {!! Form::label('TipoRiesgo', 'Tipo de riesgo*', ['class' => 'form-label']) !!}
                                        {!! Form::select('TipoRiesgo', collect($TipoRiesgo)->pluck('nombre', 'codigo')->toArray(), null, [
                                            'class' => 'form-control',
                                            'required',
                                            'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                        ]) !!}
                                    </label>
                                </div>
                            </div>
                            <!-- Campo para Detalle del riesgo -->
                            <div class="form-group col-4">
                                <div>
                                    <label>
                                        {!! Form::label('DetalleRiesgo', 'Detalle de riesgo*', ['class' => 'form-label']) !!}
                                        {!! Form::select(
                                            'DetalleRiesgo',
                                            [null => 'Seleccione... '] + collect($DetalleRiesgo)->pluck('nombre', 'codigo')->toArray(),
                                            null,
                                            [
                                                'class' => 'form-control',
                                                'required',
                                                'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            ],
                                        ) !!}
                                    </label>
                                </div>
                            </div>
                            <!-- Campo Para Tipo de Tramite -->
                            <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoTramite', 'Tipo de tramite*', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoTramite',
                                                    [null => 'Seleccione... '] + collect($TipoTramite)
                                                    ->filter(function($item) {
                                                        return in_array($item->nombre, ['Nómina de Pensionados', 'Reconocimiento']);
                                                    })
                                                    ->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                        'required',
                                                        'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                </div>
                            <!-- Campo para tipo de pension -->
                            <div class="form-group col-4">
                                <div>
                                    <label>
                                        {!! Form::label('TipoPension', 'Tipo de pension', ['class' => 'form-label']) !!}
                                        {!! Form::select(
                                            'TipoPension',
                                            [null => 'Seleccione... '] + collect($TipoPension)->pluck('nombre', 'codigo')->toArray(),
                                            null,
                                            [
                                                'class' => 'form-control',
                                                'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            ],
                                        ) !!}
                                    </label>
                                </div>
                            </div>
                            <!-- Informacion de Junta -->
                            <!-- Quitamos el id=listaValidacionDocumental, el cual no permitia mostrar esta
                            sección cuando la investigación se trata de una validación documenta
                            para dejar solo la siguiente comprobacion $investigacion->TipoInvestigacion == 'VD'-->
                            @if ($investigacion->TipoInvestigacion == 'VD')
                                <div  class="row">
                                    <div class="row">
                                        <div class="form-group col-4">
                                            {!! Form::label('Junta', 'Junta', ['class' => 'form-label']) !!}
                                            {!! Form::select('Junta', collect($TipoJuntas)->pluck('nombre', 'id')->toArray(), null, [
                                                'class' => 'form-control',
                                                'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-4">
                                            {!! Form::label('NumeroDictamen', 'Número de dictamen', ['class' => 'form-label']) !!}
                                            {!! Form::text('NumeroDictamen', null, [
                                                'class' => 'form-control',
                                                'disabled' => Auth::user()->roles->pluck('id')[0] == 12 && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-4">
                                            {!! Form::label('FechaDictamen', 'Fecha de dictamen', ['class' => 'form-label']) !!}
                                            {!! Form::date('FechaDictamen', null, [
                                                'class' => 'form-control',
                                                'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                            ]) !!}
                                        </div>
                                    </div>
                                    <!-- Tipo de Tramite -->
                                    <!-- <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoTramite', 'Tipo de tramite', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoTramite',
                                                    [null => 'Seleccione... '] + collect($TipoTramite)->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                        'disabled' => Auth::user()->roles->pluck('id')[0] == 12 && $investigacion->estado == 19 ? false : true,
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                    </div> -->
                                    <!-- Tipo de Solicitud -->
                                    <!-- <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoSolicitud', 'Tipo de solicitud', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoSolicitud',
                                                    [null => 'Seleccione... '] + collect($TipoSolicitud)->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                        'disabled' => Auth::user()->roles->pluck('id')[0] == 12 && $investigacion->estado == 19 ? false : true,
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                    </div> -->
                                    <!-- Tipo de Solicitante -->
                                    <!-- <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoSolicitante', 'Tipo de solicitante', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoSolicitante',
                                                    [null => 'Seleccione... '] + collect($TipoSolicitante)->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                        'disabled' => Auth::user()->roles->pluck('id')[0] == 12 && $investigacion->estado == 19 ? false : true,
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                    </div> -->
                                </div>
                            @endif
                            <!-- Primer nombre -->
                            <div class="form-group col-3">
                                {!! Form::label('PrimerNombre', 'Primer nombre*', ['class' => 'form-label']) !!}
                                {!! Form::text('PrimerNombre', null, [
                                    'class' => 'form-control',
                                    'required',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Segundo nombre -->
                            <div class="form-group col-3">
                                {!! Form::label('SegundoNombre', 'Segundo nombre', ['class' => 'form-label']) !!}
                                {!! Form::text('SegundoNombre', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Primer apellido -->
                            <div class="form-group col-3">
                                {!! Form::label('PrimerApellido', 'Primer apellido*', ['class' => 'form-label']) !!}
                                {!! Form::text('PrimerApellido', null, [
                                    'class' => 'form-control',
                                    'required',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Segundo apellido -->
                            <div class="form-group col-3">
                                {!! Form::label('SegundoApellido', 'Segundo apellido', ['class' => 'form-label']) !!}
                                {!! Form::text('SegundoApellido', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Radicado asociado -->
                            {{-- <div class="form-group col-4">
                                {!! Form::label('RadicadoAsociado', 'Radicado asociado', ['class' => 'form-label']) !!}
                                {!! Form::text('RadicadoAsociado', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div> --}}
                        </div>
                        <div class="row">
                            <!-- Ciudad causante -->
                            <div class="form-group col-4">
                                {!! Form::label('Ciudad', 'Ciudad causante', ['class' => 'form-label']) !!}
                                {!! Form::text('Ciudad', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Dirección causante -->
                            <div class="form-group col-4">
                                {!! Form::label('DireccionCausante', 'Dirección causante', ['class' => 'form-label']) !!}
                                {!! Form::text('DireccionCausante', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Teléfono causante -->
                            <div class="form-group col-4">
                                {!! Form::label('TelefonoCausante', 'Teléfono causante', ['class' => 'form-label']) !!}
                                {!! Form::text('TelefonoCausante', null, [
                                    'class' => 'form-control',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                            <!-- Observación -->
                            <div class="form-group col-12">
                                {!! Form::label('Observacion', 'Observación*', ['class' => 'form-label']) !!}
                                {!! Form::textarea('Observacion', null, [
                                    'class' => 'form-control',
                                    'rows' => 3,
                                    'required',
                                    'disabled' => (Auth::user()->roles->isNotEmpty() && Auth::user()->roles->pluck('id')[0] == 12) && ($investigacion->estado == 19 || $investigacion->estado == 17) ? false : true,
                                ]) !!}
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Codigo sin ningún uso -->
                            {{-- <div class="col-12 my-3">
                                <table class="table datatable table-striped" style="width: 100%">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Guia</th>
                                            <th>Documento</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documentos as $documento)
                                            <tr>
                                                <td>
                                                    @if (strpos($documento, 'investigacion') !== false)
                                                        Investigación
                                                    @elseif(strpos($documento, 'soporteFotografico') !== false)
                                                        Soporte fotografico
                                                    @else
                                                        Bizagi
                                                    @endif
                                                </td>
                                                <td>{{ explode('/', $documento)[count(explode('/', $documento)) - 1] }}</td>
                                                <td>
                                                    <a class="btn btn-primary" target="_blank"
                                                        href="{{ env('APP_URL') . '/investigaciones/' . str_replace(' ', '%20', $documento) }}">Ver
                                                        anexo</a>
                                                    @if ($investigacion->estado == 19 && Auth::user()->roles->pluck('id')[0] == 12)
                                                        <form action="{{ route('eliminarSoporte') }}" method="POST"
                                                            style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="ruta_archivo"
                                                                value="{{ $documento }}">
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Eliminar</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div> --}}
                            @if (($investigacion->estado == 19 || $investigacion->estado == 17) && (Auth::user()->roles->pluck('id')[0] == 12 || Auth::user()->roles->pluck('id')[0] == 1))
                                <div class="col-12 d-flex justify-content-between">
                                    <h3 class="">Documentos</h3>
                                </div>
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <div class="form-group">
                                            {{ Form::label('files', 'Seleccione archivos:') }}
                                            {{ Form::file('files[]', ['multiple', 'class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                @if (($investigacion->estado == 19 || $investigacion->estado == 17) && (Auth::user()->roles->pluck('id')[0] == 12 || Auth::user()->roles->pluck('id')[0] == 1))
                                    {!! Form::submit('Actualizar información', ['class' => 'btn btn-primary mt-3']) !!}
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                </div>
            </div>

            <!-- SECCION DOCUMENTOS ANEXOS -->
            <div class="card mt-2">
                
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-between">
                        <h3 class="">Documentos anexos</h3>
                    </div>
                    <div class="col-12 my-3">
                        <table class="table datatable table-striped" style="width: 100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Guia</th>
                                    <th>Documento</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documentos as $documento)
                                    <tr>
                                        <td>
                                            @if (strpos($documento, 'investigacion') !== false)
                                                Investigación
                                            @elseif(strpos($documento, 'soporteFotografico') !== false)
                                                Soporte fotografico
                                            @else
                                                Bizagi
                                            @endif
                                        </td>
                                        <td>{{ explode('/', $documento)[count(explode('/', $documento)) - 1] }}</td>
                                        <td>
                                            <a class="btn btn-primary" target="_blank"
                                                href="{{ env('APP_URL') . '/investigaciones/' . str_replace(' ', '%20', $documento) }}">Ver
                                                anexo</a>
                                            @if (($investigacion->estado == 19 || $investigacion->estado == 17) && (Auth::user()->roles->pluck('id')[0] == 12 || Auth::user()->roles->pluck('id')[0] == 1))
                                                <form action="{{ route('eliminarSoporte') }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="ruta_archivo" value="{{ $documento }}">
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- BENEFICIARIOS -->
            <div id="beneficiariosContainer" data-count="{{ count($beneficiarios) }}"></div>
            <div class="card mt-2">
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-between mt-4">
                        <h3 class="mt-1">Beneficiarios</h3>
                        @if (($investigacion->estado == 19 || $investigacion->estado == 17) &&
                                (Auth::user()->roles->pluck('id')[0] == 12 || Auth::user()->roles->pluck('id')[0] == 1))
                            <button type="button" id="nuevo_beneficiario" class="btn btn-primary my-2">Agregar beneficiario</button>
                        @endif
                    </div>

                    <div class="col-12 mb-4">
                        
                        <div class="accordion">
                            @foreach ($beneficiarios as $beneficiario)
                            {!! Form::model($beneficiario, ['route' => ['beneficiarios.update', $beneficiario->id], 'method' => 'post']) !!}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $beneficiario->id }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $beneficiario->id }}" aria-expanded="true" aria-controls="collapse{{ $beneficiario->id }}">
                                        Beneficiario {{ $loop->iteration }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $beneficiario->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $beneficiario->id }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="PrimerNombre{{ $beneficiario->id }}">Primer nombre*</label>
                                                {!! Form::text('PrimerNombre', null, ['class' => 'form-control', 'id' => 'PrimerNombre'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="SegundoNombre{{ $beneficiario->id }}">Segundo nombre</label>
                                                {!! Form::text('SegundoNombre', null, ['class' => 'form-control', 'id' => 'SegundoNombre'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="PrimerApellido{{ $beneficiario->id }}">Primer apellido*</label>
                                                {!! Form::text('PrimerApellido', null, ['class' => 'form-control', 'id' => 'PrimerApellido'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="SegundoApellido{{ $beneficiario->id }}">Segundo apellido</label>
                                                {!! Form::text('SegundoApellido', null, ['class' => 'form-control', 'id' => 'SegundoApellido'.$beneficiario->id]) !!}
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                <label for="TipoDocumento{{ $beneficiario->id }}">Tipo documento*</label>
                                                {!! Form::select('TipoDocumento', $tipoDocumento->pluck('nombre', 'codigo'), $beneficiario->TipoDocumento, ['class' => 'form-control', 'id' => 'TipoDocumento'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <label for="NumeroDocumento{{ $beneficiario->id }}">Número documento*</label>
                                                {!! Form::text('NumeroDocumento', null, ['class' => 'form-control', 'id' => 'NumeroDocumento'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <label for="Parentesco{{ $beneficiario->id }}">Parentesco*</label>
                                                {!! Form::select('Parentesco', $parentesco->pluck('nombre', 'id'), $beneficiario->Parentesco_id, ['class' => 'form-control', 'id' => 'Parentesco'.$beneficiario->id]) !!}
                                            </div>
                                            <div class="col-md-2 NitField" style="display:none;">
                                                <label for="Nit">Nit</label>
                                                {!! Form::text('Nit', null, ['class' => 'form-control', 'id'=>'Nit']) !!}
                                            </div>
                                            <div class="col-md-4 InstitucionEducativaField" style="display:none;">
                                                <label for="InstitucionEducativa">Institución educativa</label>
                                                {!! Form::text('InstitucionEducativa', null, ['class' => 'form-control', 'id'=>'InstitucionEducativa']) !!}
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-1">
                                                @if (($investigacion->estado == 19 || $investigacion->estado == 17))
                                                    {!! Form::submit('Actualizar', ['class' => 'btn btn-primary']) !!}
                                                @endif
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-md-1">
                                                @if (($investigacion->estado == 19 || $investigacion->estado == 17))
                                                    <form id="formularioEliminar" action="{{ route('eliminarBeneficarioRevision') }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id" value="{{ $beneficiario->id }}">
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este beneficiario?')">Eliminar</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @endforeach
                        </div>
                        
                    </div>
                </div>
                {!! Form::model($investigacion, [
                    'route' => ['updateBeneficiarios', $investigacion->id],
                    'method' => 'put',
                    'enctype' => 'multipart/form-data',
                ]) !!}

                <div id="accordion">
                </div>

                @if (($investigacion->estado == 19 || $investigacion->estado == 17) &&
                        (Auth::user()->roles->pluck('id')[0] == 12 || Auth::user()->roles->pluck('id')[0] == 1))
                    {!! Form::submit('Agregar Beneficiarios', ['class' => 'btn btn-primary mt-3']) !!}
                @endif
                    
                
                {!! Form::close() !!}

            </div>

        </div>
    </div>

    
    <!-- Prioridad de investigación -->
    @if ($investigacion->estado == 17)
        @if (count($estados) > 0)
            @can('componentes.prioridad')
                <div class="row justify-content-center my-4">
                    <div class="col-12">
                        @include('componentes.InvestigacionPrioridad')
                    </div>
                </div>
            @endcan
        @endif
    @endif
    
    <!-- Actualizacion de Estados -->
    <div class="row justify-content-center my-4">
        <div class="col-12">
            @include('componentes.ActualizacionEstado')
        </div>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-3 col-md-12">
            <a href="{{ route('investigacionesTodas') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
    <script src=../js/revisionblade.js></script>
@endsection
