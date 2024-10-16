<?php
$Sucursal = $Sucursal ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicons -->
    <link href="<?= base_url('Plantilla/assets/img/favicon.png') ?>" rel="icon">
    <link href="<?= base_url('Plantilla/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">
    <script src="<?= base_url('Reconocimiento/face-api.min.js') ?>"></script>

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

    <!-- Template Main CSS File -->
    <link href="<?= base_url('Plantilla/assets/css/style.css') ?>" rel="stylesheet">
    <!-- Estilos de Face Api --->
    <link href="<?= base_url('Reconocimiento/EstilosF.css') ?>" rel="stylesheet">
    <script src="<?= base_url('js/jsReconocimiento.js') ?>"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="<?= base_url('/api/Asistencia') ?>" class="logo d-flex align-items-center">
                <img src="<?= base_url('Plantilla/assets/img/logo.png') ?>" alt="">
                <span class="d-none d-lg-block">Facial Recognition</span>
            </a>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
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
                            $cargoNombre = $cargoModel->getNombreById($cargoId);                            
                            // Procesar el nombre del usuario
                            $partesNombre = explode(' ', $nombreCompleto);

                            if (count($partesNombre) >= 3) {
                                $inicialPrimerNombre = substr($partesNombre[2], 0, 1) . '.';
                                $apellidoCompleto = end($partesNombre);
                                $nombreFormateado = $inicialPrimerNombre . ' ' . strtoupper($apellidoCompleto);
                            } else {
                                // Manejar el caso cuando no hay suficientes partes en el nombre
                                $nombreFormateado = $nombreCompleto; // O cualquier valor predeterminado
                            }
                    ?>
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="<?= base_url('Perfil/Sucursal/Swiss_pharmacy_logo_(old).svg.png') ?>" alt="Profile"
                    class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $nombreFormateado; ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $nombreCompleto; ?></h6>
                            <span><?php echo $cargoNombre; ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="<?= base_url('/Login/cerrarsesion') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                    <?php } ?>
                </li>
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <video id="video" width="720" height="560" autoplay muted></video>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('Plantilla/assets/vendor/apexcharts/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/chart.js/chart.umd.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/echarts/echarts.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/quill/quill.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?= base_url('Plantilla/assets/vendor/php-email-form/validate.js') ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Template Main JS File -->
</body>

</html>
<script>
const video = document.getElementById('video');
let recognition; // Variable para el objeto de reconocimiento de voz
let synth; // Variable para el objeto de síntesis de voz
let faceDetected = false; // Bandera para controlar si se ha detectado un rostro
let usuarioEncontrado = false;
let reconocimientoActivo = true;
let reconocimientoPausado = false;
let alertaMostrada = false;
// fotos de los trabajadores
let referencias2 = [];

// Realizar la solicitud HTTP a la ruta del controlador
fetch(`http://localhost:8080/api/Asistencia/<?= $Sucursal ?>`)
    .then(response => response.json())
    .then(data => {
        referencias2 = data;
        console.log('Datos obtenidos:', referencias2);
    })
    .catch(error => {
        console.error('Error al obtener datos:', error);
    });

// importacion de los modelos
Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('<?= base_url('Reconocimiento/models') ?>'),
    faceapi.nets.faceLandmark68Net.loadFromUri('<?= base_url('Reconocimiento/models') ?>'),
    faceapi.nets.faceRecognitionNet.loadFromUri('<?= base_url('Reconocimiento/models') ?>'),
    faceapi.nets.faceExpressionNet.loadFromUri('<?= base_url('Reconocimiento/models') ?>'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('<?= base_url('Reconocimiento/models') ?>'),
]).then(startVideo).catch(err => console.error(err));

// inicio del video
function startVideo() {
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => console.error(err));

    // Inicializar el objeto de reconocimiento de voz
    // cambiar de voz
    recognition = new webkitSpeechRecognition();
    recognition.lang = 'es-ES';
    recognition.continuous = true;
    recognition.interimResults = false;

    // Inicializar el objeto de síntesis de voz
    synth = window.speechSynthesis;

    // Evento para cuando se reconozca una palabra
    recognition.onresult = (event) => {
        const speechResult = event.results[0][0].transcript.toLowerCase();
        console.log('Reconocimiento de voz:', speechResult);
    };
}

