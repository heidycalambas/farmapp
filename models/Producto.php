<?php
/**
 * Modelo Producto
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Producto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos($filtros = []) {
        $sql = "
            SELECT p.*, c.nombre as categoria_nombre, 
                   i.cantidad as stock, i.fecha_caducidad, i.stock_minimo
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN inventario i ON p.id = i.producto_id
            WHERE p.activo = 1
        ";
        
        $params = [];
        
        if (!empty($filtros['nombre'])) {
            $sql .= " AND p.nombre LIKE ?";
            $params[] = '%' . $filtros['nombre'] . '%';
        }
        
        if (!empty($filtros['categoria_id'])) {
            $sql .= " AND p.categoria_id = ?";
            $params[] = $filtros['categoria_id'];
        }
        
        $sql .= " ORDER BY p.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre, 
                   i.cantidad as stock, i.fecha_caducidad, i.stock_minimo, i.lote
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN inventario i ON p.id = i.producto_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("
            INSERT INTO productos (nombre, descripcion, precio, categoria_id, imagen) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $result = $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'] ?? null,
            $datos['precio'],
            $datos['categoria_id'],
            $datos['imagen'] ?? null
        ]);
        
        if ($result) {
            $productoId = $this->db->lastInsertId();
            // Crear registro de inventario
            $this->crearInventario($productoId, $datos);
            return $productoId;
        }
        return false;
    }
    
    public function actualizar($id, $datos) {
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ?";
        $params = [
            $datos['nombre'],
            $datos['descripcion'] ?? null,
            $datos['precio'],
            $datos['categoria_id']
        ];
        
        if (isset($datos['imagen'])) {
            $sql .= ", imagen = ?";
            $params[] = $datos['imagen'];
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
        $stmt = $this->db->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function crearInventario($productoId, $datos) {
        $stmt = $this->db->prepare("
            INSERT INTO inventario (producto_id, cantidad, stock_minimo, fecha_caducidad, lote) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $productoId,
            $datos['stock'] ?? 0,
            $datos['stock_minimo'] ?? STOCK_MINIMO_DEFAULT,
            $datos['fecha_caducidad'] ?? null,
            $datos['lote'] ?? null
        ]);
    }
    
    public function actualizarStock($productoId, $cantidad) {
        $stmt = $this->db->prepare("
            UPDATE inventario 
            SET cantidad = cantidad + ? 
            WHERE producto_id = ?
        ");
        return $stmt->execute([$cantidad, $productoId]);
    }
    
    public function verificarStock($productoId, $cantidadSolicitada) {
        $producto = $this->obtenerPorId($productoId);
        return $producto && $producto['stock'] >= $cantidadSolicitada;
    }
}

