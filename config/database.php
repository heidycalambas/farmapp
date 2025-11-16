<?php
/**
 * Configuración de Base de Datos
 * FarmApp - Sistema de Gestión Farmacéutica
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Detectar entorno automáticamente
    private $isProduction = false; // Cambiar a true para producción
    
    // Configuración para LOCALHOST (XAMPP/Laragon)
    // NOTA: Si MySQL tiene contraseña, cámbiala aquí
    private $configLocal = [
        'host' => 'localhost',
        'dbname' => 'farmapp',
        'username' => 'root',
        'password' => '1003418409', // Si MySQL tiene contraseña, ponla aquí (ej: 'root', 'password', etc.)
        'charset' => 'utf8mb4'
    ];
    
    // Configuración para INFINITYFREE (completar con tus datos)
    private $configProduction = [
        'host' => 'sqlXXX.epizy.com', // Reemplazar con tu host de InfinityFree
        'dbname' => 'epiz_XXXXXX_farmapp', // Reemplazar con tu nombre de BD
        'username' => 'epiz_XXXXXX', // Reemplazar con tu usuario
        'password' => 'tu_password_aqui', // Reemplazar con tu contraseña
        'charset' => 'utf8mb4'
    ];
    
    private function getConfig() {
        return $this->isProduction ? $this->configProduction : $this->configLocal;
    }
    
    private function __construct() {
        try {
            $config = $this->getConfig();
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            $env = $this->isProduction ? 'PRODUCCIÓN' : 'LOCALHOST';
            die("Error de conexión a la base de datos ({$env}): " . $e->getMessage() . 
                "<br><br>Verifica la configuración en config/database.php");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

