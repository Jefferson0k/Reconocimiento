function listar(idSucursal) {
    var apiUrl = ruta + 'api/Usuario/' + idSucursal;
    var contador = 1; 
    $('#Usuario').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    $('#id_sucursal1').val(idSucursal);
    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblUsuarios tbody').empty();

            $.each(response.data, function(index, usuarios) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (usuarios.estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else if (usuarios.estado == 3) {
                    estadoIcono = '<i class="fa-solid fa-recycle" style="color: orange;"></i>'; // Icono de Recuperación
                    estadoTexto = 'Restableciendo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                var acciones = '<td style="text-align: center;">' +
                    '<button type="button" onclick="editar(' + usuarios.id + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                    '<button type="button" onclick="eliminar(' + usuarios.id + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                '</td>';

                $('#tblUsuarios tbody').append(
                    '<tr>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + usuarios.nombre + '</td>' +
                    '<td>' + usuarios.id_cargo.Nombre + '</td>' +
                    '<td>' + usuarios.login + '</td>' + 
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Destruir y reiniciar la tabla DataTable después de agregar los nuevos datos
            $('#tblUsuarios').DataTable().destroy();
            $('#tblUsuarios').DataTable({
                responsive: true,
                language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar   _MENU_ ",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            alert('Ocurrió un error al obtener los datos.');
        }
    });
}

function editar(id) {
    $('#updateButton').off('click'); // Desvincular eventos click previos
    $.ajax({
        url: ruta + "api/Usuarios/" + id,
        method: 'GET',
        success: function(data) {
            console.log(data); // Añadir esta línea para verificar los datos recibidos
            $('#nombre1').val(data.nombre);
            $('#login3').val(data.login);
            $('#estado1').val(data.estado);
            $('#id_sucursal1').val(data.id_sucursal);
            $('#id_cargo1').val(data.id_cargo);
            $('#administrador1').val(data.administrador);
            $('#updateButton').click(function() {
                updateCargo(id);
            });
            $('#update').modal('show');
        },        
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del cargo.');
        }
    });
}

function updateCargo(id) {
    var idSucursal = $('#id_sucursal').val();

    var nombre = $('#nombre1').val().trim();
    var login = $('#login3').val().trim();
    var estado = $('#estado1').val().trim();
    var id_sucursal = $('#id_sucursal1').val().trim();
    var clave = $('#clave1').val().trim();
    var id_cargo = $('#id_cargo1').val().trim();


    var data = {
        nombre: nombre,
        login: login,
        estado:estado,
        id_sucursal:id_sucursal,
        clave: clave ? clave : null,
        id_cargo:id_cargo,
    };

    $.ajax({
        url: ruta + "api/Usuario/" + id,
        method: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario actualizada con éxito',
                showConfirmButton: false,
                timer: 1500
            });
            $('#update').modal('hide');
            $('#tblUsuarios').DataTable().destroy();
            listar(idSucursal);
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al actualizar el cargo.');
        }
    });
}

function buscarSucursal() {
    $('#id_sucursal').select2();
    $('#id_sucursal').on('change', function() {
        var idSucursal = $(this).val();
        var tblUser = $('#tblUsuarios').DataTable();
        tblUser.destroy();
        listar(idSucursal);
        buscarTrabajadores(idSucursal);
    });

    $.ajax({
        url: ruta + 'api/Sucursalesv',
        dataType: 'json',
        success: function(data) {
            $('#id_sucursal').empty();
            $.each(data.data, function(index, item) {
                $('#id_sucursal').append($('<option>', {
                    value: item.id,
                    text: item.direccion
                }));
            });
            $('#id_sucursal').trigger('change');
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener los datos de las sucursales:', errorThrown);
        }
    });
}

function buscarTrabajadores(idSucursal) {
    $.ajax({
        url: ruta + 'api/Trabajador/' + idSucursal,
        dataType: 'json',
        success: function(data) {
            $('#id_trabajador').empty().append('<option value="" selected disabled>Seleccione un Trabajador</option>');

            if (Array.isArray(data.data)) {
                $.each(data.data, function(index, item) {
                    var textoOption = item.Apellidos + ' ' + item.nombres; // Solo el nombre completo para mostrar
                    var optionText = item.dni + ' - ' + textoOption; // Texto completo para la opción

                    $('#id_trabajador').append($('<option>', {
                        value: item.Apellidos + ' ' + item.nombres,
                        'data-id': item.id,
                        text: optionText // Mostrar DNI - Nombre completo en la opción
                    }));
                });
            } else {
                console.error('Error: la respuesta de datos no es un array:', data);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error al obtener los datos de los trabajadores:', errorThrown);
        }
    });
}

function mostrarOcultarNombre() {
    var cargoSeleccionado = document.getElementById("cargo").value;
    var nombreContainer = document.getElementById("nombreContainer");

    if (cargoSeleccionado === "SUCURSAL") {
        nombreContainer.style.display = "none";
    } else {
        nombreContainer.style.display = "block";
    }
}

function eliminar(id) {
    // Obtén el idSucursal del selector
    var idSucursal = $('#id_sucursal').val();

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás revertir esto',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: ruta + 'api/Usuario/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar(); // Mostrar la alerta después de eliminar el usuario
                    // Eliminar la fila de la tabla
                    $('#tblUsuarios').DataTable().row('#sucursal_' + id).remove();
                    // Destruir la tabla actual
                    $('#tblUsuarios').DataTable().destroy();
                    // Volver a cargar los datos y crear la tabla nuevamente
                    listar(idSucursal);
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el usuario:', error);
                    alert('Ocurrió un error al eliminar el usuario.');
                }
            });
        }
    });
}

