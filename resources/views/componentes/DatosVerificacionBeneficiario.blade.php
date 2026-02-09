<div class="accordion-item my-2">
    <h2 class="accordion-header" id="verificacionDatosBeneficiarios">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#verificacionDatosBeneficiario{{ $beneficiario->id }}" aria-expanded="false"
            aria-controls="verificacionDatosBeneficiario{{ $beneficiario->id }}">
            Verificación de datos - {{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }}
            {{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}
        </button>
    </h2>
    <div id="verificacionDatosBeneficiario{{ $beneficiario->id }}" class="accordion-collapse collapse"
        aria-labelledby="validacionDocumental" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.verificacionDatos')
        </div>
    </div>
</div>
