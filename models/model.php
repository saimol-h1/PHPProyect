<?php
class EnlacesPagina
{
    public function enlacesPaginasModel($enlacesModel)
    {
        $validPages = ["inicio", "nosotros", "contactanos", "servicios", "login", "unauthorized"];

        // Si es la página de servicios, redirigir a login
        if ($enlacesModel === "servicios") {
            // Incluir sistema de autenticación
            require_once 'config/auth.php';

            // Si no está logueado, redirigir al login
            if (!isLoggedIn()) {
                header('Location: index.php?action=login&error=required');
                exit();
            }
        }

        if (in_array($enlacesModel, $validPages)) {
            $module = getPath('view', $enlacesModel . ".php");
        } else {
            $module = getPath('view', "inicio.php");
        }

        return $module;
    }
}
