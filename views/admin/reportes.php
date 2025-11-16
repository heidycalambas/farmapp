<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador']);
$titulo = 'Reportes';
?>

<div class="container">
    <h1>Reportes</h1>
    
    <div class="filtros-container">
        <form method="GET" action="<?php echo BASE_URL; ?>/index.php" class="filtros-form">
            <input type="hidden" name="action" value="admin_reportes">
            
            <div class="filtro-group">
                <label>Tipo de Reporte:</label>
                <select name="tipo" class="form-control">
                    <option value="ventas" <?php echo ($tipo ?? 'ventas') == 'ventas' ? 'selected' : ''; ?>>Ventas</option>
                    <option value="inventario" <?php echo ($tipo ?? '') == 'inventario' ? 'selected' : ''; ?>>Inventario</option>
                </select>
            </div>
            
            <div class="filtro-group">
                <label>Período:</label>
                <select name="periodo" class="form-control">
                    <option value="diario" <?php echo ($periodo ?? 'diario') == 'diario' ? 'selected' : ''; ?>>Diario</option>
                    <option value="semanal" <?php echo ($periodo ?? '') == 'semanal' ? 'selected' : ''; ?>>Semanal</option>
                    <option value="mensual" <?php echo ($periodo ?? '') == 'mensual' ? 'selected' : ''; ?>>Mensual</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </form>
    </div>
    
    <?php if (isset($reporte) && $tipo === 'ventas'): ?>
        <div class="reporte-container">
            <h2>Reporte de Ventas - <?php echo ucfirst($reporte['periodo']); ?></h2>
            <p><strong>Período:</strong> <?php echo date('d/m/Y', strtotime($reporte['fecha_inicio'])); ?> - <?php echo date('d/m/Y', strtotime($reporte['fecha_fin'])); ?></p>
            
            <div class="reporte-resumen">
                <div class="resumen-card">
                    <h3>Resumen</h3>
                    <p><strong>Total de Pedidos:</strong> <?php echo $reporte['resumen']['total_pedidos']; ?></p>
                    <p><strong>Total de Ventas:</strong> $<?php echo number_format($reporte['resumen']['total_ventas'] ?? 0, 2); ?></p>
                    <p><strong>Promedio por Venta:</strong> $<?php echo number_format($reporte['resumen']['promedio_venta'] ?? 0, 2); ?></p>
                    <p><strong>Ventas Completadas:</strong> $<?php echo number_format($reporte['resumen']['ventas_completadas'] ?? 0, 2); ?></p>
                    <p><strong>Pedidos Pendientes:</strong> <?php echo $reporte['resumen']['pedidos_pendientes']; ?></p>
                </div>
            </div>
            
            <?php if (!empty($reporte['productos_vendidos'])): ?>
                <h3>Productos Más Vendidos</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Vendida</th>
                            <th>Total Vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reporte['productos_vendidos'] as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['producto_nombre']); ?></td>
                                <td><?php echo $producto['cantidad_vendida']; ?></td>
                                <td>$<?php echo number_format($producto['total_vendido'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php elseif (isset($reporte) && $tipo === 'inventario'): ?>
        <div class="reporte-container">
            <h2>Reporte de Inventario</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Stock Mínimo</th>
                        <th>Fecha Caducidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reporte as $item): ?>
                        <tr class="<?php echo $item['estado'] == 'Bajo Stock' ? 'row-warning' : ''; ?>">
                            <td><?php echo htmlspecialchars($item['producto_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['categoria_nombre']); ?></td>
                            <td><?php echo $item['stock']; ?></td>
                            <td><?php echo $item['stock_minimo']; ?></td>
                            <td><?php echo $item['fecha_caducidad'] ? date('d/m/Y', strtotime($item['fecha_caducidad'])) : 'N/A'; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $item['estado'] == 'Normal' ? 'success' : 'warning'; ?>">
                                    <?php echo $item['estado']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

