<?php
/**
 * Controlador de Notificaciones
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../utils/Auth.php';

class NotificacionController {
    private $notificacionModel;
    
    public function __construct() {
        $this->notificacionModel = new Notificacion();
    }
    
    public function obtener() {
        Auth::requiereAuth();
        
        $notificaciones = $this->notificacionModel->obtenerPorUsuario(Auth::id(), true);
        $noLeidas = $this->notificacionModel->contarNoLeidas(Auth::id());
        
        echo json_encode([
            'notificaciones' => $notificaciones,
            'no_leidas' => $noLeidas
        ]);
    }
    
    public function marcarLeida() {
        Auth::requiereAuth();
        
        $id = $_POST['id'] ?? 0;
        if ($this->notificacionModel->marcarComoLeida($id, Auth::id())) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}

