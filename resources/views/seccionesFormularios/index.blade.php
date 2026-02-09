@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body overflow-auto">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    <h4>Listado de formularios</h4>
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
                            @foreach ($servicios as $servicio)
                                <td class="align-middle">{{ $servicio->id }}</td>
                                <td class="align-middle">{{ $servicio->nombre }}</td>
                                @can('roles.view.btn-edit')
                                    <td>
                                        <a href="{{ route('seccionesformulario.edit', $servicio) }}"
                                            class="btn btn-primary">Asignar
                                            secciones</a>
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
