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