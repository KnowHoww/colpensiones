$(document).ready(function() {
    $('#validarCarpetasBtn').on('click', function() {
        var modal = new bootstrap.Modal(document.getElementById('validarCarpetasModal'));
        modal.show();

        $.ajax({
            url: routes.validarCarpetas,
            method: 'GET',
            success: function(data) {
                let tableBody = $('#validarCarpetasTableBody');
                tableBody.empty();
                data.forEach(item => {
                    let estado = item.existe ? '✔' : '✖';
                    let row = `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.nombreCarpeta}</td>
                            <td>${estado}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    $('#regresarBtn').on('click', function() {
        sessionStorage.clear(); // Limpiar la sesión antes de regresar
    });
});
