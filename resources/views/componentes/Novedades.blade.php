<div class="col-12 col-xxl-8 mb-3">
    <h4>Novedades de investigación</h4>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item my-2">
            <h2 class="accordion-header" id="novedades">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapsenovedades" aria-expanded="true"
                    aria-controls="collapsenovedades">
                    Novedades de la investigación
                </button>
            </h2>
            <div id="collapsenovedades" class="accordion-collapse collapse"
                aria-labelledby="novedades" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table class="table datatable table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Observación novedad</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($novedades) > 0)
                                @foreach ($novedades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->creadores->name }} {{ $actividad->creadores->lastname }}</td>
                                        <td>{{ $actividad->rol_usuario }}</td>
                                        <td>{{ $actividad->novedad }}</td>
                                        <td>{{ $actividad->fecha }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No hay datos para mostrar en este momento
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    @can('novedades.view.formulario')
                        @include('formularios.Novedades')
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
