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
				
				
				@can('firma.view') 
				<form action="{{ route('aceptarinforme') }}" id="aceptarinforme" method="post" class="mb-3">
				 @csrf
					<input type="hidden" id="id" name="id" class="form-control"
                                    value="{{ $id }}"/>
                           
                                  {!! Form::label('Observaciones', 'Observaciones', ['class' => 'form-label']) !!}
								{!! Form::textarea('Observaciones', null, [
									'class' => 'form-control',
									'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
									'rows' => 4,
								]) !!}
								<div class="col-md-4 my-2"><b>NOTA IMPORTANTE: La Aceptación no tiene correción.</b></div>
								<div class="col-md-4 my-2"><button type="submit" class="btn btn-primary mt-3">Aceptar</button> </div>
				 </div>
								
								
				</form>
				 @endcan

			<div class="col-12" >
				<div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th title="ID Investigación">Id</th>
                                    <th>Número Caso</th>
                                    <th title="Tipo de documento y número de documento causante">Documento</th>
                                    <th title="">Causante</th>
                                    <th title="Radicado Bizagi">Radicado</th>
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
								
									<td class="align-middle">{{ $investigacion->idInvestigacion }}</td>
									<td class="align-middle">{{ $investigacion->NumeroRadicacionCaso }}</td>
									<td class="align-middle">{{ $investigacion->TipoDocumento }} {{ $investigacion->NumeroDeDocumento }}</td>
									<td class="align-middle">{{ $investigacion->PrimerNombre }} {{ $investigacion->PrimerApellido }}</td>
									<td class="align-middle">{{ $investigacion->FechaRadicacion }}</td>
									<td class="align-middle">{{ $investigacion->estado }}</td>
									<td class="align-middle">{{ $investigacion->Investigador }}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($investigacion->comision_investigador, 2) }}</td>
									<td class="align-middle">{{ $investigacion->Auxiliar}}</td>
									<td class="align-middle" style="text-align: right">{{ number_format($investigacion->comision_auxiliar, 2) }}</td>
									<td class="align-middle">{{ $investigacion->tipoInvestigacion }}</td>
									<td class="align-middle">	
									@if ($investigacion->porBeneficiario >0 )
									
									<image src ="/images/on.png"/> 
									@else
									<image src ="/images/off.png"/> 
									@endif
									</td><td class="align-middle">	
									@if ($investigacion->doble >0 )
									<image src ="/images/on.png"/> 
									@else
									<image src ="/images/off.png"/> 
									@endif
									</td><td class="align-middle">	
									@if ($investigacion->AuxiliarCompleta >0 )
									<image src ="/images/on.png"/> 
									@else
									<image src ="/images/off.png"/>  
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
				 </form>	

       
    </div>
@endsection
