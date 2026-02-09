<div class="accordion-item my-2">
    <h2 class="accordion-header" id="ActualizacionEstado">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseActualizacionEstado" aria-expanded="true" aria-controls="collapseActualizacionEstado">
            Actualización de estado
        </button>
    </h2>
    <div id="collapseActualizacionEstado" class="accordion-collapse collapse show" aria-labelledby="ActualizacionEstado"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table class="table datatable table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Causal Objeción primaria</th>
                        <th>Causal Objeción secundaria</th>
                        <th>Observación</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($historialEstados) > 0)
                        @foreach ($historialEstados as $historial)
                            <tr>
                                <td>{{ optional($historial->creadores)->name }}
                                    {{ optional($historial->creadores)->lastname }}</td>
                                <td>{{ $historial->rol_usuario }}</td>
                                <td>{{ optional($historial->estados)->name }}</td>
                                <td>{{ optional($historial->CausalPrimaria)->nombre }}</td>
                                <td>{{ optional($historial->CausalSecundaria)->nombre }}</td>
                                {{-- <td>{{ optional($historial->estados)->name }}</td> --}}
                                <td>{{ $historial->observacion }}</td>
                                <td>{{ $historial->created_at }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No hay datos para mostrar en este momento</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if ($estadoCount)
                @can('formulario.actualizacionEstado')
                    @include('formularios.actualizacionEstado')
                @endcan
            @endif
        </div>
    </div>
</div>
