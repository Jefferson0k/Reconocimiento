<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/face-api.js/3.0.0/face-api.min.js"></script>
<script src="<?= base_url('js/jsUsuarios.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    buscarSucursal();
    buscarCargos();
    inicializarSelectores();
    configurarEventos();
    buscarCargosActualizar();
    $('#clave').on('input', function() {
        actualizarFortalezaClave();
    });
});
</script>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Usuarios Asignados Por Sucursales</h5>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#RegistroUsuarios"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                        </div>
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
            <!-- Table with stripped rows -->
            <table id="tblUsuarios" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Usuario</th>
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

<div class="modal fade" id="RegistroUsuarios" tabindex="-1">
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4f9406;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registros de Usuarios</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormUser">
                    <input type="hidden" name="id_sucursal" id="sucursal1">
                    <input type="hidden" name="nombre" id="nombre">
                    <input type="hidden" name="administrador" id="administrador" value="0">
                    <div class="col-lg-12">
                        <label for="cargo" class="form-label">Cargo</label>
                        <select class="form-control" name="id_cargo" id="id_cargo" style="width: 100%;">
                            <option value="" selected disabled>Seleccione un Cargo</option>
                        </select>
                    </div>
                    <div class="col-lg-12" id="nombreContainer" style="display: none;">
                        <br>
                        <label for="id_trabajador" class="form-label">Nombre</label>
                        <div class="input-group has-validation">
                            <select class="form-control" name="id_trabajador" id="id_trabajador" style="width: 100%;">
                                <option value="" selected disabled>Seleccione un Trabajador</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <br>
                        <label for="login" class="form-label">User</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i
                                    class="fa-solid fa-newspaper"></i></span>
                            <input type="text" name="login" class="form-control" id="login1" readonly>
                            <button type="button" class="btn btn-warning" id="generateUserCode">Generar</button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <br>
                        <label for="clave" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i
                                    class="fa-solid fa-newspaper"></i></span>
                            <input type="password" name="clave" class="form-control" id="clave">
                        </div>
                        <div class="mt-2">
                            <div class="progress">
                                <div id="password-strength-progress" class="progress-bar" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <br>
                        <label for="Estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Desactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #4f9406;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="saveButton" class="btn" style="margin-left: 10px; color: #4f9406;" onclick="agregar()">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6e3284;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actualizar Usuario</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <form id="formUpdate">
                <input type="hidden" id="id_sucursal1">
                    <div class="col-md-12">
                        <label for="nombre1" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre1">
                    </div>
                    <div class="col-md-12">
                        <label for="login3" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login3">
                    </div>
                    <div class="col-lg-12">
                        <label for="id_cargo3" class="form-label">Cargo</label>
                        <select class="form-select"  id="id_cargo1" aria-label="Default select example">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i
                                    class="fa-solid fa-newspaper"></i></span>
                            <input type="password" class="form-control" id="clave1">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="estado1" class="form-label">Estado</label>
                        <select class="form-select" id="estado1" aria-label="Default select example">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6e3284;"
                    onclick="this.style.borderColor='#6e3284';">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </span>
                <span id="updateButton" class="btn" style="margin-left: 10px; color: #6e3284;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
            </form>
        </div>
    </div>
</div>