# 🚀 Instrucciones Rápidas - Configuración del Sistema

## ✅ Ya Configurado

El sistema ya está configurado para:
- **URL**: `http://localhost/inventariopech`
- **Puerto MySQL**: `3307`
- **Base de datos**: `inventario_ana`
- **Usuario**: `root`
- **Contraseña**: (vacía)

## 📋 Paso 1: Crear la Base de Datos

### Opción A: Desde línea de comandos

```bash
# Conectar a MySQL en puerto 3307
mysql -u root -p -P 3307 -h localhost

# Ejecutar dentro de MySQL:
CREATE DATABASE inventario_ana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_ana;
SOURCE config/database.sql;
exit;
```

### Opción B: Con comando directo

```bash
# Crear la base de datos
mysql -u root -p -P 3307 -h localhost -e "CREATE DATABASE inventario_ana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar el script
mysql -u root -p -P 3307 -h localhost inventario_ana < config/database.sql
```

### Opción C: Usando phpMyAdmin

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Servidor: `localhost:3307`
3. Usuario: `root`
4. Click en "Nueva" para crear base de datos
5. Nombre: `inventario_ana`
6. Cotejamiento: `utf8mb4_unicode_ci`
7. Crear
8. Click en "Importar"
9. Selecciona el archivo: `config/database.sql`
10. Click en "Continuar"

### Opción D: Usando XAMPP/WAMP Control Panel

Si usas XAMPP o WAMP:
1. Abre el panel de control
2. Click en "Shell" o "MySQL Console"
3. Ejecuta:
```sql
CREATE DATABASE inventario_ana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_ana;
SOURCE C:/xampp/htdocs/inventariopech/config/database.sql;
```

## 🔍 Paso 2: Verificar Instalación

Abre en tu navegador:
```
http://localhost/inventariopech/diagnostico.php
```

Este diagnóstico te mostrará:
- ✅ Si PHP está correctamente configurado
- ✅ Si la base de datos está conectada
- ✅ Cuántos registros hay (debería mostrar 3 de ejemplo)
- ✅ Si todos los archivos están en su lugar

## 🎯 Paso 3: Usar el Sistema

Una vez que el diagnóstico muestre todo en verde:

### Página Principal
```
http://localhost/inventariopech
```

### Ver Inventario
```
http://localhost/inventariopech/infraestructura
```

### Registrar Nueva Infraestructura
```
http://localhost/inventariopech/infraestructura/crear
```

### Ver Estadísticas
```
http://localhost/inventariopech/infraestructura/estadisticas
```

### API REST
```
http://localhost/inventariopech/api/infraestructuras
```

## ❌ Solución de Problemas Comunes

### Problema 1: "Error de conexión a base de datos"

**Causa**: La base de datos no existe o las credenciales son incorrectas.

**Solución**:
1. Verifica que MySQL esté corriendo en puerto 3307
2. Ejecuta: `netstat -an | grep 3307` (Linux/Mac) o `netstat -an | findstr 3307` (Windows)
3. Crea la base de datos usando las instrucciones del Paso 1

### Problema 2: "404 Not Found" en los enlaces

**Causa**: mod_rewrite no está habilitado o .htaccess no se está leyendo.

**Solución**:

**En XAMPP (Windows)**:
- Edita: `C:\xampp\apache\conf\httpd.conf`
- Busca: `LoadModule rewrite_module modules/mod_rewrite.so`
- Asegúrate que NO tenga `#` al inicio
- Reinicia Apache desde el panel XAMPP

**En Ubuntu/Linux**:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

**En WAMP (Windows)**:
- Click izquierdo en el icono WAMP
- Apache → Apache Modules → rewrite_module (debe estar marcado)

### Problema 3: "Access denied for user 'root'"

**Causa**: La contraseña de MySQL no está vacía.

**Solución**:
Edita `config/config.php` y cambia:
```php
define('DB_PASS', 'tu_contraseña_aqui');
```

### Problema 4: Los enlaces apuntan a "inventario-ana" en vez de "inventariopech"

**Causa**: Caché del navegador.

**Solución**:
- Presiona `Ctrl + F5` para limpiar caché
- O abre en modo incógnito

## 🔧 Verificaciones Rápidas

### ¿MySQL está corriendo?

**Windows (XAMPP/WAMP)**:
- Verifica que el servicio MySQL esté en verde en el panel de control

**Linux/Mac**:
```bash
ps aux | grep mysql
# o
service mysql status
```

### ¿Qué puerto usa MySQL?

```bash
# Conectar sin especificar puerto (usa 3306 por defecto)
mysql -u root -p

# Dentro de MySQL, ejecutar:
SHOW VARIABLES LIKE 'port';
```

Si muestra `3307`, todo está correcto.
Si muestra `3306`, debes cambiar `DB_PORT` en `config/config.php` a `3306`.

### ¿Apache está leyendo .htaccess?

Crea un archivo `test.php` en la carpeta `php-mvc`:
```php
<?php
echo "Apache funciona!";
```

Accede a:
```
http://localhost/inventariopech/test.php
```

Si ves "Apache funciona!", Apache está configurado correctamente.

## 📊 Datos de Ejemplo

El sistema incluye 3 infraestructuras de ejemplo:

1. **Presa San Lorenzo** (Lima, Cañete)
   - Código: ANA-LIM-PRES-0001
   - Tipo: Presa/Represa
   - Capacidad: 15,000,000 m³

2. **Bocatoma Muyurina** (Cusco)
   - Código: ANA-CUZ-BOCA-0001
   - Tipo: Bocatoma
   - Capacidad: 3.5 m³/s

3. **Canal Principal La Irrigación** (Arequipa)
   - Código: ANA-ARE-CANA-0001
   - Tipo: Canal Principal
   - Capacidad: 5.2 m³/s

## 🆘 Si Nada Funciona

1. **Ejecuta el diagnóstico**:
   ```
   http://localhost/inventariopech/diagnostico.php
   ```

2. **Verifica los logs de Apache**:
   - XAMPP: `C:\xampp\apache\logs\error.log`
   - Linux: `/var/log/apache2/error.log`

3. **Verifica los logs de PHP**:
   - Busca `php_error.log` en la carpeta del proyecto

4. **Habilita errores en pantalla**:
   Edita `config/config.php`:
   ```php
   define('ENVIRONMENT', 'development');
   ```

## ✨ Todo Listo

Si el diagnóstico muestra todo en verde (✓), el sistema está funcionando correctamente.

Puedes empezar a:
- ✅ Registrar nuevas infraestructuras
- ✅ Consultar el inventario
- ✅ Ver estadísticas
- ✅ Usar la API REST

---

**Sistema de Inventario ANA v1.0**
Autoridad Nacional del Agua - Perú
