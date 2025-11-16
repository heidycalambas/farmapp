<?php
/**
 * Controlador de Pedidos
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../utils/Auth.php';

class PedidoController {
    private $pedidoModel;
    
    public function __construct() {
        $this->pedidoModel = new Pedido();
    }
    
    public function crear() {
        Auth::requiereAuth();
        
        if (empty($_SESSION['carrito'])) {
            $_SESSION['error'] = 'El carrito está vacío';
            header('Location: ' . BASE_URL . '/index.php?action=carrito');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $direccionEntrega = $_POST['direccion'] ?? '';
            $metodoPago = $_POST['metodo_pago'] ?? 'simulado';
            
            if (empty($direccionEntrega)) {
                $_SESSION['error'] = 'Por favor ingresa una dirección de entrega';
                header('Location: ' . BASE_URL . '/index.php?action=checkout');
                exit;
            }
            
            try {
                $pedidoId = $this->pedidoModel->crear(
                    Auth::id(),
                    $_SESSION['carrito'],
                    $direccionEntrega,
                    $metodoPago
                );
                
                // Vaciar carrito
                $_SESSION['carrito'] = [];
                
                $_SESSION['success'] = 'Pedido realizado exitosamente. Número de pedido: #' . $pedidoId;
                header('Location: ' . BASE_URL . '/index.php?action=pedido_detalle&id=' . $pedidoId);
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ' . BASE_URL . '/index.php?action=carrito');
                exit;
            }
        } else {
            require_once __DIR__ . '/../views/pedidos/checkout.php';
        }
    }
    
    public function misPedidos() {
        Auth::requiereAuth();
        
        $pedidos = $this->pedidoModel->obtenerPorUsuario(Auth::id());
        require_once __DIR__ . '/../views/pedidos/mis_pedidos.php';
    }
    
    public function detalle() {
        Auth::requiereAuth();
        
        $id = $_GET['id'] ?? 0;
        $pedido = $this->pedidoModel->obtenerPorId($id);
        
        if (!$pedido || ($pedido['usuario_id'] != Auth::id() && !Auth::esAdmin() && !Auth::esFarmaceutico())) {
            $_SESSION['error'] = 'Pedido no encontrado';
            header('Location: ' . BASE_URL . '/index.php?action=mis_pedidos');
            exit;
        }
        
        $detalles = $this->pedidoModel->obtenerDetalles($id);
        require_once __DIR__ . '/../views/pedidos/detalle.php';
    }
    
    public function adminLista() {
        Auth::requiereRol(['Administrador', 'Farmacéutico']);
        
        $pedidos = $this->pedidoModel->obtenerTodos();
        require_once __DIR__ . '/../views/admin/pedidos.php';
    }
    
    public function actualizarEstado() {
        Auth::requiereRol(['Administrador', 'Farmacéutico']);
        
        $id = $_POST['pedido_id'] ?? 0;
        $estado = $_POST['estado'] ?? '';
        
        $estadosValidos = ['pendiente', 'en_preparacion', 'enviado', 'completado', 'cancelado'];
        
        if (!in_array($estado, $estadosValidos)) {
            $_SESSION['error'] = 'Estado inválido';
            header('Location: ' . BASE_URL . '/index.php?action=admin_pedidos');
            exit;
        }
        
        if ($this->pedidoModel->actualizarEstado($id, $estado)) {
            $_SESSION['success'] = 'Estado del pedido actualizado';
        } else {
            $_SESSION['error'] = 'Error al actualizar estado';
        }
        
        header('Location: ' . BASE_URL . '/index.php?action=admin_pedidos');
        exit;
    }
}

