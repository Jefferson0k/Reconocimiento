<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="<?= base_url('js/jsCargo.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    listar();
});
</script>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Cargos Glovales / Sucursales Generales</h5>
            <p>Estos cargos tendran acciones importantes en el sistema, cada cargo añadido tendra una accion
                que esta vinculada a un trabajador en la <a href="<?= base_url('/api/Usuario/vista') ?>"
                    target="_blank">tabla usuarios.</a></p>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#CargosRegistro"
                                style="background: #22c55e; border: 1px solid #22c55e;"><i class="fa-solid fa-plus"></i>
                                New</button>
                        </div>
                    </div>
                </div>
            </div>
            <table id="tblCargos" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
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

<div class="modal fade" id="CargosRegistro" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6379f1;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Registro de Cargos</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <form id="FrmCargos">
                <input type="hidden" name="pagina_ids" id="pagina_ids">
                    <div class="row">
                        <div class="col-lg-9">
                            <label for="Nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="Nombre" name="Nombre" required>
                        </div>
                        <div class="col-lg-3">
                            <label for="Estado" class="form-label">Estado</label>
                            <select class="form-select" name="Estado" id="Estado" aria-label="Default select example">
                                <option value="1">Activo</option>
                                <option value="0">Desactivo</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12">
                        <label for="titulo" class="form-label" style="color: brown">Tener en cuenta que son <b>accesos
                                al sistema...</b>
                        </label>
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 me-2">
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Reconocimineto
                                        Facial</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="6,7,8,9,10,11"
                                            id="ReconocimientoF">
                                        <label class="form-check-label" for="ReconocimientoF">
                                            Reconocimiento (Asistencia)
                                        </label>
                                    </div>
                                </div>
                                <label for="estado" class="col-form-label" style="color: #000">Panel General</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="19" id="Panel1">
                                    <label class="form-check-label" for="Panel1">
                                        Vista
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Perfil</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="35" id="Perfilv">
                                        <label class="form-check-label" for="Perfilv">
                                            Vista
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="36" id="Perfilc">
                                        <label class="form-check-label" for="Perfilc">
                                            Cambiar Contraseña
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Permisos</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="12,13," id="Permisosv">
                                        <label class="form-check-label" for="Permisosv">
                                            Vista
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="15" id="PermisosR">
                                        <label class="form-check-label" for="PermisosR">
                                            Registrar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="18" id="PermisosE">
                                        <label class="form-check-label" for="PermisosE">
                                            Eliminar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="16,17" id="PermisosA">
                                        <label class="form-check-label" for="PermisosA">
                                            Actualizar
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <label for="estado" class="col-form-label" style="color: #000">Modulo de
                                    Trabajador</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75" id="TrabajadorAt">
                                    <label class="form-check-label" for="TrabajadorAt">
                                        Acceso Total
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion
                                        Trabajadores</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="59,60,62,63,64,65,66,67,33,34" id="Trabajadorv">
                                        <label class="form-check-label" for="Trabajadorv">
                                            Vizualizacion
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="68,74" id="TrabajadorR">
                                        <label class="form-check-label" for="TrabajadorR">
                                            Registrar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="71" id="TrabajadorE">
                                        <label class="form-check-label" for="TrabajadorE">
                                            Eliminar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="68,69" id="TrabajadorA">
                                        <label class="form-check-label" for="TrabajadorA">
                                            Actualizar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="72" id="TrabajadorCM">
                                        <label class="form-check-label" for="TrabajadorCM">
                                            Carga Masiva
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="67,61,33,34" id="TrabajadorOB">
                                        <label class="form-check-label" for="TrabajadorOB">
                                            Observaciones
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="TrabajadorDP">
                                        <label class="form-check-label" for="TrabajadorDP">
                                            Descarga de Plantilla
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <label for="estado" class="col-form-label" style="color: #000">Modulo
                                    Sucursales</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="47,48,49,50,51,52,53,54,55,56,57,58" id="SucursalAT">
                                    <label class="form-check-label" for="SucursalAT">
                                        Acceso Total
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion de
                                        Sucursal</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="47,48,57,58" id="SucursalV">
                                        <label class="form-check-label" for="SucursalV">Vizualizacion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="51" id="SucursalR">
                                        <label class="form-check-label" for="SucursalR">Registrar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="50,49" id="SucursalA">
                                        <label class="form-check-label" for="SucursalA">Actualizar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="52,53" id="SucursalE">
                                        <label class="form-check-label" for="SucursalE">Eliminar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="SucursalDP">
                                        <label class="form-check-label" for="SucursalDP">Descargar Plantilla</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="54" id="SucursalCM">
                                        <label class="form-check-label" for="SucursalCM">Carga Masiva</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="56" id="SucursalEM">
                                        <label class="form-check-label" for="SucursalEM">Eliminacion Masica</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion
                                        de Turnos</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="76,77," id="TurnosV">
                                        <label class="form-check-label" for="TurnosV">Vizualizacion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="79" id="TurnosR">
                                        <label class="form-check-label" for="TurnosR">Registrar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="80,81" id="TurnosA">
                                        <label class="form-check-label" for="TurnosA">Actualizar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="82" id="TurnosE">
                                        <label class="form-check-label" for="TurnosE">Eliminar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="83" id="TurnosCM">
                                        <label class="form-check-label" for="TurnosCM">Carga Masiva</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="84" id="TurnosEM">
                                        <label class="form-check-label" for="TurnosEM">Eliminacion Masiva</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion
                                        de Horarios</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="20,21" id="HorariosV">
                                        <label class="form-check-label" for="HorariosV">Vizualizacion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="26" id="HorariosR">
                                        <label class="form-check-label" for="HorariosR">Registrar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="26,27" id="HorariosA">
                                        <label class="form-check-label" for="HorariosA">Actualizar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="28" id="HorariosE">
                                        <label class="form-check-label" for="HorariosE">Eliminar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="31," id="HorariosCM">
                                        <label class="form-check-label" for="HorariosCM">Carga Masiva</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="30" id="HorariosEM">
                                        <label class="form-check-label" for="HorariosEM">Eliminacion Masiva</label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <label for="estado" class="col-form-label" style="color: #000">Modulo
                                    Usuarios</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="85,86,87,88,89,90,91,92,93,94" id="UsuarioAT">
                                    <label class="form-check-label" for="UsuarioAT">
                                        Acceso Total
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion
                                        Usuario</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="85,86,87,88,89,94" id="UsuarioV">
                                        <label class="form-check-label" for="UsuarioV">Vizualizacion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="90" id="UsuarioR">
                                        <label class="form-check-label" for="UsuarioR">Registrar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="93" id="UsuarioE">
                                        <label class="form-check-label" for="UsuarioE">Eliminar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="91,92" id="UsuarioA">
                                        <label class="form-check-label" for="UsuarioA">Actualizar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <label for="estado" class="col-form-label" style="color: #000">Modulo
                                    Reportes</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="37,38,39,40,41,42,43,44,45,46" id="ReportesAT">
                                    <label class="form-check-label" for="ReportesAT">
                                        Acceso Total
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="extraInfo" class="col-form-label" style="color: #000">Seccion
                                        Reportes (Asistencia)</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="37" id="ReportesV">
                                        <label class="form-check-label" for="ReportesV">Vizualizacion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="38,39,40,41,42,43,44,45,46" id="TrabajadoresR">
                                        <label class="form-check-label" for="TrabajadoresR">Busqueda</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #6379f1;"
                    onclick="this.style.borderColor='#6379f1';">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </span>
                <!-- Botón "Save" en el modal -->
                <span id="saveButton" onclick="agregar()" class="btn" style="margin-left: 10px; color: #6379f1;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateCargos" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #103f1f;">
                <h5 class="modal-title" style="color: #ffffff;"><b>Actualizar de Cargos</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <form id="formUpdate">
                    <div class="col-md-12">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="Nombre1">
                    </div>
                    <div class="col-md-12">
                        <label for="Estado1" class="form-label">Estado</label>
                        <select class="form-select" id="Estado1" aria-label="Default select example">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <span class="btn" data-bs-dismiss="modal" style="margin-left: 10px; color: #103f1f;"
                    onclick="this.style.borderColor='#103f1f';">
                    <i class="fa-solid fa-times"></i> <b>Cancel</b>
                </span>
                <span id="updateButton" class="btn" style="margin-left: 10px; color: #103f1f;">
                    <i class="fa-solid fa-check"></i> <b>Save</b>
                </span>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
