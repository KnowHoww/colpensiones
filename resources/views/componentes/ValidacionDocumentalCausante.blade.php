<div class="accordion-item my-2">
    <h2 class="accordion-header" id="validacionDocumentalCausante">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseValidacionDocumental" aria-expanded="false"
            aria-controls="collapseValidacionDocumental">
            Validación documental Causante - {{ $investigacion->PrimerNombre }} {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }} {{ $investigacion->SegundoApellido }}
        </button>
    </h2>
    <div id="collapseValidacionDocumental" class="accordion-collapse collapse" aria-labelledby="validacionDocumental"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.validacionDocumentalCausante')
        </div>
    </div>
</div>
