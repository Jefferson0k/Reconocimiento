var ruta = 'http://localhost:8080/';

function agregar() {
    var id_trabajador = $('#idTrabajador').val(); // Corregido el ID del campo oculto
    var id_sucursal = $('#id_sucursal').val();

    var formData = new FormData();
    formData.append('id_trabajador', id_trabajador);
    formData.append('id_sucursal', id_sucursal);

    console.log('Datos del formulario:', {
        id_trabajador: id_trabajador,
        id_sucursal: id_sucursal,
    });

    $.ajax({
        url: ruta + 'api/Asistencia',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            if (response.success) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Asistencia registrada con éxito.',
                    showConfirmButton: false,
                    timer: 1500,
                    willClose: function() {
                        // Recargar la página después de que el mensaje se cierre
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un problema al agregar la asistencia.',
                    footer: '<a href="#">¿Por qué tengo este problema?</a>'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al enviar el formulario:', error);
            // Mostrar el mensaje de error utilizando SweetAlert2
            if (xhr.status === 400) {
                const errorMessage = xhr.responseJSON.error;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al enviar el formulario.',
                    footer: '<a href="#">¿Por qué tengo este problema?</a>'
                });
            }
        }
    });
}