<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereAuth();
$titulo = 'Detalle del Pedido #' . $pedido['id'];
?>

<div class="container">
    <a href="<?php echo BASE_URL; ?>/index.php?action=mis_pedidos" class="btn btn-link">← Volver a Mis Pedidos</a>
    
    <div class="pedido-detalle">
        <div class="pedido-detalle-header">
            <h1>Pedido #<?php echo $pedido['id']; ?></h1>
            <span class="badge badge-<?php echo $pedido['estado']; ?> badge-lg">
                <?php 
                $estados = [
                    'pendiente' => 'Pendiente',
                    'en_preparacion' => 'En Preparación',
                    'enviado' => 'Enviado',
                    'completado' => 'Completado',
                    'cancelado' => 'Cancelado'
                ];
                echo $estados[$pedido['estado']] ?? $pedido['estado'];
                ?>
            </span>
        </div>
        
        <div class="pedido-info-grid">
            <div class="info-card">
                <h3>Información del Pedido</h3>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
                <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago'] ?? 'No especificado'); ?></p>
                <?php if ($pedido['direccion_entrega']): ?>
                    <p><strong>Dirección de Entrega:</strong><br><?php echo nl2br(htmlspecialchars($pedido['direccion_entrega'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="pedido-productos">
            <h3>Productos del Pedido</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $detalle): ?>
                        <tr>
                            <td>
                                <div class="producto-item-info">
                                    <?php if ($detalle['producto_imagen']): ?>
                                        <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($detalle['producto_imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($detalle['producto_nombre']); ?>"
                                             class="producto-item-img"
                                             onerror="this.src='<?php echo BASE_URL; ?>/public/images/placeholder.jpg'">
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($detalle['producto_nombre']); ?></span>
                                </div>
                            </td>
                            <td><?php echo $detalle['cantidad']; ?></td>
                            <td>$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td>$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($pedido['total'], 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

