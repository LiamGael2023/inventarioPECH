# Sistema de Inventario de Infraestructura Mayor - ANA (PHP MVC)

Sistema de gestión de inventario de infraestructura hidráulica mayor según la normativa de la Autoridad Nacional del Agua (ANA) del Perú. Implementado con PHP usando arquitectura MVC y MySQL.

## Características

- ✅ Arquitectura MVC (Modelo-Vista-Controlador)
- ✅ PHP 7.4+ con PDO
- ✅ Base de datos MySQL/MariaDB
- ✅ API REST completa
- ✅ Registro de infraestructura mayor (Presas, Bocatomas, Canales, Túneles, etc.)
- ✅ Validaciones según normativa ANA
- ✅ Sistema de filtros y búsqueda
- ✅ Estadísticas y reportes
- ✅ Interfaz responsive
- ✅ CRUD completo

## Tipos de Infraestructura Soportados

1. **Presa/Represa** - Estructuras de almacenamiento
2. **Bocatoma** - Estructuras de captación
3. **Canal Principal** - Canales de conducción
4. **Túnel** - Túneles de conducción
5. **Sifón** - Estructuras de cruce
6. **Acueducto** - Estructuras elevadas
7. **Desarenador** - Estructuras de sedimentación
8. **Estructura de Medición** - Aforadores y medidores

## Requisitos del Sistema

### Software Necesario
- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Apache 2.4+ o Nginx
- Extensiones PHP requeridas:
  - PDO
  - pdo_mysql
  - mbstring
  - json

## Instalación

### 1. Clonar o Copiar Archivos

```bash
# Copiar el directorio php-mvc al directorio web del servidor
# Por ejemplo en XAMPP: C:/xampp/htdocs/inventario-ana
# Por ejemplo en Linux: /var/www/html/inventario-ana
```

### 2. Configurar Base de Datos

```bash
# Acceder a MySQL
mysql -u root -p

# Ejecutar el script SQL
mysql -u root -p < config/database.sql

# O importar manualmente desde phpMyAdmin
```

El script creará:
- Base de datos `inventario_ana`
- Tabla `infraestructuras` con todos los campos
- 3 registros de ejemplo
- Tabla `usuarios` (opcional para futuras mejoras)

### 3. Configurar la Aplicación

Editar `config/config.php`:

```php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'inventario_ana');
define('DB_USER', 'root');        // Cambiar según tu configuración
define('DB_PASS', '');            // Cambiar según tu configuración

// URL de la aplicación
define('APP_URL', 'http://localhost/inventario-ana');  // Ajustar según tu instalación
```

### 4. Configurar Apache

El archivo `.htaccess` ya está incluido. Asegúrate de que `mod_rewrite` esté habilitado:

```bash
# En Ubuntu/Debian
sudo a2enmod rewrite
sudo service apache2 restart

# En XAMPP/Windows
# mod_rewrite ya viene habilitado por defecto
```

### 5. Verificar Permisos

```bash
# En Linux, dar permisos de escritura si es necesario
chmod -R 755 /var/www/html/inventario-ana
chown -R www-data:www-data /var/www/html/inventario-ana
```

### 6. Acceder al Sistema

Abrir en el navegador:
```
http://localhost/inventario-ana
```

## Estructura del Proyecto

```
php-mvc/
├── app/
│   ├── controllers/          # Controladores MVC
│   │   ├── HomeController.php
│   │   ├── InfraestructuraController.php
│   │   └── ApiController.php
│   ├── models/              # Modelos
│   │   └── Infraestructura.php
│   └── views/               # Vistas
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       ├── home/
│       │   └── index.php
│       └── infraestructura/
│           ├── index.php
│           ├── formulario.php
│           └── estadisticas.php
├── config/
│   ├── config.php           # Configuración general
│   └── database.sql         # Script de base de datos
├── core/                    # Clases del núcleo
│   ├── App.php             # Router principal
│   ├── Controller.php      # Controlador base
│   ├── Model.php           # Modelo base
│   └── Database.php        # Conexión a BD
├── public/                  # Archivos públicos
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── app.js
│       └── formulario.js
├── .htaccess               # Configuración Apache
├── index.php               # Punto de entrada
└── README.md
```

## Uso del Sistema

### Interfaz Web

1. **Inicio**: Vista general del sistema
2. **Inventario**: Lista de todas las infraestructuras con filtros
3. **Nuevo Registro**: Formulario para registrar infraestructura
4. **Estadísticas**: Dashboard con métricas y gráficos

