function listar(idSucursal) {
    var apiUrl = ruta + 'api/Trabajador/' + idSucursal;
    var contador = 1; 
    $('#sucursal').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    $('#sucursal2').val(idSucursal);

    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblTrabajadores tbody').empty();

            $.each(response.data, function(index, trabajador) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (trabajador.estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                var acciones = '<td style="text-align: center;">' +
                    '<button type="button" onclick="Editar(' + trabajador.id + ', ' + idSucursal + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                    '<button type="button" onclick="eliminar(' + trabajador.id + ', ' + idSucursal + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                '</td>';

                $('#tblTrabajadores tbody').append(
                    '<tr>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + trabajador.dni + '</td>' +
                    '<td>' + trabajador.nombres + '</td>' +
                    '<td>' + trabajador.Apellidos + '</td>' + 
                    '<td>' + trabajador.telefono + '</td>' +
                    '<td>' + trabajador.id_horario.ingreso + '</td>' +
                    '<td>' + trabajador.id_horario.salida + '</td>' +
                    '<td>' + trabajador.id_horario.totalHoras + '</td>' +
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Destruir y reiniciar la tabla DataTable después de agregar los nuevos datos
            $('#tblTrabajadores').DataTable().destroy();
            $('#tblTrabajadores').DataTable({
                responsive: true,
                language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
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

function buscarSucursal() {
    $.ajax({
        url: ruta + 'api/getUserInfo',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var idSucursal = response.id_sucursal;
            var cargo = response.cargo;

            $.ajax({
                url: ruta + 'api/Sucursalesv',
                dataType: 'json',
                success: function(data) {
                    $('#id_sucursal').empty();

                    if (cargo == 1) { // Super Administrador
                        // Mostrar todas las sucursales para el super administrador
                        $.each(data.data, function(index, item) {
                            $('#id_sucursal').append($('<option>', {
                                value: item.id,
                                text: item.direccion
                            }));
                        });
                    } else {
                        // Mostrar solo la sucursal asociada al usuario no super administrador
                        $.each(data.data, function(index, item) {
                            if (item.id == idSucursal) {
                                $('#id_sucursal').append($('<option>', {
                                    value: item.id,
                                    text: item.direccion
                                }));
                            }
                        });
                    }

                    // Seleccionar automáticamente la sucursal del usuario
                    $('#id_sucursal').val(idSucursal).trigger('change');
                    $('#id_sucursal').select2();
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('Error al obtener los datos de las sucursales:', errorThrown);
                }
            });

            $('#id_sucursal').on('change', function() {
                var idSucursal = $(this).val();
                var tblTrabajadores = $('#tblTrabajadores').DataTable();

                // Destruir la tabla antes de recargarla
                tblTrabajadores.destroy();

                // Llamar a las funciones con el idSucursal seleccionado
                listar(idSucursal);
                buscarTrabajadores(idSucursal);
                buscarHorarios(idSucursal);
                buscarHorariosActualizar(idSucursal);
            });
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener la información del usuario:', errorThrown);
        }
    });
}

function buscarHorarios(idSucursal) {
    $('#id_horario').select2({
        dropdownParent: $('#RegistroTrabajadores')
    });

    // Limpiar las opciones previas
    $('#id_horario').empty();

    $.ajax({
        url: ruta + 'api/Horario-Estados/' + idSucursal,
        dataType: 'json',
        success: function(data) {
            $.each(data.data, function(index, item) {
                // Convertir las horas a formato AM/PM usando moment.js
                var ingresoAMPM = moment(item.ingreso, 'HH:mm:ss').format('hh:mm A');
                var salidaAMPM = moment(item.salida, 'HH:mm:ss').format('hh:mm A');
                var breakEntradaAMPM = moment(item.break_entrada, 'HH:mm:ss').format('hh:mm A');
                var breakSalidaAMPM = moment(item.break_salida, 'HH:mm:ss').format('hh:mm A');
                var totalHorasAMPM = moment(item.totalHoras, 'HH:mm:ss').format('hh:mm A');

                // Crear el texto de la opción incluyendo los horarios en formato AM/PM
                var optionText = ingresoAMPM + ' - ' + salidaAMPM + ' - ' + item.id_Turnos.Turno + ' - ' + totalHorasAMPM;
                $('#id_horario').append($('<option>', {
                    value: item.id,
                    text: optionText
                }));
            });

            // Volver a inicializar select2 después de agregar opciones
            $('#id_horario').trigger('change');
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error al obtener los datos:', errorThrown);
        }
    });
}

function ConsultaDniTelefono() {
    // Limitar la longitud del DNI a 8 dígitos
    $('#dni, #dni1').on('input', function() {
        var dni = $(this).val();
        if (dni.length > 8) {
            $(this).val(dni.slice(0, 8));
        }
    });

    // Limitar la longitud del teléfono a 9 dígitos
    $('#telefono').on('input', function() {
        var telefono = $(this).val();
        if (telefono.length > 9) {
            $(this).val(telefono.slice(0, 9));
        }
    });

    $('#dni, #dni1').on('change', function() {
        var dni = $(this).val();
        if (dni.length === 8) {
            $('#dni-error').hide();
            $(this).removeClass('is-invalid');
            consultarDatosDNI(dni, $(this).attr('id') === 'dni' ? 'principal' : 'actualizacion');
        } else {
            $('#dni-error').text('El DNI debe tener exactamente 8 dígitos.').show();
            $(this).addClass('is-invalid');
        }
    });

    $('#telefono').on('change', function() {
        var telefono = $(this).val();
        if (telefono.length === 9) {
            $('#telefono-error').hide();
            $(this).removeClass('is-invalid');
        } else {
            $('#telefono-error').text('El teléfono debe tener exactamente 9 dígitos.').show();
            $(this).addClass('is-invalid');
        }
    });

    function consultarDatosDNI(dni, tipo) {
        var apiUrl = ruta + 'consulta-dni/' + dni;

        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (tipo === 'principal') {
                        $('#nombres').val(response.data.nombres);
                        $('#Apellidos').val(response.data.apellido_paterno + ' ' + response.data.apellido_materno);
                    } else {
                        $('#nombres1').val(response.data.nombres);
                        $('#Apellidos1').val(response.data.apellido_paterno + ' ' + response.data.apellido_materno);
                    }
                } else {
                    $('#dni-error').text('No se encontraron datos para el DNI proporcionado').show();
                    if (tipo === 'principal') {
                        $('#dni').addClass('is-invalid');
                        $('#nombres').val('');
                        $('#Apellidos').val('');
                    } else {
                        $('#dni1').addClass('is-invalid');
                        $('#nombres1').val('');
                        $('#Apellidos1').val('');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al consultar datos del DNI:', error);
                $('#dni-error').text('Error al consultar datos del DNI.').show();
                if (tipo === 'principal') {
                    $('#dni').addClass('is-invalid');
                    $('#nombres').val('');
                    $('#Apellidos').val('');
                } else {
                    $('#dni1').addClass('is-invalid');
                    $('#nombres1').val('');
                    $('#Apellidos1').val('');
                }
            }
        });
    }
}


function agregar() {
    var dni = $('#dni').val();
    var nombres = $('#nombres').val();
    var Apellidos = $('#Apellidos').val();
    var telefono = $('#telefono').val();
    var id_horario = $('#id_horario').val();
    var id_sucursal = $('#id_sucursal').val();
    var estado = $('select[name="estado"]').val(); 

    var formData = new FormData();
    formData.append('dni', dni);
    formData.append('nombres', nombres);
    formData.append('Apellidos', Apellidos);
    formData.append('telefono', telefono);
    formData.append('id_horario', id_horario);
    formData.append('id_sucursal', id_sucursal);
    formData.append('foto', $('#foto')[0].files[0]); 
    formData.append('estado', estado);

    $.ajax({
        url: ruta + 'api/Trabajador',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response === 'Agregado con éxito') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El Trabajador se ha creado con éxito.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#dni').val('');
                $('#nombres').val('');
                $('#Apellidos').val('');
                $('#telefono').val('');
                // Mostrar una imagen predeterminada después de limpiar
                $('#foto').attr('src', ruta + 'Trabajadores/SinFoto/default.jpg');

                var tblTrabajadores = $('#tblTrabajadores').DataTable();
                tblTrabajadores.destroy();
                listar(id_sucursal);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response,
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

function eliminar(id) {
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
                url: ruta + 'api/Trabajador/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar();
                    var tblTrabajadores = $('#tblTrabajadores').DataTable();
                    tblTrabajadores.destroy();
                    listar($('#sucursal').val());
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el trabajador:', error);
                    alert('Ocurrió un error al eliminar el trabajador.');
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

function handleCsvUpload() {
    $('#CargaP').click(function() {
        console.log('Botón de carga masiva clickeado');
        // Al hacer clic en el botón, se abre el selector de archivos
        $('#fileInput').click();
    });

    // Función para manejar la selección de archivo CSV
    $('#fileInput').change(function() {
        console.log('Archivo seleccionado');
        var file = this.files[0];
        if (file) {
            // Validar si el archivo seleccionado es de tipo CSV
            if (file.type !== 'text/csv') {
                // Mostrar un mensaje de error con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo no válido',
                    text: 'Por favor, selecciona un archivo CSV.',
                });
                // Limpiar el input file
                $('#fileInput').val('');
                return; // Salir de la función si el archivo no es CSV
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                var csv = e.target.result;
                var lines = csv.split('\n');
                var data = [];
                lines.forEach(function(line) {
                    var parts = line.split(';');
                    // Verificar si la fila tiene datos válidos antes de agregarla
                    if (parts.length === 6 && parts.every(part => part.trim() !== '')) {
                        data.push({
                            dni: parts[0],
                            nombres: parts[1],
                            Apellidos: parts[2],
                            telefono: parts[3],
                            id_horario: parts[4],
                            estado: parts[5]
                        });
                    }
                });
                mostrarDatosEnTabla(data);
            };
            reader.readAsText(file);
        }
    });

    // Función para mostrar los datos en la tabla
    function mostrarDatosEnTabla(data) {
        var tbl = $('#tblPreviu').DataTable();
        tbl.clear().draw();
        data.forEach(function(item, index) {
            tbl.row.add([
                index + 1, // contador
                item.dni,
                item.nombres,
                item.Apellidos,
                item.telefono,
                item.id_horario,
                item.estado
            ]);
        });
        tbl.draw(); // Dibujar la tabla después de agregar todas las filas
    }
    $('#btnGuardarMasivo').click(function() {
        var file = $('#fileInput').prop('files')[0];
        var id_sucursal = $('#sucursal1').val();
    
        if (file) {
            var formData = new FormData();
            formData.append('userfile', file);
            formData.append('id_sucursal', id_sucursal);
    
            $.ajax({
                url: ruta + 'api/Trabajador/Bulkload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.trim() === "Los datos se han insertado correctamente.") {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        var tblTrabajadores = $('#tblTrabajadores').DataTable();
                        tblTrabajadores.destroy();
                        listar(id_sucursal);
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response, // Mostrar el mensaje de error real recibido del servidor
                            footer: '<a href="#">Why do I have this issue?</a>'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la carga masiva:', error);
                    // Mostrar mensaje de error genérico en caso de error de conexión
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href="#">Why do I have this issue?</a>'
                    });
                }
            });
        } else {
            // Mostrar mensaje de advertencia cuando no se selecciona un archivo CSV
            Swal.fire({
                title: "Sin seleccionar",
                text: "Seleccione el archivo CSV =)",
                icon: "question"
            });
        }
    });     
}

function buscarTrabajadores(idSucursal) {
    $('#id_trabajador').select2({
        dropdownParent: $('#ObservacionesTrabajadores') 
    });
    $.ajax({
        url: ruta + 'api/Trabajador-Estado/' + idSucursal,
        dataType: 'json',
        success: function(data) {
            $('#id_trabajador').empty();
            $.each(data.data, function(index, item) {
                var textoOption = item.dni + ' - ' + item.Apellidos + ' ' + item.nombres;
                $('#id_trabajador').append($('<option>', {
                    value: item.id,
                    text: textoOption
                }));
            });
            $('#id_trabajador').trigger('change');
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error al obtener los datos:', errorThrown);
        }
    });
}

function agregarObservaciones() {
    var fecha = $('#fecha').val();
    var Modificacion = $('#Modificacion').val();
    var observaciones = $('#observaciones').val();
    var id_trabajador = $('#id_trabajador').val();
    var id_Sucursal = $('#sucursal2').val();
    var formData = new FormData();
    formData.append('fecha', fecha);
    formData.append('Modificacion', Modificacion);
    formData.append('observaciones', observaciones);
    formData.append('id_trabajador', id_trabajador);
    formData.append('id_Sucursal', id_Sucursal);
    
    $.ajax({
        url: ruta + 'api/Observaciones',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Verificar el tipo de respuesta del servidor
            if (response.error) {
                // Manejar el error específico de observación duplicada
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error,
                    footer: '<a href="#">¿Por qué tengo este problema?</a>'
                });
            } else {
                // Mostrar mensaje de éxito
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'La Observación se ha creado con éxito.',
                    showConfirmButton: false,
                    timer: 1500
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

function Editar(id, idSucursal) {
    $('#updateButton').off('click'); 
    $.ajax({
        url: ruta + "api/Trabajador/" + id + "/" + idSucursal,
        method: 'GET',
        success: function(response) {
            var data = response.data;

            $('#update').modal('show');
            $('#dni1').val(data.dni);
            $('#nombres1').val(data.nombres);
            $('#Apellidos1').val(data.Apellidos);
            $('#telefono1').val(data.telefono);
            $('#estado1').val(data.estado);
            $('#fotoPreview').attr('src', ruta + 'Trabajadores/Sucursales/' + data.id_sucursal + '/' + data.foto);
            $('#foto1').val('');
            $('#id_horario1').val(data.id_horario);

            $('#updateButton').click(function() {
                update(id, idSucursal);
            });
            $('#update').modal('show');

            buscarHorariosActualizar(idSucursal);
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del trabajador.');
        }
    });
}

function update(id, idSucursal) {
    console.log("update function called with id:", id, "idSucursal:", idSucursal);
    var formData = new FormData();
    formData.append('dni', $('#dni1').val());
    formData.append('nombres', $('#nombres1').val());
    formData.append('Apellidos', $('#Apellidos1').val());
    formData.append('telefono', $('#telefono1').val());
    formData.append('id_horario', $('#id_horario1').val());
    formData.append('estado', $('#estado1').val());

    var logoFile = $('#foto1')[0].files[0];
    if (logoFile) {
        formData.append('foto', logoFile);
    }

    $.ajax({
        url: ruta + "api/Trabajador/" + id + "/" + idSucursal,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Trabajador actualizado con éxito",
                showConfirmButton: false,
                timer: 1500
            });
            $('#update').modal('hide');
            $('#tblTrabajadores').DataTable().destroy();
            listar(idSucursal);
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al actualizar el trabajador.');
        }
    });
}

function buscarHorariosActualizar(idSucursal) {
    var $select = $('#id_horario1');

    // Inicializar Select2 con dropdown en el modal
    $select.select2({
        dropdownParent: $('#update'),
        placeholder: 'Seleccione un horario',
        allowClear: true
    });

    // Limpiar las opciones previas
    $select.empty();

    $.ajax({
        url: ruta + 'api/Horario-Estados/' + idSucursal,
        dataType: 'json',
        success: function(data) {
            $.each(data.data, function(index, item) {
                var optionText = item.ingreso + ' - ' + item.salida + ' - ' + item.id_Turnos.Turno + ' - ' + item.totalHoras;
                $select.append($('<option>', {
                    value: item.id,
                    text: optionText
                }));
            });

            // Si ya hay un valor seleccionado, asegúrate de que Select2 lo muestre correctamente
            if ($select.data('selected')) {
                $select.val($select.data('selected')).trigger('change');
                $select.data('selected', null); // Limpiar el valor después de usarlo
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los horarios:', errorThrown);
        }
    });
}