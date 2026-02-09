@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar nuevo rol</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'roles.store']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre rol', ['class' => 'form-label']) !!}
                        {!! Form::text('name', null, [
                            'class' => 'form-control',
                            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                            'placeholder' => 'Nombre del rol',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    @foreach ($permisos as $permiso)
                        <div>
                            <label>
                                {!! Form::checkbox('permisos[]', $permiso->id, null, ['class' => 'my-1']) !!}
                                {{ $permiso->name }}
                            </label>
                        </div>
                    @endforeach
                    {!! Form::submit('Registrar', ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('roles.index') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
