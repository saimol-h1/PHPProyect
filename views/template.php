<?php
// Las funciones de autenticación están disponibles desde el controlador
// No incluir auth.php aquí para evitar conflictos de headers
?>
<html>

<head>
    <title>UTA - Universidad Técnica de Ambato</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo getUrl('img', 'favicon.svg'); ?>">
    <link rel="stylesheet" href="<?php echo getUrl('css', 'style.css'); ?>">
    <link rel="stylesheet" href="<?php echo getUrl('css', 'banner-png.css'); ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tema UTA personalizado -->
    <link rel="stylesheet" href="<?php echo getUrl('css', 'uta-theme.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <div class="container-fluid p-0 position-relative">
            <!-- Banner ocupa todo el ancho -->
            <div class="banner-full-width ">
                <img src="<?php echo getUrl('img', 'BannerUta.png'); ?>"
                    alt="Universidad Técnica de Ambato"
                    class="img-fluid w-110 banner-full-logo"
                    onerror="this.parentElement.innerHTML='<div class=\'banner-fallback\'><i class=\'fas fa-university me-2\'></i>Universidad Técnica de Ambato</div>'">
            </div>

            <!-- Información del usuario superpuesta -->
            <!-- <?php if (isLoggedIn()): ?>
                <div class="user-info-overlay position-absolute top-0 end-0 p-3">
                    <span class="text-white">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?>
                        <small class="d-block text-muted">
                            <?php echo ucfirst($_SESSION['tipo_usuario'] ?? 'usuario'); ?>
                        </small>
                    </span>
                    <a href="<?php echo getUrl('', 'logout.php'); ?>" class="btn btn-sm btn-outline-light ms-2">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            <?php endif; ?> -->
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



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>