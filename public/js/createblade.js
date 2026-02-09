var cantidad = 1;
let selectedFiles = [];  
let fileInputIndex = 0;  

$(document).ready(function () {
    $('#myForm').on('submit', function (e) {
        
        if (selectedFiles.length === 0) {
            
            alert('Por favor, seleccione al menos un documento antes de continuar.');

            e.preventDefault();
        }
    });

	$('#file-input').on('change', function(event) {
		const files = event.target.files;

		addFileInput(files);

		$('#file-input').val('');
	});


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

    
    toggleFields();

	$('#nuevo_beneficiario').click(function () {
        
        nuevoBeneficiarioItem();
		toggleFields();
    });

    $(document).on('click', '.eliminarBeneficiario', function () {
        $(this).closest('.beneficiario-item').remove(); 
        console.log('Beneficiario eliminado.');
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


function addFileInput(files) {
    const form = $('#myForm');  
    
    Array.from(files).forEach((file, index) => {
        const currentIndex = fileInputIndex++;  
        const fileInput = $('<input>')
            .attr('type', 'file')
            .attr('name', 'files[]')
            .attr('class', 'd-none')  
            .attr('data-file-index', currentIndex)  
            .prop('files', createFileList(file)); 

        form.append(fileInput); 
        selectedFiles.push({ file: file, index: currentIndex });  
    });

    renderFileList();  
}

function createFileList(file) {
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    return dataTransfer.files;
}

function renderFileList() {
    const fileList = $('#file-list');
    fileList.empty();  

    selectedFiles.forEach((item, index) => {
        const listItem = $('<li></li>')
            .addClass('list-group-item d-flex justify-content-between align-items-center')
            .text(item.file.name);

        const removeButton = $('<button></button>')
            .addClass('btn btn-danger btn-sm')
            .text('Eliminar')
            .attr('data-index', index)
            .on('click', function() {
                removeFile(index);  
            });

        listItem.append(removeButton);
        fileList.append(listItem);
    });
}

function removeFile(index) {
    const fileToRemove = selectedFiles[index];  

    selectedFiles.splice(index, 1);

    
    $(`input[data-file-index="${fileToRemove.index}"]`).remove();

    renderFileList(); 
}

function nuevoBeneficiarioItem() {
    var formulario = `
    <div class="accordion-item beneficiario-item">
        <h2 class="accordion-header" id="heading_${cantidad}">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${cantidad}" aria-expanded="true" aria-controls="collapse_${cantidad}">
                Beneficiario ${cantidad + 1}
            </button>
        </h2>
        <div id="collapse_${cantidad}" class="accordion-collapse collapse show" aria-labelledby="heading_${cantidad}" >
            <div class="accordion-body">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="PrimerNombre">Primer nombre*</label>
                                <input type="text" name="beneficiarios[${cantidad}][PrimerNombre]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="SegundoNombre">Segundo nombre</label>
                                <input type="text" name="beneficiarios[${cantidad}][SegundoNombre]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="PrimerApellido">Primer apellido*</label>
                                <input type="text" name="beneficiarios[${cantidad}][PrimerApellido]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="SegundoApellido">Segundo apellido</label>
                                <input type="text" name="beneficiarios[${cantidad}][SegundoApellido]" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label for="TipoDocumento">Tipo documento*</label>
                                <select name="beneficiarios[${cantidad}][TipoDocumento]" class="form-control" required>
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
                                <label for="NumeroDocumento">Número documento*</label>
                                <input type="text" name="beneficiarios[${cantidad}][NumeroDocumento]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label for="Parentesco">Parentesco*</label>
                                <select name="beneficiarios[${cantidad}][Parentesco]" class="form-control parentesco-select" required>
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
                                <input type="text" name="beneficiarios[${cantidad}][Nit]" class="form-control">
                            </div>
                            <div class="col-md-4 InstitucionEducativaField" style="display:none;">
                                <label for="InstitucionEducativa">Institución educativa</label>
                                <input type="text" name="beneficiarios[${cantidad}][InstitucionEducativa]" class="form-control">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger eliminarBeneficiario">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
	$('#accordion').append(formulario);
    cantidad++; 
};