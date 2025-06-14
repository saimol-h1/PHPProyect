<?php
// Incluir la configuración de rutas
require_once "config/config.php";

// Incluir sistema de autenticación al inicio
require_once "config/auth.php";

// Incluir archivos usando rutas relativas configuradas
requireFile('controller', 'controller.php');
requireFile('model', 'model.php');

$mvc = new MvcController();
$mvc->template();
