<?php
/**
 * Script de Prueba de Conexión a Base de Datos
 * Ejecuta este archivo para verificar que la conexión funciona
 * Accede desde: http://localhost/farmapp/public/test_connection.php
 */

require_once __DIR__ . '/../config/config.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test de Conexión - FarmApp</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Test de Conexión a Base de Datos</h1>";

try {
    // Intentar conectar
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<div class='success'>
            <strong>✅ ¡Conexión exitosa!</strong><br>
            La conexión a la base de datos se estableció correctamente.
          </div>";
    
    // Obtener información de la base de datos
    $stmt = $conn->query("SELECT DATABASE() as db_name, VERSION() as db_version");
    $dbInfo = $stmt->fetch();
    
    echo "<div class='info'>
            <strong>📊 Información de la Base de Datos:</strong><br>
            <strong>Base de datos:</strong> {$dbInfo['db_name']}<br>
            <strong>Versión MySQL:</strong> {$dbInfo['db_version']}
          </div>";
    
    // Verificar tablas
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>📋 Tablas en la Base de Datos</h2>";
    echo "<p>Se encontraron <strong>" . count($tables) . "</strong> tablas:</p>";
    echo "<table>";
    echo "<tr><th>#</th><th>Nombre de la Tabla</th><th>Registros</th></tr>";
    
    foreach ($tables as $index => $table) {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $stmt->fetch()['count'];
        echo "<tr>";
        echo "<td>" . ($index + 1) . "</td>";
        echo "<td><strong>$table</strong></td>";
        echo "<td>$count registros</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Verificar usuarios de prueba
    echo "<h2>👥 Usuarios de Prueba</h2>";
    $stmt = $conn->query("
        SELECT u.id, u.nombre, u.email, u.rol_id, r.nombre as rol 
        FROM usuarios u 
        JOIN roles r ON u.rol_id = r.id 
        ORDER BY u.id
    ");
    $usuarios = $stmt->fetchAll();
    
    if (count($usuarios) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>";
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>{$usuario['id']}</td>";
            echo "<td>{$usuario['nombre']}</td>";
            echo "<td>{$usuario['email']}</td>";
            echo "<td>{$usuario['rol']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<div class='info'>
                <strong>💡 Contraseña de prueba:</strong> 123456 (para todos los usuarios)
              </div>";
    }
    
    // Verificar productos
    $stmt = $conn->query("SELECT COUNT(*) as count FROM productos");
    $productosCount = $stmt->fetch()['count'];
    
    echo "<div class='info'>
            <strong>📦 Productos en el catálogo:</strong> $productosCount productos
          </div>";
    
    echo "<a href='" . BASE_URL . "/index.php' class='btn'>🚀 Ir a la Aplicación</a>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <strong>❌ Error de Conexión</strong><br>
            " . htmlspecialchars($e->getMessage()) . "
          </div>";
    
    echo "<div class='info'>
            <strong>🔧 Pasos para solucionar:</strong><br>
            1. Verifica que MySQL esté corriendo (XAMPP/Laragon)<br>
            2. Verifica que la base de datos 'farmapp' exista<br>
            3. Verifica que hayas importado el archivo database/farmapp.sql<br>
            4. Revisa la configuración en config/database.php<br>
            5. Asegúrate de que \$isProduction = false para localhost
          </div>";
}

echo "    </div>
</body>
</html>";
?>


