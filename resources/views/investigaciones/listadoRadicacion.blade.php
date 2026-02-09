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
                    <form action="{{ route('pendienteradicacion') }}" method="get" class="mb-3">
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
					<form action="{{ route('descargarZIPRadicacion') }}" method="post" class="mb-3">
                       @csrf
                                <input type="hidden" id="fecha_inicio" name="fecha_inicio" class="form-control"
                                    value="{{ $fecha_inicio }}"/>
                           
                                <input type="hidden" id="fecha_fin" name="fecha_fin" class="form-control"
                                    value="{{ $fecha_fin }}"/>
     

                       <div class="col-md-4 my-2"> <button type="submit" class="btn btn-primary mt-3">Descargar ZIP</button></div>
                    </form>			
                </div>
				<form action="{{ route('actualizarRadicados') }}" id="actualizarRadicados" method="post" class="mb-3">
				 @csrf
				 
				 <div  class="col-12 row"  id="myTabsContent">
				 
				 <div class="col-md-4 my-2"><label for="fecha_facturacion" class="form-label">Fecha Radicacion:</label>
                 <input type="date" id="fecha_radicacion" name="fecha_radicacion" class="form-control"></input> </div>
				  <div class="col-md-4 my-2">
					<label for="estadosRadicacion" class="form-label">Estado Radicación</label>
					<select name="estadosRadicacion" id="estadosRadicacion" class="form-select">
						<option value=""></option>
						@foreach ($estadosRadicacion as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
						@endforeach
					</select>
				</div>

				 <div class="col-md-4 my-2"><button type="submit" class="btn btn-primary mt-3">Actualizar </button> </div>
				 </div>
				 
				
				 
				 <div class="col-12 mt-3">
                    <b>Registros: {{ $cantidad }}</b> 
			</div><div class="col-12 mt-3">
                    <b>Registros Pendientes: {{ $cantidadPendientes }}</b> 
			</div>
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
                                    <th >Fecha Radicado</th>
                                    <th>Estado</th>
                                   
                                    <th>Tipo de Investigación</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($investigaciones as $investigacion)
								<tr>
									<td class="align-middle">@if (($investigacion->idEstado ==7 && $investigacion->FechaRadicacion == null)||($investigacion->idEstado ==16 && $investigacion->FechaCorrecionRadicacion == null))
										<div  class="form-control">
											<input type="checkbox" id="investigaciones[]" name="investigaciones[]"
											value="{{ $investigacion->idInvestigacion }}" checked />
										</div>
									@endif
									</td>
									<td class="align-middle">{{ $investigacion->idInvestigacion }}</td>
									<td class="align-middle">{{ $investigacion->NumeroRadicacionCaso }}</td>
									<td class="align-middle">{{ $investigacion->TipoDocumento }} {{ $investigacion->NumeroDeDocumento }}</td>
									<td class="align-middle">{{ $investigacion->PrimerNombre }} {{ $investigacion->PrimerApellido }}</td>
									<td class="align-middle">@if ($investigacion->idEstado ==7 )
											{{ $investigacion->FechaRadicacion }}
										@else
											{{ $investigacion->FechaCorrecionRadicacion }}
										@endif
									</td>
											<td class="align-middle">{{ $investigacion->estado }}</td>
									<td class="align-middle">{{ $investigacion->tipoInvestigacion }}</td>
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
				 </form>	

       
    </div>
@endsection
