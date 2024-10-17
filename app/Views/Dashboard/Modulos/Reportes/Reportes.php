<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/face-api.js/3.0.0/face-api.min.js"></script>
<script src="<?= base_url('js/jsAsistencia.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    inicializarComponentes();
});
</script>
<style>
    /* Cambiar el color de fondo de la fila cuando se hace clic */
.fila-tabla:hover {
    background-color: lightgrey;
    cursor: pointer;
}

</style>
<div class="container-xxl flex-grow-1 container-p-y">
<section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Consultar - Fechas</h5>
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="date" id="fecha_inicio" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <input type="date" id="fecha_fin" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <select class="form-control" name="id_sucursal" id="id_sucursal" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Trabajadores Generales</h5>
            <table id="tblTGenerales" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>Dias Laborados</th>
                        <th>No Laborados</th>
                        <th>(Horas) Trabajadas</th>
                        <th>(Horas) Extras</th>
                        <th>(Break)Tardanzas</th>
                        <th>Dias no Laborables</th>
                        <th>Total de horas no Trabajadas</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>Dias Laborados</th>
                        <th>No Laborados</th>
                        <th>(Horas) Trabajadas</th>
                        <th>(Horas) Extras</th>
                        <th>(Break) Tardanzas</th>
                        <th>Dias no Laborables</th>
                        <th>Total de horas no Trabajadas</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
            <!-- End Table with stripped rows -->
        </div>
    </div>
</div>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detalle de Trabajadores</h5>
            <table id="tblTDetalles" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Nombre Completo</th>
                        <th>Dni</th>
                        <th>Entradas</th>
                        <th>Break Entrada</th>
                        <th>Break Salida</th>
                        <th>Salida</th>
                        <th>H. Trabajadas</th>
                        <th>H. Extras</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Nombre Completo</th>
                        <th>Dni</th>
                        <th>Entradas</th>
                        <th>Break Entrada</th>
                        <th>Break Salida</th>
                        <th>Salida</th>
                        <th>H. Trabajadas</th>
                        <th>H. Extras</th>
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
