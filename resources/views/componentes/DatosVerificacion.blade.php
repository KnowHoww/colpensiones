<div class="col-12 col-xxl-8 mb-3">
    <h4>Verificacion de datos</h4>
    <div class="accordion" id="accordionExample">
        @foreach ($investigacionVerificacion as $beneficiario)
            @include('componentes.DatosVerificacionBeneficiario')
        @endforeach
    </div>
</div>
