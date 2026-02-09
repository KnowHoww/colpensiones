@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 mb-3">
            <h2>Editar de servicio</h2>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::model($servicio, ['route' => ['servicios.update', $servicio], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('nombre', 'Nombre servicio', ['class' => 'form-label']) !!}
                        {!! Form::text('nombre', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('TiempoEntrega', 'Duración servicio (días)', ['class' => 'form-label']) !!}
                        {!! Form::number('TiempoEntrega', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('codigo', 'Código de servicio', ['class' => 'form-label']) !!}
                        {!! Form::text('codigo', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>

                    @can('formulario.guardar-cambios')
                        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
                    @endcan
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('servicios.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
