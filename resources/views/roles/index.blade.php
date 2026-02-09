@extends('layouts.app')
@section('content')
    @can('roles.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('roles.create') }}" class="btn btn-primary">Nuevo rol</a>
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
                    <h4>Listado de roles</h4>
                    <table id="rolesTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>id</th>
                                <th>Nombre</th>
                                @can('roles.view.btn-edit')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $rol)
                                <td class="align-middle">{{ $rol->id }}</td>
                                <td class="align-middle">{{ $rol->name }}</td>
                                @can('roles.view.btn-edit')
                                    <td>
                                        <a href="{{ route('roles.edit', $rol) }}" class="btn btn-primary">Editar</a>
                                    </td>
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
