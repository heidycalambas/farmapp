<?php
/**
 * Utilidad de Autenticación
 * FarmApp
 */

class Auth {
    public static function check() {
        return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']);
    }
    
    public static function user() {
        return $_SESSION['usuario'] ?? null;
    }
    
    public static function id() {
        return $_SESSION['usuario']['id'] ?? null;
    }
    
    public static function rol() {
        return $_SESSION['usuario']['rol_nombre'] ?? null;
    }
    
    public static function esAdmin() {
        return self::rol() === 'Administrador';
    }
    
    public static function esFarmaceutico() {
        return self::rol() === 'Farmacéutico';
    }
    
    public static function esCliente() {
        return self::rol() === 'Cliente';
    }
    
    public static function requiereAuth() {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/index.php?action=login');
            exit;
        }
    }
    
    public static function requiereRol($roles) {
        self::requiereAuth();
        if (!in_array(self::rol(), $roles)) {
            header('Location: ' . BASE_URL . '/index.php?action=home');
            exit;
        }
    }
    
    public static function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

