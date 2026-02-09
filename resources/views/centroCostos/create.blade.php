@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nuevo centro de costos</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'centrocostos.store']) !!}
                    <div class="form-group">
                        {!! Form::label('nombre', 'Centro de costos', ['class' => 'form-label']) !!}
                        {!! Form::text('nombre', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('codigo', 'Codigo centro de costos', ['class' => 'form-label']) !!}
                        {!! Form::text('codigo', null, [
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
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('centrocostos.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