function mostrarAlertaEliminar() {
    let timerInterval;
    Swal.fire({
        title: "Auto close alert!",
        html: "I will close in <b></b> milliseconds.",
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
}

function agregar() {
    var nombre = $('#nombre').val();
    var login = $('#login1').val();
    var clave = $('#clave').val();
    var estado = $('#estado').val();
    var id_sucursal = $('#id_sucursal').val();
    var id_cargo = $('#id_cargo').val();
    var administrador = $('#administrador').val();

    var formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('login', login);
    formData.append('clave', clave);
    formData.append('estado', estado);
    formData.append('id_sucursal', id_sucursal);
    formData.append('id_cargo', id_cargo);
    formData.append('administrador', administrador);

    $.ajax({
        url: ruta + 'api/Usuario',
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
                    title: 'El Usuario se ha creado con éxito.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#clave').val('');
                $('#login1').val('');

                var tblTurnos = $('#tblUsuarios').DataTable();
                tblTurnos.destroy();

                listar(id_sucursal);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un problema al agregar el Usuario.',
                    footer: '<a href="#">¿Por qué tengo este problema?</a>'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al enviar el formulario:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un error al enviar el formulario.',
                footer: '<a href="#">¿Por qué tengo este problema?</a>'
            });
        }
    });
}

function buscarCargos() {
    $('#id_cargo').select2({
        dropdownParent: $('#RegistroUsuarios')
    });

    $.ajax({
        url: ruta + 'api/Cargo',
        dataType: 'json',
        success: function(data) {
            $('#id_cargo').empty().append();

            if (Array.isArray(data.data)) {
                // Encontrar y mover el cargo con id 4 al inicio del array
                const sucursal = data.data.find(item => item.Id === "2");
                if (sucursal) {
                    data.data = data.data.filter(item => item.Id !== "2");
                    data.data.unshift(sucursal);
                }

                // Agregar los cargos al select
                $.each(data.data, function(index, item) {
                    $('#id_cargo').append($('<option>', {
                        value: item.Id,
                        text: item.Nombre
                    }));
                });
            } else {
                console.error('Error: la respuesta de datos no es un array:', data);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los cargos:', errorThrown);
        }
    });
}


function CodeSucursal(id_sucursal) {
    $.ajax({
        url: ruta + 'api/UserSucursal/' + id_sucursal,
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            $('#login1').val(data.data);
            $('#nombre').val($('#id_sucursal option:selected').text()); // Insertar el nombre de la sucursal en el campo oculto
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al generar el código de sucursal:', errorThrown);
        }
    });
}

function CodeTrabajador() {
    var selectedOption = $('#id_trabajador').find(':selected');
    var id_trabajador = selectedOption.data('id');
    var nombreCompleto = selectedOption.text().split(' - ')[1].trim(); // Obtener solo el nombre completo sin el DNI

    var formData = {
        id_trabajador: id_trabajador
    };

    $.ajax({
        url: ruta + 'api/generate-username',
        type: 'POST',
        data: formData,
        success: function(data) {
            $('#login1').val(data.username);
            $('#nombre').val(nombreCompleto); // Insertar el nombre en el campo oculto
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al generar el código de trabajador:', errorThrown);
        }
    });
}

function inicializarSelectores() {
    $('#id_cargo').select2({
        dropdownParent: $('#RegistroUsuarios')
    });

    $('#id_trabajador').select2({
        dropdownParent: $('#RegistroUsuarios')
    });
}

