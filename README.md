# FarmApp - Sistema de Gestión Farmacéutica

Sistema completo de gestión farmacéutica desarrollado en PHP puro (sin frameworks) con arquitectura MVC.

## 🚀 Características

- ✅ Catálogo de productos con filtros
- ✅ Sistema de carrito de compras
- ✅ Gestión de pedidos
- ✅ Sistema de roles (Administrador, Farmacéutico, Cliente)
- ✅ Panel de administración completo
- ✅ Gestión de inventario
- ✅ Alertas automáticas (bajo stock, caducidad)
- ✅ Sistema de notificaciones
- ✅ Reportes automáticos (diarios, semanales, mensuales)
- ✅ Diseño responsive

## 📋 Requisitos

- PHP 7.4 - 8.x
- MySQL 5.7+ o MariaDB
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL, mbstring

## 🛠️ Instalación

### 1. Base de Datos

1. Importa el archivo `database/farmapp.sql` en MySQL Workbench o phpMyAdmin
2. Crea una base de datos llamada `farmapp` (o modifica la configuración)

### 2. Configuración

Edita el archivo `config/database.php` con tus credenciales:

```php
private $host = 'localhost';
private $dbname = 'farmapp';
private $username = 'root';
private $password = '';
```

### 3. Configuración de URL Base

Edita el archivo `config/config.php` y ajusta la constante `BASE_URL`:

```php
define('BASE_URL', 'http://localhost/farmapp');
```

### 4. Permisos de Carpetas

Asegúrate de que la carpeta de imágenes tenga permisos de escritura:

```bash
chmod 755 public/images/productos/
```

## 👥 Usuarios de Prueba

- **Administrador:**
  - Email: admin@mail.com
  - Password: 123456

- **Farmacéutico:**
  - Email: farma@mail.com
  - Password: 123456

- **Cliente:**
  - Email: cliente@mail.com
  - Password: 123456

## 📁 Estructura del Proyecto

```
farmapp/
├── config/          # Configuración
├── controllers/     # Controladores MVC
├── models/          # Modelos de datos
├── views/           # Vistas (HTML/PHP)
├── public/          # Archivos públicos
│   ├── css/         # Estilos
│   ├── js/          # JavaScript
│   ├── images/      # Imágenes
│   └── index.php    # Punto de entrada
├── database/        # Scripts SQL
└── utils/           # Utilidades
```

## 🌐 Compatibilidad con InfinityFree

Para desplegar en InfinityFree:

1. Sube todos los archivos vía FTP
2. Importa la base de datos desde el panel de control
3. Ajusta `BASE_URL` en `config/config.php` con tu dominio
4. Ajusta las credenciales de base de datos en `config/database.php`

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Protección contra SQL Injection con PDO Prepared Statements
- Validación de sesiones
- Control de acceso por roles

## 📝 Notas

- Las imágenes de productos se guardan en `public/images/productos/`
- El sistema genera alertas automáticas para bajo stock y productos por caducar
- Los reportes se generan automáticamente según el período seleccionado

## 🐛 Solución de Problemas

**Error de conexión a la base de datos:**
- Verifica las credenciales en `config/database.php`
- Asegúrate de que MySQL esté corriendo

**Imágenes no se cargan:**
- Verifica permisos de la carpeta `public/images/productos/`
- Asegúrate de que la ruta `BASE_URL` sea correcta

**Sesiones no funcionan:**
- Verifica que la carpeta de sesiones tenga permisos de escritura
- Revisa la configuración de PHP para sesiones

## 📄 Licencia

Este proyecto es de código abierto y está disponible para uso educativo y comercial.

---

Desarrollado con ❤️ usando PHP puro

