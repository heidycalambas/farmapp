<?php
/**
 * Controlador de Administración
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Reporte.php';
require_once __DIR__ . '/../models/Inventario.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../utils/Auth.php';

class AdminController {
    private $reporteModel;
    private $inventarioModel;
    private $usuarioModel;
    private $categoriaModel;
    private $notificacionModel;
    
    public function __construct() {
        $this->reporteModel = new Reporte();
        $this->inventarioModel = new Inventario();
        $this->usuarioModel = new Usuario();
        $this->categoriaModel = new Categoria();
        $this->notificacionModel = new Notificacion();
    }
    
    public function dashboard() {
        Auth::requiereRol(['Administrador']);
        
        // Generar alertas automáticas
        $this->generarAlertas();
        
        $alertasStock = $this->inventarioModel->obtenerAlertasBajoStock();
        $productosPorCaducar = $this->inventarioModel->obtenerProductosPorCaducar();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function farmaceuticoDashboard() {
        Auth::requiereRol(['Farmacéutico']);
        
        $alertasStock = $this->inventarioModel->obtenerAlertasBajoStock();
        $productosPorCaducar = $this->inventarioModel->obtenerProductosPorCaducar();
        
        require_once __DIR__ . '/../views/farmaceutico/dashboard.php';
    }
    
    public function reportes() {
        Auth::requiereRol(['Administrador']);
        
        $periodo = $_GET['periodo'] ?? 'diario';
        $tipo = $_GET['tipo'] ?? 'ventas';
        
        if ($tipo === 'ventas') {
            $reporte = $this->reporteModel->generarReporteVentas($periodo);
        } else {
            $reporte = $this->reporteModel->generarReporteInventario();
        }
        
        require_once __DIR__ . '/../views/admin/reportes.php';
    }
    
    public function inventario() {
        Auth::requiereRol(['Administrador', 'Farmacéutico']);
        
        require_once __DIR__ . '/../models/Producto.php';
        $productoModel = new Producto();
        $productos = $productoModel->obtenerTodos();
        
        require_once __DIR__ . '/../views/admin/inventario.php';
    }
    
    public function actualizarStock() {
        Auth::requiereRol(['Administrador', 'Farmacéutico']);
        
        $productoId = $_POST['producto_id'] ?? 0;
        $cantidad = intval($_POST['cantidad'] ?? 0);
        $fechaCaducidad = $_POST['fecha_caducidad'] ?? null;
        $lote = $_POST['lote'] ?? null;
        
        if ($this->inventarioModel->actualizarStock($productoId, $cantidad, $fechaCaducidad, $lote)) {
            $_SESSION['success'] = 'Stock actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar stock';
        }
        
        header('Location: ' . BASE_URL . '/index.php?action=admin_inventario');
        exit;
    }
    
    public function agregarStock() {
        Auth::requiereRol(['Administrador', 'Farmacéutico']);
        
        $productoId = $_POST['producto_id'] ?? 0;
        $cantidad = intval($_POST['cantidad'] ?? 0);
        
        if ($this->inventarioModel->agregarStock($productoId, $cantidad)) {
            $_SESSION['success'] = 'Stock agregado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al agregar stock';
        }
        
        header('Location: ' . BASE_URL . '/index.php?action=admin_inventario');
        exit;
    }
    
    public function usuarios() {
        Auth::requiereRol(['Administrador']);
        
        $usuarios = $this->usuarioModel->obtenerTodos();
        require_once __DIR__ . '/../views/admin/usuarios.php';
    }
    
    public function categorias() {
        Auth::requiereRol(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            
            if ($accion === 'crear') {
                $datos = [
                    'nombre' => $_POST['nombre'] ?? '',
                    'descripcion' => $_POST['descripcion'] ?? ''
                ];
                if ($this->categoriaModel->crear($datos)) {
                    $_SESSION['success'] = 'Categoría creada exitosamente';
                }
            } elseif ($accion === 'editar') {
                $id = $_POST['id'] ?? 0;
                $datos = [
                    'nombre' => $_POST['nombre'] ?? '',
                    'descripcion' => $_POST['descripcion'] ?? '',
                    'activa' => $_POST['activa'] ?? 1
                ];
                if ($this->categoriaModel->actualizar($id, $datos)) {
                    $_SESSION['success'] = 'Categoría actualizada exitosamente';
                }
            } elseif ($accion === 'eliminar') {
                $id = $_POST['id'] ?? 0;
                if ($this->categoriaModel->eliminar($id)) {
                    $_SESSION['success'] = 'Categoría eliminada exitosamente';
                }
            }
            
            header('Location: ' . BASE_URL . '/index.php?action=admin_categorias');
            exit;
        }
        
        $categorias = $this->categoriaModel->obtenerTodas();
        require_once __DIR__ . '/../views/admin/categorias.php';
    }
    
    private function generarAlertas() {
        // Alertas de bajo stock
        $alertasStock = $this->inventarioModel->obtenerAlertasBajoStock();
        foreach ($alertasStock as $alerta) {
            // Verificar si ya existe notificación
            // (En producción, deberías verificar duplicados)
        }
        
        // Alertas de caducidad
        $productosPorCaducar = $this->inventarioModel->obtenerProductosPorCaducar();
        foreach ($productosPorCaducar as $producto) {
            // Crear notificaciones para administradores
        }
    }
}

