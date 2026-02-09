<div class="col-12 col-xxl-8 mb-3">
    <h4>Validación documental</h4>
    <div class="accordion" id="accordionExample">
        @include('componentes.ValidacionDocumentalCausante')
        @foreach ($validacionDocumentalBeneficiarios as $beneficiario)
            @include('componentes.ValidacionDocumentalBeneficiarios')
        @endforeach
    </div>
</div>