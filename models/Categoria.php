<?php
/**
 * Modelo Categoria
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodas() {
        $stmt = $this->db->query("
            SELECT * FROM categorias 
            WHERE activa = 1 
            ORDER BY nombre ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("
            INSERT INTO categorias (nombre, descripcion) 
            VALUES (?, ?)
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'] ?? null
        ]);
    }
    
    public function actualizar($id, $datos) {
        $stmt = $this->db->prepare("
            UPDATE categorias 
            SET nombre = ?, descripcion = ?, activa = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'] ?? null,
            $datos['activa'] ?? 1,
            $id
        ]);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("UPDATE categorias SET activa = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

