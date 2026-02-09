{{-- <div class="col-12 col-xxl-8 mb-3">
    <h4>Acreditación</h4>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item my-2">
            <h2 class="accordion-header" id="acreditacion">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseacreditacion" aria-expanded="true" aria-controls="collapseacreditacion">
                    Acreditación
                </button>
            </h2>
            <div id="collapseacreditacion" class="accordion-collapse collapse" aria-labelledby="acreditacion"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @foreach ($antecedentesBeneficiarios as $beneficiario)
                        @include('formularios.acreditacion')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
 --}}

 <div class="col-12 col-xxl-8 mb-3">
    <h4>Conclusión(es)</h4>
    <div class="accordion" id="accordionExample">
        @foreach ($acreditaciones as $beneficiario)
            @include('componentes.AcreditacionBeneficiarios')
        @endforeach
    </div>
</div>