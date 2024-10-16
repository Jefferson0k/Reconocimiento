<script src="<?= base_url('js/jsRecuperacion.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    cambiarPass();
});
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Recuperacion</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="" <?= base_url('Plantilla/assets/img/favicon.png') ?>" rel="icon">
    <link href="" <?= base_url('Plantilla/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url('Plantilla/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/boxicons/css/boxicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/quill/quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/quill/quill.bubble.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/remixicon/remixicon.css') ?>" rel="stylesheet">
    <link href="<?= base_url('Plantilla/assets/vendor/simple-datatables/style.css') ?>" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url('Plantilla/assets/css/style.css') ?>" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 7 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <?php

use App\Models\CargoModelo;

$session = \Config\Services::session(); 
if ($session->has('usuario')) {
    $cargoModel = new CargoModelo();
    // Obtiene los datos de la sesión
    $iduser = $session->get('iduser');
    $Usuario = $session->get('usuario');
    $nombreCompleto  = $session->get('Nombre');
    $apellidos = $session->get('NomApell');
    $cargoId = $session->get('Cargo');
    // Obtiene el nombre del cargo usando el modelo
    $cargoNombre = $cargoModel->getNombreById($cargoId);                            
    // Procesar el nombre del usuario
    $partesNombre = explode(' ', $nombreCompleto);
    
    if (isset($partesNombre[2])) {
        $inicialPrimerNombre = substr($partesNombre[2], 0, 1) . '.';
    } else {
        $inicialPrimerNombre = ''; // Set a default value or handle the case when the third element doesn't exist
    }

    $apellidoCompleto = end($partesNombre);
    $nombreFormateado = $inicialPrimerNombre . ' ' . strtoupper($apellidoCompleto);
    
    // Obtiene el nombre del cargo usando el modelo
    $cargoNombre = $cargoModel->getNombreById($cargoId);
?>
    <!-- Your HTML/PHP content here -->
    <?php 
} 
?>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="<?= base_url('Plantilla/assets/img/logo.png') ?>" alt="">
                <span class="d-none d-lg-block">Facial recognition</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?= base_url('Perfil/Restableciendo/6146587.png') ?>" alt="Profile"
                            class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $nombreFormateado; ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $nombreCompleto ; ?></h6>
                            <span><?php echo $cargoNombre ; ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="<?= base_url('/Login/cerrarsesion')?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <main id="main" class="main">
        <section class="section profile">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">Cambiar Contraseña</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active pt-3" id="profile-change-password">
                                    <form id="formCambiarPassword" action="<?= base_url('/NuevaPassword') ?>"
                                        method="post">
                                        <div class="row mb-3">
                                            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Actual
                                                Contraseña</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="Pass_actual" type="password" class="form-control"
                                                    id="currentPassword" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nuevo
                                                Contraseña</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="nueva_Pass" type="password" class="form-control"
                                                    id="newPassword" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="confirmNewPassword"
                                                class="col-md-4 col-lg-3 col-form-label">Confirmar Contraseña</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="confirmar_Pass" type="password" class="form-control"
                                                    id="confirmNewPassword" required>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Cambio de Contraseña</button>
                                        </div>
                                        <br>
                                        <div id="message" style="width: 100%;"></div>
                                    </form>
                                </div>
                            </div><!-- End Bordered Tabs -->
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Soluciones en ingenieria T & J</span></strong>. Todos los derechos
            reservados.
        </div>
        <div class="credits">
            Diseñado por <a href="https://www.facebook.com/jefer.covenasroman?mibextid=JRoKGi">Jefferson0k</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('Plantilla/assets/vendor/apexcharts/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/chart.js/chart.umd.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/echarts/echarts.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/quill/quill.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/simple-datatables/simple-datatables.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/php-email-form/validate.js') ?>"></script>

    <!-- Template Main JS File -->
    <script src="<?= base_url('Plantilla/assets/js/main.js') ?>"></script>

</body>

</html>