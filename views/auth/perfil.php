<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../layout/header.php';

Auth::requiereAuth();
$titulo = 'Mi Perfil';

$usuario = Auth::user();
?>

<div class="container">
    <h1>Mi Perfil</h1>
    
    <div class="auth-container">
        <div class="auth-card">
            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=perfil">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($usuario['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" 
                           value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="password">Nueva Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres">
                    <small>Deja este campo vacío si no deseas cambiar tu contraseña</small>
                </div>
                
                <div class="form-group">
                    <label>Rol</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['rol_nombre']); ?>" disabled>
                    <small>El rol no puede ser modificado</small>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Actualizar Perfil</button>
            </form>
            
            <div style="margin-top: 20px;">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-secondary">Volver al Inicio</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

