@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nuevo usuario</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'user.store']) !!}
                    <div class="form-group">
                        {!! Form::label('idTypeDocument', 'Tipo de documento', ['class' => 'form-label']) !!}
                        {!! Form::select('idTypeDocument', $tipoDocumento->pluck('nombre', 'id'), null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('numberDocument', 'Número de documento', ['class' => 'form-label']) !!}
                        {!! Form::text('numberDocument', null, [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', 'Nombres', ['class' => 'form-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('lastname', 'Apellido', ['class' => 'form-label']) !!}
                        {!! Form::text('lastname', null, [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Teléfono', ['class' => 'form-label']) !!}
                        {!! Form::text('phone', null, [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email', ['class' => 'form-label']) !!}
                        {!! Form::email('email', null, [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('roles', 'Rol', ['class' => 'form-label']) !!}
                        {!! Form::select('roles', $roles->pluck('name', 'id'), null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('estado', 'Estado', ['class' => 'form-label']) !!}
                        {!! Form::select('estado', $estados->pluck('name', 'id'), null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('centroCosto', 'Centro de costos', ['class' => 'form-label']) !!}
                        {!! Form::select('centroCosto', $costos->pluck('nombre', 'id'), null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                     <div class="form-group">
                        {!! Form::label('municipio', 'Municipio', ['class' => 'form-label']) !!}
                         {!! Form::select(
                            'municipio',
                            ['0' => 'Seleccione un municipio'] + collect($municipios)->pluck('municipio', 'id')->toArray(),
                            null,
                            [
                                'class' => 'form-control',
                            ],
                        ) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('coordinador', 'Coordinador', ['class' => 'form-label']) !!}
                        {!! Form::select(
                            'coordinador',
                            ['0' => 'Seleccione un coordinador'] + collect($coordinador)->pluck('full_name', 'id')->toArray(),
                            null,
                            [
                                'class' => 'form-control',
                            ],
                        ) !!}
                    </div>
                    {!! Form::submit('Registrar', ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('user.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
