function cambiarPassword() {
    var Pass_actual = $('#Pass_actual').val();
    var nueva_Pass = $('#nueva_Pass').val();
    var confirmar_Pass = $('#confirmar_Pass').val();
    $.ajax({
        url: ruta + "Login/cambiarPass",
        type: 'POST',
        data: {
            Pass_actual: Pass_actual,
            nueva_Pass: nueva_Pass,
            confirmar_Pass: confirmar_Pass
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Ocurrio un error al cambiar la contraseñas.');
        }
    });
}
