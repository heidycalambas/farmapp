<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador']);
$titulo = 'Gestión de Productos';
?>

<div class="container">
    <div class="page-header">
        <h1>Gestión de Productos</h1>
        <a href="<?php echo BASE_URL; ?>/index.php?action=admin_producto_crear" class="btn btn-primary">Nuevo Producto</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td>
                        <?php if ($producto['imagen']): ?>
                            <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                 alt="" class="table-img"
                                 onerror="this.src='<?php echo BASE_URL; ?>/public/images/placeholder.jpg'">
                        <?php else: ?>
                            <span class="placeholder-small">💊</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td>
                        <span class="<?php echo $producto['stock'] <= $producto['stock_minimo'] ? 'text-danger' : ''; ?>">
                            <?php echo $producto['stock']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/index.php?action=admin_producto_editar&id=<?php echo $producto['id']; ?>" 
                           class="btn btn-sm btn-outline">Editar</a>
                        <a href="<?php echo BASE_URL; ?>/index.php?action=admin_producto_eliminar&id=<?php echo $producto['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

