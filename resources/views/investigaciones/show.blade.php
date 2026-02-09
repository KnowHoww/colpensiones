@extends('layouts.app')
@section('content')
    <div class="row justify-content-center my-4">
        <div class="col-12 col-xxl-8 mb-3">
            @if (session('info'))
                <div class="alert alert-success">
                    {{ session('info') }}
                </div>
            @endif
            <h3> {{ $investigacion->TipoInvestigacion }} - {{ optional($investigacion->TipoInvestigaciones)->nombre }}</h3>
            @can('title.section-radicado')
                <h2>Investigación radicado: {{ $investigacion->CasoPadreOriginal }} - IdCase: {{ $investigacion->id }}</h2>
            @endcan
            <div class="accordion" id="accordionExample">
                @can('InformacionInvestigacion.view')
                    @include('componentes.InformacionInvestigacion')
                @endcan
                @can('BeneficiariosLista.view')
                    @include('componentes.BeneficiariosLista')
                @endcan
                @can('DocumentosAnexos.view')
                    @include('componentes.DocumentosAnexos')
                @endcan
                @can('ActualizacionEstado.view')
                    @include('componentes.ActualizacionEstado')
                @endcan
            </div>
        </div>
        @can('DatosVerificacion.view')
            @include('componentes.DatosVerificacion')
        @endcan
        <hr>

        @can('trazabilidadActividades.view')
            @include('componentes.trazabilidadActividades')
            <hr>
        @endcan
        @can('Fraude.view')
            @include('componentes.Fraude')
            <hr>
        @endcan
        @can('Acreditaciones.view')
            @include('componentes.Acreditaciones')
            <hr>
        @endcan
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-dark my-2">Regresar</a>
        </div>
    </div>
@endsection
