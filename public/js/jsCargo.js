function agregar() {
    const nombre = document.getElementById('Nombre').value;
    const estado = document.getElementById('Estado').value;
    const idPaginasSeleccionadas = [];

    // Obtener IDs de página seleccionados
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked && checkbox.value !== "") {
            idPaginasSeleccionadas.push(...checkbox.value.split(',').map(id => id.trim()).filter(id => id !== ''));
        }
    });

    if (idPaginasSeleccionadas.length === 0) {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'Debe seleccionar al menos un acceso',
            showConfirmButton: false,
            timer: 1500
        });
        return;
    }

    const data = {
        Nombre: nombre,
        Estado: estado,
        idPaginas: idPaginasSeleccionadas
    };

    fetch(ruta + 'api/Cargo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Manejar la respuesta del servidor, si es necesario
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'El Cargo ha sido guardado',
            showConfirmButton: false,
            timer: 1500
        });
        $('#tblCargos').DataTable().destroy();
        listar();
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Error al enviar los datos',
            showConfirmButton: false,
            timer: 1500
        });
    });
}


function listar() {
    var apiUrl = ruta + 'api/Cargo-vista';
    var contador = 1; // Inicializamos el contador

    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#tblCargos tbody').empty();

            $.each(response.data, function(index, cargo) {
                var estadoIcono = '';
                var estadoTexto = '';

                if (cargo.Estado == 1) {
                    estadoIcono = '<i class="fa-solid fa-check" style="color: green;"></i>';
                    estadoTexto = 'Activo';
                } else {
                    estadoIcono = '<i class="fa-solid fa-times" style="color: red;"></i>';
                    estadoTexto = 'Inactivo';
                }

                var acciones = '<td style="text-align: center;">' +
                '<button type="button" onclick="editarCargo(' + cargo.Id + ')" title="Editar" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-edit"></i></button>' +
                '<button type="button" onclick="eliminarCargo(' + cargo.Id + ')" title="Eliminar" class="btn btn-sm btn-danger" style="margin-right: 5px;"><i class="fa-solid fa-trash"></i></button>' +
                '<button type="button" onclick="Configuraciones(' + cargo.Id + ')" title="Ajustes" class="btn btn-sm btn-dark" style="margin-right: 5px;"><i class="fa-solid fa-gear"></i></button>' +
                '</td>';

                $('#tblCargos tbody').append(
                    '<tr>' +
                    '<td>' + contador++ + '</td>' +
                    '<td>' + cargo.Nombre + '</td>' +
                    '<td>' + estadoTexto + ' ' + estadoIcono + '</td>' +
                    acciones +
                    '</tr>'
                );
            });

            // Destruye la tabla actual y vuelve a inicializarla con los botones de exportación
            $('#tblCargos').DataTable().destroy();
            $('#tblCargos').DataTable({
                responsive: true,
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
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            alert('Ocurrió un error al obtener los datos.');
        }
    });
    
}

function editarCargo(Id) {
    $('#updateButton').off('click'); // Desvincular eventos click previos
    $.ajax({
        url: ruta + "api/Cargo/" + Id,
        method: 'GET',
        success: function(data) {
            console.log(data); // Añadir esta línea para verificar los datos recibidos
            $('#Nombre1').val(data.Nombre);
            $('#Estado1').val(data.Estado);
            $('#updateButton').click(function() {
                updateCargo(Id);
            });
            $('#updateCargos').modal('show');
        },        
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al obtener los datos del cargo.');
        }
    });
}

function updateCargo(Id) {
    var Nombre = $('#Nombre1').val();
    var Estado = $('#Estado1').val();

    var data = {
        Nombre: Nombre,
        Estado: Estado
    };

    $.ajax({
        url: ruta + "api/Cargo/" + Id,
        method: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Cargo actualizada con éxito',
                showConfirmButton: false,
                timer: 1500
            });
            $('#updateCargos').modal('hide');
            $('#tblCargos').DataTable().destroy();
            listar();
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
            alert('Ocurrió un error al actualizar el cargo.');
        }
    });
}

function eliminarCargo(Id) {
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
                url: ruta + 'api/Cargo/' + Id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    mostrarAlertaEliminar(); // Mostrar la alerta después de eliminar la sucursal
                    // Eliminar la fila de la tabla
                    $('#tblCargos').DataTable().row('#sucursal_' + Id).remove();
                    // Destruir la tabla actual
                    $('#tblCargos').DataTable().destroy();
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