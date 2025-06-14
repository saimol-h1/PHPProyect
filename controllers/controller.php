<?php

class MvcController
{
    public function template()
    {
        // Las funciones de auth.php ya están disponibles desde index.php
        includeFile('view', 'template.php');
    }
    public function EnlacesPaginasController()
    {
        // Las funciones de auth.php ya están disponibles desde index.php

        // get post
        if (isset($_GET['action'])) {
            $enlacesController = $_GET['action'];
        } else {
            $enlacesController = "inicio";
        }

        // Verificar autenticación ANTES de procesar la vista
        if ($enlacesController === "servicios") {
            // Si no está logueado, redirigir al login
            if (!isLoggedIn()) {
                header('Location: index.php?action=login&error=required');
                exit();
            }
        }

        $enlacesPagina = new EnlacesPagina();
        $respuesta = $enlacesPagina->enlacesPaginasModel($enlacesController);

        // Incluir la vista usando ruta relativa
        if (file_exists($respuesta)) {
            include $respuesta;
        } else {
            includeFile('view', 'inicio.php');
        }
    }
}
