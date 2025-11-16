<?php
require_once __DIR__ . '/../layout/header.php';
$titulo = htmlspecialchars($producto['nombre']);
?>

<div class="container">
    <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo" class="btn btn-link">← Volver al Catálogo</a>
    
    <div class="producto-detalle">
        <div class="producto-detalle-imagen">
            <?php if ($producto['imagen']): ?>
                <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                     onerror="this.src='<?php echo BASE_URL; ?>/public/images/placeholder.jpg'">
            <?php else: ?>
                <div class="placeholder-img-large">💊</div>
            <?php endif; ?>
        </div>
        
        <div class="producto-detalle-info">
            <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            <p class="producto-categoria"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
            
            <div class="producto-precio-grande">
                $<?php echo number_format($producto['precio'], 2); ?>
            </div>
            
            <div class="producto-stock-info">
                <strong>Stock disponible:</strong> 
                <span class="<?php echo $producto['stock'] > $producto['stock_minimo'] ? 'stock-ok' : 'stock-bajo'; ?>">
                    <?php echo $producto['stock']; ?> unidades
                </span>
            </div>
            
            <?php if ($producto['fecha_caducidad']): ?>
                <p><strong>Fecha de caducidad:</strong> <?php echo date('d/m/Y', strtotime($producto['fecha_caducidad'])); ?></p>
            <?php endif; ?>
            
            <?php if ($producto['lote']): ?>
                <p><strong>Lote:</strong> <?php echo htmlspecialchars($producto['lote']); ?></p>
            <?php endif; ?>
            
            <div class="producto-descripcion-completa">
                <h3>Descripción</h3>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'] ?? 'Sin descripción disponible.')); ?></p>
            </div>
            
            <?php if ($producto['stock'] > 0): ?>
                <div class="producto-compra">
                    <form id="formAgregarCarritoDetalle" class="form-inline">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" min="1" 
                                   max="<?php echo $producto['stock']; ?>" value="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Agregar al Carrito</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Este producto no está disponible en este momento.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

