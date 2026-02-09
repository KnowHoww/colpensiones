@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-xxl-8 mb-3">
            @if (session('info'))
                <div class="alert alert-success">
                    {{ session('info') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <h3> {{ $investigacion->TipoInvestigacion }} - {{ optional($investigacion->TipoInvestigaciones)->nombre }}</h3>
            @can('title.section-radicado')
                <h2>Investigación radicado: {{ $investigacion->CasoPadreOriginal }} - IdCase: {{ $investigacion->id }}</h2>
                {{-- @if ($investigacion->RadicadoAsociado !== null)
                    @if ($investigacion->CentroCosto == 1)
                        <a href="/investigacion/{{ $investigacion->RadicadoAsociado }}/edit" class="btn btn-dark">Ver radicado
                            asociado - {{$RadicadoAsociado}}</a>
                    @else
                        <a href="/investigacion/{{ $RadicadoAsociado }}" class="btn btn-dark">Ver radicado
                            asociado - {{$RadicadoAsociado}}</a>
                    @endif
                @endif --}}
            @endcan
            <div class="accordion" id="accordionExample">
                @if ($secciones->contains('nombre', 'InformacionInvestigacion'))
                    @can('InformacionInvestigacion.view')
                        @include('componentes.InformacionInvestigacion')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'CapacidadTrabajo'))
                    @can('capacidadTrabajo.view')
                        @include('componentes.CapacidadTrabajo')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'InvestigacionRegion'))
                    @can('investigacionRegion.view')
                        @include('componentes.InvestigacionRegion')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'BeneficiariosLista'))
                    @can('BeneficiariosLista.view')
                        @include('componentes.BeneficiariosLista')
                    @endcan
                @endif
                @if (
                    $investigacion->estado == 17 ||
                        $investigacion->estado == 18 ||
                        $investigacion->estado == 19 ||
                        $investigacion->estado == 3 ||
                        $investigacion->estado == 6 ||
                        $investigacion->estado == 11 ||
                        $investigacion->estado == 7 ||
                        $investigacion->estado == 8 ||
                        $investigacion->estado == 5)
                    @if ($secciones->contains('nombre', 'CargaDocumentos'))
                        @can('CargaDocumentos.view')
                            @include('componentes.CargaDocumentos')
                        @endcan
                    @endif
                @endif
                @if ($secciones->contains('nombre', 'DocumentosAnexos'))
                    @can('DocumentosAnexos.view')
                        @include('componentes.DocumentosAnexos')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'ActualizacionEstado'))
                    @can('ActualizacionEstado.view')
                        @include('componentes.ActualizacionEstado')
                    @endcan
                @endif
            </div>
        </div>
        <hr>
        @can('Novedades.view')
            @include('componentes.Novedades')
        @endcan
        <hr>
        @if ($secciones->contains('nombre', 'DatosVerificacion'))
            @can('DatosVerificacion.view')
                @include('componentes.DatosVerificacion')
            @endcan
            <hr>
        @endif
        @if ($secciones->contains('nombre', 'ValidacionDocumental'))
            @can('ValidacionDocumental.view')
                @include('componentes.ValidacionDocumental')
                <hr>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'ConsultaBaseDatos'))
            @can('ConsultaBaseDatos.view')
                @include('componentes.ConsultaBaseDatos')
                <hr>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'TrazabilidadActividades'))
            @can('trazabilidadActividades.view')
                @include('componentes.TrazabilidadActividades')
                <hr>
            @endcan
        @endif
        <div class="col-12 col-xxl-8 mb-3">
            @can('title.section-trabajo-investigacion')
                <h4>Trabajo de investigación realizado</h4>
            @endcan
            <div class="accordion" id="accordionExample">
                @if ($secciones->contains('nombre', 'AuxilioFunerario'))
                    @can('auxilioFunerario.view')
                        @include('componentes.AuxilioFunerario')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'Entrevistasolicitante'))
                    @can('entrevistasolicitante.view')
                        @include('componentes.Entrevistasolicitante')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'GastosVivienda'))
                    @can('gastosVivienda.view')
                        @include('componentes.GastosVivienda')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'LaborCampo'))
                    @can('laborCampo.view')
                        @include('componentes.LaborCampo')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'EntrevistaFamiliares'))
                    @can('entrevistaFamiliares.view')
                        @include('componentes.EntrevistaFamiliares')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'EntrevistaExtrajuicio'))
                    @can('EntrevistaExtrajuicio.view')
                        @include('componentes.EntrevistaExtrajuicio')
                    @endcan
                @endif
                @if ($secciones->contains('nombre', 'EntrevistaHallazgos'))
                    @can('entrevistaHallazgos.view')
                        @include('componentes.EntrevistaHallazgos')
                    @endcan
                @endif
            </div>
        </div>
        <hr>
        @if ($secciones->contains('nombre', 'Escolaridad'))
            @can('escolaridad.view')
                @include('componentes.Escolaridad')
                <hr>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'EstudiosAuxiliares'))
            @can('EstudiosAuxiliares.view')
                @include('componentes.EstudiosAuxiliares')
                <hr>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'Fraude'))
            @can('Fraude.view')
                @include('componentes.Fraude')
                <hr>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'RegistroFotografico'))
            @can('registroFotografico.view')
                <div class="col-12 col-xxl-8 mb-3">
                    <h4>Registro fotográfico</h4>
                    <div class="accordion" id="accordionExample">
                        @include('componentes.RegistroFotografico')
                    </div>
                </div>
            @endcan
        @endif
        @if ($secciones->contains('nombre', 'Acreditaciones'))
            @can('Acreditaciones.view')
                @include('componentes.Acreditaciones')
                <hr>
            @endcan
        @endif
        <div class="col-12">
            <a href="{{ route('investigacionesTodas') }}" class="btn btn-dark my-2">Regresar</a>
        </div>
    </div>
@endsection
