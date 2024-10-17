<?php
$Sucursal = $Sucursal ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Attendance Control | Face Recognition</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Modern CSS Framework -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    
    <!-- Modern Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= base_url('js/jsReconocimiento.js') ?>"></script>
    <script src="<?= base_url('Reconocimiento/face-api.min.js') ?>"></script>

    <!-- Custom Styles -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .video-container {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .recording-indicator {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen">
    <!-- Modern Header -->
    <header class="glass-effect fixed w-full top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                </div>
                
                <!-- User Profile -->
                <?php
                $session = \Config\Services::session();
                if ($session->has('usuario')) {
                    // Obtener datos de la sesión
                    $nombreCompleto = $session->get('Nombre');
                    $cargoId = $session->get('Cargo');
                    
                    // Cargar el modelo de Cargo
                    $cargoModel = new \App\Models\CargoModelo();
                    $cargoNombre = $cargoModel->getNombreById($cargoId);
                    
                    // Formatear el nombre
                    $partesNombre = explode(' ', $nombreCompleto);
                    if (count($partesNombre) >= 3) {
                        $inicialPrimerNombre = substr($partesNombre[2], 0, 1) . '.';
                        $apellidoCompleto = end($partesNombre);
                        $nombreFormateado = $inicialPrimerNombre . ' ' . strtoupper($apellidoCompleto);
                    } else {
                        $nombreFormateado = $nombreCompleto;
                    }
                ?>
                <div class="relative" id="profileDropdown">
                    <button 
                        class="flex items-center space-x-3 glass-effect px-4 py-2 rounded-lg hover:bg-white/30 transition"
                        onclick="toggleDropdown()"
                    >
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-semibold text-gray-800"><?= esc($nombreFormateado) ?></p>
                            <p class="text-xs text-gray-600"><?= esc($cargoNombre) ?></p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-600 text-sm transition-transform duration-200" id="dropdownIcon"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 glass-effect rounded-lg shadow-lg hidden transition-all duration-200 transform opacity-0">
                        <a href="<?= base_url('/Login/cerrarsesion') ?>" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-white/30 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                        </a>
                    </div>
                </div>
                <?php } else { ?>
                <div class="relative">
                    <a href="<?= base_url('/Login') ?>" 
                       class="glass-effect px-4 py-2 rounded-lg hover:bg-white/30 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                <?php } ?>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 pt-24 pb-12">
    <div class="max-w-4xl mx-auto">
        <!-- Video Container -->
        <div class="video-container bg-white p-4">
            <div class="relative">
                <!-- Video Element -->
                <video id="video" width="720" height="560" class="w-full h-auto rounded-lg shadow-lg" autoplay muted></video>

                <!-- Canvas for FaceAPI -->
                <canvas id="overlay" class="absolute top-0 left-0 w-full h-full"></canvas>
                
                <!-- Recording Indicator -->
                <div class="absolute top-4 right-4 flex items-center space-x-2 glass-effect px-3 py-1 rounded-full">
                    <div class="recording-indicator w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-800">Recording</span>
                </div>
            </div>
        </div>
    </div>
</main>


    <!-- Scripts -->
</body>
</html>
<script>
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        // Toggle classes for animation
        if (dropdownMenu.classList.contains('hidden')) {
            // Show menu
            dropdownMenu.classList.remove('hidden');
            setTimeout(() => {
                dropdownMenu.classList.remove('opacity-0');
                dropdownMenu.classList.add('opacity-100');
                dropdownIcon.classList.add('rotate-180');
            }, 20);
        } else {
            // Hide menu
            dropdownMenu.classList.remove('opacity-100');
            dropdownMenu.classList.add('opacity-0');
            dropdownIcon.classList.remove('rotate-180');
            setTimeout(() => {
                dropdownMenu.classList.add('hidden');
            }, 200);
        }
    }
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        if (!dropdown.contains(event.target) && !dropdownMenu.classList.contains('hidden')) {
            dropdownMenu.classList.remove('opacity-100');
            dropdownMenu.classList.add('opacity-0');
            dropdownIcon.classList.remove('rotate-180');
            setTimeout(() => {
                dropdownMenu.classList.add('hidden');
            }, 200);
        }
    });
</script>
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
    const canvas = document.getElementById('overlay');
    
    // Ajustar el tamaño del canvas al tamaño del video
    canvas.width = video.offsetWidth;
    canvas.height = video.offsetHeight;

    const displaySize = {
        width: video.offsetWidth,  // Usar las dimensiones del video del HTML
        height: video.offsetHeight // Usar las dimensiones del video del HTML
    };

    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceExpressions();

        const resizedDetections = faceapi.resizeResults(detections, displaySize);

        // Limpiar el canvas y dibujar las detecciones sobre el video
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        faceapi.draw.drawDetections(canvas, resizedDetections);
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        faceapi.draw.drawFaceExpressions(canvas, resizedDetections);
    }, 100);

    // Procesar las referencias de los rostros
    for (const referencia of referencias2) {
        const referenciaImg = new Image();
        referenciaImg.src = referencia.ruta;
        referenciaImg.src = referenciaImg.src.replace('/api/public/', '/');
        console.log('Datos obtenidos:', referencia.ruta);
        console.log('Datos asignados:', referenciaImg.src);
        try {
            const referenciaDetections = await faceapi.detectSingleFace(referenciaImg).withFaceLandmarks().withFaceDescriptor();
            referencia.descriptor = referenciaDetections?.descriptor;
            console.log('rostro identificado:', referenciaDetections?.descriptor);
        } catch (error) {
            console.error('rostro no identificado', error);
        }
    }

    // Intervalo para la detección continua y comparación
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

                    if (referenciaDescriptor && descriptor) {
                        try {
                            const distancia = faceapi.euclideanDistance(descriptor, referenciaDescriptor);
                            const umbralDistancia = 0.50;
                            const umbralConfianza = 0.30;

                            if (distancia < umbralDistancia && detection.detection._score > umbralConfianza) {
                                usuarioEnBaseDeDatos = true;
                                console.log(`Usuario encontrado: ${referencia.idTrabajador}`);
                                console.log(`Usuario encontrado: ${referencia.id_sucursal}`);
                                if (!alertaMostrada) {
                                    reconocimientoPausado = true;

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
                                    alertaMostrada = true;
                                }
                                return;
                            }
                        } catch (error) {
                            console.error('Error al comparar descriptores faciales:', error);
                        }
                    }
                }

                if (!usuarioEnBaseDeDatos) {
                    if (!alertaMostrada) {
                        reconocimientoPausado = true;
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
                                setTimeout(() => {
                                    usuarioEncontrado = false;
                                    reconocimientoPausado = false;
                                    alertaMostrada = false;
                                }, 3000);
                            } else {
                                usuarioEncontrado = false;
                                reconocimientoPausado = false;
                                alertaMostrada = false;
                            }
                        });
                        alertaMostrada = true;
                    }
                }
                usuarioEncontrado = true;
            });
        } else if (detections.length === 0 && usuarioEncontrado) {
            usuarioEncontrado = false;
            alertaMostrada = false;
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