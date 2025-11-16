<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/config.php';

Auth::requiereRol(['Administrador']);
$titulo = 'Gestión de Categorías';
?>

<div class="container">
    <h1>Gestión de Categorías</h1>
    
    <div class="form-card">
        <h3>Nueva Categoría</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=admin_categorias">
            <input type="hidden" name="accion" value="crear">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </div>
        </form>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?php echo $categoria['id']; ?></td>
                    <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $categoria['activa'] ? 'success' : 'danger'; ?>">
                            <?php echo $categoria['activa'] ? 'Activa' : 'Inactiva'; ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=admin_categorias" style="display: inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('¿Estás seguro?');">Eliminar</button>
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

