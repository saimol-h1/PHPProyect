<?php
// Las funciones de autenticación están disponibles desde el controlador
// No incluir auth.php aquí para evitar conflictos de headers
?>
<html>

<head>
    <title>UTA - Universidad Técnica de Ambato</title>
    <link rel="stylesheet" href="<?php echo getUrl('css', 'style.css'); ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Información del usuario logueado (si existe) -->
    <?php if (isLoggedIn()): ?>
        <div class="container-fluid">
            <?php mostrarUsuarioLogueado(); ?>
        </div>
    <?php endif; ?>

    <header>
        <div class="container-fluid text-center">
            <img src="<?php echo getUrl('img', 'utabanner.svg'); ?>" width="90%" alt="Universidad Técnica de Ambato" class="img-fluid">
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
    <section class="container-fluid mt-3">
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