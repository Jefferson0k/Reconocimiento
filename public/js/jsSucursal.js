function listar() {
    var apiUrl = ruta + 'api/Sucursal';
    var contador = 1; // Inicializamos el contador

    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblSucursales tbody').empty();

            $.each(response.data, function(index, sucursal) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (sucursal.estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                var acciones = '<td style="text-align: center;">' +
                    '<button type="button" onclick="Editar(' + sucursal.id + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                    '<button type="button" onclick="eliminarSucursal(' + sucursal.id + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                    '</td>';

                $('#tblSucursales tbody').append(
                    '<tr>' +
                    '<td><input type="checkbox" class="select-row form-check-input" value="' + sucursal.id + '"></td>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + sucursal.nombre + '</td>' +
                    '<td>' + sucursal.direccion + '</td>' +
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Destruye la tabla actual y vuelve a inicializarla con los botones de exportación
            $('#tblSucursales').DataTable().destroy();
            $('#tblSucursales').DataTable({
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
                                    columns: [0, 1, 2, 3], // Índices de las columnas que deseas exportar (0, 1, 2 son ejemplos)
                                    orthogonal: 'selected' // Opción para exportar solo filas seleccionadas
                                },
                                customize: function (xlsx) {
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                    // Añadir un título a la hoja de Excel
                                    $('row c[r^="A1"]', sheet).attr('s', '2').text('Sucursales Generales - Excel');
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
                                    columns: [0, 1, 2, 3],
                                    modifier: {
                                        selected: true
                                    }
                                },
                                title: 'Sucursales Generales - CSV'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"></i>',
                                titleAttr: 'PDF',
                                exportOptions: {
                                    columns: [0, 1, 2, 3],
                                    modifier: {
                                        selected: true
                                    }
                                },
                                title: 'Sucursales Generales - PDF'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fa fa-print"></i>',
                                titleAttr: 'Print',
                                customize: function (win) {
                                    $(win.document.body).find('h1').text('Sucursales Generales');
                                    $(win.document.body).find('table').addClass('display').addClass('compact');
                                },
                                exportOptions: {
                                    columns: [0, 1, 2, 3] // Índices de las columnas que deseas imprimir (0, 1, 2 son ejemplos)
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

            $('#tblSucursales').off('change', '.select-row').on('change', '.select-row', function() {
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

function guardarCambios(id) {
    var nombre = $('#validationDefault01').val();
    var direccion = $('#inputPassword').val();
    var estado = $('input[name="estado"]:checked').val();
    $.ajax({
        url: ruta + "api/Sucursal/" + id,
        type: 'PUT',
        dataType: 'json',
        data: {
            nombre: nombre,
            direccion: direccion,
            estado: estado
        },
        success: function(response) {
            if (response.message) {
                alert(response.message);
                listar(); // Actualiza la lista después de la actualización
                $('#SucursalEditar').modal('hide');
            } else {
                alert('Error al actualizar la sucursal.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar la sucursal:', error);
            alert('Ocurrió un error al actualizar la sucursal.');
        }
    });
}

function Editar(id) {
    $('#updateButton').off('click'); // Desvincular eventos click previos
    $.ajax({
        url: ruta + "api/Sucursal/" + id,
        method: 'GET',
        success: function(data) {
            console.log(data); // Verificar los datos recibidos
            $('#validationDefault01').val(data.nombre);
            $('#inputPassword').val(data.direccion);
            $('#gridRadios1').val(data.estado);

            $('#updateButton').click(function() {
                update(id);
            });
            $('#update').modal('show');
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del cargo.');
        }
    });
}

function update(id) {
    var nombre = $('#validationDefault01').val();
    var direccion = $('#inputPassword').val();
    var estado = $('#gridRadios1').val();

    console.log('Estado seleccionado:', estado); // Añadir esta línea para depuración

    var data = {
        nombre: nombre,
        direccion: direccion,
        estado: estado
    };

    $.ajax({
        url: ruta + "api/Sucursal/" + id,
        method: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Sucursal actualizada con éxito',
                showConfirmButton: false,
                timer: 1500
            });
            $('#update').modal('hide');
            $('#tblSucursales').DataTable().destroy();
            listar();
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al actualizar el cargo.');
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

function eliminarSucursal(id) {
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
                url: ruta + 'api/Sucursal/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar(); // Mostrar la alerta después de eliminar la sucursal
                    // Eliminar la fila de la tabla
                    $('#tblSucursales').DataTable().row('#sucursal_' + id).remove();
                    // Destruir la tabla actual
                    $('#tblSucursales').DataTable().destroy();
                    // Volver a cargar los datos y crear la tabla nuevamente
                    listar();
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar la sucursal:', error);
                    alert('Ocurrió un error al eliminar la sucursal.');
                }
            });
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
                    if (parts.length === 3 && parts.every(part => part.trim() !== '')) {
                        data.push({
                            nombre: parts[0],
                            direccion: parts[1],
                            estado: parts[2]
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
                item.nombre,
                item.direccion,
                item.estado
            ]);
        });
        tbl.draw(); // Dibujar la tabla después de agregar todas las filas
    }

    $('#btnGuardarMasivo').click(function() {
        var file = $('#fileInput').prop('files')[0];
        if (file) {
            var formData = new FormData();
            formData.append('userfile', file);
            $.ajax({
                url: ruta + 'api/Sucursal/Bulkload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#tblSucursales').DataTable().destroy();
                    // Volver a cargar los datos y crear la tabla nuevamente
                    listar();
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

function agregarSucursal() {
    $(document).ready(function() {
        // Función para manejar el envío del formulario al hacer clic en Save
        $('#saveButton').click(function() {
            // Obtener los valores de los campos del formulario
            var nombre = $('#nombre').val();
            var direccion = $('#direccion').val();
            var estado = $('input[name="estado"]:checked').val(); // Obtener el valor del radio button seleccionado
            
            // Validar los campos
            var isValid = true;
            
            // Validar el campo nombre (solo letras)
            var nombreRegex = /^[a-zA-Z\s]+$/;
            if (!nombreRegex.test(nombre)) {
                $('#nombreError').show();
                isValid = false;
            } else {
                $('#nombreError').hide();
            }

            // Validar el campo direccion (no vacío)
            if (direccion.trim() === '') {
                $('#direccionError').show();
                isValid = false;
            } else {
                $('#direccionError').hide();
            }

            // Si la validación falla, no enviar el formulario
            if (!isValid) {
                return;
            }

            // Crear un objeto con los datos del formulario
            var formData = {
                nombre: nombre,
                direccion: direccion,
                estado: estado
            };

            // Enviar los datos al controlador mediante AJAX
            $.ajax({
                url: ruta + 'api/Sucursal',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Manejar la respuesta del servidor
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'La sucursal se ha creado con éxito.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Limpiar el formulario después de la inserción exitosa
                        $('#nombre').val('');
                        $('#direccion').val('');
                        $('input[name="estado"][value="1"]').prop('checked', true); // Establecer estado activo por defecto
                        $('#tblSucursales').DataTable().destroy();
                        // Volver a cargar los datos y crear la tabla nuevamente
                        listar();
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Hubo un error al crear la sucursal.',
                            footer: '<a href="#">¿Por qué tengo este problema?</a>'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al enviar el formulario:', error);
                    // Mostrar mensaje de error genérico
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al enviar el formulario.',
                        footer: '<a href="#">¿Por qué tengo este problema?</a>'
                    });
                }
            });
        });

        // Eventos de cambio para validar en tiempo real
        $('#nombre').on('input', function() {
            var nombre = $(this).val();
            var nombreRegex = /^[a-zA-Z\s]+$/;
            if (!nombreRegex.test(nombre)) {
                $('#nombreError').show();
            } else {
                $('#nombreError').hide();
            }
        });

        $('#direccion').on('input', function() {
            var direccion = $(this).val();
            if (direccion.trim() === '') {
                $('#direccionError').show();
            } else {
                $('#direccionError').hide();
            }
        });
    });
}

function descargarArchivo() {
    var icon = document.getElementById('downloadIcon');
    var originalIconClass = icon.className;
    icon.className = 'fa-solid fa-spinner fa-spin';
    var link = document.createElement('a');
    link.href = ruta + 'FormatoCSV/Sucursales Generales - CSV.csv';
    link.download = 'Plantilla.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    setTimeout(function() {
        icon.className = originalIconClass;
    }, 3000);
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
                    url: ruta + 'api/Sucursal/delete',  // Asegúrate de que esta ruta es correcta
                    type: 'DELETE',
                    contentType: 'application/json',
                    data: JSON.stringify({ ids: idsSeleccionados }),
                    success: function(response) {
                        mostrarAlertaEliminar(); 
                        $('#tblSucursales').DataTable().destroy();
                        listar();
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