### API REST

La API está disponible en `/api/` y soporta los siguientes endpoints:

#### Infraestructuras

```bash
# Listar todas
GET /api/infraestructuras

# Listar con filtros
GET /api/infraestructuras?tipo=Presa/Represa&region=Lima

# Obtener una específica
GET /api/infraestructuras/1

# Crear nueva
POST /api/infraestructuras
Content-Type: application/json
{
    "codigo": "ANA-LIM-PRES-0002",
    "nombre": "Presa Nueva",
    "tipo": "Presa/Represa",
    "region": "Lima",
    "provincia": "Huaral",
    "distrito": "Chancay"
    // ... más campos
}

# Actualizar
PUT /api/infraestructuras/1
Content-Type: application/json
{
    "nombre": "Presa Actualizada",
    // ... campos a actualizar
}

# Eliminar
DELETE /api/infraestructuras/1
```

#### Otros Endpoints

```bash
# Estadísticas
GET /api/estadisticas

# Catálogos (tipos, estados, usos, materiales)
GET /api/catalogos
```

### Respuestas de la API

```json
// Éxito
{
    "success": true,
    "data": { ... },
    "total": 10
}

// Error
{
    "error": "Mensaje de error",
    "errores": ["detalle 1", "detalle 2"]
}
```

## Campos del Inventario

### Datos Generales
- Código único (formato: ANA-REGIÓN-TIPO-NÚMERO)
- Nombre de la infraestructura
- Tipo de infraestructura

### Ubicación
- Región, Provincia, Distrito
- Coordenadas UTM (Este, Norte, Zona)
- Altitud (msnm)

### Hidrografía
- Cuenca hidrográfica
- Subcuenca
- Cuerpo de agua

### Características Técnicas
- Capacidad (m³ o m³/s)
- Dimensiones (longitud, ancho, altura)
- Área de influencia (hectáreas)
- Material de construcción
- Año de construcción

### Información Administrativa
- Titular/Propietario
- Operador actual
- Uso principal
- Número de licencia de agua

### Estado
- Estado de conservación (Muy Bueno, Bueno, Regular, Malo, Muy Malo)
- Estado operativo (Operativo, Inoperativo, En mantenimiento)
- Fecha de última inspección
- Observaciones

## Validaciones

El sistema valida automáticamente:
- Formato del código ANA
- Coordenadas UTM válidas para Perú (zonas 17, 18, 19)
- Tipos de infraestructura según normativa
- Estados de conservación permitidos
- Unicidad del código de infraestructura

## Seguridad

- Prepared statements para prevenir SQL injection
- Sanitización de entradas
- Validación de datos en backend
- Headers de seguridad HTTP
- Protección CSRF (implementable)

## Personalización

### Cambiar el Logo o Colores

Editar `public/css/style.css`:

```css
.header {
    background: linear-gradient(135deg, #TU-COLOR-1, #TU-COLOR-2);
}
```

### Agregar Campos Personalizados

1. Agregar columna en la base de datos
2. Actualizar el modelo `Infraestructura.php`
3. Agregar campo en `formulario.php`
4. Actualizar `ApiController.php`

## Solución de Problemas

### Error: "404 Not Found" en las rutas

Verificar que `mod_rewrite` esté habilitado y `.htaccess` funcione.

### Error de conexión a base de datos

Verificar credenciales en `config/config.php`.

### Página en blanco

Habilitar errores en `config/config.php`:
```php
define('ENVIRONMENT', 'development');
```

### Problemas con caracteres especiales

Verificar que la base de datos use charset UTF-8:
```sql
ALTER DATABASE inventario_ana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Desarrollo

### Agregar un Nuevo Controlador

```php
<?php
class MiController extends Controller {
    public function index() {
        $this->view('mi_vista/index');
    }
}
```

### Agregar un Nuevo Modelo

```php
<?php
class MiModelo extends Model {
    protected $table = 'mi_tabla';

    public function miMetodo() {
        // Lógica del modelo
    }
}
```

## Mantenimiento

### Backup de Base de Datos

```bash
mysqldump -u root -p inventario_ana > backup_$(date +%Y%m%d).sql
```

### Restaurar Backup

```bash
mysql -u root -p inventario_ana < backup_20240101.sql
```

## Licencia

MIT

## Soporte

Para reportar problemas o sugerencias, crear un issue en el repositorio.

## Créditos

Sistema desarrollado según la normativa de la Autoridad Nacional del Agua (ANA) del Perú.
