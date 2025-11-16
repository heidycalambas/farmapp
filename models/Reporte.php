<?php
/**
 * Modelo Reporte
 * FarmApp
 */

require_once __DIR__ . '/../config/database.php';

class Reporte {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function generarReporteVentas($periodo = 'diario') {
        $fechas = $this->obtenerFechasPeriodo($periodo);
        
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_pedidos,
                SUM(total) as total_ventas,
                AVG(total) as promedio_venta,
                SUM(CASE WHEN estado = 'completado' THEN total ELSE 0 END) as ventas_completadas,
                SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pedidos_pendientes
            FROM pedidos
            WHERE created_at >= ? AND created_at <= ?
        ");
        $stmt->execute([$fechas['inicio'], $fechas['fin']]);
        $resumen = $stmt->fetch();
        
        // Productos más vendidos
        $stmt = $this->db->prepare("
            SELECT 
                p.nombre as producto_nombre,
                SUM(dp.cantidad) as cantidad_vendida,
                SUM(dp.subtotal) as total_vendido
            FROM detalle_pedidos dp
            INNER JOIN productos p ON dp.producto_id = p.id
            INNER JOIN pedidos ped ON dp.pedido_id = ped.id
            WHERE ped.created_at >= ? AND ped.created_at <= ?
            GROUP BY dp.producto_id, p.nombre
            ORDER BY cantidad_vendida DESC
            LIMIT 10
        ");
        $stmt->execute([$fechas['inicio'], $fechas['fin']]);
        $productosVendidos = $stmt->fetchAll();
        
        return [
            'periodo' => $periodo,
            'fecha_inicio' => $fechas['inicio'],
            'fecha_fin' => $fechas['fin'],
            'resumen' => $resumen,
            'productos_vendidos' => $productosVendidos
        ];
    }
    
    public function generarReporteInventario() {
        $stmt = $this->db->query("
            SELECT 
                p.nombre as producto_nombre,
                c.nombre as categoria_nombre,
                i.cantidad as stock,
                i.stock_minimo,
                i.fecha_caducidad,
                CASE 
                    WHEN i.cantidad <= i.stock_minimo THEN 'Bajo Stock'
                    WHEN i.fecha_caducidad IS NOT NULL AND i.fecha_caducidad <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Por Caducar'
                    ELSE 'Normal'
                END as estado
            FROM inventario i
            INNER JOIN productos p ON i.producto_id = p.id
            INNER JOIN categorias c ON p.categoria_id = c.id
            WHERE p.activo = 1
            ORDER BY i.cantidad ASC
        ");
        return $stmt->fetchAll();
    }
    
    private function obtenerFechasPeriodo($periodo) {
        $hoy = date('Y-m-d 23:59:59');
        
        switch ($periodo) {
            case 'diario':
                $inicio = date('Y-m-d 00:00:00');
                break;
            case 'semanal':
                $inicio = date('Y-m-d 00:00:00', strtotime('-7 days'));
                break;
            case 'mensual':
                $inicio = date('Y-m-d 00:00:00', strtotime('-30 days'));
                break;
            default:
                $inicio = date('Y-m-d 00:00:00');
        }
        
        return ['inicio' => $inicio, 'fin' => $hoy];
    }
    
    public function guardarReporte($tipo, $periodo, $datos) {
        $fechas = $this->obtenerFechasPeriodo($periodo);
        $stmt = $this->db->prepare("
            INSERT INTO reportes (tipo, periodo, fecha_inicio, fecha_fin, datos) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $tipo,
            $periodo,
            $fechas['inicio'],
            $fechas['fin'],
            json_encode($datos)
        ]);
    }
}

