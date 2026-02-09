@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nuevo festivo</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'diafestivo.store']) !!}
                    <div class="form-group">
                        {!! Form::label('fecha', 'Fecha festivo', ['class' => 'form-label']) !!}
                        {!! Form::date('fecha', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('observacion', 'Nombre', ['class' => 'form-label']) !!}
                        {!! Form::text('observacion', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    {!! Form::submit('Registrar', ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <a href="{{ route('diafestivo.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
