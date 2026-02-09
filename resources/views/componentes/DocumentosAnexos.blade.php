<div class="accordion-item my-2">
    <h2 class="accordion-header" id="heading4">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4"
            aria-expanded="false" aria-controls="collapse4">
            documentos de investigación
        </button>
    </h2>
    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table class="table datatable table-striped" style="width: 100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Guia</th>
                        <th>Documento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $documento)
                        <tr>
                            <td>
                                @if (strpos($documento, 'investigacion') !== false)
                                    Investigación
                                @elseif(strpos($documento, 'soporteFotografico') !== false)
                                    Soporte fotografico
                                @else
                                    Bizagi
                                @endif
                            </td>
                            <td>{{ explode('/', $documento)[count(explode('/', $documento)) - 1] }}</td>
                            <td>
                                @if (strpos($documento, 'investigacion') !== false)
                                    <a class="btn btn-primary" target="_blank"
                                        href="{{ env('APP_URL') . '/investigaciones/' . str_replace('#', '%23',str_replace(' ', '%20', $documento)) }}">Ver
                                        anexo</a>
                                    @if (($investigacion->estado == 3 || $investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11) && Auth::user()->centroCosto == 1)
                                        @can('formularios.eliminar.documento')
                                            <form action="{{ route('eliminarSoporte') }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="ruta_archivo" value="{{ $documento }}">
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Eliminar</button>
                                            </form>
                                        @endcan
                                    @endif
                                @elseif(strpos($documento, 'soporteFotografico') !== false)
                                    <a class="btn btn-primary" target="_blank"
                                        href="{{ env('APP_URL') . '/investigaciones/' . str_replace('#', '%23',str_replace(' ', '%20', $documento)) }}">Ver
                                        anexo</a>
                                    @if (($investigacion->estado == 3 || $investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11) && Auth::user()->centroCosto == 1)
                                        @can('formularios.eliminar.documento')
                                            <form action="{{ route('eliminarSoporte') }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="ruta_archivo" value="{{ $documento }}">
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Eliminar</button>
                                            </form>
                                        @endcan
                                    @endif
                                @else
                                    <a class="btn btn-primary" target="_blank"
                                        href="{{ env('APP_URL') . '/investigaciones/' . str_replace('#', '%23',str_replace(' ', '%20', $documento) ) }}">Ver
                                        anexo</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
