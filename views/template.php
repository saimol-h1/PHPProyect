<?php
// Las funciones de autenticación están disponibles desde el controlador
// No incluir auth.php aquí para evitar conflictos de headers
?>
<html>

<head>
    <title>UTA - Universidad Técnica de Ambato</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- PRELOAD CRÍTICO: Banner UTA desde Cloudinary CDN para carga prioritaria -->
    <link rel="preload" href="https://res.cloudinary.com/dwwvecqnu/image/upload/f_auto,q_auto/srjxoupeycmg9yaanbz3" as="image" type="image/png">

    <!-- CSS crítico inline para el banner (evita esperar archivos CSS externos) -->
    <style>
        /* Eliminar espacios en blanco */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Eliminar espacios de elementos */
        * {
            box-sizing: border-box;
        }

        .container-fluid {
            margin: 0;
            padding: 0;
            flex: 1;
        }

        /* Margen específico para evitar solapamiento con navbar */
        .container-fluid {
            margin-top: 10px;
            padding-top: 10px;
            margin-bottom: 20px;
            /* Añadido para evitar solapamiento con footer */
        }

        /* Espaciado especial para formularios de login */
        .login-container,
        .auth-container {
            margin-top: 40px;
            padding-top: 30px;
        }

        /* Asegurar separación de navbar en todas las páginas */
        .container-fluid>.row:first-child,
        .container-fluid>.col:first-child,
        .container-fluid>div:first-child,
        .container-fluid>form:first-child {
            margin-top: 20px;
        }

        /* Formularios específicos */
        form.login-form,
        .card.login-card,
        .login-wrapper {
            margin-top: 30px;
        }

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
            object-position: center;
            display: block;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .banner-img.ready {
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
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #901B21 0%, #B22429 50%, #701418 100%);
        }

        .banner-placeholder i {
            margin-right: 10px;
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
            transition: all 0.3s ease;
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
            transition: all 0.3s ease;
        }

        .user-overlay .btn:hover {
            background-color: #E0E0E0;
            color: #901B21;
        }

        /* Media Queries para Responsividad */

        /* Pantalla Flow AMOLED DotDisplay CrystalRes 1.5K de 6.67" (2712 × 1220, 446 PPP) */
        @media screen and (max-width: 2712px) and (min-width: 1221px) and (orientation: landscape),
        screen and (max-width: 1220px) and (min-width: 1100px) and (orientation: portrait) {
            .banner-container {
                min-height: 140px;
                padding: 8px;
            }

            .banner-img {
                max-height: 180px;
                min-height: 130px;
            }

            .banner-placeholder {
                font-size: 1.8rem;
                padding: 15px;
            }

            .banner-placeholder i {
                margin-right: 12px;
                font-size: 2rem;
            }

            .user-overlay {
                top: 15px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 25px;
                backdrop-filter: blur(15px);
            }

            .user-overlay .text-white {
                font-size: 1.1rem;
                line-height: 1.3;
            }

            .user-overlay .text-white small {
                font-size: 0.85rem;
                margin-top: 3px;
            }

            .user-overlay .btn {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
                margin-top: 5px;
            }
        }

        @media (max-width: 1200px) {
            .banner-container {
                min-height: 110px;
            }

            .banner-img {
                max-height: 140px;
            }

            .user-overlay {
                padding: 6px 12px;
            }

            .user-overlay .text-white {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 992px) {
            .banner-container {
                min-height: 100px;
            }

            .banner-img {
                max-height: 130px;
            }

            .user-overlay {
                top: 8px;
                right: 10px;
                padding: 5px 10px;
            }

            .user-overlay .text-white {
                font-size: 0.8rem;
            }

            .user-overlay .btn {
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }
        }

        @media (max-width: 768px) {
            .banner-container {
                min-height: 80px;
                flex-direction: column;
                padding: 5px;
            }

            .banner-img {
                max-height: 100px;
                min-height: 80px;
            }

            .banner-placeholder {
                font-size: 1rem;
                padding: 10px;
            }

            .user-overlay {
                position: static;
                margin: 5px auto 0;
                text-align: center;
                border-radius: 15px;
                background: rgba(144, 27, 33, 0.95);
                border-top: 2px solid #E0E0E0;
                width: 95%;
                max-width: 300px;
            }

            .user-overlay .text-white {
                font-size: 0.85rem;
                display: block;
                margin-bottom: 5px;
            }

            .user-overlay .text-white small {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .banner-container {
                min-height: 70px;
            }

            .banner-img {
                max-height: 90px;
                min-height: 70px;
            }

            .banner-placeholder {
                font-size: 0.9rem;
                padding: 8px;
            }

            .user-overlay {
                margin: 8px auto 0;
                padding: 8px 12px;
                border-radius: 12px;
            }

            .user-overlay .text-white {
                font-size: 0.8rem;
            }

            .user-overlay .btn {
                font-size: 0.7rem;
                padding: 0.15rem 0.3rem;
                margin-top: 3px;
            }
        }

        @media (max-width: 390px) {
            .banner-container {
                min-height: 75px;
                padding: 3px;
            }

            .banner-img {
                max-height: 95px;
                min-height: 75px;
            }

            .banner-placeholder {
                font-size: 0.95rem;
                padding: 8px;
            }

            .banner-placeholder i {
                margin-right: 8px;
                font-size: 1.1rem;
            }

            .user-overlay {
                margin: 6px auto 0;
                padding: 6px 10px;
                border-radius: 10px;
                width: 96%;
                max-width: 280px;
            }

            .user-overlay .text-white {
                font-size: 0.8rem;
                line-height: 1.2;
            }

            .user-overlay .text-white small {
                font-size: 0.65rem;
                margin-top: 2px;
            }

            .user-overlay .btn {
                font-size: 0.7rem;
                padding: 0.15rem 0.35rem;
                margin-top: 4px;
            }
        }

        @media (max-width: 320px) {
            .banner-container {
                min-height: 60px;
            }

            .banner-img {
                max-height: 80px;
                min-height: 60px;
            }

            .banner-placeholder {
                font-size: 0.8rem;
                padding: 5px;
            }

            .user-overlay {
                width: 98%;
                padding: 6px 8px;
            }

            .user-overlay .text-white {
                font-size: 0.75rem;
            }
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="<?php echo getUrl('img', 'escudo-uta232.png'); ?>">

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
                <span>Universidad Técnica de Ambato</span>
            </div> <!-- Banner real con carga optimizada desde Cloudinary CDN -->
            <img id="banner-img"
                src="https://res.cloudinary.com/dwwvecqnu/image/upload/f_auto,q_auto/srjxoupeycmg9yaanbz3"
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
                            <?php echo ucfirst($_SESSION['usuario_tipo'] ?? 'usuario'); ?>
                        </small>
                    </span>
                    <!-- <a href="<?php echo getUrl('', 'logout.php'); ?>" class="btn btn-sm ms-2">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a> -->
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

    <section class="container-fluid">
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
        let bannerLoaded = false; // Función para mostrar el banner cuando carga
        function showBanner() {
            const placeholder = document.getElementById('banner-placeholder');
            const banner = document.getElementById('banner-img');

            if (placeholder && banner && !bannerLoaded) {
                bannerLoaded = true;

                // Mostrar banner y ocultar placeholder simultáneamente
                banner.classList.add('ready');
                placeholder.style.display = 'none';
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
        } // Optimización de carga del banner
        document.addEventListener('DOMContentLoaded', function() {
            const bannerImg = document.getElementById('banner-img');
            const placeholder = document.getElementById('banner-placeholder');

            // Verificar si la imagen ya está en cache y cargada
            if (bannerImg && bannerImg.complete && bannerImg.naturalHeight !== 0) {
                // Imagen ya está en caché, mostrar inmediatamente
                bannerImg.classList.add('ready');
                if (placeholder) placeholder.style.display = 'none';
                bannerLoaded = true;
                return;
            }

            // Si no está en caché, precarga la imagen
            const img = new Image();
            img.onload = function() {
                if (bannerImg && !bannerLoaded) {
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

    <?php
    // Redirección automática al login cuando la sesión expire (cliente)
    if (function_exists('isLoggedIn') && isLoggedIn()) {
        if (function_exists('getSessionTimeRemaining')) {
            $timeRemaining = getSessionTimeRemaining();
            echo "<script>
                let sessionTimeRemaining = {$timeRemaining};
                function updateSessionTimerAuto() {
                    if (sessionTimeRemaining <= 0) {
                        window.location.href = 'index.php?action=login&expired=1';
                        return;
                    }
                    sessionTimeRemaining--;
                }
                setInterval(updateSessionTimerAuto, 1000);
            </script>";
        }
    }
    ?>
</body>

</html>