<script src="<?= base_url('js/jsCambiarPassword.js') ?>"></script>
<script>
var ruta = '<?= base_url() ?>';
document.addEventListener('DOMContentLoaded', function() {
    cambiarPass();
});
</script>
<div class="container-xxl flex-grow-1 container-p-y">
<section class="section profile">
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <?php

                        use App\Models\CargoModelo;

                        $session = \Config\Services::session(); 
                        if ($session->has('usuario')) {
                            // Carga el modelo de Cargo
                            $cargoModel = new CargoModelo();

                            // Obtiene los datos de la sesión
                            $iduser = $session->get('iduser');
                            $Usuario = $session->get('usuario');
                            $nom = $session->get('NomApell');
                            $nombre = $session->get('Nombre');
                            $cargoId = $session->get('Cargo');
                            
                            // Obtiene el nombre del cargo usando el modelo
                            $cargoNombre = $cargoModel->getNombreById($cargoId);
                            ?>

                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="<?= base_url('templeate/assets/img/avatars/1.png') ?>" alt="Profile"
                            class="rounded-circle">
                        <h2><?php echo $nombre; ?></h2>
                        <h3><?php echo $cargoNombre; ?></h3> <!-- Muestra el nombre del cargo -->
                    </div>
                    <?php 
                    } 
                ?>

                </div>

            </div>

            <div class="col-xl-6">
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
                                <form id="formCambiarPassword" action="<?php echo base_url('api/cambiarPass'); ?>"
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
                                    <div id="message"></div>
                                </form>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>