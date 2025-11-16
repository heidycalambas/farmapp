<?php
/**
 * Controlador de Productos
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../utils/Auth.php';

class ProductoController {
    private $productoModel;
    private $categoriaModel;
    
    public function __construct() {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }
    
    public function catalogo() {
        $filtros = [
            'nombre' => $_GET['buscar'] ?? '',
            'categoria_id' => $_GET['categoria'] ?? ''
        ];
        
        $productos = $this->productoModel->obtenerTodos($filtros);
        $categorias = $this->categoriaModel->obtenerTodas();
        
        require_once __DIR__ . '/../views/productos/catalogo.php';
    }
    
    public function detalle() {
        $id = $_GET['id'] ?? 0;
        $producto = $this->productoModel->obtenerPorId($id);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: ' . BASE_URL . '/index.php?action=catalogo');
            exit;
        }
        
        require_once __DIR__ . '/../views/productos/detalle.php';
    }
    
    public function adminLista() {
        Auth::requiereRol(['Administrador']);
        
        $productos = $this->productoModel->obtenerTodos();
        $categorias = $this->categoriaModel->obtenerTodas();
        
        require_once __DIR__ . '/../views/admin/productos.php';
    }
    
    public function adminCrear() {
        Auth::requiereRol(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'categoria_id' => $_POST['categoria_id'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'stock_minimo' => $_POST['stock_minimo'] ?? STOCK_MINIMO_DEFAULT,
                'fecha_caducidad' => $_POST['fecha_caducidad'] ?? null,
                'lote' => $_POST['lote'] ?? null
            ];
            
            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $uploadDir = UPLOAD_PATH;
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $rutaCompleta = $uploadDir . $nombreArchivo;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    $datos['imagen'] = $nombreArchivo;
                }
            }
            
            if ($this->productoModel->crear($datos)) {
                $_SESSION['success'] = 'Producto creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear producto';
            }
            
            header('Location: ' . BASE_URL . '/index.php?action=admin_productos');
            exit;
        } else {
            $categorias = $this->categoriaModel->obtenerTodas();
            require_once __DIR__ . '/../views/admin/producto_form.php';
        }
    }
    
    public function adminEditar() {
        Auth::requiereRol(['Administrador']);
        
        $id = $_GET['id'] ?? 0;
        $producto = $this->productoModel->obtenerPorId($id);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: ' . BASE_URL . '/index.php?action=admin_productos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'categoria_id' => $_POST['categoria_id'] ?? 0
            ];
            
            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $uploadDir = UPLOAD_PATH;
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $rutaCompleta = $uploadDir . $nombreArchivo;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    $datos['imagen'] = $nombreArchivo;
                }
            }
            
            if ($this->productoModel->actualizar($id, $datos)) {
                $_SESSION['success'] = 'Producto actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar producto';
            }
            
            header('Location: ' . BASE_URL . '/index.php?action=admin_productos');
            exit;
        } else {
            $categorias = $this->categoriaModel->obtenerTodas();
            require_once __DIR__ . '/../views/admin/producto_form.php';
        }
    }
    
    public function adminEliminar() {
        Auth::requiereRol(['Administrador']);
        
        $id = $_GET['id'] ?? 0;
        
        if ($this->productoModel->eliminar($id)) {
            $_SESSION['success'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar producto';
        }
        
        header('Location: ' . BASE_URL . '/index.php?action=admin_productos');
        exit;
    }
}

