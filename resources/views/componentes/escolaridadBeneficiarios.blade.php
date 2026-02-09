<div class="accordion-item my-2">
    <h2 class="accordion-header" id="escolaridadBeneficiarios">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#escolaridadBeneficiarios{{$beneficiario->id}}" aria-expanded="false"
            aria-controls="escolaridadBeneficiarios{{$beneficiario->id}}">
            Escolaridad - {{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }} {{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}
        </button>
    </h2>
    <div id="escolaridadBeneficiarios{{$beneficiario->id}}" class="accordion-collapse collapse" aria-labelledby="validacionDocumental"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.escolaridadBeneficiarios')
        </div>
    </div>
</div>
