<div class="accordion-item my-2">
    <h2 class="accordion-header" id="antecedentesCausante">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#antecendentesCausante" aria-expanded="false"
            aria-controls="antecendentesCausante">
            Consulta bases de datos - {{ $investigacion->PrimerNombre }} {{ $investigacion->SegundoNombre }} {{ $investigacion->PrimerApellido }} {{ $investigacion->SegundoApellido }}
        </button>
    </h2>
    <div id="antecendentesCausante" class="accordion-collapse collapse" aria-labelledby="validacionDocumental"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            @include('formularios.antecedentesCausante')
        </div>
    </div>
</div>
