<div class="accordion-item my-2">
    <h2 class="accordion-header" id="AcreditacionBeneficiarios">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#antecendentesBeneficiarios{{$beneficiario->id}}" aria-expanded="false"
            aria-controls="antecendentesBeneficiarios{{$beneficiario->id}}">
            Acreditación de - {{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }} {{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}
        </button>
    </h2>
    <div id="antecendentesBeneficiarios{{$beneficiario->id}}" class="accordion-collapse collapse" aria-labelledby="validacionDocumental"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.acreditacionBeneficiario')
        </div>
    </div>
</div>
