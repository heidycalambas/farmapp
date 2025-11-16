<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Farmacéutico']);
$titulo = 'Panel de Farmacéutico';
?>

<div class="container">
    <h1>Panel de Farmacéutico</h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>⚠️ Alertas de Stock Bajo</h3>
            <?php if (empty($alertasStock)): ?>
                <p class="text-success">✓ No hay productos con bajo stock</p>
            <?php else: ?>
                <ul class="alertas-lista">
                    <?php foreach ($alertasStock as $alerta): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($alerta['producto_nombre']); ?></strong>
                            - Stock: <?php echo $alerta['cantidad']; ?> (Mínimo: <?php echo $alerta['stock_minimo']; ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h3>📅 Productos por Caducar</h3>
            <?php if (empty($productosPorCaducar)): ?>
                <p class="text-success">✓ No hay productos próximos a caducar</p>
            <?php else: ?>
                <ul class="alertas-lista">
                    <?php foreach ($productosPorCaducar as $producto): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($producto['producto_nombre']); ?></strong>
                            - Caduca: <?php echo date('d/m/Y', strtotime($producto['fecha_caducidad'])); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <a href="<?php echo BASE_URL; ?>/index.php?action=admin_pedidos" class="btn btn-primary">Gestionar Pedidos</a>
        <a href="<?php echo BASE_URL; ?>/index.php?action=admin_inventario" class="btn btn-primary">Gestionar Inventario</a>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

