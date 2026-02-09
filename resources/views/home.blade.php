@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            {{-- informe general --}}
            @can('dashboard.formulario-excel-con-filtros')
                <div class="col-12">
                    <form action="{{ route('generarInformeInvestigacionesFiltros') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: (Fecha solicitud)</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: (Fecha solicitud)</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="estado" class="form-label">Estado:</label>
                                <select name="estado" id="estado" class="form-select">
                                    @can('excel.informe.investigaciones-todas')
                                        <option value='0'>Todos</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-pendientes')
                                        <option value='17'>Pendiente de aprobación</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-devuelto')
                                        <option value='19'>Devuelto</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-Cancelado-colpensiones')
                                        <option value='20'>Cancelado colpensiones</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-solicitadas')
                                        <option value='3'>Solicitados</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-asignadas')
                                        <option value='5'>Asignadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-revision')
                                        <option value='6'>En revisión</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizadas')
                                        <option value='7'>Finalizadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-objetadas')
                                        <option value='8'>Objetadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-canceladas-javh')
                                        <option value='9'>Canceladas Javh</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-correccion')
                                        <option value='11'>En corrección</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-aprobado-objetado')
                                        <option value='17'>Aprobado Objetado</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizado-objetado')
                                        <option value='16'>Objetado Finalizado</option>
                                    @endcan
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="tipo_investigacion" class="form-label">Tipo de investigación:</label>
                                <select name="tipo_investigacion" id="tipo_investigacion" class="form-select">
                                    <option value='0'>Todos</option>
                                    @foreach ($servicios as $item)
                                        <option value='{{ $item->codigo }}'>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="beneficiarios" class="form-label">Incluir beneficiarios</label>
                                <select name="beneficiarios" id="beneficiarios" class="form-select">
                                    <option value='0'>No</option>
                                    <option value='1'>Si</option>
                                </select>
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
                                <label for="solo_fecha" class="form-label">Solo Fecha</label>
                                <input type="checkbox"  name="solo_fecha" id="solo_fecha">
                             </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
                <hr>
            @endcan
            {{-- informe de analista/creador --}}
            @can('dashboard.formulario-excel-con-filtros-analista')
                <div class="col-12">
                    <form action="{{ route('generarInformeInvestigacionesFiltrosCreador') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: (Fecha solicitud)</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: (Fecha solicitud)</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="estado" class="form-label">Estado:</label>
                                <select name="estado" id="estado" class="form-select">
                                    @can('excel.informe.investigaciones-todas')
                                        <option value='0'>Todos</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-pendientes')
                                        <option value='17'>Pendiente de aprobación</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-devuelto')
                                        <option value='19'>Devuelto</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-Cancelado-colpensiones')
                                        <option value='20'>Cancelado colpensiones</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-solicitadas')
                                        <option value='3'>Solicitados</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-asignadas')
                                        <option value='5'>Asignadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-revision')
                                        <option value='6'>En revisión</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizadas')
                                        <option value='7'>Finalizadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-objetadas')
                                        <option value='8'>Objetadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-canceladas-javh')
                                        <option value='9'>Canceladas Javh</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-correccion')
                                        <option value='11'>En corrección</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-aprobado-objetado')
                                        <option value='17'>Aprobado Objetado</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizado-objetado')
                                        <option value='16'>Objetado Finalizado</option>
                                    @endcan
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
                <hr>
            @endcan
            {{-- informe general --}}
            @can('dashboard.formulario-excel-con-filtros-aprobador')
                <div class="col-12">
                    <form action="{{ route('generarInformeInvestigacionesFiltros') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: (Fecha solicitud)</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: (Fecha solicitud)</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="estado" class="form-label">Estado:</label>
                                <select name="estado" id="estado" class="form-select">
                                    @can('excel.informe.investigaciones-todas')
                                        <option value='0'>Todos</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-pendientes')
                                        <option value='17'>Pendiente de aprobación</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-devuelto')
                                        <option value='19'>Devuelto</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-Cancelado-colpensiones')
                                        <option value='20'>Cancelado colpensiones</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-solicitadas')
                                        <option value='3'>Solicitados</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-asignadas')
                                        <option value='5'>Asignadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-revision')
                                        <option value='6'>En revisión</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizadas')
                                        <option value='7'>Finalizadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-objetadas')
                                        <option value='8'>Objetadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-canceladas-javh')
                                        <option value='9'>Canceladas Javh</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-correccion')
                                        <option value='11'>En corrección</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-aprobado-objetado')
                                        <option value='17'>Aprobado Objetado</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizado-objetado')
                                        <option value='16'>Objetado Finalizado</option>
                                    @endcan
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="tipo_investigacion" class="form-label">Tipo de investigación:</label>
                                <select name="tipo_investigacion" id="tipo_investigacion" class="form-select">
                                    <option value='0'>Todos</option>
                                    @foreach ($servicios as $item)
                                        <option value='{{ $item->codigo }}'>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="beneficiarios" class="form-label">Incluir beneficiarios</label>
                                <select name="beneficiarios" id="beneficiarios" class="form-select">
                                    <option value='0'>No</option>
                                    <option value='1'>Si</option>
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <input type="hidden" name="centroCostoNumero" id="centroCostoNumero"
                                    value="{{ Auth::user()->centroCosto }}" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
                <hr>
            @endcan
            {{-- Informe general  Operaciones--}}
            @if (Auth::user()->hasAnyRole(['Coordinador operativo', 'Coordinador regional', 'root']))
                <!-- El usuario tiene al menos uno de los roles -->
                <p>Opción asignada a Operaciones</p>
                <div class="col-12" >
                    <form action="{{ route('generarInformeInvestigacionesFiltrosOperacion') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: (Fecha solicitud)</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: (Fecha solicitud)</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="estado" class="form-label">Estado:</label>
                                <select name="estado" id="estado" class="form-select">
                                    @can('excel.informe.investigaciones-todas')
                                        <option value='0'>Todos</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-pendientes')
                                        <option value='17'>Pendiente de aprobación</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-devuelto')
                                        <option value='19'>Devuelto</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-Cancelado-colpensiones')
                                        <option value='20'>Cancelado colpensiones</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-solicitadas')
                                        <option value='3'>Solicitados</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-asignadas')
                                        <option value='5'>Asignadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-revision')
                                        <option value='6'>En revisión</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizadas')
                                        <option value='7'>Finalizadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-objetadas')
                                        <option value='8'>Objetadas</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-canceladas-javh')
                                        <option value='9'>Canceladas Javh</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-correccion')
                                        <option value='11'>En corrección</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-aprobado-objetado')
                                        <option value='17'>Aprobado Objetado</option>
                                    @endcan
                                    @can('excel.informe.investigaciones-finalizado-objetado')
                                        <option value='16'>Objetado Finalizado</option>
                                    @endcan
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="tipo_investigacion" class="form-label">Tipo de investigación:</label>
                                <select name="tipo_investigacion" id="tipo_investigacion" class="form-select">
                                    <option value='0'>Todos</option>
                                    @foreach ($servicios as $item)
                                        <option value='{{ $item->codigo }}'>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="beneficiarios" class="form-label">Incluir beneficiarios</label>
                                <select name="beneficiarios" id="beneficiarios" class="form-select">
                                    <option value='0'>No</option>
                                    <option value='1'>Si</option>
                                </select>
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
                                <label for="solo_fecha" class="form-label">Solo Fecha</label>
                                <input type="checkbox"  name="solo_fecha" id="solo_fecha">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
