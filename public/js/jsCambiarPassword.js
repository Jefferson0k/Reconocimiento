function cambiarPass() {
    $('#formCambiarPassword').submit(function(e) {
        e.preventDefault(); // Previene la recarga de la página
        var formData = $(this).serialize(); // Serializa los datos del formulario
        $.ajax({
            url: $(this).attr('action'), // URL del formulario
            type: 'POST', // Método HTTP
            data: formData, // Datos del formulario
            dataType: 'json', // Tipo de datos esperados en la respuesta
            success: handleResponse, // Maneja la respuesta
            error: handleError // Maneja el error
        });
    });
}

// Función para manejar la respuesta del servidor
function handleResponse(response) {
    if (response.status === 'success') {
        showMessage('success', response.message);
        setTimeout(function() {
            window.location.href = ruta + 'api/Perfil/vista'; // Redirige después de 2 segundos
        }, 2000);
    } else {
        showMessage('danger', response.message);
    }
}

// Función para manejar errores en la solicitud AJAX
function handleError(xhr, status, error) {
    console.error(error);
    showMessage('danger', 'Ocurrió un error al cambiar la contraseña: ' + error);
}

// Función para mostrar mensajes al usuario
function showMessage(type, message) {
    $('#message').html('<div class="alert alert-' + type + '">' + message + '</div>');
}
