# 📋 Guía de Instalación - FarmApp

## 🎯 Paso 1: Configuración en LOCALHOST

### 1.1. Verificar que XAMPP/Laragon esté funcionando

1. Abre XAMPP Control Panel o Laragon
2. Inicia los servicios:
   - ✅ **Apache** (debe estar en verde)
   - ✅ **MySQL** (debe estar en verde)

### 1.2. Crear la base de datos con MySQL Workbench

1. Abre **MySQL Workbench**
2. Conecta a tu servidor local (normalmente hay una conexión predefinida llamada "Local instance MySQL" o similar)
   - Si no tienes conexión, crea una nueva:
     - Hostname: `localhost` o `127.0.0.1`
     - Port: `3306`
     - Username: `root`
     - Password: (déjalo vacío si no tienes contraseña, o ingresa la tuya)
3. Una vez conectado, verás el panel principal
4. En la barra de herramientas, haz clic en el botón **"Create a new schema"** (icono de base de datos con un +)
   - O escribe en la pestaña de consultas:
   ```sql
   CREATE DATABASE farmapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
5. Si usaste el botón:
   - Nombre del schema: `farmapp`
   - Default Collation: `utf8mb4_unicode_ci`
   - Haz clic en "Apply"
6. ✅ Verás la base de datos `farmapp` en el panel lateral izquierdo

### 1.3. Importar el archivo SQL con MySQL Workbench

**Método 1: Usando el menú de importación (Recomendado)**

1. En MySQL Workbench, haz clic en el menú **Server** → **Data Import**
2. Selecciona **"Import from Self-Contained File"**
3. Haz clic en el botón **"..."** y navega hasta el archivo: `C:\farmapp\database\farmapp.sql`
4. En **"Default Target Schema"**, selecciona `farmapp` (o haz clic en "New" si no aparece)
5. Haz clic en **"Start Import"** en la esquina inferior derecha
6. ✅ Espera a que termine (verás un mensaje de éxito en verde)

**Método 2: Usando la pestaña de consultas**

1. En MySQL Workbench, haz clic en la base de datos `farmapp` en el panel lateral
2. Abre una nueva pestaña de consultas (icono de SQL o File → New Query Tab)
3. Abre el archivo SQL:
   - File → Open SQL Script
   - Navega a: `C:\farmapp\database\farmapp.sql`
4. Verifica que el schema seleccionado sea `farmapp` (dropdown en la barra de herramientas)
5. Haz clic en el botón **"Execute"** (rayo) o presiona `Ctrl + Shift + Enter`
6. ✅ Verás los mensajes de éxito en el panel de resultados

### 1.4. Verificar la configuración

1. Abre el archivo `config/database.php`
2. Verifica que `$isProduction = false;` (línea 12)
3. Verifica que la configuración local sea:
   ```php
   'host' => 'localhost',
   'dbname' => 'farmapp',
   'username' => 'root',
   'password' => '',
   ```

### 1.5. Verificar que funciona

1. Abre tu navegador y ve a: `http://localhost/farmapp/`
   - O directamente: `http://localhost/farmapp/index.php`
2. Deberías ver la página principal de FarmApp
3. Prueba acceder al login:
   - `http://localhost/farmapp/index.php?action=login`
4. Prueba iniciar sesión con:
   - **Email:** `admin@mail.com`
   - **Password:** `123456`
5. Si todo funciona, serás redirigido al dashboard de administrador

**⚠️ Si ves errores "Not Found":**
- Verifica que el módulo `mod_rewrite` esté habilitado en Apache
- Revisa el archivo `SOLUCION_RUTAS.md` para más detalles

✅ **Si todo funciona, continúa con el paso 2**

---

## 🌐 Paso 2: Subir a InfinityFree

### 2.1. Obtener credenciales de InfinityFree

1. Inicia sesión en tu cuenta de InfinityFree
2. Ve al panel de control (cPanel)
3. Busca la sección "MySQL Databases" o "Bases de datos MySQL"
4. Anota la siguiente información:
   - **Host MySQL:** (ejemplo: `sqlXXX.epizy.com`)
   - **Nombre de usuario:** (ejemplo: `epiz_XXXXXX`)
   - **Nombre de la base de datos:** (ejemplo: `epiz_XXXXXX_farmapp`)
   - **Contraseña:** (la que configuraste)

### 2.2. Crear la base de datos en InfinityFree

1. En el panel de InfinityFree, ve a "MySQL Databases"
2. Crea una nueva base de datos llamada `farmapp` (o el nombre que prefieras)
3. Crea un nuevo usuario MySQL y asígnalo a la base de datos
4. Anota todas las credenciales

### 2.3. Importar la base de datos a InfinityFree

**Opción A: Usando phpMyAdmin de InfinityFree**

1. En el panel de InfinityFree, busca "phpMyAdmin"
2. Abre phpMyAdmin
3. Selecciona tu base de datos en el menú lateral
4. Ve a la pestaña "Importar" o "Import"
5. Selecciona el archivo `database/farmapp.sql`
6. Haz clic en "Continuar" o "Go"
7. ✅ Espera a que termine la importación

