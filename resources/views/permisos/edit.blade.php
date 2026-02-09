@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <h2>Editar de permisos</h2>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::model($permiso, ['route' => ['permisos.update', $permiso], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Permiso', ['class' => 'form-label']) !!}
                        {!! Form::text('name', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'required' => 'required',
                        ]) !!}
                    </div>
                    @foreach ($roles as $rol)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $rol->id, null, ['class' => 'my-1']) !!}
                                {{ $rol->name }}
                            </label>
                        </div>
                    @endforeach
                    @can('formulario.guardar-cambios')
                        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
                    @endcan
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('permisos.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
