<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .info-card h3 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .contact-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Contáctanos</h1>

        <div class="contact-info">
            <div class="info-card">
                <h3>📍 Dirección</h3>
                <p>Calle Principal #123<br>
                    Ciudad, Estado 12345</p>
            </div>

            <div class="info-card">
                <h3>📞 Teléfono</h3>
                <p>+1 (555) 123-4567<br>
                    +1 (555) 987-6543</p>
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