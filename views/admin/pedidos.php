<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador', 'Farmacéutico']);
$titulo = 'Gestión de Pedidos';
?>

<div class="container">
    <h1>Gestión de Pedidos</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td>#<?php echo $pedido['id']; ?></td>
                    <td>
                        <?php echo htmlspecialchars($pedido['usuario_nombre']); ?><br>
                        <small><?php echo htmlspecialchars($pedido['usuario_email']); ?></small>
                    </td>
                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                    <td>
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
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/index.php?action=pedido_detalle&id=<?php echo $pedido['id']; ?>" 
                           class="btn btn-sm btn-outline">Ver</a>
                        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=pedido_actualizar_estado" 
                              style="display: inline-block;">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                            <select name="estado" class="form-control-sm" onchange="this.form.submit()">
                                <option value="pendiente" <?php echo $pedido['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en_preparacion" <?php echo $pedido['estado'] == 'en_preparacion' ? 'selected' : ''; ?>>En Preparación</option>
                                <option value="enviado" <?php echo $pedido['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                <option value="completado" <?php echo $pedido['estado'] == 'completado' ? 'selected' : ''; ?>>Completado</option>
                                <option value="cancelado" <?php echo $pedido['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

