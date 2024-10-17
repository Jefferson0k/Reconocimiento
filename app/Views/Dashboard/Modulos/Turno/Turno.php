<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="<?= base_url('js/jsTurno.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    buscarSucursal();
    handleCsvUpload();
    // Example usage when a button is clicked
    $('#multiDelete').on('click', function() {
        var idSucursal = $('#id_sucursal').val();
        eliminarTurnos(idSucursal);
    });

});
</script>
<style>
.select-row {
    width: 20px;
    height: 20px;
    cursor: pointer;
    appearance: none;
    border: 2px solid #ccc;
    border-radius: 3px;
    position: relative;
    margin: 0;
    padding: 0;
    background-color: #fff;
}

.select-row:checked {
    background-color: #fff;
}

.select-row:checked::after {
    content: '\2713';
    /* Checkmark character */
    font-size: 18px;
    color: green;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Turnos Por Sucursales</h5>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#verticalycentered"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                            <button type="button" id="multiDelete" class="btn btn-danger" disabled><i
                                    class="fa-solid fa-trash"></i>
                                Eliminar</button>
                        </div>
                        <div class="col-md-3">
                            <p></p>
                        </div>
                        <div class="col-md-5 d-flex justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#largeModal"
                                class="btn btn-success ms-auto"
                                style="background: #6366F1; border: 1px solid #6366F1;"><i class="fa-solid fa-plus"></i>
                                Importar</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <select class="form-control" name="id_sucursal" id="id_sucursal" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table with stripped rows -->
            <table id="tblTurnos" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 1%;"><input class="form-check-input" type="checkbox" value="" id="selectAll">
                        </th>
                        <th style="width: 2%;">#</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width: 1%;"></th>
                        <th style="width: 2%;">#</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
            <!-- End Table with stripped rows -->
        </div>
    </div>
</div>

<div class="modal fade" id="verticalycentered" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #953292;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registros de Turnos</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ForTurno">
                    <input type="hidden" name="id_sucursal" id="id_sucursal">
                    <div class="row mb-3">
                        <label for="Turno" class="col-sm-3 col-form-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" id="Turno" name="Turno" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Estado</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="estado" name="estado" aria-label="Default select example">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #953292;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="saveButton" class="btn" onclick="agregar()" style="margin-left: 10px; color: #953292;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #953292;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actalizacion de Turnos</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ForTurno">
                    <div class="row mb-3">
                        <label for="Turno1" class="col-sm-3 col-form-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" id="Turno1" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Estado</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="estado1" aria-label="Default select example">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #953292;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="updateButton" class="btn" style="margin-left: 10px; color: #953292;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0e5084;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Carga Masiva, CSV</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <span id="CargaP" class="btn" style="margin-left: 10px; color: #0e5084; cursor: pointer;">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> <b>Cargar Masiva</b>
                    </span>
                    <input type="file" id="userfile" accept=".csv" style="display: none;">
                </div>
                <div class="col-lg-12">
                    <input type="hidden" name="id_sucursal" id="sucursal1">
                    <br>
                    <table id="tblPreviu" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #0e5084;"
                    onclick="this.style.borderColor='#0e5084';">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </span>
                <!-- Botón "Save" en el modal -->
                <span id="btnGuardarMasivo" class="btn" style="margin-left: 10px; color: #0e5084;">
                    <i class="fa-solid fa-check"></i> <b>Cargar</b>
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