@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignación Masiva de Analistas</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('asignacion.masiva.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="tipo_archivo" class="form-label">Tipo de Archivo:</label>
            <select name="tipo_archivo" id="tipo_archivo" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="solo_asignacion"> BASE 01_Asignación_Analista (3 columnas)</option>
                <option value="asignacion_cambio"> BASE 02_Estado y Analista (5 columnas)</option>
                <option value="asignacion_coordinador_investigador">BASE 03_Coordinador e Investigador (8 columnas)</option>
            </select>
            @error('tipo_archivo')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Archivo Excel (.xlsx, .xls o .csv):</label>
            <input type="file" name="file" id="file" class="form-control" required>
            @error('file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
</div>
@endsection
