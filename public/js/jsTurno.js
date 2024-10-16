function listar(idSucursal) {
    var apiUrl = ruta + 'api/Trunos/' + idSucursal;
    var contador = 1; // Inicializamos el contador
    $('#sucursal').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblTurnos tbody').empty();

            $.each(response.data, function(index, turnos) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (turnos.estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                var acciones = '<td style="text-align: center;">' +
                    '<button type="button" onclick="editar(' + turnos.id + ', ' + idSucursal + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                    '<button type="button" onclick="eliminar(' + turnos.id + ', ' + idSucursal + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                    '</td>';

                $('#tblTurnos tbody').append(
                    '<tr>' +
                    '<td><input type="checkbox" class="select-row form-check-input" value="' + turnos.id + '"></td>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + turnos.Turno + '</td>' +
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Destruye la tabla actual y vuelve a inicializarla con los botones de exportación
            $('#tblTurnos').DataTable().destroy();
            $('#tblTurnos').DataTable({
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
                                    columns: [0, 1, 2], // Índices de las columnas que deseas exportar (0, 1, 2 son ejemplos)
                                    orthogonal: 'selected' // Opción para exportar solo filas seleccionadas
                                },
                                customize: function (xlsx) {
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                    // Añadir un título a la hoja de Excel
                                    $('row c[r^="A1"]', sheet).attr('s', '2').text('Turnos Generales - Excel');
                                    $('row[r="1"] c', sheet).each(function () {
                                        $(this).attr('s', '27');
                                    });
                            
                                    // Imprimir el contenido de la celda A1 en la consola
                                    var cellA1 = $('row c[r^="A1"]', sheet).text();
                                    console.log('Contenido de la celda A1:', cellA1);
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-text-o"></i>',
                                titleAttr: 'CSV',
                                exportOptions: {
                                    columns: [0, 1, 2],
                                    modifier: {
                                        selected: true
                                    }
                                },
                                title: 'Turnos Generales - CSV'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"></i>',
                                titleAttr: 'PDF',
                                exportOptions: {
                                    columns: [0, 1, 2],
                                    modifier: {
                                        selected: true
                                    }
                                },
                                title: 'Turnos Generales - PDF'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fa fa-print"></i>',
                                titleAttr: 'Print',
                                customize: function (win) {
                                    $(win.document.body).find('h1').text('Turnos Generales');
                                    $(win.document.body).find('table').addClass('display').addClass('compact');
                                },
                                exportOptions: {
                                    columns: [0, 1, 2]
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
            $('#selectAll').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.select-row').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
                verificarEstadoMultiDelete();
            });
            $('#tblTurnos').off('change', '.select-row').on('change', '.select-row', function() {
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

function buscarSucursal() {
    $('#id_sucursal').select2();
    $('#id_sucursal').on('change', function() {
        var idSucursal = $(this).val();
        var tblHorario = $('#tblTurnos').DataTable();
        tblHorario.destroy();
        listar(idSucursal);
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

function agregar() {
    var Turno = $('#Turno').val();
    var id_sucursal = $('#id_sucursal').val();
    var estado = $('#estado').val();

    var formData = new FormData();
    formData.append('Turno', Turno);
    formData.append('id_sucursal', id_sucursal);
    formData.append('estado', estado);

    console.log('Datos del formulario:', {
        Turno: Turno,
        id_sucursal: id_sucursal,
        estado: estado
    });

    $.ajax({
        url: ruta + 'api/Trunos',
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
                    title: 'El Turno se ha creado con éxito.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#Turno').val('');
                
                // Guarda una referencia a la tabla antes de destruirla
                var tblTurnos = $('#tblTurnos').DataTable();

                // Destruye la tabla DataTable
                tblTurnos.destroy();

                // Llama a la función listar después de reinicializar la tabla
                listar(id_sucursal);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un problema al agregar el turno.',
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

function editar(id, idSucursal) {
    $('#updateButton').off('click');
    $.ajax({
        url: ruta + "api/Trunos/" + id + "/" + idSucursal,
        method: 'GET',
        success: function(data) {
            // Abre el modal
            $('#update').modal('show');
            // Llena los demás campos con los datos del horario
            $('#Turno1').val(data.Turno);
            $('#estado1').val(data.estado);
            
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
    var Turno = $('#Turno1').val();
    var estado = $('#estado1').val();

    var data = {
        Turno: Turno, // Corregido a id_Turno
        estado: estado
    };
    
    $.ajax({
        url: ruta + "api/Trunos/" + id + "/" + idSucursal,
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
            $('#tblTurnos').DataTable().destroy();
            listar(idSucursal)
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al actualizar el cargo.');
        }
    });
}

function eliminarTurnos(idSucursal) {
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
                    url: ruta + 'api/Trunos/delete',  // Asegúrate de que esta ruta es correcta
                    type: 'DELETE',
                    contentType: 'application/json',
                    data: JSON.stringify({ ids: idsSeleccionados, idSucursal: idSucursal }),
                    success: function(response) {
                        mostrarAlertaEliminar(); 
                        $('#tblTurnos').DataTable().destroy();
                        listar(idSucursal);
                        $('.select-row').prop('checked', false).closest('tr').removeClass('selected');
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

function eliminar(id, idSucursal){
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
                url: ruta + 'api/Trunos/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar();
                    $('#tblTurnos').DataTable().row('#sucursal_' + id).remove();
                    $('#tblTurnos').DataTable().destroy();
                    listar(idSucursal)
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el turno:', error);
                    alert('Ocurrió un error al eliminar el turno.');
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
                    if (parts.length === 2 && parts.every(part => part.trim() !== '')) {
                        data.push({
                            Turno: parts[0],
                            estado: parts[1]
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
                item.Turno,
                estado
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
                url: ruta + 'api/Trunos/Bulkload',
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
                        $('#tblTurnos').DataTable().destroy();
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

function obtenerIdsSeleccionados() {
    var selectedIds = [];
    $('.select-row:checked').each(function() {
        selectedIds.push($(this).val());
    });
    console.log('IDs seleccionados:', selectedIds);
    return selectedIds;
}