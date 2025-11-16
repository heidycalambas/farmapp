<?php
require_once __DIR__ . '/../layout/header.php';
$titulo = 'Catálogo de Productos';
?>

<div class="container">
    <h1>Catálogo de Medicamentos</h1>
    
    <div class="filtros-container">
        <form method="GET" action="<?php echo BASE_URL; ?>/index.php" class="filtros-form">
            <input type="hidden" name="action" value="catalogo">
            
            <div class="filtro-group">
                <input type="text" name="buscar" placeholder="Buscar por nombre..." 
                       value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" 
                       class="form-control">
            </div>
            
            <div class="filtro-group">
                <select name="categoria" class="form-control">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>" 
                                <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo" class="btn btn-secondary">Limpiar</a>
        </form>
    </div>
    
    <?php if (empty($productos)): ?>
        <div class="empty-state">
            <p>No se encontraron productos con los filtros seleccionados.</p>
        </div>
    <?php else: ?>
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <div class="producto-imagen">
                        <?php if ($producto['imagen']): ?>
                            <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                 onerror="this.src='<?php echo BASE_URL; ?>/public/images/placeholder.jpg'">
                        <?php else: ?>
                            <div class="placeholder-img">💊</div>
                        <?php endif; ?>
                        <?php if ($producto['stock'] <= $producto['stock_minimo']): ?>
                            <span class="badge badge-warning">Bajo Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="producto-info">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="producto-categoria"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
                        <p class="producto-descripcion"><?php echo htmlspecialchars(substr($producto['descripcion'] ?? '', 0, 100)); ?>...</p>
                        <div class="producto-footer">
                            <span class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></span>
                            <span class="producto-stock">Stock: <?php echo $producto['stock']; ?></span>
                        </div>
                        <div class="producto-actions">
                            <a href="<?php echo BASE_URL; ?>/index.php?action=producto_detalle&id=<?php echo $producto['id']; ?>" 
                               class="btn btn-sm btn-outline">Ver Detalles</a>
                            <?php if ($producto['stock'] > 0): ?>
                                <button class="btn btn-sm btn-primary agregar-carrito" 
                                        data-producto-id="<?php echo $producto['id']; ?>"
                                        data-precio="<?php echo $producto['precio']; ?>">
                                    Agregar al Carrito
                                </button>
                            <?php else: ?>
                                <span class="btn btn-sm btn-disabled">Sin Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para agregar al carrito -->
<div id="modalCarrito" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Agregar al Carrito</h3>
        <form id="formAgregarCarrito">
            <input type="hidden" id="modal_producto_id" name="producto_id">
            <div class="form-group">
                <label for="modal_cantidad">Cantidad:</label>
                <input type="number" id="modal_cantidad" name="cantidad" min="1" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

