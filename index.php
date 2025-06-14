<?php
// Iniciar output buffering para prevenir problemas de headers
ob_start();

// Incluir la configuración de rutas
require_once "config/config.php";

// Incluir sistema de autenticación al inicio
require_once "config/auth.php";

// Incluir archivos usando rutas relativas configuradas
requireFile('controller', 'controller.php');
requireFile('model', 'model.php');

$mvc = new MvcController();

// Manejar acciones ANTES de cargar el template
$mvc->handleActions();

// Hacer el controlador disponible globalmente para el template
global $mvcController;
$mvcController = $mvc;

// Cargar el template solo si no hubo redirecciones
$mvc->template();

// Enviar el buffer
ob_end_flush();
