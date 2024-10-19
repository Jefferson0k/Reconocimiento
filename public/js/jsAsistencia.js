var ruta = 'http://localhost:8080/';

function obtenerFechas() {
    return {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val()
    };
}

function inicializarComponentes() {
    $('#id_sucursal').on('change', function() {
        var idSucursal = $(this).val();
        
        var fechas = obtenerFechas(); // Obtener fechas aquí
        if (fechas.fecha_inicio && fechas.fecha_fin) {
            listar(idSucursal, fechas.fecha_inicio, fechas.fecha_fin);
        }
    });

    $.ajax({
        url: ruta + 'api/getUserInfo',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var idSucursal = response.id_sucursal;
            var cargo = response.cargo;
            cargarTodasLasSucursales(idSucursal, cargo);
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener la información del usuario:', errorThrown);
        }
    });
}

function cargarTodasLasSucursales(idSucursalUsuario, cargoUsuario) {
    $.ajax({
        url: ruta + 'api/Sucursalesv',
        dataType: 'json',
        success: function(data) {
            $('#id_sucursal').empty();

            if (cargoUsuario == 1) { // Super Administrador
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
                    if (item.id == idSucursalUsuario) {
                        $('#id_sucursal').append($('<option>', {
                            value: item.id,
                            text: item.direccion
                        }));
                    }
                });
            }

            // Seleccionar automáticamente la sucursal del usuario
            $('#id_sucursal').val(idSucursalUsuario).trigger('change');
            $('#id_sucursal').select2();
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error al obtener los datos de las sucursales:', errorThrown);
        }
    });

    $('#fecha_inicio, #fecha_fin').on('change', function() {
        var idSucursal = $('#id_sucursal').val();
        var fechas = obtenerFechas();
        if (fechas.fecha_inicio && fechas.fecha_fin) {
            listar(idSucursal, fechas.fecha_inicio, fechas.fecha_fin);
        }
    });

    $('#id_sucursal').on('change', function() {
        var idSucursal = $(this).val();
        var fechas = obtenerFechas();
        if (fechas.fecha_inicio && fechas.fecha_fin) {
            listar(idSucursal, fechas.fecha_inicio, fechas.fecha_fin);
        }
        
        if ($.fn.DataTable.isDataTable('#tblTGenerales')) {
            $('#tblTGenerales').DataTable().destroy();
        }
        $('#tblTGenerales tbody').empty();
        $('#tblTGenerales').DataTable({
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
        // Destruir la tabla de detalles y vaciar su contenido
        if ($.fn.DataTable.isDataTable('#tblTDetalles')) {
            $('#tblTDetalles').DataTable().destroy();
        }
        $('#tblTDetalles tbody').empty();
        $('#tblTDetalles').DataTable({
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
    });
}

function listar(idSucursal, fecha_inicio, fecha_fin) {
    $('#sucursal').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    var contador = 1;
    var postData = {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        id_sucursal: idSucursal
    };

    $.ajax({
        url: ruta + 'api/Reportes-General/' + idSucursal,
        type: 'POST',
        dataType: 'json',
        data: postData,
        success: function(response) {
            $('#tblTGenerales').DataTable().destroy();
            $('#tblTGenerales tbody').empty();
            $.each(response.data, function(index, trabajador) {
                $('#tblTGenerales tbody').append(
                    '<tr class="fila-tabla" data-id="' + trabajador.id + '">' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + trabajador.NombreCompleto + '</td>' +
                    '<td>' + trabajador.dias_laborados + '</td>' +
                    '<td>' + trabajador.dias_no_laborados + '</td>' +
                    '<td>' + trabajador.total_horas_trabajadas + '</td>' +
                    '<td>' + trabajador.total_horas_extras + '</td>' +
                    '<td>' + trabajador.total_horas_tardanzas + '</td>' +
                    '<td>' + trabajador.total_tardanza_break + '</td>' +
                    '<td>' + trabajador.total_horas_no_trabajadas + '</td>' +
                    '</tr>'
                );
                console.log('ID de la consulta:', trabajador.id);
            });
            $('#tblTGenerales').DataTable({
                responsive: true,
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
                                    $('row c[r^="A1"]', sheet).attr('s', '2').text('Sucursales Generales - Excel');
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
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7],
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
                                    columns: [0, 1, 2, 3,  4, 5, 6, 7],
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
                                    columns: [0, 1, 2, 3,  4, 5, 6, 7]
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

            $('#tblTGenerales tbody').off('click', 'tr').on('click', 'tr', function() {
                $('#tblTGenerales tbody tr').removeClass('fila-seleccionada');
                $(this).addClass('fila-seleccionada');
                var id_trabajador = $(this).data('id');

                var fechas = obtenerFechas();
                if (fechas.fecha_inicio && fechas.fecha_fin) {
                    listarDetalle(idSucursal, id_trabajador, fechas.fecha_inicio, fechas.fecha_fin);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor selecciona una fecha de inicio y una fecha de fin.'
                    });
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.error 
            });
        }
    });
}

function listarDetalle(idSucursal, id_trabajador, fecha_inicio, fecha_fin) {
    $('#sucursal').val(idSucursal);
    $('#sucursal1').val(idSucursal);
    var contador = 1;
    var postData = {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        id_trabajador: id_trabajador,
        id_sucursal: idSucursal
    };

    $.ajax({
        url: ruta + 'api/Reportes/' + idSucursal,
        type: 'POST',
        dataType: 'json',
        data: postData,
        success: function(response) {
            console.log(response); // Para verificar los datos devueltos por el servidor

            // Verificar si la respuesta tiene datos
            if (response.data && response.data.length > 0) {
                // Destruir la instancia de DataTables si ya existe
                if ($.fn.DataTable.isDataTable('#tblTDetalles')) {
                    $('#tblTDetalles').DataTable().destroy();
                    $('#tblTDetalles tbody').empty(); // Vaciar la tabla
                }

                // Llenar la tabla con los datos recibidos
                $.each(response.data, function(index, trabajador) {
                    if (trabajador && trabajador.id_trabajador) { // Validar los datos
                        $('#tblTDetalles tbody').append(
                            '<tr>' +
                            '<td>' + contador++ + '</td>' +
                            '<td>' + (trabajador.fecha || '') + '</td>' +
                            '<td>' + (trabajador.id_trabajador.NombreCompleto || '') + '</td>' +
                            '<td>' + (trabajador.id_trabajador.dni || '') + '</td>' +
                            '<td>' + (trabajador.hora_entrada || '') + '</td>' +
                            '<td>' + (trabajador.break_inicio || '') + '</td>' +
                            '<td>' + (trabajador.break_final || '') + '</td>' +
                            '<td>' + (trabajador.hora_salida || '') + '</td>' +
                            '<td>' + (trabajador.horas_trabajadas || '') + '</td>' +
                            '<td>' + (trabajador.horas_extras || '') + '</td>' +
                            '</tr>'
                        );
                    }
                });
            } else {
                // Si no hay datos, destruir la tabla y mostrar un mensaje vacío
                if ($.fn.DataTable.isDataTable('#tblTDetalles')) {
                    $('#tblTDetalles').DataTable().destroy();
                    $('#tblTDetalles tbody').empty();
                }

                // Agregar una fila vacía como mensaje
                $('#tblTDetalles tbody').append('<tr><td colspan="10">No hay datos disponibles</td></tr>');
            }

            // Inicializar DataTables
            $('#tblTDetalles').DataTable({
                responsive: true,
                dom: 'Bfrtip', // Añadir para que los botones funcionen
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Exportar a Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Ajusta según las columnas visibles
                            orthogonal: 'selected' // Exportar solo filas seleccionadas
                        },
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="A1"]', sheet).attr('s', '2').text('Asistencia');
                            $('row[r="1"] c', sheet).each(function () {
                                $(this).attr('s', '27');
                            });
                            
                            var cellA1 = $('row c[r^="A1"]', sheet).text();
                            console.log('Contenido de la celda A1:', cellA1);
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'Exportar a PDF',
                        title: 'Asistencia Detallada',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Ajusta según las columnas visibles
                            modifier: {
                                selected: true
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Imprimir',
                        customize: function (win) {
                            $(win.document.body).find('h1').text('Asistencia');
                            $(win.document.body).find('table').addClass('display').addClass('compact');
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Ajusta según las columnas visibles
                        }
                    }
                ],
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.error 
            });
        }
    });
}