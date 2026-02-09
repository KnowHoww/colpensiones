<div class="accordion-item my-2">
    <h2 class="accordion-header" id="heading3">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3"
            aria-expanded="false" aria-controls="collapse3">
            Beneficiarios
        </button>
    </h2>
    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table class="table datatable table-bordered">
                <tr>
                    <th>Tipo y número de documento</th>
                    <th>Nombres y apellidos</th>
                    <th>Parentesco</th>
                    <th>Nit</th>
                    <th>Institución Educativa</th>
                </tr>
                @foreach ($beneficiarios as $beneficiario)
                    <tr>
                        <td>{{ $beneficiario->TipoDocumento }} {{ $beneficiario->NumeroDocumento }}</td>
                        <td>{{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }}
                            {{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}</td>
                        <td>{{ optional($beneficiario->Parentescos)->nombre ?? 'No registrado' }}</td>
                        <td>{{ $beneficiario->Nit ?? 'No registrado' }}</td>
                        <td>{{ $beneficiario->InstitucionEducativa ?? 'No registrado' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
