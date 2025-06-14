<?php

class MvcController
{
    private $enlacesController = "inicio";

    /**
     * Manejar acciones y autenticación ANTES de generar output
     */
    public function handleActions()
    {
        // Manejar logout
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            logout(); // Esta función hace header() y exit()
        }        // Obtener la acción solicitada
        if (isset($_GET['action'])) {
            $this->enlacesController = $_GET['action'];
        }

        // Debug temporal
        if (isset($_GET['debug'])) {
            echo "<!-- DEBUG handleActions: action=" . ($_GET['action'] ?? 'no_action') . ", enlacesController=" . $this->enlacesController . " -->";
        } // Verificar autenticación ANTES de procesar la vista
        if ($this->enlacesController === "servicios") {
            // Si no está logueado, redirigir al login
            if (!isLoggedIn()) {
                ob_clean(); // Limpiar buffer antes de redirigir
                header('Location: index.php?action=login&error=required');
                exit();
            }
        }

        // Procesar login si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->enlacesController === 'login') {
            $this->processLogin();
        }
    }

    /**
     * Procesar login
     */
    private function processLogin()
    {
        if (isset($_POST['usuario']) && isset($_POST['password'])) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            if (login($usuario, $password)) {
                // Login exitoso, redirigir al inicio
                ob_clean(); // Limpiar buffer antes de redirigir
                header('Location: index.php?action=inicio&login=success');
                exit();
            } else {
                // Login fallido, redirigir al login con error
                ob_clean(); // Limpiar buffer antes de redirigir
                header('Location: index.php?action=login&error=invalid');
                exit();
            }
        }
    }

    public function template()
    {
        // Las funciones de auth.php ya están disponibles desde index.php
        includeFile('view', 'template.php');
    }
    public function EnlacesPaginasController()
    {
        // DEBUG: Mostrar información de debugging
        if (isset($_GET['debug'])) {
            echo "<div style='background: #f0f0f0; padding: 10px; border: 1px solid #ccc; margin: 10px;'>";
            echo "<h3>🔍 DEBUG INFO</h3>";
            echo "<p><strong>Acción solicitada:</strong> " . $this->enlacesController . "</p>";
        }

        // La acción ya fue determinada en handleActions()
        $enlacesPagina = new EnlacesPagina();
        $respuesta = $enlacesPagina->enlacesPaginasModel($this->enlacesController);

        // DEBUG: Mostrar respuesta del modelo
        if (isset($_GET['debug'])) {
            echo "<p><strong>Respuesta del modelo:</strong> $respuesta</p>";
            echo "<p><strong>Archivo existe:</strong> " . (file_exists($respuesta) ? '✅ SÍ' : '❌ NO') . "</p>";
            echo "</div>";
        }

        // Incluir la vista usando ruta relativa
        if (file_exists($respuesta)) {
            include $respuesta;
        } else {
            echo "<div style='background: #ffebee; padding: 20px; border: 1px solid #f44336; margin: 20px;'>";
            echo "<h3>❌ Error: Vista no encontrada</h3>";
            echo "<p><strong>Archivo buscado:</strong> $respuesta</p>";
            echo "<p><strong>Acción:</strong> " . $this->enlacesController . "</p>";
            echo "<p>Cargando vista por defecto...</p>";
            echo "</div>";
            includeFile('view', 'inicio.php');
        }
    }
}
