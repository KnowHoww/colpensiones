@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nuevo permiso</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'permisos.store']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre permiso', ['class' => 'form-label']) !!}
                        {!! Form::text('name', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'placeholder' => 'Nombre del permiso',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    @foreach ($roles as $role)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'my-1']) !!}
                                {{ $role->name }}
                            </label>
                        </div>
                    @endforeach
                    {!! Form::submit('Registrar', ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <a href="{{ route('permisos.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
