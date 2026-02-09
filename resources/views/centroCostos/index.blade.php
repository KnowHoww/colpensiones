@extends('layouts.app')
@section('content')
    @can('centroCostos.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('centrocostos.create') }}" class="btn btn-primary">Nuevo centro de costo</a>
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
                    <h4>Listado de centro de Costos</h4>
                    <table id="centroCostosTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>id</th>
                                <th>Nombre</th>
                                <th>codigo</th>
                                @can('centroCostos.view.btn-edit')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($centrosCosto as $centro)
                                <td class="align-middle">{{ $centro->id }}</td>
                                <td class="align-middle">{{ $centro->nombre }}</td>
                                <td class="align-middle">{{ $centro->codigo }}</td>
                                @can('centroCostos.view.btn-edit')
                                    <td>
                                        <a href="{{ route('centrocostos.edit', $centro) }}" class="btn btn-primary">Editar</a>
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
