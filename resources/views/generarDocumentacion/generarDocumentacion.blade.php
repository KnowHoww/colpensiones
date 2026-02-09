@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/progress.js') }}"></script>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Generar Documentación</h2>
                <form action="{{ route('documentacion.descargarHoy') }}" method="GET">
                    <button type="submit" class="btn btn-primary mt-3">Descargar Finalizadas Hoy</button>
                </form>
                @if(session('message') || request()->get('message'))
                    <div class="alert alert-success">
                        {{ session('message') ?? request()->get('message') }}
                    </div>
                    
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('documentacion.descargarExcel1') }}";
                        }, 2000);
                    </script>
                @endif

                @if(session('file_errors'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach(session('file_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <form action="{{ route('documentacion.descargarErrores') }}" method="GET">
                        <button type="submit" class="btn btn-warning mt-3">Descargar Información Existente</button>
                    </form>
                @endif

                @if(session('file_uploaded'))
                    <form id="radicar-form" action="{{ route('documentacion.generarDocumentacion') }}" method="POST">
                        @csrf
                        <button id="radicar-btn" type="submit" class="btn btn-primary mt-3">Radicar Investigaciones</button>
                    </form>
                    
                    <!-- Barra de progreso -->
                    <div id="progress-container" class="progress mt-3" style="display: none;">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">0%</div>
                    </div>
                    <!-- Contenedor para mensajes -->
                    <div id="messages-container" class="mt-3" style="display: none;"></div>
                @else
                    <!-- Acordeón con Bootstrap -->
                    <div class="accordion-item my-2">
                        <h2 class="accordion-header" id="headingDocumentosCargados">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseDocumentosCargados" aria-expanded="false" aria-controls="collapseDocumentosCargados">
                                Cargar documentos de investigación
                            </button>
                        </h2>
                        <div id="collapseDocumentosCargados" class="accordion-collapse collapse" aria-labelledby="headingDocumentosCargados"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <!-- Formulario para subir archivo -->
                                <form action="{{ route('documentacion.uploadFile') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="file">Seleccione archivo:</label>
                                        <input type="file" id="file" name="file" required class="form-control">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" class="btn btn-info">Cargar Radicación Primera Vez</button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>

                @endif

                <!-- @if(session('message') || request()->get('message'))
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('documentacion.exportarExcel', ['tipo' => 'finalizadas-reconocimiento']) }}" method="GET">
                                <button type="submit" class="btn btn-secondary btn-block">Exportar Finalizadas Reconocimiento</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('documentacion.exportarExcel', ['tipo' => 'finalizadas-nomina']) }}" method="GET">
                                <button type="submit" class="btn btn-secondary btn-block">Exportar Finalizadas Nomina</button>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <form action="{{ route('documentacion.exportarExcel', ['tipo' => 'objetadas-reconocimiento']) }}" method="GET">
                                <button type="submit" class="btn btn-secondary btn-block">Exportar Objetadas Reconocimiento</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('documentacion.exportarExcel', ['tipo' => 'objetadas-nomina']) }}" method="GET">
                                <button type="submit" class="btn btn-secondary btn-block">Exportar Objetadas Nomina</button>
                            </form>
                        </div>

                    </div>
                </div>
                @endif -->
            </div>
        </div>
    </div>
    <div>
        <form action="{{ route('mostrarVista') }}" method="GET">
                <button id="volver" type="submit" class="btn btn-primary mt-3">Regresar</button>
        </form>
    </div>
@endsection
