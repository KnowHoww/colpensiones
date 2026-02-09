@extends('layouts.app')
@section('content')
    <div class="container">
        @can('comisiones.view')
        <div class="row">
            {{-- Filtro General --}}
        <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ $title }}  </h1>
                </div>
            </div>
        </div>
       
                <div class="col-12">
                    <form action="{{ route('pendientecomision') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: </label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: </label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}">
                            </div>

                            <div class="col-md-4 my-2">
                                <label for="centroCosto" class="form-label">Centro de costos</label>
                                <select name="centroCosto" id="centroCosto" class="form-select">
                                    <option value="0">Todos</option>
                                    @foreach ($centroCosto as $item)
                                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="centroCosto" class="form-label">Investigacion</label>
                                <input class="form-control rounded-0" name="filtro" type="search" placeholder="Consultar" aria-label="Search" >
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="centroCosto" class="form-label">Ver Detalle</label>
                                <input type="checkbox" name="detail" id="detail" value="1">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
                <hr>

        </div>
        @endcan
    </div>
@endsection
