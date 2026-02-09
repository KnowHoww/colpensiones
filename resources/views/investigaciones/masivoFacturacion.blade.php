@extends('layouts.app')
@section('content')

    <div class="container">
        <h1>Investigaciones Actualizadas Masivamente</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <br>
            </div>
        @endif

    </div>


  <div class="col-12">
                    <form action="{{ route('pendientefacturacion') }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: </label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: </label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="">
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
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
@endsection

