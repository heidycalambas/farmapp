<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../layout/header.php';

Auth::requiereAuth();
$titulo = 'Carrito de Compras';

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
foreach ($carrito as $item) {
    $total += $item['subtotal'];
}
?>

<div class="container">
    <h1>Carrito de Compras</h1>
    
    <?php if (empty($carrito)): ?>
        <div class="empty-state">
            <p>Tu carrito está vacío.</p>
            <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo" class="btn btn-primary">Ver Catálogo</a>
        </div>
    <?php else: ?>
        <div class="carrito-container">
            <div class="carrito-items">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $index => $item): ?>
                            <tr data-index="<?php echo $index; ?>">
                                <td>
                                    <div class="carrito-item-info">
                                        <?php if ($item['imagen']): ?>
                                            <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($item['imagen']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                                                 class="carrito-item-img"
                                                 onerror="this.src='<?php echo BASE_URL; ?>/public/images/placeholder.jpg'">
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['precio'], 2); ?></td>
                                <td>
                                    <input type="number" class="cantidad-input" 
                                           value="<?php echo $item['cantidad']; ?>" 
                                           min="1" 
                                           data-index="<?php echo $index; ?>"
                                           data-precio="<?php echo $item['precio']; ?>">
                                </td>
                                <td class="subtotal">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger eliminar-item" 
                                            data-index="<?php echo $index; ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="carrito-actions">
                    <button class="btn btn-secondary" id="vaciar-carrito">Vaciar Carrito</button>
                </div>
            </div>
            
            <div class="carrito-resumen">
                <div class="resumen-card">
                    <h3>Resumen del Pedido</h3>
                    <div class="resumen-item">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>Envío:</span>
                        <span>Gratis</span>
                    </div>
                    <div class="resumen-item resumen-total">
                        <span><strong>Total:</strong></span>
                        <span><strong>$<?php echo number_format($total, 2); ?></strong></span>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/index.php?action=checkout" class="btn btn-primary btn-block btn-lg">
                        Proceder al Pago
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

