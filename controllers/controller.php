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
        }

        // Obtener la acción solicitada
        if (isset($_GET['action'])) {
            $this->enlacesController = $_GET['action'];
        }        // Verificar autenticación ANTES de procesar la vista
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
        // La acción ya fue determinada en handleActions()
        $enlacesPagina = new EnlacesPagina();
        $respuesta = $enlacesPagina->enlacesPaginasModel($this->enlacesController);

        // Incluir la vista usando ruta relativa
        if (file_exists($respuesta)) {
            include $respuesta;
        } else {
            includeFile('view', 'inicio.php');
        }
    }
}
