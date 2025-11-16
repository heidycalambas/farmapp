<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div class="hero">
    <div class="container">
        <h1>Bienvenido a FarmApp</h1>
        <p class="lead">Tu farmacia en línea. Medicamentos de calidad al alcance de un clic.</p>
        <div class="hero-buttons">
            <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo" class="btn btn-primary btn-lg">Ver Catálogo</a>
            <?php if (!Auth::check()): ?>
                <a href="<?php echo BASE_URL; ?>/index.php?action=login" class="btn btn-secondary btn-lg">Iniciar Sesión</a>
                <a href="<?php echo BASE_URL; ?>/index.php?action=registro" class="btn btn-outline btn-lg">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <section class="features">
        <h2 class="section-title">Nuestros Servicios</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">💊</div>
                <h3>Catálogo Completo</h3>
                <p>Amplia variedad de medicamentos y productos farmacéuticos</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚚</div>
                <h3>Envío Rápido</h3>
                <p>Entrega rápida y segura a tu domicilio</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Compra Segura</h3>
                <p>Proceso de compra seguro y confiable</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Seguimiento en Tiempo Real</h3>
                <p>Monitorea el estado de tus pedidos en tiempo real</p>
            </div>
        </div>
    </section>
    
    <?php if (!empty($categorias)): ?>
    <section class="categorias-preview">
        <h2 class="section-title">Categorías Populares</h2>
        <div class="categorias-grid">
            <?php foreach (array_slice($categorias, 0, 4) as $categoria): ?>
                <div class="categoria-card">
                    <h3><?php echo htmlspecialchars($categoria['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></p>
                    <a href="<?php echo BASE_URL; ?>/index.php?action=catalogo&categoria=<?php echo $categoria['id']; ?>" class="btn btn-sm">Ver Productos</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

