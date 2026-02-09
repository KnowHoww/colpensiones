<div class="col-12 col-xxl-8 mb-3">
    <h4>Trazabilidad de las actividades realizadas</h4>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item my-2">
            <h2 class="accordion-header" id="trazabilidadActividades">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapsetrazabilidadActividades" aria-expanded="true"
                    aria-controls="collapsetrazabilidadActividades">
                    Trazabilidad de las actividades realizadas
                </button>
            </h2>
            <div id="collapsetrazabilidadActividades" class="accordion-collapse collapse"
                aria-labelledby="trazabilidadActividades" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        @can('excel.informe.trazabilidadActividades')
                            <a href="{{ route('generarInformeTrazabilidadInvestigacion', $investigacion->id) }}"
                                class="d-none d-sm-inline-block btn btn-primary shadow-sm" target="_blank">
                                <i class="fas fa-download fa-sm text-white-50"></i> Descargar trazabilidad
                            </a>
                        @endcan
                    </div>
                    <table class="table datatable table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Actividad</th>
                                {{-- <th>Fecha</th> --}}
                                <th>Observación</th>
                                <th>Fecha<th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($trazabilidadActividades) > 0)
                                @foreach ($trazabilidadActividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->creadores->name }} {{ $actividad->creadores->lastname }}</td>
                                        <td>{{ $actividad->rol_usuario }}</td>
                                        {!! Form::model($actividad, ['route' => ['trazabilidadactividad.update', $actividad->id], 'method' => 'PUT']) !!}
                                            <td>
                                            @php
                                                // Decodificar el array JSON
                                                $actividadestipoinvestigacionArray = json_decode($actividadestipoinvestigacion, true);
                                                
                                                // Buscar el índice que corresponde al valor actual de la actividad
                                                $indiceActividad = array_search($actividad->actividad, $actividadestipoinvestigacionArray);
                                            @endphp
                                            {!! Form::select('actividad', $actividadestipoinvestigacion, $indiceActividad, ['class' => 'form-control', 'disabled' => true]) !!}
                                            <!-- {!! Form::select('actividad', $actividadestipoinvestigacion, null, [
                                                'class' => 'form-control',
                                                'required',
                                                'disabled'=> true,
                                            ]) !!} -->
                                            {!! Form::hidden('actividadestipoinvestigacion', json_encode($actividadestipoinvestigacion)) !!}
    
                                            <!-- {!! Form::select('actividad', $actividadestipoinvestigacion, null, ['class' => 'form-control', 'disabled' => true]) !!} -->
                                            </td>
                                            <td>
                                                {!! Form::text('observacion', $actividad->observacion, ['class' => 'form-control', 'disabled' => true]) !!}
                                            </td>
                                            <td>
                                            {!! Form::input('datetime-local', 'fecha', $actividad->fecha ? \Carbon\Carbon::parse($actividad->fecha)->format('Y-m-d\TH:i') : null, ['class' => 'form-control', 'disabled' => true]) !!}  
                                            </td>

                                            <td>
                                                {!! Form::submit('Guardar', ['class' => 'btn btn-success d-none btn-save']) !!}
                                            </td>
                                                
                                            <td>
                                                <button type="button" class="btn btn-primary btn-edit">Editar</button>
                                            </td>

                                        {!! Form::close() !!}
                                    </tr>
                                @endforeach

                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No hay datos para mostrar en este momento
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    @can('trazabilidadActividades.view.formulario')
                        @include('formularios.trazabilidadActividades')
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

