@extends('layouts.app')
@section('content')
    <div>

        <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ $title }}  </h1>
                </div>
            </div>
        </div>
       
                <div class="col-12">
                    <form action="{{ route('pendientecomision') }}" method="get" class="mb-3">
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
					@if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if (session('infoError'))
                        <div class="alert alert-danger">
                            {{ session('infoError') }}
                        </div>
                    @endif
				
				<div class="col-12" >
					<form action="{{ route('descargarXLScomision') }}" method="post" class="mb-3">
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
				<div class="col-12" >
					<form action="{{ route('descargarXLScomisionResumen') }}" method="post" class="mb-3">
                       @csrf
                                <input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
                              <input type="hidden" id="centroCosto" name="centroCosto" class="form-control"
                                    value="{{ $centroCostos }}"/>
                                

                       <div class="col-md-4 my-2"> <button type="submit" class="btn btn-primary mt-3">Descargar XLS Resumen</button></div>
                    </form>			
                </div>		
				<div class="col-12" >
					<form action="{{ route('descargarZipInformes') }}" method="post" class="mb-3">
                       @csrf
                                <input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
                              <input type="hidden" id="centroCosto" name="centroCosto" class="form-control"
                                    value="{{ $centroCostos }}"/>
                                

                       <div class="col-md-4 my-2"> <button type="submit" class="btn btn-primary mt-3">Descargar Informes Aprobados</button></div>
                    </form>			
                </div>				
				
				@if ($perfil==1)
				<form action="{{ route('actualizarcomision') }}" id="actualizarcomision" method="post" class="mb-3">
				 @csrf
					<input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
                              <input type="hidden" id="centroCosto" name="centroCosto" class="form-control"
                                    value="{{ $centroCostos }}"/>				 
				 <div  class="col-12 row"  id="myTabsContent">
				 
				 <div class="col-md-4 my-2"><label for="fecha_comision" class="form-label">Fecha Comisión:</label>
                 <input type="date" id="fecha_facturacion" name="fecha_comision" class="form-control"></input> </div>
				  
				 <div class="col-md-4 my-2"><button type="submit" class="btn btn-primary mt-3">Actualizar Fecha de Comisión</button> </div>
				 </div>
				</form>
				 @endif

				<form action="{{ route('notificarcomisiones') }}" id="notificarcomisiones" method="post" class="mb-3">
				 @csrf
				  <div class="col-md-4 my-2"><button type="submit" class="btn btn-primary mt-3">Notificar Comisiones</button> </div>
				  	<input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
                              <input type="hidden" id="centroCosto" name="centroCosto" class="form-control"
                                    value="{{ $centroCostos }}"/>	
				  
				  
				<div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sel</th>
                                    <th>Investigador</th>
                                    <th>Cantidad Inv.</th>
                                    <th>Valor Inv.</th>
                                    <th>Cantidad Apoyo</th>
                                    <th>Valor Apoyo</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
							 @forelse ($totales as $total)
								<tr>
									<td class="align-middle">
										<div  class="form-control">
											<input type="checkbox" id="investigadores[]" name="investigadores[]"
											value="{{ $total->id }}" 
											@if ($total->idInforme  == null )
												checked 
												@endif
											/>
										</div>
									</td>								
									<td class="align-middle"><a href="/verinformeInvestigadorpdf/{{ $total->id }}/{{ $fecha_inicio }}/{{ $fecha_fin}}/" target="_blank" > {{ $total->Investigador }}</a>
									@if ($total->aceptado )
										<img src="/images/check.png">
									@endif
									</td>
									<td  class="align-middle" style="text-align: center">{{ $total->Investigaciones }}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($total->comision_investigador, 2) }} </td>
									<td  class="align-middle" style="text-align: center">{{ $total->Apoyos }}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($total->comision_auxiliar, 2) }} </td>
									<td class="align-middle" style="text-align: right">{{ number_format($total->comision_auxiliar + $total->comision_investigador,  2) }} </td>
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
								 
				 </form>


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
                                    <th>Estado</th>
                                    <th>Investigador</th>
                                    <th>Tarifa</th>
                                    <th>Auxiliar</th>
                                    <th>Tarifa</th>
                                    <th>Tipo de Investigación</th>
                                    <th>Por Beneficiario</th>
                                    <th>Doble</th>
                                    <th>Tarifa Auxiliar Completa</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($investigaciones as $investigacion)
								<tr>
									<td class="align-middle">
										<div  class="form-control">
											<input type="checkbox" id="investigaciones[]" name="investigaciones[]"
											value="{{ $investigacion->idInvestigacion }}" 
											@if ( $investigacion->FechaComision == null && $investigacion->Investigador!=null )
												checked 
												@endif
											/>
										</div>
									</td>
									<td class="align-middle">{{ $investigacion->idInvestigacion }}</td>
									<td class="align-middle">{{ $investigacion->NumeroRadicacionCaso }}</td>
									<td class="align-middle">{{ $investigacion->TipoDocumento }} {{ $investigacion->NumeroDeDocumento }}</td>
									<td class="align-middle">{{ $investigacion->PrimerNombre }} {{ $investigacion->PrimerApellido }}</td>
									<td class="align-middle">{{ $investigacion->estado }}</td>
									<td class="align-middle">{{ $investigacion->Investigador }}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($investigacion->comision_investigador, 2) }}</td>
									<td class="align-middle">{{ $investigacion->Auxiliar}}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($investigacion->comision_auxiliar, 2) }}</td>
									<td class="align-middle">{{ $investigacion->tipoInvestigacion }}</td>
									<td class="align-middle">	
									@if ($investigacion->porBeneficiario >0 )
									<a href ="/actualizarPorBeneficiario/{{ $investigacion->idInvestigacion }}/0" >
									<image src ="/images/on.png"/> </a>
									@else
									<a href ="/actualizarPorBeneficiario/{{ $investigacion->idInvestigacion }}/1" >
									<image src ="/images/off.png"/>  </a>
									@endif
									</td><td class="align-middle">	
									@if ($investigacion->doble >0 )
									<a href ="/actualizarDoble/{{ $investigacion->idInvestigacion }}/0" >
									<image src ="/images/on.png"/> </a>
									@else
									<a href ="/actualizarDoble/{{ $investigacion->idInvestigacion }}/1" >
									<image src ="/images/off.png"/>  </a>
									@endif
									</td><td class="align-middle">	
									@if ($investigacion->AuxiliarCompleta >0 )
									<a href ="/actualizarAuxiliar/{{ $investigacion->idInvestigacion }}/0" >
									<image src ="/images/on.png"/> </a>
									@else
									<a href ="/actualizarAuxiliar/{{ $investigacion->idInvestigacion }}/1" >
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

       
    </div>
@endsection
