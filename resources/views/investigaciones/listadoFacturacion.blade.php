@extends('layouts.app')
@section('content')
    <div>
        @can('facturacion.view')
        <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ $title }}  </h1>
                </div>
            </div>
        </div>
       
                <div class="col-12">
                    <form action="{{ route('pendientefacturacion') }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <label for="fecha_inicio" class="form-label">Fecha inicio: </label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="fecha_fin" class="form-label">Fecha final: </label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}">
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
                                <input type="checkbox" name="detail" id="detail" 
                                @if ($verDetalle>0)
                                  value="1"   checked
                                @else
                                  value="1"
                                @endif
                                  >
                            </div>                            
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
                <hr>
             @if ($filtro== "") 
                <div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Tipo de Investigación</th>
                                    <th>Región</th>
                                    <th>Numero de Investigaciones</th>
                                    <th>Tarifa</th>
                                    <th>Facturadas</th>
                                    <th>Facturado</th>
                                    <th>Total del Periodo</th>
                                    <th>Total del Periodo</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($totales as $totali)
                                <tr>
                                    
                                    <td class="align-middle">{{ $totali->tipoInvestigacion }}</td>
                                    <td  class="align-middle" style="text-align: center">{{ $totali->idregion }}</td>
                                    <td class="align-middle" style="text-align: center">{{ $totali->numInvestigaciones }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($totali->tarifa, 2) }}</td>
                                    <td class="align-middle" style="text-align: center">{{ $totali->facturadas }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($totali->facturado, 2) }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($totali->total, 2) }} </td>

                                </tr>
                                @empty
                                @endforelse
                                <tr>
                                    
                                    <td class="align-middle">TOTAL:____________</td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"  style="text-align: center">{{ $cantidad }}</td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"  style="text-align: center">{{ $cantidadFacturados }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($valorFacturados, 2) }}</td>                                    
                                    <td class="align-middle" style="text-align: right">{{ number_format($totalreporte, 2) }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>      
                
                <div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>ANS</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                    <th>Pj. ANS</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">Investigaciones Fuera de tiempo</td>
                                    <td  class="align-middle" style="text-align: center">{{ $ANSFueraDeTiempo }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($ANSFueraDeTiempoP * 100, 2) }}%</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($ANSFueraDeTiempoV * 100, 2) }}%</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($totalreporte * $ANSFueraDeTiempoV , 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Investigaciones Objetadas</td>
                                    <td  class="align-middle" style="text-align: center">{{ $ANSObjeciones }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($ANSObjecionesP * 100, 2) }}%</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($ANSObjecionesV * 100, 2) }}%</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($totalreporte * $ANSObjecionesV , 2) }}</td>
                                </tr>
                            
                            
                            </tbody>
                        </table>
                    </div>
                </div>  
                
                
                <div class="col-12" >
                    <form action="{{ route('descargarXLSFacturacion') }}" method="post" class="mb-3">
                       @csrf
                                <input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
                              <input type="hidden" id="centroCosto" name="centroCosto" class="form-control"
                                    value="{{ $centroCostos }}"/>
                                

                       <div class="col-md-4 my-2"> <button type="submit" class="btn btn-primary mt-3">Descargar XLS</button></div>
                    </form>         
                </div>
                
                <div class="row justify-content-center my-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body overflow-auto">
                                {!! Form::open([
                                    'route' => 'cargarMasivoFacturacion',
                                    'method' => 'post',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                {!! Form::token() !!}
                                {!! Form::file('archivo', ['accept' => '.xls,.xlsx', 'required' => 'required', 'class' => 'btn btn-dark']) !!}
                                {{ Form::submit('Cargar actualizacion de estado de Facturación', ['class' => 'btn btn-primary']) }}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>  
                
                 @endif
                <form action="{{ route('actualizarFacturados') }}" id="actualizarFacturados" method="post" class="mb-3">
                 @csrf
                 
                 <div  class="col-12 row"  id="myTabsContent">
                 
                 <div class="col-md-4 my-2"><label for="fecha_facturacion" class="form-label">Fecha Facturacion:</label>
                 <input type="date" id="fecha_facturacion" name="fecha_facturacion" class="form-control"></input> </div>
                  <div class="col-md-4 my-2">
                                <label for="estadoFacturacion" class="form-label">Estado :</label>
                                <select name="estadoFacturacion" id="estadoFacturacion" class="form-select">
                                    
                                    @foreach ($estadosFacturacion as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                 <div class="col-md-4 my-2"><button type="submit" class="btn btn-primary mt-3">Actualizar Fecha de Facturacion</button> </div>
                 </div>
                
                
                 
                 <div class="col-12 mt-3">
                    <b>Registros: {{ $cantidad }}</b> 
            </div><div class="col-12 mt-3">
                    <b>Registros Pendientes: {{ $cantidadPendientes }}</b> 
            </div>
              @if ($verDetalle>0 || $filtro!= "") 
            <div class="col-12" >
                <div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sel.</th>
                                    <th title="ID Investigación">Id</th>
                                    <th>Número Caso</th>
                                    <th title="Tipo de documento y número de documento causante">Documento</th>
                                    <th title="">Causante</th>
                                    <th title="Radicado Bizagi">Radicado</th>
                                    <th>Estado</th>
                                    <th>Analista</th>
                                    <th>Investigador</th>
                                    <th>Tipo de Investigación</th>
                                    <th>Región</th>
                                    <th>Fecha Finalizacion</th>
                                    <th>Fecha de Facturación</th>
                                    <th>Tarifa</th>
                                    <th>Con Labor de Campo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($investigaciones as $investigacion)
                                <tr>
                                    <td class="align-middle">
                                        <div  class="form-control">
                                            <input type="checkbox" id="investigaciones[]" name="investigaciones[]"
                                            value="{{ $investigacion->idInvestigacion }}" 
                                            @if ($investigacion->FechaFacturacion == null)
                                                checked 
                                                @endif
                                            />
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $investigacion->idInvestigacion }}</td>
                                    <td class="align-middle">{{ $investigacion->NumeroRadicacionCaso }}</td>
                                    <td class="align-middle">{{ $investigacion->TipoDocumento }} {{ $investigacion->NumeroDeDocumento }}</td>
                                    <td class="align-middle">{{ $investigacion->PrimerNombre }} {{ $investigacion->PrimerApellido }}</td>
                                    <td class="align-middle">{{ $investigacion->FechaRadicacion }}</td>
                                    <td class="align-middle">{{ $investigacion->estado }}</td>
                                    <td class="align-middle">{{ $investigacion->Analista }}</td>
                                    <td class="align-middle">{{ $investigacion->Investigador }}</td>
                                    <td class="align-middle">{{ $investigacion->tipoInvestigacion }}</td>
                                    <td class="align-middle">{{ $investigacion->region }}</td>
                                    <td class="align-middle">{{ $investigacion->FechaFinalizacion }}</td>
                                    <td class="align-middle">{{ $investigacion->FechaFacturacion }}</td>
                                    <td class="align-middle" style="text-align: right">{{ number_format($investigacion->tarifa, 2) }}</td>
                                    <td class="align-middle">   
                                    @if ($investigacion->extendida >0 )
                                    <a href ="/actualizarTarifaExtendida/{{ $investigacion->idInvestigacion }}/0" >
                                    <image src ="/images/on.png"/> </a>
                                    @else
                                    <a href ="/actualizarTarifaExtendida/{{ $investigacion->idInvestigacion }}/1" >
                                    <image src ="/images/off.png"/>  </a>
                                    @endif
                                    </td> 
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="28" class="text-center">No se encontraron registros con el
                                            parametro de busqueda</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>              
            </div>
            @endif
                 </form>    

       @endcan
    </div>
@endsection
