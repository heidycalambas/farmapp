<?php
/**
 * Modelo Notificacion
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Notificacion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerPorUsuario($usuarioId, $noLeidas = false) {
        $sql = "SELECT * FROM notificaciones WHERE usuario_id = ?";
        $params = [$usuarioId];
        
        if ($noLeidas) {
            $sql .= " AND leida = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT 50";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function marcarComoLeida($id, $usuarioId) {
        $stmt = $this->db->prepare("
            UPDATE notificaciones 
            SET leida = 1 
            WHERE id = ? AND usuario_id = ?
        ");
        return $stmt->execute([$id, $usuarioId]);
    }
    
    public function contarNoLeidas($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM notificaciones 
            WHERE usuario_id = ? AND leida = 0
        ");
        $stmt->execute([$usuarioId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function crear($usuarioId, $tipo, $titulo, $mensaje) {
        $stmt = $this->db->prepare("
            INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$usuarioId, $tipo, $titulo, $mensaje]);
    }
}

