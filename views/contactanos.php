<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos - UTA</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3><i class="fas fa-envelope"></i> Contáctanos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Información de Contacto</h4>
                                <div class="mb-3">
                                    <h5><i class="fas fa-map-marker-alt text-danger"></i> Dirección</h5>
                                    <p>Av. Los Chasquis y Río Payamino<br>
                                        Campus Huachi - Ambato, Ecuador</p>
                                </div>

                                <div class="mb-3">
                                    <h5><i class="fas fa-phone text-success"></i> Teléfonos</h5>
                                    <p>PBX: (03) 2521-081<br>
                                        Fax: (03) 2521-081 Ext. 115</p>
                                </div>

                                <div class="mb-3">
                                    <h5><i class="fas fa-envelope text-info"></i> Correos Electrónicos</h5>
                                    <p>
                                        <strong>General:</strong> info@uta.edu.ec<br>
                                        <strong>Admisiones:</strong> admisiones@uta.edu.ec<br>
                                        <strong>Sistemas:</strong> sistemas@uta.edu.ec
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h5><i class="fas fa-clock text-warning"></i> Horarios de Atención</h5>
                                    <p>
                                        <strong>Lunes a Viernes:</strong> 08:00 - 17:00<br>
                                        <strong>Sábados:</strong> 08:00 - 12:00
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4>Integrantes del Grupo</h4>

                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <img src="https://via.placeholder.com/100x100/007bff/ffffff?text=E1" class="rounded-circle mb-2" alt="Estudiante 1">
                                        <h5>Estudiante 1</h5>
                                        <p class="text-muted">Desarrollador Frontend</p>
                                        <p><i class="fas fa-envelope"></i> estudiante1@uta.edu.ec</p>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <img src="https://via.placeholder.com/100x100/28a745/ffffff?text=E2" class="rounded-circle mb-2" alt="Estudiante 2">
                                        <h5>Estudiante 2</h5>
                                        <p class="text-muted">Desarrollador Backend</p>
                                        <p><i class="fas fa-envelope"></i> estudiante2@uta.edu.ec</p>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <img src="https://via.placeholder.com/100x100/dc3545/ffffff?text=E3" class="rounded-circle mb-2" alt="Estudiante 3">
                                        <h5>Estudiante 3</h5>
                                        <p class="text-muted">Diseñador UI/UX</p>
                                        <p><i class="fas fa-envelope"></i> estudiante3@uta.edu.ec</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h4>Formulario de Contacto</h4>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre Completo</label>
                                                <input type="text" class="form-control" id="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="asunto" class="form-label">Asunto</label>
                                        <input type="text" class="form-control" id="asunto" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mensaje" class="form-label">Mensaje</label>
                                        <textarea class="form-control" id="mensaje" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Enviar Mensaje
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
</div>

<div class="info-card">
    <h3>✉️ Email</h3>
    <p>info@empresa.com<br>
        contacto@empresa.com</p>
</div>

<div class="info-card">
    <h3>🕒 Horarios</h3>
    <p>Lun - Vie: 9:00 AM - 6:00 PM<br>
        Sáb: 9:00 AM - 2:00 PM</p>
</div>
</div>

<div class="contact-form">
    <h2>Envíanos un mensaje</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono">
        </div>

        <div class="form-group">
            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" required>
        </div>

        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
        </div>

        <button type="submit" class="btn">Enviar mensaje</button>
    </form>
</div>
</div>
</body>

</html>