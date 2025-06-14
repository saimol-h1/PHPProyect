<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">📞 Contáctanos</h2>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                            </h5>
                            <p class="card-text">
                                <strong>Dirección:</strong><br>
                                Av. Los Chasquis y Río Payamino<br>
                                Huachi Chico, Ambato - Ecuador
                            </p>
                            <p class="card-text">
                                <strong>Código Postal:</strong> 180206
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i class="fas fa-phone me-2"></i>Contacto
                            </h5>
                            <p class="card-text">
                                <strong>Teléfono:</strong><br>
                                (03) 2848487 - 2400087
                            </p>
                            <p class="card-text">
                                <strong>Email:</strong><br>
                                info@uta.edu.ec<br>
                                admisiones@uta.edu.ec
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-clock me-2"></i>Horarios de Atención
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Lunes a Viernes:</strong></p>
                            <ul class="list-unstyled">
                                <li>Mañana: 08:00 - 12:00</li>
                                <li>Tarde: 14:00 - 18:00</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sábados:</strong></p>
                            <ul class="list-unstyled">
                                <li>Mañana: 08:00 - 12:00</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isLoggedIn()): ?>
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope me-2"></i>Enviar Mensaje</h5>
                    </div>
                    <div class="card-body">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="<?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto:</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" required>
                            </div>
                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje:</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <h5>📝 ¿Quieres enviarnos un mensaje?</h5>
                    <p class="mb-2">Inicia sesión para acceder al formulario de contacto</p>
                    <a href="<?php echo getActionUrl('login'); ?>" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isLoggedIn()): ?>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Simular envío de mensaje
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            submitBtn.disabled = true;

            setTimeout(() => {
                alert('¡Mensaje enviado correctamente! Te contactaremos pronto.');
                e.target.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    </script>
<?php endif; ?>