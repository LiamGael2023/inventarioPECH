# Sistema de Inventario de Infraestructura Mayor - ANA

Sistema de gestión de inventario de infraestructura hidráulica mayor según la normativa de la Autoridad Nacional del Agua (ANA) del Perú.

## Características

- ✅ Registro de infraestructura mayor (Presas, Bocatomas, Canales, Túneles, etc.)
- ✅ Gestión de datos técnicos y administrativos
- ✅ Georreferenciación de infraestructuras
- ✅ Validaciones según normativa ANA
- ✅ Consultas y reportes
- ✅ Exportación de datos
- ✅ Registro de estado de conservación

## Tipos de Infraestructura Soportados

1. **Presa/Represa** - Estructuras de almacenamiento de agua
2. **Bocatoma** - Estructuras de captación
3. **Canal Principal** - Canales de conducción
4. **Túnel** - Túneles de conducción
5. **Sifón** - Estructuras de cruce
6. **Acueducto** - Estructuras elevadas de conducción
7. **Desarenador** - Estructuras de sedimentación
8. **Estructura de Medición** - Aforadores y medidores

## Instalación

### Requisitos previos
- Node.js >= 14.x
- npm >= 6.x

### Backend

```bash
# Instalar dependencias
npm install

# Configurar variables de entorno
cp .env.example .env

# Inicializar base de datos
npm run init-db

# Iniciar servidor de desarrollo
npm run dev
```

El servidor estará disponible en `http://localhost:3001`

### Frontend

```bash
# Ir al directorio del cliente
cd client

# Instalar dependencias
npm install

# Iniciar aplicación
npm start
```

La aplicación estará disponible en `http://localhost:3000`

## Estructura del Proyecto

```
inventarioPECH/
├── server.js                 # Servidor Express principal
├── database/
│   └── inventario_ana.db    # Base de datos SQLite
├── models/
│   └── infraestructura.js   # Modelos de datos
├── routes/
│   └── api.js               # Rutas de la API
├── scripts/
│   └── initDatabase.js      # Script de inicialización
├── client/                  # Frontend React
│   ├── src/
│   │   ├── components/      # Componentes React
│   │   ├── services/        # Servicios API
│   │   └── App.js          # Componente principal
│   └── package.json
└── README.md
```

## API Endpoints

### Infraestructuras

- `GET /api/infraestructuras` - Listar todas las infraestructuras
- `GET /api/infraestructuras/:id` - Obtener una infraestructura específica
- `POST /api/infraestructuras` - Crear nueva infraestructura
- `PUT /api/infraestructuras/:id` - Actualizar infraestructura
- `DELETE /api/infraestructuras/:id` - Eliminar infraestructura
- `GET /api/infraestructuras/tipo/:tipo` - Filtrar por tipo
- `GET /api/infraestructuras/cuenca/:cuenca` - Filtrar por cuenca

### Estadísticas

- `GET /api/estadisticas` - Obtener estadísticas generales
- `GET /api/estadisticas/por-tipo` - Estadísticas por tipo
- `GET /api/estadisticas/por-region` - Estadísticas por región

## Campos del Inventario

Según la normativa ANA, cada infraestructura registra:

### Datos Generales
- Código único de identificación
- Nombre de la infraestructura
- Tipo de infraestructura

### Ubicación
- Región, Provincia, Distrito
- Coordenadas UTM (Este, Norte, Zona)
- Altitud (msnm)
- Cuenca hidrográfica
- Cuerpo de agua

### Características Técnicas
- Capacidad de almacenamiento/conducción
- Dimensiones principales
- Material de construcción
- Año de construcción

### Información Administrativa
- Titular/Propietario
- Operador actual
- Uso principal
- Estado de conservación

### Estado de Conservación
- Muy Bueno
- Bueno
- Regular
- Malo
- Muy Malo

## Uso del Sistema

### Registrar Nueva Infraestructura

1. Acceder al formulario de registro
2. Completar todos los campos obligatorios
3. Ingresar coordenadas geográficas
4. Especificar características técnicas
5. Guardar el registro

### Consultar Inventario

1. Ver lista completa de infraestructuras
2. Filtrar por tipo, región o cuenca
3. Ver detalles de cada infraestructura
4. Exportar datos en formato CSV/JSON

### Actualizar Registros

1. Buscar la infraestructura
2. Editar campos necesarios
3. Actualizar estado de conservación
4. Guardar cambios

## Normativa ANA

Este sistema cumple con los lineamientos de la Autoridad Nacional del Agua para el registro y control de infraestructura hidráulica mayor en el Perú.

## Licencia

MIT

## Soporte

Para reportar problemas o sugerencias, crear un issue en el repositorio.
