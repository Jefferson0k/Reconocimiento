function cambiarPass() {
    $('#formCambiarPassword').submit(function(e) {
        e.preventDefault(); // Previene la recarga de la página
        var formData = $(this).serialize(); // Serializa los datos del formulario
        $.ajax({
            url: $(this).attr('action'), // URL del formulario
            type: 'POST', // Método HTTP
            data: formData, // Datos del formulario
            dataType: 'json', // Tipo de datos esperados en la respuesta
            success: function(response) {
                if (response.status === 'success') {
                    showMessage('success', response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url; // Redirige después de 2 segundos
                    }, 2000);
                } else {
                    showMessage('danger', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                showMessage('danger', 'Ocurrió un error al cambiar la contraseña: ' + error);
            }
        });
    });
}

// Función para mostrar mensajes al usuario
function showMessage(type, message) {
    $('#message').html('<div class="alert alert-' + type + '">' + message + '</div>');
}
