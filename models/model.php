<?php
class EnlacesPagina
{    public function enlacesPaginasModel($enlacesModel)
    {
        $validPages = ["inicio", "nosotros", "contactanos", "servicios", "login", "unauthorized"];

        if (in_array($enlacesModel, $validPages)) {
            $module = getPath('view', $enlacesModel . ".php");
        } else {
            $module = getPath('view', "inicio.php");
        }

        return $module;
    }
}
