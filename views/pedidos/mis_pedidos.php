<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../layout/header.php';

Auth::requiereAuth();
$titulo = 'Mis Pedidos';
?>

<div class="container">
    <h1>Mis Pedidos</h1>
    
    <?php if (empty($pedidos)): ?>
        <div class="empty-state">
            <p>No has realizado ningún pedido aún.</p>
            <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo" class="btn btn-primary">Ver Catálogo</a>
        </div>
    <?php else: ?>
        <div class="pedidos-lista">
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido-card">
                    <div class="pedido-header">
                        <div>
                            <h3>Pedido #<?php echo $pedido['id']; ?></h3>
                            <p class="pedido-fecha">Fecha: <?php echo date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
                        </div>
                        <div>
                            <span class="badge badge-<?php echo $pedido['estado']; ?>">
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
                    </div>
                    <div class="pedido-body">
                        <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                        <?php if ($pedido['direccion_entrega']): ?>
                            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion_entrega']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="pedido-footer">
                        <a href="<?php echo BASE_URL; ?>/index.php?action=pedido_detalle&id=<?php echo $pedido['id']; ?>" 
                           class="btn btn-sm btn-outline">Ver Detalles</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

