<?php
/**
 * Configuración General del Sistema
 * FarmApp - Sistema de Gestión Farmacéutica
 */

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Rutas base (definir primero para usarlas después)
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/farmapp');

// Configurar ruta de sesiones (soluciona problemas de permisos en Windows)
$sessionPath = sys_get_temp_dir();
if (is_writable($sessionPath)) {
    ini_set('session.save_path', $sessionPath);
} else {
    // Si no se puede escribir en temp, usar una carpeta en el proyecto
    $customSessionPath = BASE_PATH . '/tmp/sessions';
    if (!is_dir($customSessionPath)) {
        @mkdir($customSessionPath, 0777, true);
    }
    if (is_writable($customSessionPath)) {
        ini_set('session.save_path', $customSessionPath);
    }
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');

// Rutas de archivos
define('UPLOAD_PATH', BASE_PATH . '/public/images/productos/');
define('UPLOAD_URL', BASE_URL . '/public/images/productos/');

// Configuración de la aplicación
define('APP_NAME', 'FarmApp');
define('APP_VERSION', '1.0.0');

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 6);

// Configuración de stock
define('STOCK_MINIMO_DEFAULT', 10);
define('DIAS_ALERTA_CADUCIDAD', 30);

// Incluir autoloader
require_once BASE_PATH . '/config/autoload.php';

