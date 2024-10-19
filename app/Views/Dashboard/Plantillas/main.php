<?php
$mensajesMotivacionales = [
    "¡Cada día es una nueva oportunidad para mejorar! Mantén el enfoque y sigue creciendo.",
    "Recuerda que el esfuerzo constante es la clave del éxito. ¡Sigue así!",
    "¡Hoy es un buen día para perseguir tus sueños! Nunca dejes de trabajar por ellos.",
    "Los grandes logros requieren tiempo y esfuerzo. ¡No te rindas!",
    "Tu dedicación es lo que te llevará lejos. ¡Sigue dando lo mejor de ti!",
    "Cada pequeño paso cuenta. Celebra tus logros, por más pequeños que sean.",
    "La perseverancia es el camino hacia el éxito. ¡Sigue adelante!",
    "Recuerda: la única forma de hacer un gran trabajo es amar lo que haces. ¡Continúa!",
    "Los límites solo existen si tú los pones. ¡Rompe tus barreras!",
    "¡Hoy es tu día para brillar! Da lo mejor de ti y los resultados llegarán."
];

$mensajeAleatorio = $mensajesMotivacionales[array_rand($mensajesMotivacionales)];
?>
<script src="https://kit.fontawesome.com/47438e1d36.js" crossorigin="anonymous"></script>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Tarjeta principal con felicitación -->
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <?php
                        use App\Models\CargoModelo;
                        $session = \Config\Services::session();
                        if ($session->has('usuario')) {
                            // Carga el modelo de Cargo
                            $cargoModel = new CargoModelo();

                            // Obtiene los datos de la sesión
                            $iduser = $session->get('iduser');
                            $Usuario = $session->get('usuario');
                            $nombreCompleto  = $session->get('Nombre');
                            $apellidos = $session->get('NomApell');
                            $cargoId = $session->get('Cargo');

                            // Obtiene el nombre del cargo usando el modelo
                            $cargoNombre = '';
                            if (!empty($cargoId)) {
                                $cargoNombre = $cargoModel->getNombreById($cargoId);
                            }

                            // Procesar el nombre del usuario
                            $nombreFormateado = '';
                            if (!empty($nombreCompleto)) {
                                $partesNombre = explode(' ', $nombreCompleto);
                                if (count($partesNombre) >= 3) {
                                    $inicialPrimerNombre = substr($partesNombre[2], 0, 1) . '.';
                                    $apellidoCompleto = end($partesNombre);
                                    $nombreFormateado = $inicialPrimerNombre . ' ' . strtoupper($apellidoCompleto);
                                } else {
                                    $nombreFormateado = $nombreCompleto; 
                                }
                            }

                            // Renderizar el perfil del usuario
                        ?>
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">¡BIENVENIDO, <?php echo $nombreCompleto; ?>! 🎉</h5>
                                    <p class="mb-4">
                                        <?php echo $mensajeAleatorio; // Mensaje motivacional dinámico ?>
                                    </p>
                                    <a href="javascript:;" class="btn btn-sm btn-outline-primary">Ver Insignias</a>
                                </div>
                            </div>
                            <div class="col-sm-5 text-center text-sm-left">
                                <div class="card-body pb-0 px-0 px-md-4">
                                    <img src="<?= base_url('templeate/assets/img/illustrations/man-with-laptop-light.png') ?>"
                                        height="140" alt="Insignia del Usuario"
                                        data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                        data-app-light-img="illustrations/man-with-laptop-light.png" />
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Trabajadores Activos -->
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Trabajadores Activos</span>
                        <h3 class="card-title mb-2 trabajadores-activos">0</h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Trabajadores Desactivos -->
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="fas fa-user-times fa-2x"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Trabajadores Desactivos</span>
                        <h3 class="card-title mb-2 trabajadores-inactivos">0</h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Total de Trabajadores -->
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total de Trabajadores</span>
                        <h3 class="card-title mb-2 total-trabajadores">0</h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Total de Sucursales -->
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total de Sucursales</span>
                        <h3 class="card-title mb-2 total-sucursales">0</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiUrl = '/api/Dashboard/General';
        async function obtenerDatos() {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la red');
                }
                const data = await response.json();

                document.querySelector('.trabajadores-activos').textContent = data.total_activos;
                document.querySelector('.trabajadores-inactivos').textContent = data.total_inactivos;
                document.querySelector('.total-trabajadores').textContent = data.total_trabajadores;
                document.querySelector('.total-sucursales').textContent = data.total_sucursales;

            } catch (error) {
                console.error('Error al obtener los datos:', error);
            }
        }

        obtenerDatos();
    });
</script>

