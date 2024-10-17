<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="<?= base_url('js/jsHorario.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    agregarHorarios();
    handleCsvUpload();
    calcularHoras();
    buscarSucursal();
    $('#updateButton').on('click', function() {
        actualizarHorario();
    });
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
            <h5 class="card-title">Horarios Generales</h5>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#verticalycentered"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                            <button type="button" id="multiDelete" class="btn btn-danger" disabled>
                                <i class="fa-solid fa-trash"></i> Borrar
                            </button>
                        </div>
                        <div class="col-md-4">
                            <p></p>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#largeModal"
                                class="btn btn-success ms-auto"
                                style="background: #a855f7; border: 1px solid #6366F1;"><i
                                    class="fa-solid fa-upload"></i>
                                Importacion</button>
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
            <table id="tblHorario" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 1%;"><input class="form-check-input" type="checkbox" value="" id="selectAll">
                        </th>
                        <th style="width: 2%;">#</th>
                        <th>Ingreso</th>
                        <th>Break Inicio</th>
                        <th>Break Fin</th>
                        <th>Salida</th>
                        <th>Total Horas</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width: 1%;"></th>
                        <th style="width: 2%;">#</th>
                        <th>Ingreso</th>
                        <th>Break Inicio</th>
                        <th>Break Fin</th>
                        <th>Salida</th>
                        <th>Total Horas</th>
                        <th>Turno</th>
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

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
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
                    <input type="file" id="userfile" accept=".csv" style="display: none;">
                </div>
                <div class="col-lg-12">
                    <input type="hidden" name="id_sucursal" id="sucursal1">
                    <br>
                    <table id="tblPreviu" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ingreso</th>
                                <th>Salida</th>
                                <th>Break Inicio</th>
                                <th>Break Fin</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                                <th>Total Horas</th>
                                <th>Turno</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Ingreso</th>
                                <th>Salida</th>
                                <th>Break Inicio</th>
                                <th>Break Fin</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                                <th>Total Horas</th>
                                <th>Turno</th>
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

<div class="modal fade" id="verticalycentered" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6366F1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registros de Horarios</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ForHorario">
                    <input type="hidden" name="id_sucursal" id="sucursal">

                    <div class="row mb-3">
                        <label for="id_Turnos" class="col-sm-3 col-form-label">Turno</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="id_Turnos" id="id_Turnos" style="width: 100%;">
                                <option value="" selected disabled>Seleccione un Turno</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ingreso" class="col-sm-3 col-form-label">Entrada</label>
                        <div class="col-sm-9">
                            <input type="time" id="ingreso" name="ingreso" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="salida" class="col-sm-3 col-form-label">Salida</label>
                        <div class="col-sm-9">
                            <input type="time" id="salida" name="salida" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="break_entrada" class="col-sm-3 col-form-label">Break Inicio</label>
                        <div class="col-sm-9">
                            <input type="time" id="break_entrada" name="break_entrada" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="break_salida" class="col-sm-3 col-form-label">Break Fin</label>
                        <div class="col-sm-9">
                            <input type="time" id="break_salida" name="break_salida" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Total de Horas</label>
                        <div class="col-sm-9">
                            <input type="time" name="totalHoras" id="totalHoras" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripcion</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="descripcion" name="descripcion" style="height: 180px"
                                readonly></textarea>
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
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="saveButton" class="btn" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6366F1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actualizar Horarios</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateHorario">
                    <div class="row mb-3">
                        <label for="id_Turnos" class="col-sm-3 col-form-label">Turno</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="id_Turnos" id="id_Turnos1" style="width: 100%;">
                                <option value="" selected disabled>Seleccione un Turno</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ingreso" class="col-sm-3 col-form-label">Entrada</label>
                        <div class="col-sm-9">
                            <input type="time" id="ingreso1" name="ingreso" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="salida" class="col-sm-3 col-form-label">Salida</label>
                        <div class="col-sm-9">
                            <input type="time" id="salida1" name="salida" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="break_entrada" class="col-sm-3 col-form-label">Break Inicio</label>
                        <div class="col-sm-9">
                            <input type="time" id="break_entrada1" name="break_entrada" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="break_salida" class="col-sm-3 col-form-label">Break Fin</label>
                        <div class="col-sm-9">
                            <input type="time" id="break_salida1" name="break_salida" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Total de Horas</label>
                        <div class="col-sm-9">
                            <input type="text" name="totalHoras" id="totalHoras1" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripcion</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="descripcion1" name="descripcion" style="height: 150px"
                                readonly></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Estado</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="estado1" name="estado" aria-label="Default select example">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </button>
                <span id="updateButton" class="btn" style="margin-left: 10px; color: #6366F1;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
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
<script src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"></script>

<script>
function calcularHoras() {
    const ingreso = document.getElementById("ingreso").value;
    const salida = document.getElementById("salida").value;
    const breakEntrada = document.getElementById("break_entrada").value;
    const breakSalida = document.getElementById("break_salida").value;

    if (ingreso && salida && breakEntrada && breakSalida) {
        const ingresoDate = new Date(`1970-01-01T${ingreso}:00`);
        const salidaDate = new Date(`1970-01-01T${salida}:00`);
        const breakEntradaDate = new Date(`1970-01-01T${breakEntrada}:00`);
        const breakSalidaDate = new Date(`1970-01-01T${breakSalida}:00`);

        if (salidaDate < ingresoDate) {
            salidaDate.setDate(salidaDate.getDate() + 1);
        }
        if (breakSalidaDate < breakEntradaDate) {
            breakSalidaDate.setDate(breakSalidaDate.getDate() + 1);
        }

        let horasTrabajadas = (salidaDate - ingresoDate) / (1000 * 60 * 60);
        const tiempoBreak = (breakSalidaDate - breakEntradaDate) / (1000 * 60 * 60);

        horasTrabajadas -= tiempoBreak;

        const horas = Math.floor(horasTrabajadas);
        const minutos = Math.round((horasTrabajadas - horas) * 60);
        const horasTotales = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;

        const $totalHoras = document.getElementById("totalHoras");
        $totalHoras.value = horasTotales;

        const descripcion =
            `Ingreso: ${ingreso}.\nBreak inicio: ${breakEntrada} \nBreak Fin: ${breakSalida}.\nTiempo total de break: ${tiempoBreak.toFixed(2)}.\nHora de salida: ${salida}.\nTotal de horas: ${horasTotales}.`;

        const $descripcion = document.getElementById("descripcion");
        $descripcion.value = descripcion; 
    }
}

window.onload = function() {
    const $ingreso = document.getElementById("ingreso");
    const $salida = document.getElementById("salida");
    const $breakEntrada = document.getElementById("break_entrada");
    const $breakSalida = document.getElementById("break_salida");

    $ingreso.addEventListener("input", calcularHoras);
    $salida.addEventListener("input", calcularHoras);
    $breakEntrada.addEventListener("input", calcularHoras);
    $breakSalida.addEventListener("input", calcularHoras);
}
</script>