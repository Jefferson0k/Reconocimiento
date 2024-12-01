<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script src="<?= base_url('js/jsTrabajador.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    ConsultaDniTelefono();
    buscarSucursal();
    handleCsvUpload();
});
</script>
<style>
    .iti {
    width: 100%;
}

.photo-container {
    background-color: #f8f9fa;
    border: 2px solid #dee2e6;
}

.photo-container img {
    border: 1px solid #ced4da;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.error-message {
    color: red;
    font-size: 0.9em;
    display: none;
}

.is-invalid {
    border-color: red;
}
#error-alert {
    display: none;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    color: #721c24;
    font-size: 14px;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Trabajadores General</h5>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#RegistroTrabajadores"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                        </div>
                        <div class="col-md-4">
                            <p></p>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#largeModal"
                                class="btn btn-success ms-auto"
                                style="background: #6366F1; border: 1px solid #6366F1;"><i class="fa-solid fa-plus"></i>
                                Importacion</button>
                            <button id="btnExportarExcel" data-bs-toggle="modal"
                                data-bs-target="#ObservacionesTrabajadores" type="button"
                                class="btn btn-success ms-auto" style="background: #a855f7; border: 1px solid #a855f7;">
                                <i class="fa-solid fa-upload"></i> Observaciones
                            </button>

                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12">
                        <select class="form-control" name="id_sucursal" id="id_sucursal" style="width: 100%;">
                        </select>
                    </div>
                </div>

            </div>
            <!-- Table with stripped rows -->
            <table id="tblTrabajadores" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Dni</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Telefono</th>
                        <th>Ingreso</th>
                        <th>Salida</th>
                        <th>T. Horas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Dni</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Telefono</th>
                        <th>Ingreso</th>
                        <th>Salida</th>
                        <th>T. Horas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
            <!-- End Table with stripped rows -->
        </div>
    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6366F1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Carga Masiva, CSV</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <span id="CargaP" class="btn" style="margin-left: 10px; color: #6366F1; cursor: pointer;">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> <b>Cargar Masiva</b>
                    </span>
                    <input type="file" id="fileInput" accept=".csv" style="display: none;">
                </div>
                <input type="hidden" name="id_sucursal" id="sucursal1">

                <div class="col-lg-12">
                    <br>
                    <table id="tblPreviu" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dni</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Telefono</th>
                                <th>Horario</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Dni</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Telefono</th>
                                <th>Horario</th>
                                <th>Estado</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6366F1;"
                    onclick="this.style.borderColor='#6366F1';">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </span>
                <span id="btnGuardarMasivo" class="btn" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-check"></i> <b>Cargar</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="RegistroTrabajadores" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f16363;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registros de Trabajadores</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTrabajador">
                    <input type="hidden" name="id_sucursal" id="sucursal">
                    <div class="row g-3">
                        <div class="col-md-3 position-relative">
                            <label for="dni" class="form-label">Dni</label>
                            <input type="number" class="form-control" name="dni" id="dni" maxlength="8" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el DNI.
                            </div>
                        </div>
                        <div class="col-md-5 position-relative">
                            <label for="nombres" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombres" id="nombres" required disabled>
                            <div class="invalid-feedback">
                                Por favor ingrese el nombre.
                            </div>
                        </div>
                        <div class="col-md-4 position-relative">
                            <label for="Apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="Apellidos" id="Apellidos" required disabled>
                            <div class="invalid-feedback">
                                Por favor ingrese los apellidos.
                            </div>
                        </div>
                        <div class="col-md-3 position-relative">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="number" class="form-control" name="telefono" id="telefono" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el teléfono.
                            </div>
                        </div>
                        <div class="col-md-5 position-relative">
                            <label for="validationTooltip02" class="form-label">Horario</label>
                            <select class="form-control" name="id_horario" id="id_horario" style="width: 100%;" required>
                                <option value="" selected disabled>Seleccione un Horario</option>
                                <!-- Opciones del select -->
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione un horario.
                            </div>
                        </div>
                        <div class="col-md-4 position-relative">
                            <label for="validationTooltip02" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado" aria-label="Default select example" required>
                                <option value="1">Activo</option>
                                <option value="0">Desactivo</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione el estado.
                            </div>
                        </div>
                        <div class="col-md-12 position-relative">
                            <br>
                            <label for="foto" class="form-label">Foto</label>
                            <div class="input-group has-validation">
                                <div class="col-lg-3 mx-auto text-center">
                                    <img id="foto-preview" src="<?= base_url('Trabajadores/SinFoto/default.jpg') ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="pt-3">
                                    <label class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload"></i>
                                        <input accept=".jpg, .png, .jpeg" type="file" name="foto" id="foto" style="display: none;" onchange="MostrarImagen(this)">
                                    </label>
                                    <a class="btn btn-danger btn-sm" onclick="Eliminar()"><i class="fas fa-trash" style="color: white;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #f16363;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="saveButton" class="btn" onclick="agregar()" style="margin-left: 10px; color: #f16363;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ObservacionesTrabajadores" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #a855f7;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Observaciones de Trabajadores (#Faltas #Accidentes
                        Laboral) </b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FomrObservaciones">
                    <input type="hidden" name="id_Sucursal" id="sucursal2">
                    <div class="row mb-2">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Trabajadores</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="id_trabajador" id="id_trabajador" style="width: 100%;">
                                <option value="" selected disabled>Seleccione un Trabajador</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Fecha</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="fecha" id="fecha" disabled>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="Modificacion" id="Modificacion"
                                    value="0">
                                <label class="form-check-label" for="Modificacion"> Modificar Fecha ?</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="observaciones" name="observaciones"
                                style="height: 100px"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #a855f7;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="saveButton1" class="btn" style="margin-left: 10px; color: #a855f7;"
                    onclick="agregarObservaciones()">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f16363; color: #ffffff;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actualización de Trabajadores</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTrabajador" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="dni1" class="form-label">DNI</label>
                            <input type="number" class="form-control" id="dni1"required>
                            <div class="valid-tooltip">
                                ¡Se ve bien!
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="nombres1" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombres1" required disabled>
                            <div class="valid-tooltip">
                                ¡Se ve bien!
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="Apellidos1" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="Apellidos1" required disabled>
                            <div class="valid-tooltip">
                                ¡Se ve bien!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="telefono1" class="form-label">Teléfono</label>
                            <input type="number" class="form-control" id="telefono1" required>
                            <div class="valid-tooltip">
                                ¡Se ve bien!
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="id_horario1" class="form-label">Horario</label>
                            <select class="form-control" id="id_horario1" style="width: 100%;">
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="estado1" class="form-label">Estado</label>
                            <select class="form-select" id="estado1" aria-label="Default select example">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <h1></h1>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <div class="border rounded shadow-sm p-2">
                                        <img id="fotoPreview" src="" alt="Perfil" class="img-fluid rounded"
                                            style="max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <div class="border rounded shadow-sm p-2">
                                        <img id="v_Imagen" src="<?= base_url('Trabajadores/SinFoto/default.jpg')?>"
                                            alt="Perfil" class="img-fluid rounded"
                                            style="max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <!-- Botones para cambiar la foto y eliminar la foto -->
                            <div class="col-lg-12 d-flex justify-content-center align-items-center gap-2 mt-3">
                                <label class="btn btn-primary btn-sm">
                                    <i class="fas fa-upload"></i>
                                    <input accept=".jpg, .png, .jpeg" type="file" id="foto1" name="v_Imagen"
                                        style="display: none;" onchange="MostrarImagenActualizar(this)">
                                </label>
                                <a class="btn btn-danger btn-sm" onclick="EliminarActualizar()"><i class="fas fa-trash" style="color: white;"></i></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #f16363;">
                    <i class="fa-solid fa-times"></i> <b>Cancelar</b>
                </button>
                <span id="updateButton" class="btn" style="margin-left: 10px; color: #f16363;">
                    <i class="fa-solid fa-check"></i> <b>Actualizar</b>
                </span>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/responsive.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>

