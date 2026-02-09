@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 mb-3">
            <h2>Asignar secciones a formulario de {{ $codigo->nombre }}</h2>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::model($codigo, [
                        'route' => ['seccionesformulario.update', $codigo->codigo],
                        'method' => 'put',
                    ]) !!}
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        {{ Form::label('secciones', 'Secciones de formulario') }}
                        @foreach ($secciones as $seccion)
                            <div class="form-check">
                                {{ Form::checkbox('secciones[]', $seccion->id, in_array($seccion->id, $seccionesFormulario->pluck('Seccion')->toArray()), ['class' => 'form-check-input']) }}
                                {{ Form::label($seccion->nombre, $seccion->nombre, ['class' => 'form-check-label']) }}
                            </div>
                        @endforeach
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
            <a href="{{ route('seccionesformulario.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
