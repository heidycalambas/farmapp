<?php
require_once __DIR__ . '/../layout/header.php';
$titulo = 'Iniciar Sesión';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Iniciar Sesión</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?action=login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
            
            <p class="auth-link">
                ¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>/index.php?action=registro">Regístrate aquí</a>
            </p>
            
            <div class="demo-accounts">
                <h4>Cuentas de Prueba:</h4>
                <ul>
                    <li><strong>Admin:</strong> admin@mail.com / 123456</li>
                    <li><strong>Farmacéutico:</strong> farma@mail.com / 123456</li>
                    <li><strong>Cliente:</strong> cliente@mail.com / 123456</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

