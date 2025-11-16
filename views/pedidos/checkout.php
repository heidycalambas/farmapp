<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereAuth();
$titulo = 'Finalizar Compra';

$carrito = $_SESSION['carrito'] ?? [];
if (empty($carrito)) {
    header('Location: ' . BASE_URL . '/index.php?action=carrito');
    exit;
}

$total = 0;
foreach ($carrito as $item) {
    $total += $item['subtotal'];
}

$usuario = Auth::user();
?>

<div class="container">
    <h1>Finalizar Compra</h1>
    
    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Datos de Entrega</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=crear_pedido">
                <div class="form-group">
                    <label for="direccion">Dirección de Entrega *</label>
                    <textarea id="direccion" name="direccion" rows="3" required 
                              placeholder="Ingresa tu dirección completa"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                        <option value="simulado">Pago Simulado (Demo)</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                    </select>
                </div>
                
                <div class="checkout-resumen">
                    <h3>Resumen del Pedido</h3>
                    <div class="resumen-items">
                        <?php foreach ($carrito as $item): ?>
                            <div class="resumen-item-row">
                                <span><?php echo htmlspecialchars($item['nombre']); ?> x<?php echo $item['cantidad']; ?></span>
                                <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="resumen-total-row">
                        <strong>Total: $<?php echo number_format($total, 2); ?></strong>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">Confirmar Pedido</button>
                <a href="<?php echo BASE_URL; ?>/index.php?action=carrito" class="btn btn-secondary btn-block">Volver al Carrito</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