video.addEventListener('play', async () => {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.body.append(canvas);
    const displaySize = {
        width: video.width,
        height: video.height
    };
    faceapi.matchDimensions(canvas, displaySize);
    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions();
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        faceapi.draw.drawDetections(canvas, resizedDetections);
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        faceapi.draw.drawFaceExpressions(canvas, resizedDetections);
    }, 100);

    // faltaria ajustar el umbral de confianza 
    for (const referencia of referencias2) {
        const referenciaImg = new Image();
        referenciaImg.src = referencia.ruta;
        referenciaImg.src = referenciaImg.src.replace('/api/public/', '/');
        console.log('Datos obtenidos:', referencia.ruta);
        console.log('Datos asignados:', referenciaImg.src);
        try {
            const referenciaDetections = await faceapi.detectSingleFace(referenciaImg).withFaceLandmarks().withFaceDescriptor();
            referencia.descriptor = referenciaDetections?.descriptor;
            // retorna los puntos del rostro
            console.log('rostro identificado:', referenciaDetections?.descriptor);
        } catch (error) {
            console.error('rostro no identificado', error);
        }
    }

    setInterval(async () => {
        if (reconocimientoPausado) {
            return;
        }

        const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

        if (detections.length > 0 && !usuarioEncontrado) {
            detections.forEach(async detection => {
                const umbralAncho = 50;
                const umbralAlto = 50;
                const deteccionesFiltradas = detections.filter(detection =>
                    detection.detection.box.width > umbralAncho && detection.detection.box.height > umbralAlto);

                const landmarks = detection.landmarks;
                if (verificarImagenEstatica(landmarks) || deteccionesFiltradas.length === 0) {
                    console.log('Imagen estática detectada o tamaño insuficiente, no es un rostro.');
                    return;
                }

                let usuarioEnBaseDeDatos = false;

                for (const referencia of referencias2) {
                    const referenciaDescriptor = referencia.descriptor;
                    const descriptor = detection.descriptor;

                    // Dentro del bucle donde se realizan las comparaciones con los descriptores faciales
                    if (referenciaDescriptor && descriptor) {
                        try {
                            // Calcular la distancia entre los descriptores faciales
                            const distancia = faceapi.euclideanDistance(descriptor, referenciaDescriptor);
                            const umbralDistancia = 0.50;
                            const umbralConfianza = 0.30;

                            // Si la distancia es menor que el umbral y la confianza es mayor que el umbral
                            if (distancia < umbralDistancia && detection.detection._score > umbralConfianza) {
                                // Usuario encontrado en la base de datos
                                usuarioEnBaseDeDatos = true;

                                // Imprimir en la consola que se ha encontrado el usuario
                                console.log(`Usuario encontrado: ${referencia.idTrabajador}`);
                                console.log(`Usuario encontrado: ${referencia.id_sucursal}`);
                                if (!alertaMostrada) {
                                    reconocimientoPausado = true; // Detener el reconocimiento

                                    Swal.fire({
                                        title: "¡Reconocimiento facial exitoso!",
                                        text: `Resultado: Correcta`,
                                        icon: "success",
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        showCloseButton: false,
                                        cancelButtonText: 'Cancelar',
                                        showLoaderOnConfirm: true,
                                        html: `
                                            <div>
                                                <p>${referencia.nombre}</p>
                                                <form id="ForTurno">
                                                    <input type="hidden" name="id_sucursal" id="id_sucursal" value="${referencia.id_sucursal}">
                                                    <input type="hidden" name="idTrabajador" id="idTrabajador" value="${referencia.idTrabajador}">
                                                </form>
                                                <span id="saveButton" class="btn" onclick="agregar()" style="margin-left: 10px; color: #953292;">
                                                    <i class="fa-solid fa-check"></i> <b>Save</b>
                                                </span>
                                            </div>`,
                                        didOpen: () => {
                                            const saludo = obtenerSaludo();
                                            speak(`${referencia.nombre}, tu rostro ha sido detectado. Que tengas un bonito ${saludo}.`);
                                        }
                                    });
                                    // Marcar la alerta como mostrada
                                    alertaMostrada = true;
                                }
                                // No es necesario continuar con otras iteraciones
                                return;
                            }
                        } catch (error) {
                            console.error('Error al comparar descriptores faciales:', error);
                        }
                    }
                }
                // Usuario no encontrado en la base de datos
                if (!usuarioEnBaseDeDatos) {
                    if (!alertaMostrada) {
                        reconocimientoPausado = true; // Detener el reconocimiento
                        Swal.fire({
                            title: "¡Usuario no reconocido!",
                            text: "Presiona OK para reiniciar el escáner.",
                            icon: "error",
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Esperar 3 segundos antes de reanudar el reconocimiento
                                setTimeout(() => {
                                    usuarioEncontrado = false;
                                    reconocimientoPausado = false; // Reanudar el reconocimiento
                                    alertaMostrada = false; // Restablecer la variable de la alerta
                                }, 3000);
                            } else {
                                // Reiniciar la detección si se cancela la alerta
                                usuarioEncontrado = false;
                                reconocimientoPausado = false; // Reanudar el reconocimiento
                                alertaMostrada = false; // Restablecer la variable de la alerta
                            }
                        });
                        // Marcar la alerta como mostrada
                        alertaMostrada = true;
                    }
                }
                usuarioEncontrado = true;
            });
        } else if (detections.length === 0 && usuarioEncontrado) {
            usuarioEncontrado = false;
            alertaMostrada = false; // Restablecer la variable de la alerta
        }
    }, 100);
});

function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    synth.speak(utterance);
}

function verificarImagenEstatica(landmarks) {
    const numLandmarks = landmarks ? landmarks.positions.length : 0;
    const umbralLandmarks = 10;
    return numLandmarks <= umbralLandmarks;
}

function obtenerSaludo() {
    const horaActual = new Date().getHours();
    if (horaActual < 12) {
        return 'día';
    } else if (horaActual < 18) {
        return 'tarde';
    } else {
        return 'noche';
    }
}
</script>