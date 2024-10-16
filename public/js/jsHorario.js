function listar(idSucursal) {
    var apiUrl = ruta + 'api/Horariov/' + idSucursal;
    var contador = 1;
    $('#sucursal').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblHorario tbody').empty();

            $.each(response.data, function(index, horario) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (horario.estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                // Función para formatear hora en formato AM/PM
                function formatTime(time) {
                    var formattedTime = new Date('1970-01-01T' + time);
                    return formattedTime.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                }

                // Función para obtener AM o PM
                function getAMPM(time) {
                    var hours = parseInt(time.substring(0, 2));
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    return ampm;
                }

                var acciones = '<td style="text-align: center;">' +
                    '<button type="button" onclick="editar(' + horario.id + ', ' + idSucursal + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                    '<button type="button" onclick="eliminarHorario(' + horario.id + ', ' + idSucursal + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                    '</td>';

                $('#tblHorario tbody').append(
                    '<tr>' +
                    '<td><input type="checkbox" class="select-row form-check-input" value="' + horario.id + '"></td>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + formatTime(horario.ingreso) + ' ' + getAMPM(horario.ingreso) + '</td>' +
                    '<td>' + formatTime(horario.break_entrada) + ' ' + getAMPM(horario.break_entrada) + '</td>' +
                    '<td>' + formatTime(horario.break_salida) + ' ' + getAMPM(horario.break_salida) + '</td>' +
                    '<td>' + formatTime(horario.salida) + ' ' + getAMPM(horario.salida) + '</td>' +
                    '<td>' + formatTime(horario.totalHoras) + ' horas</td>' +
                    '<td>' + horario.id_Turnos.Turno + '</td>' +
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Inicializa DataTable nuevamente
            $('#tblHorario').DataTable().destroy();
            $('#tblHorario').DataTable({
                responsive: true,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }  // Desactiva la ordenación para la primera columna
                ],
                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fa fa-file-excel-o"></i>',
                                titleAttr: 'Excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7],
                                    orthogonal: 'selected'
                                },
                                customize: function (xlsx) {
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                    $('row c[r^="A1"]', sheet).attr('s', '2').text('Horarios Generales - Excel');
                                    $('row[r="1"] c', sheet).each(function () {
                                        $(this).attr('s', '27');
                                    });
                                    var cellA1 = $('row c[r^="A1"]', sheet).text();
                                    console.log('Contenido de la celda A1:', cellA1);
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-text-o"></i>',
                                titleAttr: 'CSV',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                title: 'Horarios Generales - CSV'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"></i>',
                                titleAttr: 'PDF',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                title: 'Horarios Generales - PDF'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fa fa-print"></i>',
                                titleAttr: 'Print',
                                customize: function (win) {
                                    $(win.document.body).find('h1').text('Horarios Generales');
                                    $(win.document.body).find('table').addClass('display').addClass('compact');
                                },
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                }
                            }
                        ]
                    }
                },
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

            // Maneja la selección y el estado del checkbox principal
            $('#selectAll').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.select-row').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
                verificarEstadoMultiDelete();
            });

            $('#tblHorario').off('change', '.select-row').on('change', '.select-row', function() {
                var total = $('.select-row').length;
                var checked = $('.select-row:checked').length;
                var selectAllCheckbox = $('#selectAll');

                if (checked === total) {
                    selectAllCheckbox.prop('checked', true).prop('indeterminate', false);
                } else if (checked === 0) {
                    selectAllCheckbox.prop('checked', false).prop('indeterminate', false);
                } else {
                    selectAllCheckbox.prop('checked', false).prop('indeterminate', true);
                }

                $(this).closest('tr').toggleClass('selected', $(this).is(':checked'));
                verificarEstadoMultiDelete();
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            alert('Ocurrió un error al obtener los datos.');
        }
    });
}

function verificarEstadoMultiDelete() {
    var seleccionados = $('.select-row:checked').length > 0;
    if (seleccionados) {
        $('#multiDelete').prop('disabled', false);
        console.log('Hay elementos seleccionados');
    } else {
        $('#multiDelete').prop('disabled', true);
        console.log('No hay elementos seleccionados');
    }
}

