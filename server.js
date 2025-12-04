/**
 * Servidor Express - Sistema de Inventario ANA
 * Autoridad Nacional del Agua - Perú
 */

const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const sqlite3 = require('sqlite3').verbose();
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3001;
const DB_PATH = process.env.DB_PATH || path.join(__dirname, 'database', 'inventario_ana.db');

// Middlewares
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Conectar a la base de datos
const db = new sqlite3.Database(DB_PATH, (err) => {
  if (err) {
    console.error('Error al conectar con la base de datos:', err);
    process.exit(1);
  }
  console.log('✓ Conectado a la base de datos SQLite');
});

// Hacer la base de datos disponible para las rutas
app.locals.db = db;

// Rutas de la API
const apiRoutes = require('./routes/api');
app.use('/api', apiRoutes);

// Ruta raíz
app.get('/', (req, res) => {
  res.json({
    nombre: 'Sistema de Inventario de Infraestructura Mayor - ANA',
    version: '1.0.0',
    descripcion: 'API REST para gestión de inventario según normativa de la Autoridad Nacional del Agua',
    endpoints: {
      infraestructuras: '/api/infraestructuras',
      estadisticas: '/api/estadisticas',
      catalogo: '/api/catalogo'
    },
    documentacion: 'Ver README.md'
  });
});

// Ruta de salud del servicio
app.get('/health', (req, res) => {
  db.get('SELECT COUNT(*) as count FROM infraestructuras', (err, row) => {
    if (err) {
      return res.status(500).json({
        status: 'error',
        database: 'disconnected',
        error: err.message
      });
    }
    res.json({
      status: 'ok',
      database: 'connected',
      registros: row.count,
      timestamp: new Date().toISOString()
    });
  });
});

// Manejo de errores
app.use((err, req, res, next) => {
  console.error('Error:', err);
  res.status(500).json({
    error: 'Error interno del servidor',
    message: err.message
  });
});

// Manejo de rutas no encontradas
app.use((req, res) => {
  res.status(404).json({
    error: 'Ruta no encontrada',
    path: req.path
  });
});

// Iniciar servidor
app.listen(PORT, () => {
  console.log(`
╔═══════════════════════════════════════════════════════════╗
║                                                           ║
║   Sistema de Inventario de Infraestructura Mayor - ANA   ║
║   Autoridad Nacional del Agua - Perú                      ║
║                                                           ║
╚═══════════════════════════════════════════════════════════╝

✓ Servidor corriendo en puerto ${PORT}
✓ API disponible en http://localhost:${PORT}/api
✓ Base de datos: ${DB_PATH}

Endpoints disponibles:
  - GET    /api/infraestructuras
  - GET    /api/infraestructuras/:id
  - POST   /api/infraestructuras
  - PUT    /api/infraestructuras/:id
  - DELETE /api/infraestructuras/:id
  - GET    /api/estadisticas
  - GET    /api/catalogo
  - GET    /health

Presiona Ctrl+C para detener el servidor
  `);
});

// Manejo de cierre limpio
process.on('SIGINT', () => {
  console.log('\n\nCerrando servidor...');
  db.close((err) => {
    if (err) {
      console.error('Error al cerrar la base de datos:', err);
    } else {
      console.log('✓ Base de datos cerrada correctamente');
    }
    process.exit(0);
  });
});

module.exports = app;
