<?php
/**
 * Modelo Inventario
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Inventario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerAlertasBajoStock() {
        $stmt = $this->db->query("
            SELECT i.*, p.nombre as producto_nombre, p.precio
            FROM inventario i
            INNER JOIN productos p ON i.producto_id = p.id
            WHERE i.cantidad <= i.stock_minimo AND p.activo = 1
            ORDER BY i.cantidad ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerProductosPorCaducar($dias = 30) {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        $stmt = $this->db->prepare("
            SELECT i.*, p.nombre as producto_nombre, p.precio
            FROM inventario i
            INNER JOIN productos p ON i.producto_id = p.id
            WHERE i.fecha_caducidad IS NOT NULL 
            AND i.fecha_caducidad <= ? 
            AND i.fecha_caducidad >= CURDATE()
            AND p.activo = 1
            ORDER BY i.fecha_caducidad ASC
        ");
        $stmt->execute([$fechaLimite]);
        return $stmt->fetchAll();
    }
    
    public function actualizarStock($productoId, $cantidad, $fechaCaducidad = null, $lote = null) {
        $sql = "UPDATE inventario SET cantidad = ?, updated_at = NOW()";
        $params = [$cantidad];
        
        if ($fechaCaducidad) {
            $sql .= ", fecha_caducidad = ?";
            $params[] = $fechaCaducidad;
        }
        
        if ($lote) {
            $sql .= ", lote = ?";
            $params[] = $lote;
        }
        
        $sql .= " WHERE producto_id = ?";
        $params[] = $productoId;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function agregarStock($productoId, $cantidad) {
        $stmt = $this->db->prepare("
            UPDATE inventario 
            SET cantidad = cantidad + ?, updated_at = NOW() 
            WHERE producto_id = ?
        ");
        return $stmt->execute([$cantidad, $productoId]);
    }
}

