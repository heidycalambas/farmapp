<?php
/**
 * Modelo Usuario
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.email = ? AND u.activo = 1
        ");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            unset($usuario['password']);
            return $usuario;
        }
        return false;
    }
    
    public function registrar($datos) {
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nombre, email, password, telefono, direccion, rol_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['email'],
            $password_hash,
            $datos['telefono'] ?? null,
            $datos['direccion'] ?? null,
            3 // Rol Cliente por defecto
        ]);
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function obtenerTodos() {
        $stmt = $this->db->query("
            SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id 
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function actualizar($id, $datos) {
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ?";
        $params = [$datos['nombre'], $datos['email'], $datos['telefono'] ?? null, $datos['direccion'] ?? null];
        
        if (!empty($datos['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($datos['rol_id'])) {
            $sql .= ", rol_id = ?";
            $params[] = $datos['rol_id'];
        }
        
        if (isset($datos['activo'])) {
            $sql .= ", activo = ?";
            $params[] = $datos['activo'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function existeEmail($email, $excluirId = null) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = ?";
        $params = [$email];
        
        if ($excluirId) {
            $sql .= " AND id != ?";
            $params[] = $excluirId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}

