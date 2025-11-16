<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador', 'Farmacéutico']);
$titulo = 'Gestión de Inventario';
?>

<div class="container">
    <h1>Gestión de Inventario</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Fecha Caducidad</th>
                <th>Lote</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr class="<?php echo $producto['stock'] <= $producto['stock_minimo'] ? 'row-warning' : ''; ?>">
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td>
                        <strong class="<?php echo $producto['stock'] <= $producto['stock_minimo'] ? 'text-danger' : ''; ?>">
                            <?php echo $producto['stock']; ?>
                        </strong>
                    </td>
                    <td><?php echo $producto['stock_minimo']; ?></td>
                    <td>
                        <?php if ($producto['fecha_caducidad']): ?>
                            <?php 
                            $fechaCad = strtotime($producto['fecha_caducidad']);
                            $hoy = time();
                            $diasRestantes = floor(($fechaCad - $hoy) / (60 * 60 * 24));
                            $class = $diasRestantes <= 30 ? 'text-danger' : '';
                            ?>
                            <span class="<?php echo $class; ?>">
                                <?php echo date('d/m/Y', $fechaCad); ?>
                                <?php if ($diasRestantes > 0): ?>
                                    (<?php echo $diasRestantes; ?> días)
                                <?php endif; ?>
                            </span>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($producto['lote'] ?? 'N/A'); ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" 
                                onclick="mostrarModalAgregarStock(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>')">
                            Agregar Stock
                        </button>
                        <button class="btn btn-sm btn-outline" 
                                onclick="mostrarModalActualizarStock(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>', <?php echo $producto['stock']; ?>, '<?php echo $producto['fecha_caducidad'] ?? ''; ?>', '<?php echo htmlspecialchars($producto['lote'] ?? ''); ?>')">
                            Actualizar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Agregar Stock -->
<div id="modalAgregarStock" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalAgregarStock')">&times;</span>
        <h3>Agregar Stock</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=admin_agregar_stock">
            <input type="hidden" id="agregar_producto_id" name="producto_id">
            <div class="form-group">
                <label>Producto: <strong id="agregar_producto_nombre"></strong></label>
            </div>
            <div class="form-group">
                <label for="agregar_cantidad">Cantidad a Agregar:</label>
                <input type="number" id="agregar_cantidad" name="cantidad" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalAgregarStock')">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal Actualizar Stock -->
<div id="modalActualizarStock" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalActualizarStock')">&times;</span>
        <h3>Actualizar Stock</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=admin_actualizar_stock">
            <input type="hidden" id="actualizar_producto_id" name="producto_id">
            <div class="form-group">
                <label>Producto: <strong id="actualizar_producto_nombre"></strong></label>
            </div>
            <div class="form-group">
                <label for="actualizar_cantidad">Cantidad Total:</label>
                <input type="number" id="actualizar_cantidad" name="cantidad" min="0" required>
            </div>
            <div class="form-group">
                <label for="actualizar_fecha_caducidad">Fecha de Caducidad:</label>
                <input type="date" id="actualizar_fecha_caducidad" name="fecha_caducidad">
            </div>
            <div class="form-group">
                <label for="actualizar_lote">Lote:</label>
                <input type="text" id="actualizar_lote" name="lote">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalActualizarStock')">Cancelar</button>
        </form>
    </div>
</div>

<script>
function mostrarModalAgregarStock(productoId, productoNombre) {
    document.getElementById('agregar_producto_id').value = productoId;
    document.getElementById('agregar_producto_nombre').textContent = productoNombre;
    document.getElementById('modalAgregarStock').style.display = 'block';
}

function mostrarModalActualizarStock(productoId, productoNombre, stock, fechaCaducidad, lote) {
    document.getElementById('actualizar_producto_id').value = productoId;
    document.getElementById('actualizar_producto_nombre').textContent = productoNombre;
    document.getElementById('actualizar_cantidad').value = stock;
    document.getElementById('actualizar_fecha_caducidad').value = fechaCaducidad;
    document.getElementById('actualizar_lote').value = lote;
    document.getElementById('modalActualizarStock').style.display = 'block';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

