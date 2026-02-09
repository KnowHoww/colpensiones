<div class="accordion-item my-2">
    <h2 class="accordion-header" id="headingRegistroFotografico">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseRegistroFotografico" aria-expanded="false"
            aria-controls="collapseRegistroFotografico">
            Registro fotográfico
        </button>
    </h2>
    @if (
        $investigacion->estado == 5 ||
            $investigacion->estado == 6 ||
            $investigacion->estado == 11 ||
            $investigacion->estado == 7 ||
            $investigacion->estado == 8)
        <div id="collapseRegistroFotografico" class="accordion-collapse collapse"
            aria-labelledby="headingRegistroFotografico" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                @include('formularios.registroFotografico')
            </div>
        </div>
    @endif
</div>
