<div class="col-12 col-xxl-8 mb-3">
    <h4>Escolaridad</h4>
    <div class="accordion" id="accordionExample">
        @foreach ($escolaridadBeneficiarios as $beneficiario)
            @include('componentes.escolaridadBeneficiarios')
        @endforeach
    </div>
</div>