<script>
function MostrarImagen(input) {
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('foto-preview').setAttribute('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
}

function Eliminar() {
    document.getElementById('foto-preview').src = '<?= base_url('Trabajadores/SinFoto/default.jpg') ?>';
    document.getElementById('foto').value = '';
}
</script>

<script>
function MostrarImagenActualizar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#v_Imagen').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function EliminarActualizar() {
    $('#v_Imagen').attr('src', '<?= base_url('Trabajadores/SinFoto/default.jpg')?>');
    $('input[name="v_Imagen"]').val('');
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var input = document.querySelector("#telefono1");
    window.intlTelInput(input, {
        initialCountry: "pe",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var input = document.querySelector("#telefono");
    window.intlTelInput(input, {
        initialCountry: "pe",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
});
</script>

<script>
document.getElementById('Modificacion').addEventListener('change', function() {
    var fechaInput = document.getElementById('fecha');
    if (this.checked) {
        fechaInput.disabled = false;
    } else {
        fechaInput.disabled = true;
        // Obtener la fecha actual en el formato YYYY-MM-DD
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
        var yyyy = today.getFullYear();
        var fechaActual = yyyy + '-' + mm + '-' + dd;
        fechaInput.value = fechaActual;
    }
});

// Establecer la fecha actual al cargar la página
window.onload = function() {
    var fechaInput = document.getElementById('fecha');
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
    var yyyy = today.getFullYear();
    var fechaActual = yyyy + '-' + mm + '-' + dd;
    fechaInput.value = fechaActual;
};
</script>