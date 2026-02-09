@extends('layouts.app')
@section('content')
    @can('servicios.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('servicios.create') }}" class="btn btn-primary">Nuevo servicio</a>
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
                    <h4>Listado de servicios</h4>
                    <table id="serviciosTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>id</th>
                                <th>codigo</th>
                                <th>Nombre</th>
                                <th>Días hábiles</th>
                                @can('servicios.view.btn-edit')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios as $servicio)
                                <td class="align-middle">{{ $servicio->id }}</td>
                                <td class="align-middle">{{ $servicio->codigo }}</td>
                                <td class="align-middle">{{ $servicio->nombre }}</td>
                                <td class="align-middle">{{ $servicio->TiempoEntrega }}</td>
                                @can('servicios.view.btn-edit')
                                    <td>
                                        <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-primary">Editar</a>
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
