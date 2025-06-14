<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - UTA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h3>Acceso Denegado</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">No tienes permisos suficientes para acceder a esta sección.</p>
                        <p>Esta funcionalidad está restringida a usuarios administradores.</p>

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="logout.php" class="btn btn-warning">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión e Iniciar con Otro Usuario
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>