function configurarEventos() {
    $(document).ready(function() {
        // Al cargar la página, ocultar el contenedor de buscarTrabajadores
        $('#buscarTrabajadoresContainer').hide();

        $('#id_cargo').on('change', function() {
            var selectedCargo = $(this).val();
            var idSucursal = $('#id_sucursal').val(); // Asumiendo que el ID de la sucursal está en un select con id #id_sucursal

            console.log("Cargo seleccionado:", selectedCargo);

            if (selectedCargo) {
                if (selectedCargo == "2") {
                    console.log("Seleccionado cargo Sucursal. Ocultando buscarTrabajadoresContainer y nombreContainer.");
                    $('#buscarTrabajadoresContainer').hide();
                    $('#nombreContainer').hide(); // Ocultar nombreContainer cuando se selecciona Sucursal
                } else {
                    console.log("Seleccionado otro cargo. Mostrando buscarTrabajadoresContainer y nombreContainer.");
                    $('#buscarTrabajadoresContainer').show();
                    $('#nombreContainer').show(); // Mostrar nombreContainer cuando se selecciona un cargo diferente de Sucursal
                    buscarTrabajadores(idSucursal);
                }
                limpiarCampoLogin(); 
            } else {
                console.log("No se ha seleccionado ningún cargo. Ocultando contenedores.");
                $('#nombreContainer').hide();
                $('#buscarTrabajadoresContainer').hide(); // Ocultar cuando no se selecciona un cargo
                $('#id_trabajador').empty().append('<option value="" selected disabled>Seleccione un Trabajador</option>');
                limpiarCampoLogin(); // Limpiar el campo cuando no se selecciona un cargo
            }
        });

        $('#id_trabajador').on('change', function() {
            var selectedTrabajador = $(this).val();

            console.log("Trabajador seleccionado:", selectedTrabajador);

            if (selectedTrabajador) {
                limpiarCampoLogin(); // Limpiar el campo cuando se selecciona un trabajador
            }
        });

        $('#generateUserCode').on('click', function() {
            var selectedCargo = $('#id_cargo').val();
            var id_sucursal = $('#id_sucursal').val();

            console.log("Botón generar código presionado. Cargo seleccionado:", selectedCargo);

            if (selectedCargo == "2") { // Si el cargo seleccionado es "Sucursal"
                CodeSucursal(id_sucursal);
            } else if (selectedCargo) {
                if ($('#id_trabajador').val()) {
                    CodeTrabajador();
                } else {
                    mostrarAlertaSeleccionarTrabajador();
                }
            } else if (id_sucursal) {
                CodeSucursal(id_sucursal);
            } else {
                console.error('No se ha seleccionado ninguna sucursal.');
            }
        });
    });
}

function limpiarCampoLogin() {
    $('#login1').val('');
}

function mostrarAlertaSeleccionarTrabajador() {
    Swal.fire({
        icon: 'warning',
        title: '¡Atención!',
        text: 'Por favor, seleccione un trabajador antes de generar el código.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Entendido'
    });
}

function verificarFortalezaClave(clave) {
    // Patrones para evaluar la fortaleza de la contraseña
    var patterns = {
        weak: /^(?=.*[a-zA-Z])(?=.*[0-9]).{6,}$/, // Al menos una letra y un número, mínimo 6 caracteres
        medium: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/, // Al menos una minúscula, una mayúscula y un número, mínimo 8 caracteres
        strong: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{10,}$/ // Al menos una minúscula, una mayúscula, un número y un carácter especial, mínimo 10 caracteres
    };

    // Evaluar la contraseña contra los patrones
    if (patterns.strong.test(clave)) {
        return { strength: "Fuerte", width: 100, class: "bg-success" };
    } else if (patterns.medium.test(clave)) {
        return { strength: "Media", width: 70, class: "bg-warning" };
    } else if (patterns.weak.test(clave)) {
        return { strength: "Débil", width: 40, class: "bg-danger" };
    } else {
        return { strength: "Muy débil", width: 20, class: "bg-danger" };
    }
}

function actualizarFortalezaClave() {
    var clave = $('#clave').val();
    var result = verificarFortalezaClave(clave);
    var mensaje = 'Fortaleza de la contraseña: ' + result.strength;

    $('#password-strength').text(mensaje); // Actualizar el mensaje de fortaleza
    $('#password-strength-progress').removeClass().addClass('progress-bar ' + result.class); // Actualizar la clase de color de la barra de progreso
    $('#password-strength-progress').css('width', result.width + '%').attr('aria-valuenow', result.width); // Actualizar el ancho de la barra de progreso
}

function buscarCargosActualizar() {
    $.ajax({
        url: ruta+'api/Cargo',
        dataType: 'json',
        success: function(data) {
            $('#id_cargo1').empty();
            $.each(data.data, function(index, item) {
                $('#id_cargo1').append($('<option>', {
                    value: item.Id,
                    text: item.Nombre
                }));
            });
            // No es necesario el trigger('change') si no estás usando select2
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los cargos:', errorThrown);
        }
    });
}
