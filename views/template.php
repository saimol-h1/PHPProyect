<?php
// Las funciones de autenticación están disponibles desde el controlador
// No incluir auth.php aquí para evitar conflictos de headers
?>
<html>

<head>
    <title>UTA - Universidad Técnica de Ambato</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- PRELOAD CRÍTICO: Banner UTA para carga prioritaria -->
    <link rel="preload" href="<?php echo getUrl('img', 'BannerUta.png'); ?>" as="image" type="image/png">

    <!-- CSS crítico inline para el banner (evita esperar archivos CSS externos) -->
    <style>
        .banner-container {
            position: relative;
            width: 100%;
            min-height: 120px;
            background: linear-gradient(135deg, #901B21 0%, #B22429 50%, #701418 100%);
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(144, 27, 33, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner-img {
            width: 100%;
            height: auto;
            min-height: 100px;
            max-height: 150px;
            object-fit: cover;
            display: block;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .banner-img.loaded {
            opacity: 1;
        }

        .banner-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            z-index: 2;
            opacity: 0.9;
        }

        .banner-placeholder i {
            animation: bannerPulse 2s infinite;
            margin-right: 10px;
        }

        @keyframes bannerPulse {
            0% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            100% {
                opacity: 0.6;
                transform: scale(1);
            }
        }

        .user-overlay {
            position: absolute;
            top: 10px;
            right: 15px;
            z-index: 10;
            background: rgba(144, 27, 33, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-overlay .text-white {
            color: #FFFFFF !important;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .user-overlay .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border: 1px solid #E0E0E0;
            color: #FFFFFF;
        }

        .user-overlay .btn:hover {
            background-color: #E0E0E0;
            color: #901B21;
        }

        @media (max-width: 768px) {
            .banner-container {
                min-height: 80px;
            }

            .banner-img {
                max-height: 100px;
            }

            .banner-placeholder {
                font-size: 1rem;
            }

            .user-overlay {
                position: static;
                margin: 5px;
                text-align: center;
                border-radius: 0;
                border-top: 2px solid #E0E0E0;
            }
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo getUrl('img', 'favicon.svg'); ?>">

    <!-- CSS no crítico carga después -->
    <link rel="stylesheet" href="<?php echo getUrl('css', 'style.css'); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo getUrl('css', 'banner-png.css'); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo getUrl('css', 'uta-theme.css'); ?>" media="print" onload="this.media='all'">

    <!-- Bootstrap CSS con carga diferida -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- Font Awesome con carga diferida -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" media="print" onload="this.media='all'">

    <!-- Fallback para navegadores sin soporte -->
    <noscript>
        <link rel="stylesheet" href="<?php echo getUrl('css', 'style.css'); ?>">
        <link rel="stylesheet" href="<?php echo getUrl('css', 'banner-png.css'); ?>">
        <link rel="stylesheet" href="<?php echo getUrl('css', 'uta-theme.css'); ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </noscript>
    <link rel="stylesheet" href="<?php echo getUrl('css', 'uta-theme.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </noscript>
</head>

<body>
    <header>
        <div class="banner-container">
            <!-- Placeholder mientras carga el banner -->
            <div id="banner-placeholder" class="banner-placeholder">
                <i class="fas fa-university"></i>
                Universidad Técnica de Ambato
            </div>

            <!-- Banner real con carga optimizada -->
            <img id="banner-img"
                src="<?php echo getUrl('img', 'BannerUta.png'); ?>"
                alt="Universidad Técnica de Ambato"
                class="banner-img"
                loading="eager"
                decoding="async"
                onload="showBanner()"
                onerror="handleBannerError()">

            <!-- Overlay de usuario superpuesto -->
            <?php if (isLoggedIn()): ?>
                <div class="user-overlay">
                    <span class="text-white">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?>
                        <small class="d-block" style="color: #E0E0E0; font-size: 0.7rem;">
                            <?php echo ucfirst($_SESSION['tipo_usuario'] ?? 'usuario'); ?>
                        </small>
                    </span>
                    <a href="<?php echo getUrl('', 'logout.php'); ?>" class="btn btn-sm ms-2">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getActionUrl('inicio'); ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getActionUrl('nosotros'); ?>">
                            <i class="fas fa-users"></i> Nosotros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getActionUrl('servicios'); ?>">
                            <i class="fas fa-cogs"></i> Servicios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getActionUrl('contactanos'); ?>">
                            <i class="fas fa-envelope"></i> Contáctanos
                        </a>
                    </li>
                </ul>

                <?php if (isLoggedIn()): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo getActionUrl('login'); ?>">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <section class="container-fluid mt-0">
        <?php
        // El contenido se renderiza desde el controlador principal
        global $mvcController;
        if (isset($mvcController)) {
            $mvcController->EnlacesPaginasController();
        } else {
            echo "<h1>Error: Controlador no disponible</h1>";
        }
        ?>
    </section>

    <!-- Script de optimización del banner -->
    <script>
        // Variables globales para el banner
        let bannerLoaded = false;

        // Función para mostrar el banner cuando carga
        function showBanner() {
            const placeholder = document.getElementById('banner-placeholder');
            const banner = document.getElementById('banner-img');

            if (placeholder && banner && !bannerLoaded) {
                bannerLoaded = true;

                // Ocultar placeholder con animación
                placeholder.style.transition = 'opacity 0.3s ease-out';
                placeholder.style.opacity = '0';

                setTimeout(() => {
                    placeholder.style.display = 'none';
                    // Mostrar banner con animación
                    banner.classList.add('loaded');
                }, 300);
            }
        }

        // Función para manejar error de carga
        function handleBannerError() {
            const placeholder = document.getElementById('banner-placeholder');
            const banner = document.getElementById('banner-img');

            if (placeholder && banner) {
                // Mostrar placeholder mejorado en caso de error
                placeholder.innerHTML = '<i class="fas fa-university"></i><br>Universidad Técnica de Ambato<br><small style="font-size: 0.8rem; opacity: 0.7;">Sistema Académico</small>';
                placeholder.style.display = 'block';
                banner.style.display = 'none';
            }
        }

        // Optimización de carga del banner
        document.addEventListener('DOMContentLoaded', function() {
            const bannerImg = document.getElementById('banner-img');
            const placeholder = document.getElementById('banner-placeholder');

            // Verificar si la imagen ya está en cache
            if (bannerImg && bannerImg.complete && bannerImg.naturalHeight !== 0) {
                showBanner();
                return;
            }

            // Precargar la imagen de manera agresiva
            const img = new Image();
            img.onload = function() {
                if (bannerImg) {
                    bannerImg.src = this.src;
                    showBanner();
                }
            };
            img.onerror = function() {
                handleBannerError();
            };

            // Comenzar precarga inmediatamente
            img.src = bannerImg.src;

            // Timeout de seguridad (5 segundos máximo)
            setTimeout(() => {
                if (!bannerLoaded && placeholder && placeholder.style.display !== 'none') {
                    handleBannerError();
                }
            }, 5000);
        });

        // Optimización adicional: cargar CSS no crítico después del banner
        function loadNonCriticalCSS() {
            const links = document.querySelectorAll('link[media="print"]');
            links.forEach(link => {
                link.media = 'all';
            });
        }

        // Cargar CSS no crítico después de que el banner esté listo
        if (bannerLoaded) {
            loadNonCriticalCSS();
        } else {
            window.addEventListener('load', loadNonCriticalCSS);
        }
    </script>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery carga después -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>