**Opción B: Usando línea de comandos (si tienes acceso SSH)**

```bash
mysql -h sqlXXX.epizy.com -u epiz_XXXXXX -p epiz_XXXXXX_farmapp < database/farmapp.sql
```

### 2.4. Subir archivos vía FTP

1. Descarga un cliente FTP (FileZilla es gratuito)
2. Conecta a tu servidor InfinityFree usando:
   - **Host:** `ftpupload.net` (o el que te proporcionen)
   - **Usuario:** Tu usuario de InfinityFree
   - **Contraseña:** Tu contraseña de InfinityFree
   - **Puerto:** 21
3. Sube TODOS los archivos de la carpeta `farmapp` a la carpeta `htdocs` o `public_html`
4. ⚠️ **IMPORTANTE:** Mantén la estructura de carpetas

### 2.5. Actualizar configuración para producción

1. Edita el archivo `config/database.php` en el servidor (o edítalo localmente y súbelo)
2. Cambia `$isProduction = true;` (línea 12)
3. Completa la configuración de producción con tus datos de InfinityFree:
   ```php
   private $configProduction = [
       'host' => 'sqlXXX.epizy.com', // Tu host de InfinityFree
       'dbname' => 'epiz_XXXXXX_farmapp', // Tu nombre de BD
       'username' => 'epiz_XXXXXX', // Tu usuario
       'password' => 'tu_password_aqui', // Tu contraseña
       'charset' => 'utf8mb4'
   ];
   ```

### 2.6. Actualizar BASE_URL

1. Edita el archivo `config/config.php` en el servidor
2. Cambia la línea:
   ```php
   define('BASE_URL', 'http://localhost/farmapp');
   ```
   Por:
   ```php
   define('BASE_URL', 'https://tu-dominio.epizy.com'); // Tu dominio de InfinityFree
   ```

### 2.7. Verificar permisos de carpetas

1. Asegúrate de que la carpeta `public/images/productos/` tenga permisos de escritura (755 o 777)
2. Puedes hacerlo desde el panel de InfinityFree en "File Manager"

### 2.8. Probar en producción

1. Abre tu navegador y ve a tu dominio de InfinityFree
2. Prueba iniciar sesión con:
   - **Email:** `admin@mail.com`
   - **Password:** `123456`
3. ✅ Si funciona, ¡listo!

---

## 🔧 Solución de Problemas

### Error: "Error de conexión a la base de datos"

**Causas posibles:**
- Las credenciales en `config/database.php` son incorrectas
- MySQL no está corriendo (en localhost)
- El host de InfinityFree es incorrecto

**Solución:**
1. Verifica las credenciales
2. En localhost, verifica que MySQL esté corriendo
3. En InfinityFree, verifica el host (a veces es `localhost` en lugar del host externo)

### Error: "Base de datos no existe"

**Solución:**
1. Crea la base de datos primero
2. Verifica que el nombre en `config/database.php` coincida exactamente

### Las imágenes no se cargan

**Solución:**
1. Verifica que `BASE_URL` en `config/config.php` sea correcto
2. Verifica permisos de la carpeta `public/images/productos/`
3. Verifica que las rutas sean relativas correctamente

### Sesiones no funcionan

**Solución:**
1. Verifica permisos de la carpeta de sesiones de PHP
2. En InfinityFree, a veces necesitas configurar la ruta de sesiones manualmente

---

## 📝 Notas Importantes

- ⚠️ **NUNCA** subas el archivo `config/database.php` con contraseñas reales a un repositorio público
- 🔒 En producción, cambia las contraseñas de los usuarios de prueba
- 📦 Asegúrate de tener una copia de seguridad de la base de datos antes de hacer cambios importantes
- 🌐 InfinityFree tiene algunas limitaciones (tiempo de ejecución, tamaño de BD, etc.)

---

## ✅ Checklist Final

### Localhost
- [ ] MySQL está corriendo
- [ ] Base de datos `farmapp` creada
- [ ] Archivo SQL importado correctamente
- [ ] Configuración en `database.php` es correcta
- [ ] La aplicación funciona en `http://localhost/farmapp`
- [ ] Puedo iniciar sesión con los usuarios de prueba

### InfinityFree
- [ ] Base de datos creada en InfinityFree
- [ ] Archivo SQL importado en InfinityFree
- [ ] Todos los archivos subidos vía FTP
- [ ] `$isProduction = true` en `database.php`
- [ ] Credenciales de InfinityFree configuradas
- [ ] `BASE_URL` actualizado con mi dominio
- [ ] Permisos de carpetas configurados
- [ ] La aplicación funciona en mi dominio
- [ ] Puedo iniciar sesión en producción

---

¡Listo! Si tienes algún problema, revisa la sección de "Solución de Problemas" o verifica los logs de error de PHP.

