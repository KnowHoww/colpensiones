@extends('layouts.app')
@section('content')
    @can('usuarios.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('user.create') }}" class="btn btn-primary">Nuevo usuario</a>
            </div>
        </div>
    @endcan
    @can('usuarios.view.btn-upload-masivo-usuarios')
        <div class="row justify-content-center my-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body overflow-auto">
                        {!! Form::open([
                            'route' => 'cargarMasivoUsuarios',
                            'method' => 'post',
                            'enctype' => 'multipart/form-data',
                        ]) !!}
                        {!! Form::token() !!}
                        {!! Form::file('archivo', ['accept' => '.xls,.xlsx', 'required' => 'required', 'class' => 'btn btn-dark']) !!}
                        {{ Form::submit('Cargar masiva de usuarios', ['class' => 'btn btn-primary']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body overflow-auto">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    <h4>Listado de usuarios</h4>
                    <table id="usuariosTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>id</th>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                @can('usuarios.view.btn-edit')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $user)
                                <td class="align-middle">{{ $user->id }}</td>
                                <td class="align-middle">
                                    ******{{ substr($user->numberDocument, strlen($user->numberDocument) - 4) }}</td>
                                <td class="align-middle">{{ $user->name }} {{ $user->lastname }}</td>
                                <td class="align-middle">{{ $user->phone }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                @can('usuarios.view.btn-edit')
                                    <td><a href="{{ route('user.edit', $user) }}" class="btn btn-primary">Editar</a></td>
                                @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
