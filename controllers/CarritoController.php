<?php
/**
 * Controlador de Carrito
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../utils/Auth.php';

class CarritoController {
    private $productoModel;
    
    public function __construct() {
        $this->productoModel = new Producto();
    }
    
    public function agregar() {
        $productoId = $_POST['producto_id'] ?? 0;
        $cantidad = intval($_POST['cantidad'] ?? 1);
        
        if ($productoId <= 0 || $cantidad <= 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit;
        }
        
        $producto = $this->productoModel->obtenerPorId($productoId);
        
        if (!$producto) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            exit;
        }
        
        if ($producto['stock'] < $cantidad) {
            echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
            exit;
        }
        
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        // Verificar si el producto ya está en el carrito
        $existe = false;
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['producto_id'] == $productoId) {
                $nuevaCantidad = $item['cantidad'] + $cantidad;
                if ($producto['stock'] >= $nuevaCantidad) {
                    $_SESSION['carrito'][$key]['cantidad'] = $nuevaCantidad;
                    $_SESSION['carrito'][$key]['subtotal'] = $nuevaCantidad * $item['precio'];
                } else {
                    echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
                    exit;
                }
                $existe = true;
                break;
            }
        }
        
        if (!$existe) {
            $_SESSION['carrito'][] = [
                'producto_id' => $productoId,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen'],
                'subtotal' => $producto['precio'] * $cantidad
            ];
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Producto agregado al carrito',
            'carrito_count' => count($_SESSION['carrito'])
        ]);
    }
    
    public function ver() {
        require_once __DIR__ . '/../views/carrito/ver.php';
    }
    
    public function actualizar() {
        $index = $_POST['index'] ?? -1;
        $cantidad = intval($_POST['cantidad'] ?? 0);
        
        if ($index >= 0 && isset($_SESSION['carrito'][$index])) {
            $item = $_SESSION['carrito'][$index];
            $producto = $this->productoModel->obtenerPorId($item['producto_id']);
            
            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            } elseif ($producto && $producto['stock'] >= $cantidad) {
                $_SESSION['carrito'][$index]['cantidad'] = $cantidad;
                $_SESSION['carrito'][$index]['subtotal'] = $cantidad * $item['precio'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
                exit;
            }
            
            echo json_encode(['success' => true, 'message' => 'Carrito actualizado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ítem no encontrado']);
        }
    }
    
    public function eliminar() {
        $index = $_POST['index'] ?? -1;
        
        if ($index >= 0 && isset($_SESSION['carrito'][$index])) {
            unset($_SESSION['carrito'][$index]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ítem no encontrado']);
        }
    }
    
    public function vaciar() {
        $_SESSION['carrito'] = [];
        echo json_encode(['success' => true, 'message' => 'Carrito vaciado']);
    }
}

