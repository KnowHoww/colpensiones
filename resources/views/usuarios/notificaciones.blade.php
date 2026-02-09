@extends('layouts.app')
@section('content')
    <div>

        <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Notificaciones ({{ $count }})  </h1>
                </div>
            </div>
        </div>
		
		 <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    &nbsp;  <a href="/LeidoTodo" class="btn btn-dark m-1" rel="noopener noreferrer">Todo leido</a>
                     <a href="/CumplidoTodo" class="btn btn-dark m-1" rel="noopener noreferrer">Todo procesado</a> &nbsp; &nbsp; &nbsp;
                </div>
            </div>
        </div>
				<div class="tab-content bg-white my-3" id="myTabsContent">
                    <div class="tab-pane fade show active" id="solicitado" role="tabpanel" aria-labelledby="solicitado-tab">
                        <table id="investigacionesTable" class="table  table-responsive table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mensaje</th>
                                    <th>Leido</th>
                                    <th>Pendiente</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
							
							 @forelse ($notificaciones as $notificacion)
								<tr>
									<td  class="align-middle" style="text-align: center">{{ $notificacion->created_at }}</td>
									<td  class="align-middle" style="text-align: center">{{ $notificacion->mensaje }}</td>
									<td  class="align-middle" style="text-align: center">
									@if ($notificacion->leido ==0 )
									<a href ="/Leido/{{ $notificacion->id }}" >
									<image src ="/images/off.png"/> </a>
									@else
									<img src="/images/check.png"> 
									@endif
									</td>
									<td  class="align-middle" style="text-align: center">
									@if ($notificacion->pendiente >0 )
									<a href ="/Cumplido/{{ $notificacion->id }}" >
									<image src ="/images/on.png"/> </a>
									@else
									<image src ="/images/off.png"/> </a>
									@endif
									</td>	
									
								</tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No se encontraron registros </td>
                                    </tr>
                                @endforelse							
							</tbody>
                        </table>
                    </div>
                </div>	
					


       
    </div>
@endsection