let idPagina = [];

function updateIdPagina() {
    const tempIdPagina = [];
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            tempIdPagina.push(...checkbox.value.split(',').map(id => id.trim()).filter(id => id !== ''));
        }
    });
    idPagina = [...new Set(tempIdPagina)];
    console.log(`[${idPagina.map(id => `'${id}'`).join(',')}]`);
}

function toggleCheckboxes(checkbox, checkboxes) {
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateIdPagina();
    updateIndeterminateState();
}

function updateIndeterminateState() {
    const groups = [
        { master: document.getElementById('ReportesAT'), slaves: document.querySelectorAll('#ReportesV, #TrabajadoresR') },
        { master: document.getElementById('UsuarioAT'), slaves: document.querySelectorAll('#UsuarioV, #UsuarioR, #UsuarioE, #UsuarioA') },
        { master: document.getElementById('SucursalAT'), slaves: document.querySelectorAll('#SucursalV, #SucursalR, #SucursalA, #SucursalE, #SucursalDP, #SucursalCM, #SucursalEM, #TurnosV, #TurnosR, #TurnosA, #TurnosE, #TurnosCM, #TurnosEM, #HorariosV, #HorariosR, #HorariosA, #HorariosE, #HorariosCM, #HorariosEM') },
        { master: document.getElementById('TrabajadorAt'), slaves: document.querySelectorAll('#Trabajadorv, #TrabajadorR, #TrabajadorE, #TrabajadorA, #TrabajadorCM, #TrabajadorOB, #TrabajadorDP') }
    ];

    groups.forEach(group => {
        const allChecked = Array.from(group.slaves).every(cb => cb.checked);
        const someChecked = Array.from(group.slaves).some(cb => cb.checked);
        group.master.checked = allChecked;
        group.master.indeterminate = !allChecked && someChecked;
    });
}

