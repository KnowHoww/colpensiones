let beneficiarioIndex = document.getElementById('beneficiariosContainer').getAttribute('data-count');

$(document).ready(function(){
    toggleFields();

	$(document).on('change', '.parentesco-select', function () {
        var selectedValue = $(this).val();
        var $beneficiario = $(this).closest('.beneficiario');

        if (selectedValue === '4') { 
            $beneficiario.find('.NitField').show();
            $beneficiario.find('.InstitucionEducativaField').show();
        } else {
            $beneficiario.find('.NitField').hide();
            $beneficiario.find('.InstitucionEducativaField').hide();
        }
    });

    
    $('#TipoInvestigacionSelect').change(function() {
        toggleFields();
    });

    // Función para agregar un nuevo beneficiario
    $('#nuevo_beneficiario').click(function() {
        
        beneficiarioIndex++;

        // Plantilla del nuevo acordeón de beneficiario
        let nuevoBeneficiario = `
            <div class="accordion-item beneficiario-item">
                <h2 class="accordion-header" id="headingNuevo${beneficiarioIndex}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNuevo${beneficiarioIndex}" aria-expanded="true" aria-controls="collapseNuevo${beneficiarioIndex}">
                        Beneficiario ${beneficiarioIndex}
                    </button>
                </h2>
                <div id="collapseNuevo${beneficiarioIndex}" class="accordion-collapse collapse show" aria-labelledby="headingNuevo${beneficiarioIndex}">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="PrimerNombreNuevo${beneficiarioIndex}">Primer nombre*</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][PrimerNombre]" class="form-control" id="PrimerNombreNuevo${beneficiarioIndex}">
                            </div>
                            <div class="col-md-3">
                                <label for="SegundoNombreNuevo${beneficiarioIndex}">Segundo nombre</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][SegundoNombre]" class="form-control" id="SegundoNombreNuevo${beneficiarioIndex}">
                            </div>
                            <div class="col-md-3">
                                <label for="PrimerApellidoNuevo${beneficiarioIndex}">Primer apellido*</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][PrimerApellido]" class="form-control" id="PrimerApellidoNuevo${beneficiarioIndex}">
                            </div>
                            <div class="col-md-3">
                                <label for="SegundoApellidoNuevo${beneficiarioIndex}">Segundo apellido</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][SegundoApellido]" class="form-control" id="SegundoApellidoNuevo${beneficiarioIndex}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label for="TipoDocumentoNuevo${beneficiarioIndex}">Tipo documento*</label>
                                <select name="beneficiarios[${beneficiarioIndex}][TipoDocumento]" class="form-control" id="TipoDocumentoNuevo${beneficiarioIndex}" required>
                                    <option value="">Seleccione...</option>
                                    <option value="NU">Número único de identificación personal</option>
                                    <option value="CC">Cédula de ciudadanía</option>
                                    <option value="NI">NIT</option>
                                    <option value="TI">Tarjeta de identidad</option>
                                    <option value="CE">Cédula de extranjería</option>
                                    <option value="PA">Pasaporte</option>
                                    <option value="RC">Registro civil</option>
                                    <option value="CF">Carné Diplomático</option>
                                    <option value="AS">Adulto sin Identificación</option>
                                    <option value="MS">Menor sin Identificación</option>
                                    <option value="F">Documento Extranjero</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="NumeroDocumentoNuevo${beneficiarioIndex}">Número documento*</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][NumeroDocumento]" class="form-control" id="NumeroDocumentoNuevo${beneficiarioIndex}">
                            </div>
                            <div class="col-md-2">
                                <label for="ParentescoNuevo${beneficiarioIndex}">Parentesco*</label>
                                <select name="beneficiarios[${beneficiarioIndex}][Parentesco]" class="form-control parentesco-select" id="ParentescoNuevo${beneficiarioIndex}" required>
                                    <option value="">Seleccione...</option>
                                    <option value="">Seleccione...</option>
                                    <option value="1">Hijo Invalido</option>
                                    <option value="2">Cónyuge o Compañera</option>
                                    <option value="3">Hijo Menor Edad</option>
                                    <option value="4">Hijo Mayor Estudios</option>
                                    <option value="5">Padre o Madre</option>
                                    <option value="6">Hermano Invalido</option>
                                    <option value="7">Causante</option>
                                    <option value="7">Otro o Tercero</option>
                                </select>
                            </div>
                            <div class="col-md-2 NitField" style="display:none;">
                                <label for="Nit">Nit</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][Nit]" class="form-control" id="Nit${beneficiarioIndex}">
                            </div>
                            <div class="col-md-4 InstitucionEducativaField" style="display:none;">
                                <label for="InstitucionEducativa">Institución educativa</label>
                                <input type="text" name="beneficiarios[${beneficiarioIndex}][InstitucionEducativa]" class="form-control" id="IE${beneficiarioIndex}">
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger eliminar_beneficiario" data-id="${beneficiarioIndex}">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        // Agregar el nuevo beneficiario al acordeón
        $('#accordion').append(nuevoBeneficiario);
        toggleFields();
    });

    // Función para eliminar un beneficiario agregado dinámicamente
    $(document).on('click', '.eliminar_beneficiario', function() {
        let id = $(this).data('id');
        $(`#headingNuevo${id}`).parent().remove();
    });
});


function toggleFields() {
    var selectedValue = $('#TipoInvestigacionSelect').val();
    if (selectedValue === 'ES') { 
        $('.NitField, .InstitucionEducativaField').show(); 
    } else {
        $('.NitField, .InstitucionEducativaField').hide(); 
    }
}