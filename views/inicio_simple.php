<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">🏠 Bienvenido a la Universidad Técnica de Ambato</h2>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title text-primary">Sistema de Gestión Académica</h4>
                    <p class="card-text">
                        Bienvenido al sistema de información académica de la Universidad Técnica de Ambato.
                        Este sistema permite la gestión integral de estudiantes y información académica.
                    </p>
                </div>
            </div>
            <?php if (isLoggedIn()): ?>
                <div class="alert alert-success">
                    <h5>👤 Usuario Conectado</h5>
                    <p class="mb-0">
                        <strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?><br>
                        <strong>Tipo:</strong> <?php echo ucfirst($_SESSION['usuario_tipo']); ?><br>
                        <strong>Última conexión:</strong> <?php echo date('d/m/Y H:i', $_SESSION['login_time']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Gestión de Estudiantes</h5>
                            <p class="card-text">Administra la información de los estudiantes de la universidad.</p>
                            <?php if (isLoggedIn()): ?>
                                <a href="<?php echo getActionUrl('servicios'); ?>" class="btn btn-primary">
                                    Acceder
                                </a>
                            <?php else: ?>
                                <a href="<?php echo getActionUrl('login'); ?>" class="btn btn-outline-primary">
                                    Iniciar Sesión
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-graduation-cap fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Portal Académico</h5>
                            <p class="card-text">Accede a la información académica y servicios universitarios.</p>
                            <a href="<?php echo getActionUrl('nosotros'); ?>" class="btn btn-success">
                                Conocer Más
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>