<div class="col-12 col-xxl-8 mb-3">
    <h4>Consulta bases de datos</h4>
    <div class="accordion" id="accordionExample">
        @include('componentes.AntecedentesCausante')
        @foreach ($antecedentesBeneficiarios as $beneficiario)
            @include('componentes.AntecedentesBeneficiarios')
        @endforeach
    </div>
</div>