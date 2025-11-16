<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador']);
$esEdicion = isset($producto) && !empty($producto);
$titulo = $esEdicion ? 'Editar Producto' : 'Nuevo Producto';
?>

<div class="container">
    <h1><?php echo $esEdicion ? 'Editar Producto' : 'Nuevo Producto'; ?></h1>
    
    <form method="POST" enctype="multipart/form-data" class="form-card">
        <div class="form-group">
            <label for="nombre">Nombre del Producto *</label>
            <input type="text" id="nombre" name="nombre" required 
                   value="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="precio">Precio *</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required 
                       value="<?php echo $producto['precio'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="categoria_id">Categoría *</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>" 
                                <?php echo (isset($producto['categoria_id']) && $producto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="imagen">Imagen del Producto</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
            <?php if ($esEdicion && !empty($producto['imagen'])): ?>
                <p>Imagen actual: <img src="<?php echo BASE_URL; ?>/public/images/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                       alt="" class="preview-img"></p>
            <?php endif; ?>
        </div>
        
        <?php if (!$esEdicion): ?>
        <div class="form-row">
            <div class="form-group">
                <label for="stock">Stock Inicial</label>
                <input type="number" id="stock" name="stock" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label for="stock_minimo">Stock Mínimo</label>
                <input type="number" id="stock_minimo" name="stock_minimo" min="0" 
                       value="<?php echo STOCK_MINIMO_DEFAULT; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_caducidad">Fecha de Caducidad</label>
                <input type="date" id="fecha_caducidad" name="fecha_caducidad">
            </div>
            
            <div class="form-group">
                <label for="lote">Lote</label>
                <input type="text" id="lote" name="lote">
            </div>
        </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo $esEdicion ? 'Actualizar' : 'Crear'; ?> Producto</button>
            <a href="<?php echo BASE_URL; ?>/index.php?action=admin_productos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

