<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo . ' - ' : ''; ?>FarmApp</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="<?php echo BASE_URL; ?>/index.php">🏥 FarmApp</a>
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>/index.php?action=catalogo">Catálogo</a></li>
                <?php if (Auth::check()): ?>
                    <li><a href="<?php echo BASE_URL; ?>/index.php?action=carrito">
                        Carrito 
                        <?php if (!empty($_SESSION['carrito'])): ?>
                            <span class="badge"><?php echo count($_SESSION['carrito']); ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="<?php echo BASE_URL; ?>/index.php?action=mis_pedidos">Mis Pedidos</a></li>
                    
                    <?php if (Auth::esAdmin()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?action=admin_dashboard">Admin</a></li>
                    <?php elseif (Auth::esFarmaceutico()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?action=farmaceutico_dashboard">Farmacéutico</a></li>
                    <?php endif; ?>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <?php echo htmlspecialchars(Auth::user()['nombre']); ?> ▼
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo BASE_URL; ?>/index.php?action=perfil">Mi Perfil</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/index.php?action=logout">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/index.php?action=login">Iniciar Sesión</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/index.php?action=registro">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <main class="main-content">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

