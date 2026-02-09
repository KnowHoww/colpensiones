@extends('layouts.app')
@section('content')

    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nueva investigación</h2>
                </div>
                <div class="card-body">
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
                    {!! Form::open([
                        'route' => 'consultavalidacioninvestigacion',
                        'method' => 'post',
                    ]) !!}
                    <div class="row">
                        <div class="form-group col-4">
                            <div>
                                <label>
                                    {!! Form::label('TipoDocumento', 'Tipo de documento*', ['class' => 'form-label']) !!}
                                    {!! Form::select(
                                        'TipoDocumento',
                                        [null => 'Seleccione... '] + collect($TipoDocumento)->pluck('nombre', 'codigo')->toArray(),
                                        null,
                                        [
                                            'class' => 'form-control',
                                            'required',
                                        ],
                                    ) !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            {!! Form::label('NumeroDeDocumento', 'Numero de documento*', ['class' => 'form-label']) !!}
                            {!! Form::number('NumeroDeDocumento', null, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                        <div class="form-group col-12">
                            {!! Form::submit('Consultar', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    @if (isset($Historial))
                        @if (count($Historial) > 0)
                            <div class="alert alert-warning" role="alert">
                                <b>ALERTA: El número de cédula del causante tiene la(s) siguiente(s) investigación(es)
                                    previa(s), validar si procede o no la investigación.</b>
                            </div>
                        @endif
                    @endif

                    @if (isset($Historial))
                        <table class="table table-responsive" style="font-size: 14px !important;">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Número radicación caso</th>
                                    <th>Estado</th>
                                    <th colspan="2">Documento</th>
                                    <th>Tipo investigación</th>
                                    <th>Tipo riesgo</th>
                                    <th>Detalle riesgo</th>
                                    <th>Tipo de Tramite</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>Usar</th>
                                    <th>Ver</th>
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
                                        <td><button id={{ $item->id }}
                                                class="btn btn-primary usarInformacion">Usar</button></td>
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
                        {!! Form::open(['route' => 'investigacion.store', 'method' => 'post', 'enctype' => 'multipart/form-data','id'=> 'myForm']) !!}
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between mt-2">
                                    <h3 class="my-2">Información del causante</h3>
                                </div>
                                <div class="form-group col-4">
                                    <div>
                                        <label>
                                            {!! Form::label('TipoDocumento', 'Tipo de documento*', ['class' => 'form-label']) !!}
                                            {!! Form::select(
                                                'TipoDocumento',
                                                collect($TipoDocumento)->pluck('nombre', 'codigo')->toArray(),
                                                $informacion->TipoDocumento,
                                                [
                                                    'class' => 'form-control',
                                                    'required',
                                                ],
                                            ) !!}

                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-4">
                                    {!! Form::label('NumeroDeDocumento', 'Numero de documento*', ['class' => 'form-label']) !!}
                                    {!! Form::text('NumeroDeDocumento', $informacion->NumeroDeDocumento, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <!-- <div class = "row">
                            <div class="form-group col-4">
                                {!! Form::label('departamentoRegion', 'Departamento*', ['class' => 'form-label']) !!}
                                {!! Form::select('departamentoRegion', [null => 'Seleccione...'] + $departamentos->pluck('departamento', 'id')->toArray(), null, [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'departamentoRegion'
                                ]) !!}
                            </div>
                            <div class="form-group col-4">
                                {!! Form::label('municipio', 'Municipio*', ['class' => 'form-label']) !!}
                                {!! Form::select('ciudadRegion', [], null, [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'ciudadRegion'
                                ]) !!}
                            </div>
                                
                                    
                            </div> -->
                            <div class="row">
                                {{-- <div class="form-group col-4">
                                    {!! Form::label('CasoPadreOriginal', 'Numero Radicacion Padre*(ORIGINAL)', ['class' => 'form-label']) !!}
                                    {!! Form::text('CasoPadreOriginal', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                                <div class="form-group col-4">
                                    {!! Form::label('NumeroRadicacionCaso', 'Número Radicacion Caso*', ['class' => 'form-label']) !!}
                                    {!! Form::text('NumeroRadicacionCaso', null, 
                                        [
                                        'class' => 'form-control',
                                        'required',
                                    ]) 
                                    !!}
                                </div>
                                <div class="form-group col-4">
                                    <div>
                                        <label>
                                            {!! Form::label('TipoInvestigacion', 'Tipo de investigación*', ['class' => 'form-label']) !!}
                                            {!! Form::select('TipoInvestigacion', collect($TipoInvestigacion)->pluck('nombre', 'codigo')->toArray(), null, [
                                                'class' => 'form-control',
                                                'required',
                                                'id' => 'TipoInvestigacionSelect'
                                            ]) !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-4">
                                    <div>
                                        <label>
                                            {!! Form::label('TipoRiesgo', 'Tipo de riesgo*', ['class' => 'form-label']) !!}
                                            {!! Form::select('TipoRiesgo', collect($TipoRiesgo)->pluck('nombre', 'codigo')->toArray(), null, [
                                                'class' => 'form-control',
                                                'required',
                                            ]) !!}
                                        </label>
                                    </div>
                                </div>
                                
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
                                                ],
                                            ) !!}
                                        </label>
                                    </div>
                                </div>
                                <!-- Hemos sacdo Tipo de TRAMITE de listaValidacionDocumental para que sea visible en otros tipos de investigaciones -->
                                <!-- Se agrega que el tipo de tramite sea obligatorio -->
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
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                </div>
                                
                                <div id="listaValidacionDocumental" class="row d-none">
                                    
                                    <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoSolicitud', 'Tipo de solicitud', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoSolicitud',
                                                    [null => 'Seleccione... '] + collect($TipoSolicitud)->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <div>
                                            <label>
                                                {!! Form::label('TipoSolicitante', 'Tipo de solicitante', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'TipoSolicitante',
                                                    [null => 'Seleccione... '] + collect($TipoSolicitante)->pluck('nombre', 'codigo')->toArray(),
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                    ],
                                                ) !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        {!! Form::label('Junta', 'Junta', ['class' => 'form-label']) !!}
                                        {!! Form::select('Junta', collect($TipoJuntas)->pluck('nombre', 'id')->toArray(), null, [
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="form-group col-4">
                                        {!! Form::label('NumeroDictamen', 'Número de dictamen', ['class' => 'form-label']) !!}
                                        {!! Form::text('NumeroDictamen', null, [
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="form-group col-4">
                                        {!! Form::label('FechaDictamen', 'Fecha de dictamen', ['class' => 'form-label']) !!}
                                        {!! Form::date('FechaDictamen', null, [
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                </div>




                                <hr>

                                <div class="form-group col-3">
                                    {!! Form::label('PrimerNombre', 'Primer nombre causante*', ['class' => 'form-label']) !!}
                                    {!! Form::text('PrimerNombre', null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                                <div class="form-group col-3">
                                    {!! Form::label('SegundoNombre', 'Segundo nombre causante', ['class' => 'form-label']) !!}
                                    {!! Form::text('SegundoNombre', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="form-group col-3">
                                    {!! Form::label('PrimerApellido', 'Primer apellido causante*', ['class' => 'form-label']) !!}
                                    {!! Form::text('PrimerApellido', null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                                <div class="form-group col-3">
                                    {!! Form::label('SegundoApellido', 'Segundo apellido causante', ['class' => 'form-label']) !!}
                                    {!! Form::text('SegundoApellido', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-4">
                                    {!! Form::label('Ciudad', 'Ciudad causante', ['class' => 'form-label']) !!}
                                    {!! Form::text('Ciudad', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="form-group col-4">
                                    {!! Form::label('DireccionCausante', 'Dirección causante', ['class' => 'form-label']) !!}
                                    {!! Form::text('DireccionCausante', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="form-group col-4">
                                    {!! Form::label('TelefonoCausante', 'Teléfono causante', ['class' => 'form-label']) !!}
                                    {!! Form::text('TelefonoCausante', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                {{-- <div class="form-group col-4">
                                    {!! Form::label('DireccionPunto', 'Direccion de punto', ['class' => 'form-label']) !!}
                                    {!! Form::text('DireccionPunto', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                                {{-- <div class="form-group col-4">
                                    {!! Form::label('PuntoAtencion', 'Punto de atencion', ['class' => 'form-label']) !!}
                                    {!! Form::text('PuntoAtencion', null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div> --}}
                                <div class="form-group col-12">
                                    {!! Form::label('Observacion', 'Causal de la investigación*', ['class' => 'form-label']) !!}
                                    {!! Form::textarea('Observacion', null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                           
                            <div class="row  card mt-2">
                                <div class="col-12 d-flex justify-content-between mt-4">
                                    <h3 class="my-2">Documentos</h3>
                                </div>
                                <div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            {{ Form::label('files', 'Seleccione archivos:') }}
                                            {{ Form::file('files[]', ['multiple', 'class' => 'form-control', 'id' => 'file-input']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Archivos seleccionados:</h4>
                                    <ul id="file-list" class="list-group">
                                    
                                </div>
                            </div>
                            </div>

                                
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between mt-4">
                                    <h3 class="my-2">Beneficiarios</h3>
                                    <button type="button" id="nuevo_beneficiario" class="btn btn-primary">Agregar
                                        beneficiario
                                    </button>
                                </div>
                                <div class="accordion" id="accordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Beneficiario 1
                                        </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" >
                                            <div class="accordion-body">
                                                <div class="card mt-2">
                                                    <div class="card-body">
                                                        <div id="contenedor-beneficiarios">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-3">
                                                                    <label for="PrimerNombre">Primer nombre*</label>
                                                                    <input type='text' name='beneficiarios[0][PrimerNombre]' class='form-control' required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="SegundoNombre">Segundo nombre</label>
                                                                    <input type='text' name='beneficiarios[0][SegundoNombre]' class='form-control'>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="PrimerApellido">Primer apellido*</label>
                                                                    <input type='text' name='beneficiarios[0][PrimerApellido]' class='form-control' required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="SegundoApellido">Segundo apellido</label>
                                                                    <input type='text' name='beneficiarios[0][SegundoApellido]' class='form-control'>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <!-- Segunda fila con 4 columnas -->
                                                                <div class="col-md-2">
                                                                    <label for="TipoDocumento">Tipo documento*</label>
                                                                    <select name='beneficiarios[0][TipoDocumento]' class='form-control' required>
                                                                        <option value=''>Seleccione...</option>
                                                                        <option value='NU'>Número único de identificación personal</option>
                                                                        <option value='CC'>Cédula de ciudadanía</option>
                                                                        <option value='NI'>NIT</option>
                                                                        <option value='TI'>Tarjeta de identidad</option>
                                                                        <option value='CE'>Cédula de extranjería</option>
                                                                        <option value='PA'>Pasaporte</option>
                                                                        <option value='RC'>Registro civil</option>
                                                                        <option value='CF'>Carné Diplomático</option>
                                                                        <option value='AS'>Adulto sin Identificación</option>
                                                                        <option value='MS'>Menor sin Identificación</option>
                                                                        <option value='F'>Documento Extranjero</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="beneficiarios[0][NumeroDocumento]">Número documento*</label>
                                                                    <input type='text' name='beneficiarios[0][NumeroDocumento]' class='form-control' required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Parentesco">Parentesco*</label>
                                                                    <select name='beneficiarios[0][Parentesco]' class='form-control' required>
                                                                        <option value=''>Seleccione...</option>
                                                                        <option value='1'>Hijo Invalido</option>
                                                                        <option value='2'>Cónyuge o Compañera</option>
                                                                        <option value='3'>Hijo Menor Edad</option>
                                                                        <option value='4'>Hijo Mayor Estudios</option>
                                                                        <option value='5'>Padre o Madre</option>
                                                                        <option value='6'>Hermano Invalido</option>
                                                                        <option value='7'>Otro o Tercero</option>
                                                                    </select>
                                                                </div>
                                                            
                                                                <div class="col-md-2 NitField"  style="display:none;">
                                                                    <label for="Nit">Nit</label>
                                                                    <input type='text' name='beneficiarios[0][Nit]' class='form-control'>
                                                                </div>
                                                                <div class="col-md-4 InstitucionEducativaField" style="display:none;">
                                                                    <label for="InstitucionEducativa">Institución educativa</label>
                                                                    <input type='text' name='beneficiarios[0][InstitucionEducativa]' class='form-control'>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>    
                                

                            <div class="row">
                                <div class="form-group col-12">
                                    {!! Form::submit('Registrar', ['class' => 'btn btn-primary mt-3', 'id'=>'submit-btn']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-3 col-md-12">
            <a href="{{ route('investigacion.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/createblade.js') }}"></script>


@endsection

