<?php
/**
 * Configuración General del Sistema
 * Sistema de Inventario ANA - PHP MVC
 */

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Inventario ANA');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/inventariopech');

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_NAME', 'inventario_ana');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de rutas
define('ROOT', dirname(__DIR__));
define('APP_PATH', ROOT . '/app');
define('PUBLIC_PATH', ROOT . '/public');
define('VIEWS_PATH', APP_PATH . '/views');

// Configuración de zona horaria
date_default_timezone_set('America/Lima');

// Configuración de sesión
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

// Configuración de errores (desarrollo)
define('ENVIRONMENT', 'development'); // cambiar a 'production' en producción

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de paginación
define('ITEMS_PER_PAGE', 20);

// Configuración de seguridad
define('ENCRYPTION_KEY', 'tu-clave-secreta-aqui'); // Cambiar en producción
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 10);
