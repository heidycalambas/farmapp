<?php
/**
 * Modelo Pedido
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function crear($usuarioId, $items, $direccionEntrega, $metodoPago) {
        $this->db->beginTransaction();
        
        try {
            // Calcular total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }
            
            // Crear pedido
            $stmt = $this->db->prepare("
                INSERT INTO pedidos (usuario_id, total, estado, metodo_pago, direccion_entrega) 
                VALUES (?, ?, 'pendiente', ?, ?)
            ");
            $stmt->execute([$usuarioId, $total, $metodoPago, $direccionEntrega]);
            $pedidoId = $this->db->lastInsertId();
            
            // Crear detalles
            $productoModel = new Producto();
            foreach ($items as $item) {
                // Verificar stock
                if (!$productoModel->verificarStock($item['producto_id'], $item['cantidad'])) {
                    throw new Exception("Stock insuficiente para el producto ID: " . $item['producto_id']);
                }
                
                $stmt = $this->db->prepare("
                    INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $subtotal = $item['precio'] * $item['cantidad'];
                $stmt->execute([
                    $pedidoId,
                    $item['producto_id'],
                    $item['cantidad'],
                    $item['precio'],
                    $subtotal
                ]);
                
                // Actualizar inventario
                $productoModel->actualizarStock($item['producto_id'], -$item['cantidad']);
            }
            
            $this->db->commit();
            
            // Crear notificación
            $this->crearNotificacion($usuarioId, 'pedido', 'Pedido realizado', "Tu pedido #{$pedidoId} ha sido registrado correctamente.");
            
            return $pedidoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function obtenerPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT * FROM pedidos 
            WHERE usuario_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
    
    public function obtenerTodos() {
        $stmt = $this->db->query("
            SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email
            FROM pedidos p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email
            FROM pedidos p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function obtenerDetalles($pedidoId) {
        $stmt = $this->db->prepare("
            SELECT dp.*, pr.nombre as producto_nombre, pr.imagen as producto_imagen
            FROM detalle_pedidos dp
            INNER JOIN productos pr ON dp.producto_id = pr.id
            WHERE dp.pedido_id = ?
        ");
        $stmt->execute([$pedidoId]);
        return $stmt->fetchAll();
    }
    
    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $result = $stmt->execute([$estado, $id]);
        
        if ($result) {
            $pedido = $this->obtenerPorId($id);
            $mensajes = [
                'en_preparacion' => 'Tu pedido #' . $id . ' está en preparación',
                'enviado' => 'Tu pedido #' . $id . ' ha sido enviado',
                'completado' => 'Tu pedido #' . $id . ' ha sido completado',
                'cancelado' => 'Tu pedido #' . $id . ' ha sido cancelado'
            ];
            
            if (isset($mensajes[$estado])) {
                $this->crearNotificacion($pedido['usuario_id'], 'pedido', 'Estado del pedido', $mensajes[$estado]);
            }
        }
        
        return $result;
    }
    
    private function crearNotificacion($usuarioId, $tipo, $titulo, $mensaje) {
        $stmt = $this->db->prepare("
            INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$usuarioId, $tipo, $titulo, $mensaje]);
    }
}

