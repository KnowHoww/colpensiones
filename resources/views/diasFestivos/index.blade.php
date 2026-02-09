@extends('layouts.app')
@section('content')
    @can('diaFestivo.view.btn-create')
        <div class="row justify-content-center my-4">
            <div class="col-12">
                <a href="{{ route('diafestivo.create') }}" class="btn btn-primary">Nuevo festivo</a>
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
                    <h4>Listado dias festivos</h4>
                    <table id="diaFestivoTable" class="table datatable table-striped" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Observación</th>
                                @can('diafestivo.view.btn-destroy')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diasFestivos as $festivo)
                                <td class="align-middle">{{ $festivo->id }}</td>
                                <td class="align-middle">{{ $festivo->fecha }}</td>
                                <td class="align-middle">{{ $festivo->observacion }}</td>
                                @can('diafestivo.view.btn-destroy')
                                    <td>
                                        <form action="{{ route('diafestivo.destroy', $festivo->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Eliminar</button>
                                        </form>
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
