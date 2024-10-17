<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<script src="<?= base_url('js/jsSucursal.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    listar();
    handleCsvUpload();
    agregarSucursal();
    $('#multiDelete').click(function() {
        eliminarHorarios();
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
            <h5 class="card-title">Sucursal</h5>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#CategoriaRegistro"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                            <button type="button" id="multiDelete" class="btn btn-danger" disabled><i
                                    class="fa-solid fa-trash"></i>
                                Borrar</button>
                        </div>
                        <div class="col-md-4">
                            <p></p>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalPrevisualizacion"
                                class="btn btn-success ms-auto"
                                style="background: #6366F1; border: 1px solid #6366F1;"><i class="fa-solid fa-plus"></i>
                                Importacion</button>
                            <button id="DescargarPlanilla" type="button" class="btn btn-success ms-auto"
                                style="background: #0a7d20; border: 1px solid #0a7d20;" onclick="descargarArchivo()">
                                <i class="fa-solid fa-download" id="downloadIcon"></i> Plantilla (.csv)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <!-- Table with stripped rows -->
            <table id="tblSucursales" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 1%;"><input class="form-check-input" type="checkbox" value="" id="selectAll">
                        </th>
                        <th style="width: 1%;">#</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th style="width: 1%;">Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width: 1%;">
                            </thstyle=>
                        <th style="width: 1%;">#</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th style="width: 1%;">Estado</th>
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

<div class="modal fade" id="CategoriaRegistro" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6366F1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registro de Sucursales</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <form id="formularioCategoria">
                    <div class="col-md-12">
                        <label for="nombre" class="form-label" style="color: #000000;">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <span class="error-message" id="nombreError" style="color: red; display: none;">Corrija el
                            nombre (solo letras).</span>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <label for="direccion" class="form-label" style="color: #000000;">Direccion</label>
                        <textarea class="form-control" id="direccion" name="direccion" style="height: 75px"></textarea>
                        <span class="error-message" id="direccionError" style="color: red; display: none;">La dirección
                            es obligatoria.</span>
                    </div>
                    <br>
                    <div class="col-sm-10">
                        <label for="inputPassword" class="form-label" style="color: #000000;">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" id="estado1" value="1" checked>
                            <label class="form-check-label" for="estado1">
                                Activo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" id="estado0" value="0">
                            <label class="form-check-label" for="estado0">
                                Desactivo
                            </label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6366F1;"
                    onclick="this.style.borderColor='#6366F1';">
                    <i class="fa-solid fa-times"></i> <b>Cancelar</b>
                </span>
                <span id="saveButton" class="btn" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-check"></i> <b>Registrar</b>
                </span>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="update" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6366F1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actualizar Sucursales</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="overflow-y: auto;">
                <form id="editForm">
                    <div class="col-md-12">
                        <label for="validationDefault01" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="validationDefault01" name="nombre" required>
                    </div>
                    <div class="col-md-12">
                        <label for="inputPassword" class="form-label">Direccion</label>
                        <textarea class="form-control" id="inputPassword" name="direccion"
                            style="height: 75px"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label for="gridRadios1" class="form-label">Estado</label>
                        <select class="form-select" id="gridRadios1" aria-label="Default select example">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-times"></i> <b>Cancelar</b>
                </button>
                <button type="button" id="updateButton" class="btn" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-check"></i> <b>Actualizar</b>
                </button>
            </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPrevisualizacion" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                <div class="col-lg-12">
                    <br>
                    <table id="tblPreviu" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Direccion</th>
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
                <!-- Botón "Save" en el modal -->
                <span id="btnGuardarMasivo" class="btn" style="margin-left: 10px; color: #6366F1;">
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