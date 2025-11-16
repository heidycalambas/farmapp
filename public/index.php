<?php
/**
 * Punto de Entrada Principal
 * FarmApp - Sistema de Gestión Farmacéutica
 */

// Incluir configuración
require_once __DIR__ . '/../config/config.php';

// Obtener acción
$action = $_GET['action'] ?? 'home';

// Router simple
switch ($action) {
    // Home
    case 'home':
    case '':
        $controller = new HomeController();
        $controller->index();
        break;
    
    // Autenticación
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    
    case 'registro':
        $controller = new AuthController();
        $controller->registro();
        break;
    
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    case 'perfil':
        $controller = new AuthController();
        $controller->perfil();
        break;
    
    // Productos
    case 'catalogo':
        $controller = new ProductoController();
        $controller->catalogo();
        break;
    
    case 'producto_detalle':
        $controller = new ProductoController();
        $controller->detalle();
        break;
    
    // Carrito
    case 'carrito':
        $controller = new CarritoController();
        $controller->ver();
        break;
    
    case 'carrito_agregar':
        header('Content-Type: application/json');
        $controller = new CarritoController();
        $controller->agregar();
        break;
    
    case 'carrito_actualizar':
        header('Content-Type: application/json');
        $controller = new CarritoController();
        $controller->actualizar();
        break;
    
    case 'carrito_eliminar':
        header('Content-Type: application/json');
        $controller = new CarritoController();
        $controller->eliminar();
        break;
    
    case 'carrito_vaciar':
        header('Content-Type: application/json');
        $controller = new CarritoController();
        $controller->vaciar();
        break;
    
    // Pedidos
    case 'checkout':
        $controller = new PedidoController();
        $controller->crear();
        break;
    
    case 'crear_pedido':
        $controller = new PedidoController();
        $controller->crear();
        break;
    
    case 'mis_pedidos':
        $controller = new PedidoController();
        $controller->misPedidos();
        break;
    
    case 'pedido_detalle':
        $controller = new PedidoController();
        $controller->detalle();
        break;
    
    // Administración
    case 'admin_dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;
    
    case 'farmaceutico_dashboard':
        $controller = new AdminController();
        $controller->farmaceuticoDashboard();
        break;
    
    case 'admin_productos':
        $controller = new ProductoController();
        $controller->adminLista();
        break;
    
    case 'admin_producto_crear':
        $controller = new ProductoController();
        $controller->adminCrear();
        break;
    
    case 'admin_producto_editar':
        $controller = new ProductoController();
        $controller->adminEditar();
        break;
    
    case 'admin_producto_eliminar':
        $controller = new ProductoController();
        $controller->adminEliminar();
        break;
    
    case 'admin_pedidos':
        $controller = new PedidoController();
        $controller->adminLista();
        break;
    
    case 'pedido_actualizar_estado':
        $controller = new PedidoController();
        $controller->actualizarEstado();
        break;
    
    case 'admin_inventario':
        $controller = new AdminController();
        $controller->inventario();
        break;
    
    case 'admin_actualizar_stock':
        $controller = new AdminController();
        $controller->actualizarStock();
        break;
    
    case 'admin_agregar_stock':
        $controller = new AdminController();
        $controller->agregarStock();
        break;
    
    case 'admin_usuarios':
        $controller = new AdminController();
        $controller->usuarios();
        break;
    
    case 'admin_categorias':
        $controller = new AdminController();
        $controller->categorias();
        break;
    
    case 'admin_reportes':
        $controller = new AdminController();
        $controller->reportes();
        break;
    
    // Notificaciones
    case 'notificaciones_obtener':
        header('Content-Type: application/json');
        $controller = new NotificacionController();
        $controller->obtener();
        break;
    
    case 'notificacion_marcar_leida':
        header('Content-Type: application/json');
        $controller = new NotificacionController();
        $controller->marcarLeida();
        break;
    
    // Test de conexión (solo para desarrollo)
    case 'test_connection':
        require_once __DIR__ . '/test_connection.php';
        exit;
        break;
    
    default:
        http_response_code(404);
        echo '<h1>404 - Página no encontrada</h1>';
        echo '<p><a href="' . BASE_URL . '/index.php">Volver al inicio</a></p>';
        break;
}

