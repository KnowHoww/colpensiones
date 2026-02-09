<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        var routes = {
            validarCarpetas: "{{ route('validarCarpetas') }}",
            investigacionesTodas: "{{ route('investigacionesTodas') }}"
        };
    </script>
</head>
<body>
    <div class="container">
        <h1>Investigaciones Cargadas Masivamente</h1>
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

        <div class="mb-3">
            <a href="{{ route('descargarErrores') }}" class="btn btn-secondary">Descargar Información</a>
            <a href="{{ route('investigacionesTodas') }}" class="btn btn-primary" id="regresarBtn">Regresar</a>
            <form action="{{ route('moverCarpetas') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">Mover Carpetas</button>
            </form>
            <button type="button" class="btn btn-success" id="validarCarpetasBtn">Validar Carpetas</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Marca Temporal</th>
                        <th>Número de Radicación Caso</th>
                        <th>ID Case</th>
                        <th>Tipo de Investigación</th>
                        <th>Tipo de Riesgo</th>
                        <th>Tipo de Trámite</th>
                        <th>Detalle de Riesgo</th>
                        <th>Primer Nombre</th>
                        <th>Segundo Nombre</th>
                        <th>Primer Apellido</th>
                        <th>Segundo Apellido</th>
                        <th>Nombre de Carpeta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investigaciones as $investigacion)
                        <tr>
                            <td>{{ $investigacion->MarcaTemporal }}</td>
                            <td>{{ $investigacion->NumeroRadicacionCaso }}</td>
                            <td>{{ $investigacion->IdCase }}</td>
                            <td>{{ $investigacion->TipoInvestigacion }}</td>
                            <td>{{ $investigacion->TipoRiesgo }}</td>
                            <td>{{ $investigacion->TipoTramite }}</td>
                            <td>{{ $investigacion->DetalleRiesgo }}</td>
                            <td>{{ $investigacion->PrimerNombre }}</td>
                            <td>{{ $investigacion->SegundoNombre }}</td>
                            <td>{{ $investigacion->PrimerApellido }}</td>
                            <td>{{ $investigacion->SegundoApellido }}</td>
                            <td>{{ $investigacion->nombreCarpeta }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Validar Carpetas -->
    <div class="modal fade" id="validarCarpetasModal" tabindex="-1" aria-labelledby="validarCarpetasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validarCarpetasModalLabel">Validar Carpetas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre de Carpeta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="validarCarpetasTableBody">
                            <!-- Los resultados de la validación se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> <!-- Asegúrate de cargar bootstrap.bundle.min.js -->
    <script src="{{ asset('js/masivo1.js') }}"></script>
</body>
</html>
