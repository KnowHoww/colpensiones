@extends('layouts.app')
@section('content')
    @can('permisos.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('permisos.create') }}" class="btn btn-primary">Nuevo permiso</a>
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
                    <h4>Listado de permisos</h4>
                    <table id="permisosTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>id</th>
                                <th>Nombre</th>
                                @can('permisos.view.btn-edit')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permisos as $permiso)
                                <td class="align-middle">{{ $permiso->id }}</td>
                                <td class="align-middle">{{ $permiso->name }}</td>
                                @can('permisos.view.btn-edit')
                                    <td>
                                        <a href="{{ route('permisos.edit', $permiso) }}" class="btn btn-primary">Editar</a>
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
