# Guía de Instalación - Sistema de Inventario ANA

## Requisitos del Sistema

### Software Necesario
- **Node.js**: Versión 14.x o superior
- **npm**: Versión 6.x o superior
- **Git**: Para clonar el repositorio

### Sistemas Operativos Soportados
- Windows 10/11
- macOS 10.14+
- Linux (Ubuntu 18.04+, Debian, CentOS, etc.)

## Instalación Paso a Paso

### 1. Clonar el Repositorio

```bash
git clone <url-del-repositorio>
cd inventarioPECH
```

### 2. Instalar Dependencias del Backend

```bash
# En el directorio raíz del proyecto
npm install
```

Esto instalará todas las dependencias necesarias:
- express
- sqlite3
- cors
- body-parser
- express-validator
- dotenv

### 3. Configurar Variables de Entorno

```bash
# Copiar el archivo de ejemplo
cp .env.example .env
```

Editar el archivo `.env` si es necesario:
```
PORT=3001
DB_PATH=./database/inventario_ana.db
NODE_ENV=development
```

### 4. Inicializar la Base de Datos

```bash
npm run init-db
```

Este comando:
- Crea el directorio `database/` si no existe
- Crea la base de datos SQLite
- Crea las tablas necesarias
- Inserta datos de ejemplo

Salida esperada:
```
✓ Directorio database/ creado
✓ Conectado a la base de datos SQLite
✓ Tabla infraestructuras creada
✓ Índices creados
✓ Datos de ejemplo insertados
✓ Base de datos inicializada correctamente
```

### 5. Instalar Dependencias del Frontend

```bash
# Ir al directorio del cliente
cd client

# Instalar dependencias
npm install
```

Esto instalará:
- react
- react-dom
- react-scripts
- axios

### 6. Iniciar el Sistema

Tiene dos opciones:

#### Opción A: Iniciar Backend y Frontend por Separado

**Terminal 1 - Backend:**
```bash
# En el directorio raíz
npm run dev
```

El servidor backend estará disponible en `http://localhost:3001`

**Terminal 2 - Frontend:**
```bash
# En el directorio client/
cd client
npm start
```

El frontend estará disponible en `http://localhost:3000`

#### Opción B: Usando scripts personalizados

Puede crear scripts para iniciar ambos servicios simultáneamente usando herramientas como `concurrently` (no incluido por defecto).

## Verificación de la Instalación

### 1. Verificar el Backend

Abrir en el navegador o usar curl:
```bash
curl http://localhost:3001/health
```

Respuesta esperada:
```json
{
  "status": "ok",
  "database": "connected",
  "registros": 3,
  "timestamp": "2024-01-01T00:00:00.000Z"
}
```

### 2. Verificar el Frontend

Abrir en el navegador: `http://localhost:3000`

Debería ver:
- Header con título "Sistema de Inventario de Infraestructura Mayor"
- Tres pestañas: Inventario, Nuevo Registro, Estadísticas
- Lista con 3 infraestructuras de ejemplo

### 3. Probar Funcionalidades

1. **Ver inventario**: Clic en pestaña "Inventario"
2. **Ver estadísticas**: Clic en pestaña "Estadísticas"
3. **Registrar infraestructura**: Clic en "Nuevo Registro"

## Estructura de Archivos Después de la Instalación

```
inventarioPECH/
├── node_modules/          # Dependencias backend
├── database/
│   └── inventario_ana.db  # Base de datos SQLite
├── models/
│   └── infraestructura.js
├── routes/
│   └── api.js
├── scripts/
│   └── initDatabase.js
├── client/
│   ├── node_modules/      # Dependencias frontend
│   ├── public/
│   ├── src/
│   │   ├── components/
│   │   ├── services/
│   │   ├── App.js
│   │   ├── index.js
│   │   └── index.css
│   └── package.json
├── .env                   # Configuración
├── .gitignore
├── server.js
├── package.json
└── README.md
```

## Solución de Problemas

### Error: "Cannot find module 'sqlite3'"

**Solución:**
```bash
npm install sqlite3 --save
```

Si persiste el error en Windows, puede necesitar:
```bash
npm install --global windows-build-tools
npm install sqlite3 --build-from-source
```

### Error: Puerto 3000 o 3001 ya en uso

**Solución:**

**Windows:**
```bash
netstat -ano | findstr :3000
taskkill /PID <PID> /F
```

**Linux/Mac:**
```bash
lsof -ti:3000 | xargs kill -9
```

O cambiar el puerto en `.env`:
```
PORT=3002
```

### Error: "EACCES: permission denied"

**Solución:**
```bash
# Linux/Mac
sudo chown -R $USER:$USER .
chmod -R 755 .

# Windows: Ejecutar terminal como Administrador
```

### La base de datos no se crea

**Solución:**
```bash
# Verificar permisos del directorio
mkdir -p database
chmod 755 database

# Reintentar inicialización
npm run init-db
```

### Frontend no se conecta al Backend

**Solución:**

1. Verificar que el backend esté corriendo en puerto 3001
2. Verificar el proxy en `client/package.json`:
   ```json
   "proxy": "http://localhost:3001"
   ```
3. Reiniciar el frontend:
   ```bash
   cd client
   npm start
   ```

## Modo Producción

### Build del Frontend

```bash
cd client
npm run build
```

Esto crea una carpeta `client/build` con los archivos optimizados.

### Servir desde Express

El servidor Express puede configurarse para servir el build del frontend:

```javascript
// En server.js
app.use(express.static(path.join(__dirname, 'client/build')));
```

### Iniciar en Producción

```bash
NODE_ENV=production npm start
```

## Backup de la Base de Datos

```bash
# Crear backup
cp database/inventario_ana.db database/inventario_ana_backup_$(date +%Y%m%d).db

# Restaurar backup
cp database/inventario_ana_backup_YYYYMMDD.db database/inventario_ana.db
```

## Actualización del Sistema

```bash
# Actualizar código
git pull

# Actualizar dependencias backend
npm install

# Actualizar dependencias frontend
cd client
npm install

# Reiniciar servicios
```

## Soporte

Si encuentra problemas durante la instalación:

1. Revisar esta guía de solución de problemas
2. Verificar los logs en la terminal
3. Consultar el archivo README.md
4. Crear un issue en el repositorio con:
   - Descripción del problema
   - Sistema operativo
   - Versión de Node.js (`node --version`)
   - Logs de error completos

## Siguientes Pasos

Una vez instalado correctamente:

1. Leer el archivo `NORMATIVA_ANA.md` para entender los requisitos
2. Revisar el `README.md` para conocer todas las funcionalidades
3. Comenzar a registrar infraestructuras reales
4. Configurar backups automáticos
5. Considerar configurar un servidor de producción
