<?php

class MvcController
{
    public function template()
    {
        includeFile('view', 'template.php');
    }

    public function EnlacesPaginasController()
    {
        // get post
        if (isset($_GET['action'])) {
            $enlacesController = $_GET['action'];
        } else {
            $enlacesController = "inicio";
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
