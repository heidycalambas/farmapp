<?php
require_once __DIR__ . '/../layout/header.php';
$titulo = 'Registro';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Crear Cuenta</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=registro">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña (mínimo 6 caracteres)</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional)</label>
                    <input type="tel" id="telefono" name="telefono" 
                           value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección (opcional)</label>
                    <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
            
            <p class="auth-link">
                ¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>/index.php?action=login">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

