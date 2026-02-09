$(document).ready(function() {
    $('#radicar-form').on('submit', function(event) {
        event.preventDefault();
        
        var $volverBtn = $('#volver');
        $volverBtn.prop('disabled', true);
        

        var $radicarBtn = $('#radicar-btn');
        $radicarBtn.prop('disabled', true);
        $radicarBtn.text('Procesando...');

        var $progressContainer = $('#progress-container');
        $progressContainer.show();

        var $messagesContainer = $('#messages-container');
        $messagesContainer.empty();
        $messagesContainer.show();

        console.log('Form submitted, sending AJAX request to start the job.');

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            timeout: 10800000,  // 3 horas en milisegundos
            success: function(response) {
                try {
                    if (response.error) {
                        throw new Error(response.error);
                    }
                    console.log('Job started successfully:', response);
                    var jobId = response.jobId;
                    var message = response.message;
                    updateProgress(jobId, message);
                } catch (e) {
                    console.error('Error in success handler:', e);
                    $messagesContainer.text('Error en el procesamiento de la respuesta del servidor.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error starting job:', xhr.responseText);
                $messagesContainer.text('Error al iniciar el trabajo: ' + error);
            }
        });
    });

    function updateProgress(jobId, message) {
        console.log('El mensaje es: ' + message);
        var $progressBar = $('#progress-bar');
        var $messagesContainer = $('#messages-container');

        var interval = setInterval(function() {
            console.log('Requesting progress update for job:', jobId);

            $.get('/documentacion/progress/' + jobId, function(data) {
                console.log('Progress update received:', data);

                var progress = data.progress;
                $progressBar.css('width', progress + '%');
                $progressBar.text(progress + '%');

                updateMessages(progress, $messagesContainer);

                if (progress >= 100) {
                    clearInterval(interval);
                    window.location.href = '/generarDocumentacion?message=' + encodeURIComponent(message);
                }
            }).fail(function() {
                console.error('Error fetching progress update.');
                clearInterval(interval);
                $messagesContainer.text('Error al actualizar el progreso.');
            });
        }, 500);
    }

    function updateMessages(progress, $messagesContainer) {
        var message;
        if (progress < 25) {
            message = 'Iniciando el proceso...';
        } else if (progress < 50) {
            message = 'Generando documentación...';
        } else if (progress < 75) {
            message = 'Casi listo...';
        } else if (progress < 100) {
            message = 'Finalizando...';
        } else {
            message = 'Proceso completado.';
        }
        $messagesContainer.text(message);
        console.log('Progress message:', message);
    }
});
