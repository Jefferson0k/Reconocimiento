<script src="<?= base_url('js/jsRecuperacion.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    cambiarPass();
});
</script>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?= base_url('templeate/assets/') ?>"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Recuperacion</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('templeate/assets/img/favicon/favicon.ico') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?= base_url('templeate/assets/vendor/fonts/boxicons.css') ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('templeate/assets/vendor/css/core.css') ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url('templeate/assets/vendor/css/theme-default.css') ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url('templeate/assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('templeate/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?= base_url('templeate/assets/vendor/js/helpers.js') ?>"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url('templeate/assets/js/config.js') ?>"></script>
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
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="<?= base_url('templeate/assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="<?= base_url('templeate/assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $nombreCompleto ; ?></span>
                            <small class="text-muted"><?php echo $cargoNombre ; ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?= base_url('/Login/cerrarsesion')?>">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
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
              <!-- Basic Bootstrap Table -->
              
              <!--/ Basic Bootstrap Table -->
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= base_url('templeate/assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('templeate/assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('templeate/assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('templeate/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>

    <script src="<?= base_url('templeate/assets/vendor/js/menu.js') ?>"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="<?= base_url('templeate/assets/js/main.js') ?>"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