function agregarHorarios() {
    $(document).ready(function() {
        $('#saveButton').click(function() {
            var ingreso = $('#ingreso').val();
            var salida = $('#salida').val();
            var break_entrada = $('#break_entrada').val();
            var break_salida = $('#break_salida').val();
            var descripcion = $('#descripcion').val();
            var totalHoras = $('#totalHoras').val();
            var id_sucursal = $('#id_sucursal').val();
            var id_Turnos = $('#id_Turnos').val();
            var estado = $('select[name="estado"]').val(); 
            var formData = {
                ingreso: ingreso,
                salida: salida,
                break_entrada: break_entrada,
                break_salida: break_salida,
                totalHoras:totalHoras,
                id_sucursal:id_sucursal,
                id_Turnos:id_Turnos,
                descripcion: descripcion,
                estado: estado
            };

            $.ajax({
                url: ruta + 'api/Horario',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'El Horario se ha creado con éxito.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#ingreso').val('');
                        $('#salida').val('');
                        $('#break_entrada').val('');
                        $('#break_salida').val('');
                        $('#totalHoras').val('');
                        $('#descripcion').val('');
                        $('select[name="estado"]').prop('checked', true);
                        $('#tblHorario').DataTable().destroy();
                        listar(id_sucursal);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Hubo un error al crear el Horario.',
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
        });
    });
}

function handleCsvUpload() {
    $('#CargaP').click(function() {
        console.log('Botón de carga masiva clickeado');
        // Al hacer clic en el botón, se abre el selector de archivos
        $('#userfile').click();
    });

    // Función para manejar la selección de archivo CSV
    $('#userfile').change(function() {
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
                $('#userfile').val('');
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
                    if (parts.length === 8 && parts.every(part => part.trim() !== '')) {
                        data.push({
                            ingreso: parts[0],
                            salida: parts[1],
                            break_entrada: parts[2],
                            break_salida: parts[3],
                            descripcion: parts[4],
                            estado: parts[5],
                            totalHoras: parts[6],
                            id_Turnos: parts[7]
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
        // Dentro de la función mostrarDatosEnTabla
        data.forEach(function(item, index) {
            // Definir el estado en función del valor de la columna "estado"
            var estado = item.estado == 1 ? 'Activo <i class="fa-solid fa-check"></i>' : 'Desactivado <i class="fa-solid fa-times"></i>';

            tbl.row.add([
                index + 1, // contador
                item.ingreso,
                item.salida,
                item.break_entrada,
                item.break_salida,
                item.descripcion,
                estado, // Mostrar "Activo" o "Desactivado" en lugar de 1 o 0
                item.totalHoras,
                item.id_Turnos
            ]);
        });
        tbl.draw(); // Dibujar la tabla después de agregar todas las filas
    }

    $('#btnGuardarMasivo').click(function() {
        var file = $('#userfile').prop('files')[0];
        var id_sucursal = $('#sucursal1').val();
        if (file) {
            var formData = new FormData();
            formData.append('userfile', file);
            formData.append('id_sucursal', id_sucursal);
            $.ajax({
                url: ruta + 'api/Horario/Bulkload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Verifica si la respuesta es un JSON válido
                    let success = false;
                    try {
                        response = JSON.parse(response);
                        success = response.success;
                    } catch (e) {
                        // Si no es un JSON válido, verificamos si es un texto plano
                        if (typeof response === "string" && response.includes("Se han insertado correctamente")) {
                            success = true;
                        }
                    }

                    if (success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#tblHorario').DataTable().destroy();
                        // Volver a cargar los datos y crear la tabla nuevamente
                        listar(id_sucursal);
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href="#">Why do I have this issue?</a>'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la carga masiva:', error);
                    // Mostrar mensaje de error
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

function eliminarHorario(id, idSucursal) {
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
                url: ruta + 'api/Horario/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar(); // Mostrar la alerta después de eliminar la sucursal
                    // Eliminar la fila de la tabla
                    $('#tblHorario').DataTable().row('#sucursal_' + id).remove();
                    // Destruir la tabla actual
                    $('#tblHorario').DataTable().destroy();
                    // Volver a cargar los datos y crear la tabla nuevamente
                    listar(idSucursal)
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar la sucursal:', error);
                    alert('Ocurrió un error al eliminar la sucursal.');
                }
            });
        }
    });
}

function editarHorario(id, id_sucursal) {
    // Inicializar select2 dentro de la función
    $('#id_Turnos1').select2({
        dropdownParent: $('#updateHorarios')
    });

    $.ajax({
        url: ruta + "api/Horario/" + id + "/" + id_sucursal,
        method: 'GET',
        success: function(data) {
            // Abre el modal
            $('#updateHorarios').modal('show');

            // Limpiar y actualizar las opciones del select
            $('#id_Turnos1').empty();
            $.each(data.id_Turnos, function(index, turno) {
                $('#id_Turnos1').append($('<option>', {
                    value: turno.id,
                    text: turno.Turnos
                }));
            });

            // Selecciona el primer turno por defecto
            $('#id_Turnos1').val(data.id_Turnos[0].id).trigger('change');

            // Llena los demás campos con los datos del horario
            $('#ingreso1').val(data.ingreso);
            $('#salida1').val(data.salida);
            $('#break_entrada1').val(data.break_entrada);
            $('#break_salida1').val(data.break_salida);
            $('#totalHoras1').val(data.totalHoras);
            $('#descripcion1').val(data.descripcion);

            // Verifica y selecciona el estado correcto en el dropdown
            if (data.estado == 1) {
                $('#estado1').val('1').trigger('change'); // Activo
            } else {
                $('#estado1').val('0').trigger('change'); // Desactivado
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del horario.');
        }
    });
}

function editar(id, idSucursal) {
    $('#updateButton').off('click');
    $('#id_Turnos1').select2({
        dropdownParent: $('#update')
    });
    $.ajax({
        url: ruta + "api/Horario/" + id + "/" + idSucursal,
        method: 'GET',
        success: function(data) {
            // Abre el modal
            $('#update').modal('show');

            // Limpiar y actualizar las opciones del select
            $('#id_Turnos1').empty();
            $.each(data.id_Turnos, function(index, turno) {
                $('#id_Turnos1').append($('<option>', {
                    value: turno.id,
                    text: turno.Turnos
                }));
            });

            // Selecciona el primer turno por defecto
            $('#id_Turnos1').val(data.id_Turnos[0].id).trigger('change');

            // Llena los demás campos con los datos del horario
            $('#ingreso1').val(data.ingreso);
            $('#salida1').val(data.salida);
            $('#break_entrada1').val(data.break_entrada);
            $('#break_salida1').val(data.break_salida);
            $('#totalHoras1').val(data.totalHoras);
            $('#descripcion1').val(data.descripcion);

            // Verifica y selecciona el estado correcto en el dropdown
            if (data.estado == 1) {
                $('#estado1').val('1').trigger('change'); // Activo
            } else {
                $('#estado1').val('0').trigger('change'); // Desactivado
            }
            $('#updateButton').click(function() {
                update(id, idSucursal);
            });
            $('#update').modal('show');
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del horario.');
        }
    });
}

function update(id, idSucursal) {
    var id_Turnos = $('#id_Turnos1').val();
    var ingreso = $('#ingreso1').val();
    var salida = $('#salida1').val();
    var break_entrada = $('#break_entrada1').val();
    var break_salida = $('#break_salida1').val();
    var totalHoras = $('#totalHoras1').val();
    var descripcion = $('#descripcion1').val();
    var estado = $('#estado1').val();

    var data = {
        id_Turno: id_Turnos, // Corregido a id_Turno
        ingreso: ingreso,
        salida: salida,
        break_entrada: break_entrada,
        break_salida: break_salida,
        totalHoras: totalHoras,
        descripcion: descripcion,
        estado: estado
    };
    
    $.ajax({
        url: ruta + "api/Horario/" + id + "/" + idSucursal,
        method: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Horario actualizada con éxito',
                showConfirmButton: false,
                timer: 1500
            });
            $('#update').modal('hide');
            $('#tblHorario').DataTable().destroy();
            listar(idSucursal)
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
        var tblHorario = $('#tblHorario').DataTable();
        tblHorario.destroy();
        listar(idSucursal);
        buscarTurnos(idSucursal);
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

function buscarTurnos(idSucursal) {
    $('#id_Turnos').select2({
        dropdownParent: $('#verticalycentered')
    });
    $.ajax({
        url: ruta + 'api/Trunos-Estados/'+ idSucursal,
        dataType: 'json',
        success: function(data) {
            $('#id_Turnos').empty(); // Limpia los elementos anteriores antes de agregar nuevos
            $.each(data.data, function(index, item) {
                $('#id_Turnos').append($('<option>', {
                    value: item.id,
                    text: item.Turno
                }));
            });
            $('#id_Turnos').trigger('change'); // Activa el evento change después de actualizar las opciones
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error al obtener los datos:', errorThrown);
        }
    });
}

function obtenerIdsSeleccionados() {
    var selectedIds = [];
    $('.select-row:checked').each(function() {
        selectedIds.push($(this).val());
    });
    console.log('IDs seleccionados:', selectedIds);
    return selectedIds;
}

function eliminarHorarios() {
    var idsSeleccionados = obtenerIdsSeleccionados();
    if (idsSeleccionados.length > 0) {
        Swal.fire({
            title: "¿Eliminacion Masiva?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ruta + 'api/horarios/delete',  // Asegúrate de que esta ruta es correcta
                    type: 'DELETE',
                    contentType: 'application/json',
                    data: JSON.stringify({ ids: idsSeleccionados }),
                    success: function(response) {
                        mostrarAlertaEliminar(); 
                        $('#tblHorario').DataTable().destroy();
                        listar($('#sucursal').val());
                        $('.select-row').prop('checked', false).closest('tr').removeClass('selected');

                        // Actualizar estado del checkbox principal
                        $('#selectAll').prop('checked', false).prop('indeterminate', false);  
                        verificarEstadoMultiDelete();   
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al eliminar los horarios:', error);
                        Swal.fire({
                            title: "Error",
                            text: "Ocurrió un error al eliminar los horarios.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: "¡Error!",
            text: "Por favor, selecciona al menos un horario para eliminar.",
            icon: "error"
        });
    }
}