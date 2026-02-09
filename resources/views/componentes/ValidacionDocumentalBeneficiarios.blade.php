<div class="accordion-item my-2">
    <h2 class="accordion-header" id="validacionDocumentalBeneficiario">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseValidacionDocumentalBeneficiario{{$beneficiario->id}}" aria-expanded="false"
            aria-controls="collapseValidacionDocumentalBeneficiario{{$beneficiario->id}}">
            Validación documental Beneficiario - {{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }} {{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}
        </button>
    </h2>
    <div id="collapseValidacionDocumentalBeneficiario{{$beneficiario->id}}" class="accordion-collapse collapse" aria-labelledby="validacionDocumental"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.validacionDocumentalBeneficiario')
        </div>
    </div>
</div>
