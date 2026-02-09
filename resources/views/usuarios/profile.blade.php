@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Actualización de contraseña</h2>
                </div>
                <div class="card-body">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if (session('infoError'))
                        <div class="alert alert-danger">
                            {{ session('infoError') }}
                        </div>
                    @endif
                    <p>Para acceder al aplicativo, la contraseña debe cumplir con los siguientes requisitos de
                        complejidad:</p>
                    <ul>
                        <li>Deben contener un mínimo de 8 caracteres.</li>
                        <li>Deben incluir al menos una letra mayúscula.</li>
                        <li>Deben tener al menos un carácter especial que no esté ubicado al principio o al final de la
                            contraseña.</li>
                        <li>Deben contener al menos un número.</li>
                    </ul>
                    <p>Por favor, asegúrate de cumplir con estos requisitos al crear tu contraseña para acceder al
                        aplicativo. Gracias.</p>
                    <p>El cambio de contraseña se solicitará cada 30 días.</p>
                    {!! Form::model($user, ['route' => ['userEditProfile', $user->id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('old_password', 'Contraseña actual', ['class' => 'form-label']) !!}
                        {!! Form::password('old_password', [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password', 'Nueva contraseña', ['class' => 'form-label']) !!}
                        {!! Form::password('new_password', [
                            'class' => 'form-control',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                @can('firma.view')  
                    <br/>
                    <hr>
                    <br/>
                    {!! Form::open(['route' => 'documentos.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="type" value="firma">
                    <div class="form-group">
                        {!! Form::label('firma', 'Firma (jpg)', ['class' => 'form-label']) !!}
                        {{ Form::file('firma', ['class' => 'form-control']) }}
                    </div>
                                      
                    <div class="form-group">
                    {{ Form::submit('Subir firma', ['class' => 'btn btn-primary']) }}
                     <div class="form-group">
                        {!! Form::label('lbl', 'Firma Actual', ['class' => 'form-label']) !!}
                        <br/>
                        </div>
                    {!! Form::close() !!}
                    @if ($user->firma != '')
                        <image src ="/images/firmas/{{ $user->firma }}" width ="130px" >
                    @endif
                    
                    
                </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('home') }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
@endsection
