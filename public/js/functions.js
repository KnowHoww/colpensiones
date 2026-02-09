$(document).ready(function () {
	const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const inputs = row.querySelectorAll('input, select');
            const saveButton = row.querySelector('.btn-save');

            // Habilitar los campos de edición
            inputs.forEach(input => {
                input.disabled = false;
            });

            // Mostrar el botón de guardar y ocultar el de editar
            this.classList.add('d-none');
            saveButton.classList.remove('d-none');
        });
    });
    
	$('.tox-notifications-container').addClass('hidden');

	loadInformacion();

	$('.gasto').on('keyup', function () {
		calcularGastos();
	});

	$('.btn_save_info').on('click', () => {
		$('#loadInfo').modal({
			keyboard: false,
			backdrop: 'static',
		})
	});

	$('#TipoInvestigacion').change(function () {
		$('#listaValidacionDocumental').addClass('d-none');
		if ($(this).val() == 'VD') {
			$('#listaValidacionDocumental').removeClass('d-none');
		}
	});

	$('#btnItem').click(function () {
		openAllTabs();
	});

	// $('#nuevo_beneficiario').click(function () {
	// 	nuevoBeneficiarioItem();
	// });


	$('.usarInformacion').click(function () {
		data = $(this).attr('id');
		usarInformacion(data);
	});

	$('#departamentoRegion').change(function () {
		municipiosDepartamento($(this).val())
	});

	$('#estado').change(function () {
		if ($(this).val() == 8) {
			$('#seleccionObjecion').removeClass('d-none');
			$('#causalPrimaria').attr('required', true);
		} else {
			$('#seleccionObjecion').addClass('d-none');
			$('#causalPrimaria').attr('required', false);
		}
	});

});

function loadInformacion() {
	let data = $('#TipoInvestigacion').val()

	if (!data == 'VD') {
		$('#listaValidacionDocumental').removeClass('d-none');
	} else {
		$('#listaValidacionDocumental').addClass('d-none');
	}
}

function municipiosDepartamento(id) {
	let url = '/api/cargaMunicipios';
	if (id) {
		url += '/' + id;
	}

	$.ajax({
		url: url,
		method: 'GET'
	}).done(function (data) {
		console.log(data);
		let lista = '';
		for (let i = 0; i < data.length; i++) {
			lista += '<option value="' + data[i].id + '">' + data[i].municipio + '</option>';
		}
		$('#ciudadRegion').html(lista);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.error('Error:', textStatus, errorThrown);
	});
}

// function nuevoBeneficiarioItem() {
// 	let cantidad = $('.beneficiario').length;
// 	var formulario = "<tr id='beneficiario_" + cantidad + "' class='beneficiario'>" +
// 		"<td><select name='TipoDocumento_" + cantidad + "' class='form-control' required>" +
// 		"<option value=''>Seleccione...</option>" +
// 		"<option value='NU'>Número único de identificación personal</option>" +
// 		"<option value='CC'>Cédula de ciudadanía</option>" +
// 		"<option value='NI'>NIT</option>" +
// 		"<option value='TI'>Tarjeta de identidad</option>" +
// 		"<option value='CE'>Cédula de extranjería</option>" +
// 		"<option value='PA'>Pasaporte</option>" +
// 		"<option value='RC'>Registro civil</option>" +
// 		"<option value='CF'>Carné Diplomático</option>" +
// 		"<option value='AS'>Adulto sin Identificación</option>" +
// 		"<option value='MS'>Menor sin Identificación</option>" +
// 		"<option value='F'>Documento Extranjero</option>" +
// 		"</select></td>" +
// 		"<td><input type='text' id='NumeroDocumento_" + cantidad + "' name='NumeroDocumento_" + cantidad + "' class='form-control'></td>" +
// 		"<td><input type='text' id='PrimerNombre_" + cantidad + "' name='PrimerNombre_" + cantidad + "' class='form-control'></td>" +
// 		"<td><input type='text' id='SegundoNombre_" + cantidad + "' name='SegundoNombre_" + cantidad + "' class='form-control'></td>" +
// 		"<td><input type='text' id='PrimerApellido_" + cantidad + "' name='PrimerApellido_" + cantidad + "' class='form-control'></td>" +
// 		"<td><input type='text' id='SegundoApellido_" + cantidad + "' name='SegundoApellido_" + cantidad + "' class='form-control'></td>" +
// 		"<td><select name='Parentesco_" + cantidad + "' class='form-control'>" +
// 		"<option value=''>Seleccione...</option>" +
// 		"<option value='1'>Hijo Invalido</option>" +
// 		"<option value='2'>Cónyuge o Compañera</option>" +
// 		"<option value='3'>Hijo Menor Edad</option>" +
// 		"<option value='4'>Hijo Mayor Estudios</option>" +
// 		"<option value='5'>Padre o Madre</option>" +
// 		"<option value='6'>Hermano Invalido</option>" +
// 		"<option value='7'>Otro o Tercero</option>" +
// 		"</select></td>" +
// 		"<td><input type='text' id='Nit_" + cantidad + "' name='Nit_" + cantidad + "' class='form-control'></td>" +
// 		"<td><input type='text' id='InstitucionEducativa_" + cantidad + "' name='InstitucionEducativa_" + cantidad + "' class='form-control'></td>" +
// 		"<td><button type='button' class='btn btn-danger btn-small eliminarBeneficiario'>x</button></td>"
// 	"</tr>";
	
// 	$('#beneficiarios').append(formulario);
// 	// Añadir el event listener al botón de eliminar
// 	$('.eliminarBeneficiario').last().click(function () {
// 		$(this).closest('tr').remove();
// 		console.log('Beneficiario eliminado.');
// 	});
// }

function eliminarBeneficiario(id) {
	document.getElementById('beneficiario_' + id).remove();
	console.log('Beneficiario con ID ' + id + ' eliminado.');
}

function usarInformacion($id) {
	$('#PrimerNombre').val($('#nombre1_' + $id).text());
	$('#SegundoNombre').val($('#nombre2_' + $id).text());
	$('#PrimerApellido').val($('#apellido1_' + $id).text());
	$('#SegundoApellido').val($('#apellido2_' + $id).text());

}

function calcularGastos() {

	dato1 = parseInt($('#serviciosPublicosValor').val());
	dato2 = parseInt($('#serviciosPublicosValorAporte').val());
	dato3 = parseInt($('#arriendoValor').val());
	dato4 = parseInt($('#arriendoValorAporte').val());
	dato5 = parseInt($('#mercadoValor').val());
	dato6 = parseInt($('#mercadoValorAporte').val());
	dato7 = parseInt($('#otrosValor').val());
	dato8 = parseInt($('#otrosValorAporte').val());

	total = dato1 + dato3 + dato5 + dato7;
	totalAporte = dato2 + dato4 + dato6 + dato8;

	dato9 = $('#totalValor').val(total);
	dato10 = $('#totalValorAporte').val(totalAporte);
}

function showCities(data) {
	$.ajax({
		url: 'selectcity',
		method: 'POST',
		data: { departament: data, _token: $('input[name="_token"]').val() },
	}).done(function (data) {
		console.log();
		lista = '';
		for (i = 0; i < data.length;) {
			lista += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
			i++;
		}
		$('#city').html(lista);
	});
}