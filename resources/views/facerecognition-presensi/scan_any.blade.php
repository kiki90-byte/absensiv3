<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Face Recognition Presensi - Sistem Presensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Presensi Face Recognition" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Face API -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom styles */
        .gradient-bg {
            background: #1e40af;
            /* Warna biru solid yang konsisten dengan tema admin */
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .webcam-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        .webcam-container video {
            width: 100%;
            height: auto;
            border-radius: 16px;
        }

        .webcam-container canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            border-radius: 16px;
        }

        /* Loading animation */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            border-radius: 16px;
            backdrop-filter: blur(8px);
        }



        .loading-content {
            text-align: center;
            color: white;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }



        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: white;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .loading-subtext {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Face landmark styles */
        .landmark-point {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #00ff00;
            border-radius: 50%;
            border: 1px solid #ffffff;
            z-index: 10;
            box-shadow: 0 0 2px rgba(0, 255, 0, 0.8);
        }

        .landmark-line {
            position: absolute;
            background: #00ff00;
            height: 1px;
            z-index: 9;
            box-shadow: 0 0 1px rgba(0, 255, 0, 0.5);
        }

        .face-landmarks {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 15;
            border-radius: 16px;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.1);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .camera-container {
            display: none;
        }

        .canvas-container {
            display: none !important;
        }

        .loading {
            display: none;
        }

        .status-message {
            display: none;
        }

        .employee-info {
            display: none;
        }





        .liveness-status {
            display: block;
        }

        .employee-info {
            display: none;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen">
    <!-- Back Button -->
    <button onclick="window.location.href='{{ route('facerecognition-presensi.index') }}'"
        class="fixed top-6 left-6 z-50 glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-200">
        <i class="ti ti-arrow-left mr-2"></i>Kembali
    </button>

    <!-- Test Voice Button -->
    {{-- <button onclick="testVoice()"
        class="fixed top-6 right-6 z-50 glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-200 bg-orange-500">
        <i class="ti ti-volume mr-2"></i>Test Suara
    </button> --}}

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-700 text-white p-6">
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-user-check text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Face Recognition Presensi</h1>
                        <p class="text-blue-100">Deteksi wajah untuk melakukan absen karyawan</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col lg:flex-row">
                <!-- Left Side - Information -->
                <div class="lg:w-1/2 p-8 bg-gray-50">
                    <!-- Time Display -->
                    <div class="text-center mb-8">
                        <div class="text-4xl font-mono font-bold text-gray-800 mb-2" id="timeDisplay"></div>
                        <div class="text-lg text-gray-600" id="dateDisplay"></div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="ti ti-info-circle text-blue-500 mr-2"></i>
                            Cara Menggunakan
                        </h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Arahkan wajah ke kamera dalam kotak biru
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Pastikan wajah terlihat jelas dan tidak blur
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Pastikan pencahayaan cukup terang
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Jaga jarak 30-50 cm dari kamera
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Sistem akan mendeteksi karyawan secara otomatis
                            </li>
                            {{-- <li class="flex items-start">
                                <i class="ti ti-volume text-orange-500 mr-3 mt-1"></i>
                                <span class="text-orange-600 font-medium">Fitur Suara:</span> Klik tombol "Test Suara" di pojok kanan atas untuk
                                menguji
                            </li> --}}
                        </ul>
                    </div>

                    <!-- Liveness Detection Status -->
                    <div class="liveness-status bg-yellow-50 rounded-xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-semibold text-yellow-800 mb-4 flex items-center">
                            <i class="ti ti-mouth text-yellow-500 mr-2"></i>
                            Liveness Detection
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span id="livenessStatus" class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu buka mulut...
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Buka Mulut:</span>
                                <span id="mouthOpenCount" class="text-sm font-medium text-gray-800">0 / 2</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">MAR:</span>
                                <span id="currentMAR" class="text-sm font-medium text-gray-800">0.000</span>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Instruksi:
                                </p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• Buka mulut 2 kali untuk verifikasi liveness</li>
                                    <li>• Pastikan wajah terlihat jelas</li>
                                    <li>• Jaga jarak 30-50 cm dari kamera</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Info -->
                    <div class="employee-info bg-blue-50 rounded-xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="ti ti-user text-blue-500 mr-2"></i>
                            Informasi Karyawan
                        </h3>
                        <div class="employee-details grid grid-cols-1 gap-3">
                            <!-- Employee details will be populated here -->
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="status-dropdown mb-4">
                        <label for="statusSelect" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="ti ti-clock mr-2"></i>Jenis Absen
                        </label>
                        <select id="statusSelect"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="1">Absen Masuk</option>
                            <option value="0">Absen Pulang</option>
                        </select>
                    </div>



                    <!-- Status Messages -->
                    <div class="status-message mt-6 p-4 rounded-xl font-semibold"></div>

                    <!-- Loading -->
                    <div class="loading mt-6 text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-gray-600">Memproses absen...</p>
                    </div>
                </div>

                <!-- Right Side - Camera -->
                <div class="lg:w-1/2 p-8 bg-white">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Face Recognition</h2>
                        <p class="text-gray-600">Arahkan wajah ke kamera untuk deteksi</p>
                    </div>

                    <!-- Face Recognition Container -->
                    <div class="relative" style="min-height: 400px;">
                        <div id="facedetection" class="webcam-container">
                            <!-- Video will be inserted here -->
                        </div>
                        <!-- Landmarks container - positioned absolutely over the video -->
                        <div id="faceLandmarks" class="face-landmarks" style="display: none;"></div>
                        <!-- Loading overlay -->
                        <div id="loadingOverlay" class="loading-overlay">
                            <div class="loading-content">
                                <div class="loading-icon"></div>
                                <div class="loading-text">Memuat sistem face recognition...</div>
                                <div class="loading-subtext">Mohon tunggu sebentar</div>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Info -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="ti ti-info-circle mr-2"></i>Status Sistem
                        </h4>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Kamera:</span>
                                <span id="cameraStatus" class="text-sm font-medium text-blue-600">Memuat...</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Face Recognition:</span>
                                <span id="faceStatus" class="text-sm font-medium text-blue-600">Memuat model...</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Error:</span>
                                <span id="cameraError" class="text-sm font-medium text-red-500">-</span>
                            </div>
                            {{-- <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Speech API:</span>
                                <span id="speechStatus" class="text-sm font-medium text-green-600">Checking...</span>
                            </div> --}}
                        </div>

                        <div class="grid grid-cols-1 gap-2">
                            <button onclick="restartCamera()"
                                class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors flex items-center justify-center">
                                <i class="ti ti-refresh mr-2"></i>Restart Camera
                            </button>
                            {{-- <button onclick="testVoice()"
                                class="bg-orange-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-orange-600 transition-colors flex items-center justify-center">
                                <i class="ti ti-volume mr-2"></i>Test Suara
                            </button> --}}
                            <div class="grid grid-cols-2 gap-2">
                                <button onclick="forceVideoDisplay()"
                                    class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors flex items-center justify-center">
                                    <i class="ti ti-eye mr-1"></i>Force Video
                                </button>
                                <button onclick="toggleLandmarks()" id="landmarkToggle"
                                    class="bg-emerald-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-emerald-600 transition-colors flex items-center justify-center">
                                    <i class="ti ti-eye mr-1"></i>Landmark
                                </button>
                            </div>
                            <button onclick="resetLivenessDetection()"
                                class="bg-orange-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-orange-600 transition-colors flex items-center justify-center hidden">
                                <i class="ti ti-refresh mr-1"></i>Reset Liveness
                            </button>
                            <button onclick="resetCounter()"
                                class="bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600 transition-colors flex items-center justify-center hidden">
                                <i class="ti ti-rotate mr-1"></i>Reset Counter
                            </button>
                            <button onclick="forceShowAbsenButtons()"
                                class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors flex items-center justify-center hidden">
                                <i class="ti ti-user-check mr-1"></i>Force Show Absen
                            </button>
                            <button onclick="toggleLivenessDetection()"
                                class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-600 transition-colors flex items-center justify-center hidden">
                                <i class="ti ti-toggle-right mr-1"></i>Disable Liveness
                            </button>
                            <div class="grid grid-cols-2 gap-2 hidden">
                                <button onclick="adjustThreshold('lower')"
                                    class="bg-purple-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-purple-600 transition-colors flex items-center justify-center">
                                    <i class="ti ti-minus mr-1"></i>Lower Threshold
                                </button>
                                <button onclick="adjustThreshold('higher')"
                                    class="bg-purple-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-purple-600 transition-colors flex items-center justify-center">
                                    <i class="ti ti-plus mr-1"></i>Higher Threshold
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Camera for Photo Capture -->
                    <div class="camera-container mt-6">
                        <video id="video" class="w-full rounded-xl" autoplay></video>
                    </div>

                    <!-- Canvas for Photo -->
                    <div class="canvas-container mt-6">
                        <canvas id="canvas" class="w-full rounded-xl"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let stream = null;
        let currentStatus = null;
        let currentEmployee = null;
        let currentJamKerja = null;
        let faceRecognitionDetected = 0;
        let lastProcessedNik = null; // Untuk mencegah pemrosesan berulang
        let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        let showLandmarks = false; // Control landmark visibility

        // Web Speech API untuk instruksi suara
        let speechSynthesis = window.speechSynthesis;
        let speechUtterance = null;
        let isSpeaking = false;

        // Liveness detection variables
        let livenessDetected = false;
        let mouthOpenCount = 0;
        let requiredMouthOpens = 2; // Jumlah buka mulut yang diperlukan
        let lastMouthOpenTime = 0;
        let mouthOpenCooldown = 500; // Cooldown 0.5 detik antara buka mulut (dikurangi untuk lebih responsif)
        let mouthAspectRatio = 0;
        let previousMouthAspectRatio = 0;
        let mouthOpenThreshold = 1.0; // Threshold untuk mendeteksi buka mulut (MAR lebih dari 1 sudah terdeteksi)
        let isInitialized = false; // Flag untuk inisialisasi awal
        let consecutiveDetections = 0; // Counter untuk deteksi berturut-turut

        // Loading overlay functions
        function showLoading(message) {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingText = loadingOverlay.querySelector('.loading-text');
            if (loadingText) {
                loadingText.textContent = message;
            }
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }



        // Draw face landmarks
        function drawLandmarks(detections, videoElement) {
            const landmarksContainer = document.getElementById('faceLandmarks');
            if (!landmarksContainer || !detections || detections.length === 0 || !showLandmarks) {
                console.log('Landmarks not drawn:', {
                    hasContainer: !!landmarksContainer,
                    hasDetections: !!detections,
                    detectionsLength: detections?.length,
                    showLandmarks: showLandmarks
                });
                return;
            }

            console.log('Drawing landmarks for', detections.length, 'detections');
            console.log('Landmarks container display:', landmarksContainer.style.display);
            console.log('Landmarks container position:', landmarksContainer.getBoundingClientRect());

            // Clear previous landmarks
            landmarksContainer.innerHTML = '';

            // Get video dimensions
            const videoRect = videoElement.getBoundingClientRect();
            const videoWidth = videoElement.videoWidth;
            const videoHeight = videoElement.videoHeight;

            console.log('Video dimensions:', {
                videoWidth,
                videoHeight,
                videoRect
            });

            detections.forEach(detection => {
                if (detection.landmarks) {
                    const landmarks = detection.landmarks.positions;

                    // Draw landmark points
                    landmarks.forEach((point, index) => {
                        const pointElement = document.createElement('div');
                        pointElement.className = 'landmark-point';

                        // Scale coordinates to match displayed video size
                        const scaledX = (point.x / videoWidth) * videoRect.width;
                        const scaledY = (point.y / videoHeight) * videoRect.height;

                        pointElement.style.left = `${scaledX}px`;
                        pointElement.style.top = `${scaledY}px`;
                        pointElement.title = `Landmark ${index}`;
                        landmarksContainer.appendChild(pointElement);
                    });

                    // Draw key facial features
                    drawFacialFeatures(landmarks, landmarksContainer, videoWidth, videoHeight, videoRect);
                }
            });
        }

        // Draw specific facial features
        function drawFacialFeatures(landmarks, container, videoWidth, videoHeight, videoRect) {
            // Eye landmarks (points 36-47)
            drawFeatureLine(landmarks, 36, 37, container, '#ff6b6b', videoWidth, videoHeight, videoRect); // Left eye
            drawFeatureLine(landmarks, 37, 38, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 38, 39, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 39, 40, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 40, 41, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 41, 36, container, '#ff6b6b', videoWidth, videoHeight, videoRect);

            drawFeatureLine(landmarks, 42, 43, container, '#ff6b6b', videoWidth, videoHeight, videoRect); // Right eye
            drawFeatureLine(landmarks, 43, 44, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 44, 45, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 45, 46, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 46, 47, container, '#ff6b6b', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 47, 42, container, '#ff6b6b', videoWidth, videoHeight, videoRect);

            // Nose landmarks (points 27-35)
            drawFeatureLine(landmarks, 27, 28, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 28, 29, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 29, 30, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 30, 31, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 31, 32, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 32, 33, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 33, 34, container, '#4ecdc4', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 34, 35, container, '#4ecdc4', videoWidth, videoHeight, videoRect);

            // Mouth landmarks (points 48-67)
            drawFeatureLine(landmarks, 48, 49, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 49, 50, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 50, 51, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 51, 52, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 52, 53, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 53, 54, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 54, 55, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 55, 56, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 56, 57, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 57, 58, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 58, 59, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 59, 48, container, '#ffa726', videoWidth, videoHeight, videoRect);

            // Inner mouth
            drawFeatureLine(landmarks, 60, 61, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 61, 62, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 62, 63, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 63, 64, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 64, 65, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 65, 66, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 66, 67, container, '#ffa726', videoWidth, videoHeight, videoRect);
            drawFeatureLine(landmarks, 67, 60, container, '#ffa726', videoWidth, videoHeight, videoRect);

            // Face contour (points 0-16)
            for (let i = 0; i < 16; i++) {
                drawFeatureLine(landmarks, i, i + 1, container, '#00ff00', videoWidth, videoHeight, videoRect);
            }
            drawFeatureLine(landmarks, 16, 0, container, '#00ff00', videoWidth, videoHeight, videoRect);
        }

        // Calculate Mouth Aspect Ratio (MAR) - Improved version
        function calculateMAR(landmarks) {
            // Use specific mouth landmarks for better accuracy
            // Vertical distance: from top lip to bottom lip
            const topLip = landmarks[51]; // Top lip center
            const bottomLip = landmarks[57]; // Bottom lip center

            // Horizontal distance: from left corner to right corner
            const leftCorner = landmarks[48]; // Left mouth corner
            const rightCorner = landmarks[54]; // Right mouth corner

            // Calculate vertical and horizontal distances
            const verticalDistance = euclideanDistance(topLip, bottomLip);
            const horizontalDistance = euclideanDistance(leftCorner, rightCorner);

            // MAR = vertical / horizontal
            const mar = verticalDistance / horizontalDistance;

            return mar;
        }

        // Calculate Euclidean distance between two points
        function euclideanDistance(point1, point2) {
            return Math.sqrt(Math.pow(point2.x - point1.x, 2) + Math.pow(point2.y - point1.y, 2));
        }

        // Detect mouth open based on Mouth Aspect Ratio - Improved version
        function detectMouthOpen(landmarks) {
            if (!landmarks || landmarks.length < 68) return false;

            // Calculate MAR using improved function
            const mar = calculateMAR(landmarks);

            // Initialize on first detection
            if (!isInitialized) {
                previousMouthAspectRatio = mar;
                mouthAspectRatio = mar;
                isInitialized = true;
                console.log('Liveness detection initialized with MAR:', mar.toFixed(3));
                return false;
            }

            // Update previous MAR
            previousMouthAspectRatio = mouthAspectRatio;
            mouthAspectRatio = mar;

            // Debug: Log MAR values more frequently
            if (Math.random() < 0.3) { // Log 30% of the time
                console.log(`MAR: ${mar.toFixed(3)}, Threshold: ${mouthOpenThreshold}, Previous: ${previousMouthAspectRatio.toFixed(3)}`);
            }

            // Detect mouth open: MAR increases when mouth opens
            const now = Date.now();

            // More sensitive detection: check if MAR increased significantly
            const marIncrease = mar - previousMouthAspectRatio;
            const isMouthOpening = mar > mouthOpenThreshold; // MAR lebih dari 1 sudah terdeteksi

            if (isMouthOpening) {
                consecutiveDetections++;
                console.log(
                    `Potential mouth open detected! Consecutive: ${consecutiveDetections}, MAR: ${mar.toFixed(3)}, Increase: ${marIncrease.toFixed(3)}`
                );

                // Require at least 2 consecutive detections to confirm mouth open (dikurangi dari 3)
                if (consecutiveDetections >= 2) {
                    if (now - lastMouthOpenTime > mouthOpenCooldown) {
                        // Prevent counter from exceeding required count
                        if (mouthOpenCount < requiredMouthOpens) {
                            mouthOpenCount++;
                            lastMouthOpenTime = now;
                            console.log(
                                `Mouth open confirmed! Count: ${mouthOpenCount}/${requiredMouthOpens}, MAR: ${mar.toFixed(3)}, Increase: ${marIncrease.toFixed(3)}`
                            );

                            // Update liveness status
                            if (mouthOpenCount >= requiredMouthOpens) {
                                livenessDetected = true;
                                console.log('Liveness detection completed!');
                                showStatus('Liveness detection berhasil! Silakan pilih jenis absen.', 'success');

                                // Instruksi suara saat liveness detection selesai dengan waktu
                                const currentTime = getCurrentTimeForSpeech();
                                //speakInstruction(`Liveness detection berhasil! Sekarang ${currentTime}. Silakan pilih jenis absen.`);



                                // Force update UI and check for employee data
                                setTimeout(() => {
                                    updateLivenessUI();
                                    // If we have a recognized employee, show their info
                                    if (lastProcessedNik) {
                                        getEmployeeData(lastProcessedNik);
                                    }
                                }, 1000);
                            }
                        }
                    }
                    consecutiveDetections = 0; // Reset consecutive counter
                    return true;
                }
            } else {
                // Reset consecutive counter if no mouth opening detected
                consecutiveDetections = 0;
            }

            return false;
        }

        // Draw line between two landmark points
        function drawFeatureLine(landmarks, startIndex, endIndex, container, color, videoWidth, videoHeight, videoRect) {
            if (landmarks[startIndex] && landmarks[endIndex]) {
                const start = landmarks[startIndex];
                const end = landmarks[endIndex];

                // Scale coordinates to match displayed video size
                const scaledStartX = (start.x / videoWidth) * videoRect.width;
                const scaledStartY = (start.y / videoHeight) * videoRect.height;
                const scaledEndX = (end.x / videoWidth) * videoRect.width;
                const scaledEndY = (end.y / videoHeight) * videoRect.height;

                const lineElement = document.createElement('div');
                lineElement.className = 'landmark-line';
                lineElement.style.background = color;
                lineElement.style.left = `${scaledStartX}px`;
                lineElement.style.top = `${scaledStartY}px`;

                const length = Math.sqrt(Math.pow(scaledEndX - scaledStartX, 2) + Math.pow(scaledEndY - scaledStartY, 2));
                const angle = Math.atan2(scaledEndY - scaledStartY, scaledEndX - scaledStartX) * 180 / Math.PI;

                lineElement.style.width = `${length}px`;
                lineElement.style.transform = `rotate(${angle}deg)`;
                lineElement.style.transformOrigin = '0 0';

                container.appendChild(lineElement);
            }
        }

        // Toggle landmark visibility
        function toggleLandmarks() {
            showLandmarks = !showLandmarks;
            const button = document.getElementById('landmarkToggle');
            const landmarksContainer = document.getElementById('faceLandmarks');

            console.log('Toggle landmarks:', showLandmarks);
            console.log('Landmarks container found:', !!landmarksContainer);

            if (showLandmarks) {
                button.innerHTML = '<i class="ti ti-eye-off mr-1"></i>Sembunyikan';
                button.className =
                    'bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600 transition-colors flex items-center justify-center';
                if (landmarksContainer) {
                    landmarksContainer.style.display = 'block';
                    console.log('Landmarks container displayed');
                } else {
                    console.error('Landmarks container not found!');
                }
            } else {
                button.innerHTML = '<i class="ti ti-eye mr-1"></i>Landmark';
                button.className =
                    'bg-emerald-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-emerald-600 transition-colors flex items-center justify-center';
                if (landmarksContainer) {
                    landmarksContainer.style.display = 'none';
                    console.log('Landmarks container hidden');
                } else {
                    console.error('Landmarks container not found!');
                }
            }
        }

        // Update waktu real-time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('timeDisplay').textContent = timeString;
            document.getElementById('dateDisplay').textContent = dateString;
        }

        // Update waktu setiap detik
        setInterval(updateTime, 1000);
        updateTime();

        // Fungsi untuk mendapatkan waktu dalam format yang bisa digunakan untuk suara
        function getCurrentTimeForSpeech() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();

            // Format jam dalam bahasa Indonesia
            let timeString = '';

            if (hours < 12) {
                timeString = `pukul ${hours} lewat ${minutes} menit pagi`;
            } else if (hours < 15) {
                timeString = `pukul ${hours} lewat ${minutes} menit siang`;
            } else if (hours < 18) {
                timeString = `pukul ${hours} lewat ${minutes} menit sore`;
            } else {
                timeString = `pukul ${hours} lewat ${minutes} menit malam`;
            }

            return timeString;
        }

        // Initialize Face Recognition
        async function initFaceRecognition() {
            try {
                showLoading('Memuat sistem face recognition...');
                document.getElementById('cameraStatus').textContent = 'Inisialisasi kamera...';
                document.getElementById('faceStatus').textContent = 'Memuat model face recognition...';

                // Start camera
                await startCamera();

                // Load face recognition models
                const modelLoadingPromise = isMobile ? Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]) : Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]);

                modelLoadingPromise.then(() => {
                    document.getElementById('faceStatus').textContent = 'Model siap, memulai deteksi...';
                    startFaceRecognition();
                }).catch(err => {
                    console.error("Error loading models:", err);
                    document.getElementById('faceStatus').textContent = 'Error memuat model';
                    document.getElementById('cameraError').textContent = err.message;
                    hideLoading();
                });

            } catch (error) {
                console.error('Error initializing face recognition:', error);
                document.getElementById('cameraStatus').textContent = 'Error inisialisasi';
                document.getElementById('cameraError').textContent = error.message;
                hideLoading();
            }
        }

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            min: 320,
                            ideal: 640,
                            max: 1280
                        },
                        height: {
                            min: 240,
                            ideal: 480,
                            max: 720
                        },
                        facingMode: 'user',
                        frameRate: {
                            ideal: 30,
                            max: 30
                        }
                    }
                });

                const video = document.createElement('video');
                video.id = 'faceVideo'; // Tambahkan ID untuk memudahkan selector
                video.srcObject = stream;
                video.autoplay = true;
                video.muted = true;
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.borderRadius = '16px';

                // Event listener untuk memastikan video sudah siap
                video.addEventListener('loadedmetadata', () => {
                    console.log('Video metadata loaded:', video.videoWidth, 'x', video.videoHeight);
                });

                video.addEventListener('canplay', () => {
                    console.log('Video can play:', video.videoWidth, 'x', video.videoHeight);
                });

                // Tunggu video siap sebelum melanjutkan
                await new Promise((resolve) => {
                    if (video.readyState >= 2) {
                        resolve();
                    } else {
                        video.addEventListener('canplay', resolve, {
                            once: true
                        });
                    }
                });

                const facedetection = document.getElementById('facedetection');
                // Clear only video elements, not the entire container
                const existingVideos = facedetection.querySelectorAll('video');
                existingVideos.forEach(video => video.remove());
                facedetection.appendChild(video);

                document.getElementById('cameraStatus').textContent = 'Kamera siap';

            } catch (error) {
                console.error('Error accessing camera:', error);
                document.getElementById('cameraStatus').textContent = 'Error akses kamera';
                document.getElementById('cameraError').textContent = error.message;
                throw error;
            }
        }

        // Get all employee face data
        async function getAllEmployeeFaceData() {
            try {
                const timestamp = new Date().getTime();
                const response = await fetch(`{{ route('facerecognition.getallwajah') }}?t=${timestamp}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('All employee face data:', data);

                // Ensure we return an array
                if (Array.isArray(data)) {
                    return data;
                } else if (data && Array.isArray(data.data)) {
                    return data.data;
                } else {
                    console.error('Unexpected data format:', data);
                    return [];
                }
            } catch (error) {
                console.error('Error getting employee face data:', error);
                return [];
            }
        }

        // Get labeled face descriptions for all employees
        async function getLabeledFaceDescriptions() {
            try {
                const allEmployeeData = await getAllEmployeeFaceData();
                console.log('Raw employee data:', allEmployeeData);

                // Check if data is valid
                if (!Array.isArray(allEmployeeData)) {
                    console.error('Employee data is not an array:', allEmployeeData);
                    return [];
                }

                const labeledFaceDescriptors = [];

                for (const employee of allEmployeeData) {
                    console.log('Processing employee:', employee);

                    if (!employee.nik || !employee.nama_karyawan) {
                        console.warn('Employee missing required fields:', employee);
                        continue;
                    }

                    const label = `${employee.nik}-${employee.nama_karyawan}`;
                    const descriptions = [];

                    // Process each face image for this employee
                    const wajahData = employee.wajah_data || [];
                    console.log(`Processing ${wajahData.length} face images for ${label}`);

                    for (const faceData of wajahData) {
                        try {
                            if (!faceData.wajah) {
                                console.warn('Face data missing wajah field:', faceData);
                                continue;
                            }

                            // Get first name (nama depan)
                            const namaDepan = employee.nama_karyawan.toLowerCase().split(' ')[0];
                            const imagePath = `/storage/uploads/facerecognition/${employee.nik}-${namaDepan}/${faceData.wajah}?t=${Date.now()}`;
                            console.log('Loading image:', imagePath);

                            const img = await faceapi.fetchImage(imagePath);

                            let detections;
                            if (isMobile) {
                                detections = await faceapi.detectSingleFace(
                                    img, new faceapi.TinyFaceDetectorOptions({
                                        inputSize: 160,
                                        scoreThreshold: 0.5
                                    })
                                ).withFaceLandmarks().withFaceDescriptor();
                            } else {
                                detections = await faceapi.detectSingleFace(
                                    img, new faceapi.SsdMobilenetv1Options({
                                        minConfidence: 0.5
                                    })
                                ).withFaceLandmarks().withFaceDescriptor();
                            }

                            if (detections) {
                                descriptions.push(detections.descriptor);
                                console.log(`Successfully processed face for ${label}`);
                            }
                        } catch (err) {
                            console.error(`Error processing face for ${label}:`, err);
                        }
                    }

                    if (descriptions.length > 0) {
                        labeledFaceDescriptors.push(new faceapi.LabeledFaceDescriptors(label, descriptions));
                        console.log(`Added ${descriptions.length} face descriptors for ${label}`);
                    } else {
                        console.warn(`No valid face descriptors found for ${label}`);
                    }
                }

                console.log(`Total labeled face descriptors: ${labeledFaceDescriptors.length}`);
                return labeledFaceDescriptors;
            } catch (error) {
                console.error('Error in getLabeledFaceDescriptions:', error);
                return [];
            }
        }

        // Start face recognition
        async function startFaceRecognition() {
            try {
                const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                let faceMatcher = null;

                if (labeledFaceDescriptors.length === 0) {
                    document.getElementById('faceStatus').textContent = 'Tidak ada data wajah karyawan, mode deteksi wajah saja';
                } else {
                    faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
                    document.getElementById('faceStatus').textContent = 'Face recognition aktif';
                }
                const video = document.querySelector('#facedetection video');

                if (!video) {
                    console.error('Video element tidak ditemukan');
                    setTimeout(startFaceRecognition, 1000);
                    return;
                }

                // Wait for video to be ready
                if (!video.videoWidth || !video.videoHeight || video.readyState < 2) {
                    console.log('Video belum ready, waiting...');
                    setTimeout(startFaceRecognition, 500);
                    return;
                }

                console.log('Video ready:', video.videoWidth, 'x', video.videoHeight);

                const canvas = faceapi.createCanvasFromMedia(video);
                canvas.style.position = 'absolute';
                canvas.style.top = '0';
                canvas.style.left = '0';
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.pointerEvents = 'none';
                canvas.style.zIndex = '10';
                canvas.style.borderRadius = '16px';

                const facedetection = document.getElementById('facedetection');
                facedetection.appendChild(canvas);

                const ctx = canvas.getContext("2d");
                const displaySize = {
                    width: video.videoWidth,
                    height: video.videoHeight
                };
                faceapi.matchDimensions(canvas, displaySize);

                document.getElementById('faceStatus').textContent = 'Face recognition aktif';
                hideLoading(); // Hide loading after everything is ready

                let lastDetectionTime = 0;
                let detectionInterval = isMobile ? 400 : 100;
                let isProcessing = false;

                async function detectFaces() {
                    try {
                        if (video.paused || video.ended) {
                            return [];
                        }

                        if (isMobile) {
                            const detection = await faceapi.detectSingleFace(
                                video, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 160,
                                    scoreThreshold: 0.4
                                })
                            ).withFaceLandmarks().withFaceDescriptor();
                            return detection ? [detection] : [];
                        } else {
                            const detection = await faceapi.detectSingleFace(
                                video, new faceapi.SsdMobilenetv1Options({
                                    minConfidence: 0.5
                                })
                            ).withFaceLandmarks().withFaceDescriptor();
                            return detection ? [detection] : [];
                        }
                    } catch (error) {
                        console.error("Error dalam deteksi wajah:", error);
                        return [];
                    }
                }

                function updateCanvas() {
                    if (!video || !canvas || !ctx) {
                        return;
                    }

                    if (!video.videoWidth || !video.videoHeight) {
                        setTimeout(updateCanvas, 500);
                        return;
                    }

                    if (!isProcessing) {
                        const now = Date.now();
                        if (now - lastDetectionTime > detectionInterval) {
                            isProcessing = true;
                            lastDetectionTime = now;

                            detectFaces().then(detections => {
                                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                                ctx.clearRect(0, 0, canvas.width, canvas.height);

                                // Draw landmarks
                                drawLandmarks(resizedDetections, video);

                                if (resizedDetections && resizedDetections.length > 0) {
                                    const detection = resizedDetections[0];

                                    // Perform liveness detection
                                    if (detection.landmarks) {
                                        detectMouthOpen(detection.landmarks.positions);
                                        updateLivenessUI();
                                    }

                                    const box = detection.detection.box;

                                    let boxColor, labelColor, labelText;

                                    if (faceMatcher) {
                                        // Ada data wajah untuk matching
                                        const match = faceMatcher.findBestMatch(detection.descriptor);
                                        const isUnknown = match.toString().includes("unknown");
                                        const isNotRecognized = match.distance > 0.55;

                                        if (isUnknown || isNotRecognized) {
                                            boxColor = '#FFC107';
                                            labelColor = '#000000';
                                            labelText = 'Wajah Tidak Dikenali';
                                            faceRecognitionDetected = 0;

                                            // Reset liveness detection dan UI jika wajah tidak dikenali
                                            if (livenessDetected || currentEmployee) {
                                                resetLivenessDetection();
                                                resetUIForNewEmployee();
                                            }
                                        } else {
                                            boxColor = '#28A745';
                                            labelColor = '#FFFFFF';
                                            labelText = match.toString();
                                            faceRecognitionDetected = 1;

                                            // Extract employee info from match
                                            const matchLabel = match.toString();
                                            const nik = matchLabel.split('-')[0];

                                            console.log('Extracted NIK from match:', nik);
                                            console.log('Full match label:', matchLabel);

                                            // Validate NIK before getting employee data
                                            if (nik && nik.trim() !== '') {
                                                // Only process if liveness detection is completed
                                                if (livenessDetected) {
                                                    // Prevent duplicate processing
                                                    if (lastProcessedNik !== nik) {
                                                        lastProcessedNik = nik;
                                                        // Get employee data
                                                        getEmployeeData(nik);
                                                    }
                                                } else {
                                                    // Show message to complete liveness detection
                                                    if (lastProcessedNik !== nik) {
                                                        lastProcessedNik = nik;

                                                        // Reset liveness detection untuk wajah baru
                                                        resetLivenessDetection();

                                                        // Reset UI hanya jika ada karyawan yang sedang aktif
                                                        if (currentEmployee) {
                                                            resetUIForNewEmployee();
                                                        }

                                                        showStatus('Wajah dikenali! Silakan buka mulut 2 kali untuk verifikasi liveness.',
                                                            'info');

                                                        // Instruksi suara otomatis untuk karyawan yang dikenali
                                                        const employeeName = matchLabel.split('-').slice(1).join(' ');
                                                        // Bersihkan nama dari angka dan karakter khusus
                                                        const cleanEmployeeName = employeeName.replace(/[0-9().]/g, '').trim();
                                                        //alert(cleanEmployeeName);
                                                        speakInstruction(
                                                            `Hi, ${cleanEmployeeName}. Silakan buka mulut 2 kali untuk melakukan presensi`
                                                        );

                                                    }
                                                }
                                            } else {
                                                console.error('Invalid NIK extracted:', nik);
                                                showStatus('NIK tidak valid dari face recognition', 'error');
                                            }
                                        }
                                    } else {
                                        // Tidak ada data wajah, hanya deteksi wajah
                                        boxColor = '#3B82F6';
                                        labelColor = '#FFFFFF';
                                        labelText = 'Wajah Terdeteksi';
                                        faceRecognitionDetected = 0;
                                    }

                                    // Draw detection box
                                    const drawBox = new faceapi.draw.DrawBox(box, {
                                        label: labelText,
                                        boxColor: boxColor,
                                        labelColor: labelColor
                                    });
                                    drawBox.draw(canvas);
                                } else {
                                    faceRecognitionDetected = 0;

                                    // Reset liveness detection dan UI jika tidak ada wajah terdeteksi
                                    if (livenessDetected || currentEmployee) {
                                        resetLivenessDetection();
                                        resetUIForNewEmployee();
                                    }
                                }

                                isProcessing = false;
                            });
                        }
                    }

                    requestAnimationFrame(updateCanvas);
                }

                updateCanvas();

            } catch (error) {
                console.error('Error in startFaceRecognition:', error);
                document.getElementById('faceStatus').textContent = 'Error face recognition';
                document.getElementById('cameraError').textContent = error.message;
            }
        }

        // Get employee data from server
        async function getEmployeeData(nik) {
            const maxRetries = 3;
            let retryCount = 0;

            while (retryCount < maxRetries) {
                try {
                    console.log(`Getting employee data for NIK: ${nik} (attempt ${retryCount + 1})`);
                    const response = await fetch(`{{ route('facerecognition-presensi.generate', ['nik' => ':nik']) }}`.replace(':nik', nik));

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Employee data response:', data);

                    if (data.status && data.karyawan) {
                        currentEmployee = data.karyawan;
                        currentJamKerja = data.jam_kerja;
                        showEmployeeInfo(data.karyawan, data.jam_kerja);
                        showStatus('Karyawan terdeteksi! Silakan pilih jenis absen.', 'success');
                        return; // Success, exit retry loop
                    } else {
                        showStatus(data.message || 'Data karyawan tidak ditemukan', 'error');
                        return; // Exit retry loop
                    }
                } catch (error) {
                    console.error(`Error getting employee data (attempt ${retryCount + 1}):`, error);
                    retryCount++;

                    if (retryCount >= maxRetries) {
                        showStatus('Terjadi kesalahan saat mengambil data karyawan: ' + error.message, 'error');
                    } else {
                        // Wait before retry
                        await new Promise(resolve => setTimeout(resolve, 1000 * retryCount));
                    }
                }
            }
        }

        // Show employee information
        function showEmployeeInfo(employee, jam_kerja) {
            const employeeInfo = document.querySelector('.employee-info');
            const employeeDetails = document.querySelector('.employee-details');
            const livenessStatus = document.querySelector('.liveness-status');

            employeeDetails.innerHTML = `
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="ti ti-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">${employee.nama_karyawan}</h4>
                            <p class="text-sm text-gray-600">NIK: ${employee.nik}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium ${employee.status_aktif_karyawan == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${employee.status_aktif_karyawan == '1' ? 'Aktif' : 'Tidak Aktif'}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Jabatan:</span>
                            <span class="ml-2 font-medium">${employee.nama_jabatan || employee.kode_jabatan || '-'}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Jam Kerja:</span>
                            <span class="ml-2 font-medium">${jam_kerja ? jam_kerja.nama_jam_kerja + ' (' + jam_kerja.jam_masuk + ' - ' + jam_kerja.jam_pulang + ')' : '-'}</span>
                        </div>
                    </div>
                </div>
            `;

            employeeInfo.style.display = 'block';



            // Hide liveness status after successful detection
            if (livenessStatus) {
                livenessStatus.style.display = 'none';
            }

            // Langsung proses absen setelah liveness detection berhasil
            setTimeout(() => {
                processAbsen();
            }, 1000);
        }



        // Start absen process
        function startAbsenProcess(status) {
            currentStatus = status;



            // Show camera for photo capture
            startPhotoCapture();
        }

        // Start photo capture
        async function startPhotoCapture() {
            try {
                console.log('Starting photo capture...');

                // Gunakan stream global yang sudah ada
                console.log('Checking stream availability:', !!stream);
                if (!stream) {
                    console.error('Stream tidak tersedia');
                    showStatus('Kamera tidak tersedia', 'error');
                    return;
                }

                console.log('Stream tracks:', stream.getTracks().length);
                const videoTrack = stream.getVideoTracks()[0];
                if (!videoTrack || videoTrack.readyState !== 'live') {
                    console.error('Video track tidak aktif');
                    showStatus('Video track tidak aktif', 'error');
                    return;
                }
                console.log('Video track state:', videoTrack.readyState);

                // Buat video element temporary untuk capture
                const tempVideo = document.createElement('video');
                tempVideo.srcObject = stream;
                tempVideo.autoplay = true;
                tempVideo.muted = true;
                tempVideo.style.position = 'absolute';
                tempVideo.style.left = '-9999px'; // Hide video

                // Append ke body untuk memastikan video bisa diakses
                document.body.appendChild(tempVideo);

                // Tunggu video siap
                await new Promise((resolve) => {
                    tempVideo.addEventListener('loadedmetadata', () => {
                        console.log('Temp video loaded:', tempVideo.videoWidth, 'x', tempVideo.videoHeight);
                        resolve();
                    }, {
                        once: true
                    });
                });

                // Tunggu video benar-benar siap
                await new Promise(resolve => setTimeout(resolve, 500));

                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');

                // Set canvas size sesuai video
                canvas.width = tempVideo.videoWidth;
                canvas.height = tempVideo.videoHeight;

                console.log('Canvas size set to:', canvas.width, 'x', canvas.height);

                // Capture foto langsung dari video temp
                context.drawImage(tempVideo, 0, 0);

                console.log('Foto berhasil di-capture:', canvas.width, 'x', canvas.height);

                // Verifikasi canvas tidak kosong dengan cek pixel data
                const imagePixelData = context.getImageData(0, 0, canvas.width, canvas.height);
                const pixels = imagePixelData.data;
                let hasContent = false;

                // Cek apakah ada pixel yang tidak hitam (0,0,0,255)
                for (let i = 0; i < pixels.length; i += 4) {
                    if (pixels[i] !== 0 || pixels[i + 1] !== 0 || pixels[i + 2] !== 0) {
                        hasContent = true;
                        break;
                    }
                }

                console.log('Canvas has visual content:', hasContent);

                // Debug: cek apakah canvas berisi data
                const imageData = canvas.toDataURL('image/png');
                console.log('Canvas image data length:', imageData.length);
                console.log('Canvas image data preview:', imageData.substring(0, 100) + '...');

                if (!hasContent) {
                    console.error('Canvas hanya berisi pixel hitam');
                    showStatus('Foto terlihat kosong/hitam. Silakan coba lagi.', 'error');
                    return;
                }

                // Hapus video temporary
                document.body.removeChild(tempVideo);

                if (imageData.length < 100) {
                    console.error('Canvas kosong setelah capture');
                    showStatus('Gagal mengambil foto. Canvas kosong.', 'error');
                    return;
                }

                document.querySelector('.canvas-container').style.display = 'block';

                // Process absen
                processAbsen();

            } catch (error) {
                console.error('Error capturing photo:', error);
                showStatus('Tidak dapat mengambil foto. Silakan coba lagi.', 'error');
            }
        }

        // Process absen
        async function processAbsen() {
            const canvas = document.getElementById('canvas');
            const imageData = canvas.toDataURL('image/png');

            // Debug: cek apakah image data berisi data
            console.log('Image data length:', imageData.length);
            console.log('Image data preview:', imageData.substring(0, 100) + '...');

            if (imageData.length < 100) {
                console.error('Image data terlalu pendek, kemungkinan kosong');
                showStatus('Gagal mengambil foto. Silakan coba lagi.', 'error');
                return;
            }

            // Get location
            let location = '';
            if (navigator.geolocation) {
                try {
                    const position = await getCurrentPosition();
                    location = `${position.coords.latitude},${position.coords.longitude}`;
                } catch (error) {
                    console.error('Error getting location:', error);
                    location = '0,0';
                }
            } else {
                location = '0,0';
            }

            // Show loading
            document.querySelector('.loading').style.display = 'block';

            // Send data to server
            try {
                const response = await fetch('{{ route('facerecognition-presensi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nik: currentEmployee.nik,
                        status: parseInt(document.getElementById('statusSelect').value),
                        image: imageData,
                        lokasi: location,
                        lokasi_unit: location,
                        kode_jam_kerja: currentJamKerja.kode_jam_kerja
                    })
                });

                const result = await response.json();

                if (result.status) {
                    showStatus(result.message, 'success');
                    //playNotificationSound('success');

                    // Instruksi suara saat absen berhasil dengan waktu
                    const currentTime = getCurrentTimeForSpeech();
                    const selectedStatus = parseInt(document.getElementById('statusSelect').value);
                    const absenType = selectedStatus === 1 ? 'masuk' : 'pulang';
                    //speakInstruction(`Presensi ${absenType} berhasil dilakukan pada ${currentTime}. Terima kasih!`);

                    // SweetAlert untuk absen berhasil
                    const employeeName = currentEmployee ? currentEmployee.nama_karyawan : 'Karyawan';
                    const jamAbsen = new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });

                    Swal.fire({
                        title: 'Presensi Berhasil!',
                        html: `
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle text-6xl text-green-500"></i>
                                </div>
                                <div class="mb-2">
                                    <strong>${employeeName}</strong>
                                </div>
                                <div class="mb-2">
                                    <span class="text-lg font-semibold text-green-600">
                                        ${absenType.toUpperCase()} - ${jamAbsen}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    ${result.message}
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10B981',
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });

                    // Reset after successful absen
                    setTimeout(() => {
                        resetFaceRecognition();
                    }, 3000);

                    // Hide canvas after 3 seconds
                    setTimeout(() => {
                        document.querySelector('.canvas-container').style.display = 'none';
                    }, 3000);
                } else {
                    showStatus(result.message, 'error');
                    playNotificationSound('error', result.notifikasi);

                    // SweetAlert untuk absen gagal
                    Swal.fire({
                        title: 'Presensi Gagal!',
                        text: result.message,
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                }

            } catch (error) {
                console.error('Error sending data:', error);
                showStatus('Terjadi kesalahan saat mengirim data', 'error');
            }

            // Hide loading and enable buttons
            document.querySelector('.loading').style.display = 'none';
            enableButtons();

            // Hide canvas after 3 seconds
            setTimeout(() => {
                document.querySelector('.canvas-container').style.display = 'none';
            }, 3000);
        }

        // Reset face recognition (untuk setelah absen berhasil)
        function resetFaceRecognition() {
            currentEmployee = null;
            currentStatus = null;
            lastProcessedNik = null; // Reset processed NIK untuk karyawan baru

            // Reset UI
            const employeeInfo = document.querySelector('.employee-info');
            const statusDropdown = document.querySelector('.status-dropdown');
            const livenessStatus = document.querySelector('.liveness-status');

            if (employeeInfo) {
                employeeInfo.style.display = 'none';
            }
            if (statusDropdown) {
                statusDropdown.style.display = 'none';
            }
            if (livenessStatus) {
                livenessStatus.style.display = 'block';
            }

            // Sembunyikan canvas container
            const canvasContainer = document.querySelector('.canvas-container');
            if (canvasContainer) {
                canvasContainer.style.display = 'none';
            }

            document.getElementById('faceStatus').textContent = 'Face recognition aktif';

            // Reset liveness detection
            resetLivenessDetection();

            console.log('Face recognition direset untuk karyawan baru');
        }

        // Restart camera
        function restartCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            document.getElementById('facedetection').innerHTML = '';
            document.getElementById('cameraStatus').textContent = 'Restarting...';
            document.getElementById('faceStatus').textContent = 'Memuat model...';
            document.getElementById('cameraError').textContent = '-';
            showLoading('Restarting camera...');
            setTimeout(() => {
                initFaceRecognition();
            }, 1000);
        }

        // Force video display
        function forceVideoDisplay() {
            const videoElements = document.querySelectorAll('#facedetection video');
            console.log('Found video elements:', videoElements.length);

            videoElements.forEach((video, index) => {
                console.log(`Video ${index}:`, video);
                console.log(`Video ${index} display:`, getComputedStyle(video).display);
                console.log(`Video ${index} width:`, video.videoWidth);
                console.log(`Video ${index} height:`, video.videoHeight);

                // Force display
                video.style.display = 'block';
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.minHeight = '300px';
                video.style.position = 'relative';
                video.style.zIndex = '10';

                console.log(`Video ${index} after force display:`, getComputedStyle(video).display);
            });
        }



        // Get current position
        function getCurrentPosition() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                });
            });
        }

        // Fungsi untuk berbicara instruksi suara
        function speakInstruction(text) {
            // Cek apakah Web Speech API tersedia
            if (!window.speechSynthesis) {
                console.error('Web Speech API tidak didukung');
                return;
            }

            // Hentikan ucapan sebelumnya jika masih berbicara
            if (isSpeaking) {
                speechSynthesis.cancel();
            }

            // Buat utterance baru
            speechUtterance = new SpeechSynthesisUtterance(text);

            // Set properti suara
            speechUtterance.lang = 'id-ID';
            speechUtterance.rate = 0.8; // Lebih lambat agar jelas
            speechUtterance.pitch = 1.0;
            speechUtterance.volume = 1.0;

            // Coba dapatkan voice Indonesia jika tersedia
            const voices = speechSynthesis.getVoices();
            const indonesianVoice = voices.find(voice =>
                voice.lang.includes('id') || voice.lang.includes('ID')
            );

            if (indonesianVoice) {
                speechUtterance.voice = indonesianVoice;
                console.log('Menggunakan voice Indonesia:', indonesianVoice.name);
            } else {
                // Fallback ke voice default jika tidak ada voice Indonesia
                const defaultVoice = voices.find(voice =>
                    voice.lang.includes('en') || voice.lang.includes('EN')
                );
                if (defaultVoice) {
                    speechUtterance.voice = defaultVoice;
                    console.log('Menggunakan voice default:', defaultVoice.name);
                }
            }

            // Event handlers
            speechUtterance.onstart = () => {
                isSpeaking = true;
                console.log('Mulai berbicara:', text);
            };

            speechUtterance.onend = () => {
                isSpeaking = false;
                console.log('Selesai berbicara');
            };

            speechUtterance.onerror = (event) => {
                isSpeaking = false;
                console.error('Error dalam speech synthesis:', event.error);
            };

            // Mulai berbicara
            try {
                // Pastikan speech synthesis tidak dalam keadaan paused
                if (speechSynthesis.paused) {
                    speechSynthesis.resume();
                }

                speechSynthesis.speak(speechUtterance);
                console.log('Memulai speech synthesis');
            } catch (error) {
                console.error('Error saat memulai speech synthesis:', error);
            }
        }

        // Test suara
        function testVoice() {
            // Untuk Chrome, perlu user interaction terlebih dahulu
            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
            }

            // Tampilkan pesan
            showStatus('Menguji sistem suara...', 'success');

            // Test suara
            speakInstruction('Test suara. Sistem presensi face recognition berfungsi dengan baik.');

            console.log('Tombol test suara ditekan');
        }

        // Update liveness detection UI
        function updateLivenessUI() {
            const livenessStatus = document.getElementById('livenessStatus');
            const mouthOpenCountElement = document.getElementById('mouthOpenCount');
            const currentMARElement = document.getElementById('currentMAR');

            if (livenessDetected) {
                livenessStatus.textContent = 'Liveness terverifikasi';
                livenessStatus.className = 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
            } else {
                livenessStatus.textContent = 'Menunggu buka mulut...';
                livenessStatus.className = 'px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
            }

            mouthOpenCountElement.textContent = `${mouthOpenCount} / ${requiredMouthOpens}`;

            // Update MAR display
            if (currentMARElement) {
                currentMARElement.textContent = mouthAspectRatio.toFixed(3);
            }
        }

        // Reset liveness detection
        function resetLivenessDetection() {
            livenessDetected = false;
            mouthOpenCount = 0;
            lastMouthOpenTime = 0;
            mouthAspectRatio = 0;
            previousMouthAspectRatio = 0;
            isInitialized = false;
            consecutiveDetections = 0;
            updateLivenessUI();
            console.log('Liveness detection reset');
        }

        // Reset UI untuk karyawan baru (hanya UI, tidak reset NIK)
        function resetUIForNewEmployee() {
            // Reset current employee data
            currentEmployee = null;
            currentStatus = null;

            // Sembunyikan info karyawan
            const employeeInfo = document.querySelector('.employee-info');
            const livenessStatus = document.querySelector('.liveness-status');

            if (employeeInfo) {
                employeeInfo.style.display = 'none';
            }
            if (livenessStatus) {
                livenessStatus.style.display = 'block';
            }

            console.log('UI reset untuk karyawan baru');
        }



        // Reset counter only
        function resetCounter() {
            mouthOpenCount = 0;
            lastMouthOpenTime = 0;
            updateLivenessUI();
            console.log('Counter reset to 0');
            showStatus('Counter direset ke 0', 'info');
        }

        // Force show absen buttons
        function forceShowAbsenButtons() {
            if (lastProcessedNik) {
                console.log('Force showing absen buttons for NIK:', lastProcessedNik);
                getEmployeeData(lastProcessedNik);
            } else {
                console.log('No NIK available, cannot show absen buttons');
                showStatus('Tidak ada NIK yang dikenali', 'error');
            }
        }

        // Toggle liveness detection on/off
        function toggleLivenessDetection() {
            livenessDetected = !livenessDetected;
            if (livenessDetected) {
                console.log('Liveness detection manually enabled');
                showStatus('Liveness detection diaktifkan manual', 'info');
                if (lastProcessedNik) {
                    getEmployeeData(lastProcessedNik);
                }
            } else {
                console.log('Liveness detection manually disabled');
                showStatus('Liveness detection dinonaktifkan', 'info');
            }
            updateLivenessUI();
        }

        // Adjust threshold for mouth open detection
        function adjustThreshold(direction) {
            if (direction === 'lower') {
                mouthOpenThreshold -= 0.1;
            } else {
                mouthOpenThreshold += 0.1;
            }

            // Keep threshold in reasonable range
            mouthOpenThreshold = Math.max(0.1, Math.min(1.0, mouthOpenThreshold));

            console.log(`Threshold adjusted to: ${mouthOpenThreshold.toFixed(2)}`);
            showStatus(`Threshold: ${mouthOpenThreshold.toFixed(2)}`, 'info');
        }

        // Show status message
        function showStatus(message, type) {
            const statusElement = document.querySelector('.status-message');
            statusElement.textContent = message;

            let statusClass = 'status-message mt-6 p-4 rounded-xl font-semibold ';
            if (type === 'success') {
                statusClass += 'bg-green-100 text-green-800';
            } else if (type === 'error') {
                statusClass += 'bg-red-100 text-red-800';
            } else if (type === 'info') {
                statusClass += 'bg-blue-100 text-blue-800';
            } else {
                statusClass += 'bg-gray-100 text-gray-800';
            }

            statusElement.className = statusClass;
            statusElement.style.display = 'block';

            // Hide after 5 seconds
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 5000);
        }



        // Play notification sound
        function playNotificationSound(type, notifikasi = null) {
            //alert(notifikasi);
            const audio = new Audio();
            if (type === 'success') {
                audio.src = '{{ asset('assets/sound/absenmasuk.wav') }}';
            } else {
                if (notifikasi == 'notifikasi_mulaiabsen') {
                    audio.src = '{{ asset('assets/sound/mulaiabsen.wav') }}';
                } else if (notifikasi == 'notifikasi_akhirabsen') {
                    audio.src = '{{ asset('assets/sound/akhirabsen.wav') }}';
                } else if (notifikasi == 'notifikasi_sudahabsen') {
                    if (currentStatus == 1) {
                        audio.src = '{{ asset('assets/sound/sudahabsen.wav') }}';
                    } else {
                        audio.src = '{{ asset('assets/sound/sudahabsenpulang.wav') }}';
                    }
                } else if (notifikasi == 'notifikasi_absenmasuk') {
                    audio.src = '{{ asset('assets/sound/absenmasuk.wav') }}';
                }
            }
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Load voices untuk speech synthesis
        function loadVoices() {
            return new Promise((resolve) => {
                let voices = speechSynthesis.getVoices();
                if (voices.length > 0) {
                    resolve(voices);
                } else {
                    speechSynthesis.onvoiceschanged = () => {
                        voices = speechSynthesis.getVoices();
                        resolve(voices);
                    };
                }
            });
        }

        // Initialize Face Recognition when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Clear any existing error messages
            document.querySelector('.status-message').style.display = 'none';



            // Inisialisasi currentStatus dari dropdown
            const statusSelect = document.getElementById('statusSelect');
            if (statusSelect) {
                currentStatus = parseInt(statusSelect.value);

                // Event listener untuk perubahan dropdown
                statusSelect.addEventListener('change', function() {
                    currentStatus = parseInt(this.value);
                    console.log('Status berubah menjadi:', currentStatus === 1 ? 'Masuk' : 'Pulang');
                });
            }

            // Pastikan canvas container tersembunyi di awal
            const canvasContainer = document.querySelector('.canvas-container');
            if (canvasContainer) {
                canvasContainer.style.display = 'none';
            }

            // Load voices untuk speech synthesis
            loadVoices().then(voices => {
                console.log('Available voices:', voices);
                if (voices.length > 0) {
                    console.log('Voice languages:', voices.map(v => v.lang));
                    document.getElementById('speechStatus').textContent = `Available (${voices.length} voices)`;
                    document.getElementById('speechStatus').className = 'text-sm font-medium text-green-600';
                } else {
                    document.getElementById('speechStatus').textContent = 'No voices available';
                    document.getElementById('speechStatus').className = 'text-sm font-medium text-red-500';
                }
            }).catch(error => {
                console.error('Error loading voices:', error);
                document.getElementById('speechStatus').textContent = 'Error loading voices';
                document.getElementById('speechStatus').className = 'text-sm font-medium text-red-500';
            });

            // Check if landmarks container exists
            const landmarksContainer = document.getElementById('faceLandmarks');
            console.log('DOM loaded - Landmarks container exists:', !!landmarksContainer);
            if (landmarksContainer) {
                console.log('Landmarks container position:', landmarksContainer.getBoundingClientRect());
            }

            // Initialize liveness detection UI
            updateLivenessUI();

            // Initialize with delay to ensure everything is ready
            setTimeout(() => {
                initFaceRecognition();
            }, 1000);
        });
    </script>
</body>

</html>
