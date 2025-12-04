<?php
/**
 * Sistema de Inventario de Infraestructura Mayor - ANA
 * Versión PHP MVC con MySQL
 *
 * Punto de entrada principal de la aplicación
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Autoload de clases core
spl_autoload_register(function ($className) {
    $coreFile = ROOT . '/core/' . $className . '.php';
    $modelFile = APP_PATH . '/models/' . $className . '.php';
    $controllerFile = APP_PATH . '/controllers/' . $className . '.php';

    if (file_exists($coreFile)) {
        require_once $coreFile;
    } elseif (file_exists($modelFile)) {
        require_once $modelFile;
    } elseif (file_exists($controllerFile)) {
        require_once $controllerFile;
    }
});

// Iniciar aplicación
$app = new App();