// Identifica los checkboxes de "Acceso Total"
const checkboxesReportes = document.querySelectorAll('#ReportesV, #TrabajadoresR');
const checkboxesUsuarios = document.querySelectorAll('#UsuarioV, #UsuarioR, #UsuarioE, #UsuarioA');
const checkboxesSucursales = document.querySelectorAll('#SucursalV, #SucursalR, #SucursalA, #SucursalE, #SucursalDP, #SucursalCM, #SucursalEM, #TurnosV, #TurnosR, #TurnosA, #TurnosE, #TurnosCM, #TurnosEM, #HorariosV, #HorariosR, #HorariosA, #HorariosE, #HorariosCM, #HorariosEM');
const checkboxesTrabajador = document.querySelectorAll('#Trabajadorv, #TrabajadorR, #TrabajadorE, #TrabajadorA, #TrabajadorCM, #TrabajadorOB, #TrabajadorDP');

const accesoTotalCheckboxReportes = document.getElementById('ReportesAT');
const accesoTotalCheckboxUsuarios = document.getElementById('UsuarioAT');
const accesoTotalCheckboxSucursales = document.getElementById('SucursalAT');
const accesoTotalCheckboxTrabajador = document.getElementById('TrabajadorAt');

accesoTotalCheckboxReportes.addEventListener('change', () => toggleCheckboxes(accesoTotalCheckboxReportes, checkboxesReportes));
accesoTotalCheckboxUsuarios.addEventListener('change', () => toggleCheckboxes(accesoTotalCheckboxUsuarios, checkboxesUsuarios));
accesoTotalCheckboxSucursales.addEventListener('change', () => toggleCheckboxes(accesoTotalCheckboxSucursales, checkboxesSucursales));
accesoTotalCheckboxTrabajador.addEventListener('change', () => toggleCheckboxes(accesoTotalCheckboxTrabajador, checkboxesTrabajador));

document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.addEventListener('change', () => {
    updateIdPagina();
    updateIndeterminateState();
}));

updateIdPagina();
updateIndeterminateState();
</script>