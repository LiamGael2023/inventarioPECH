/**
 * Script de inicialización de Base de Datos
 * Sistema de Inventario ANA
 */

const sqlite3 = require('sqlite3').verbose();
const fs = require('fs');
const path = require('path');

const DB_DIR = path.join(__dirname, '..', 'database');
const DB_PATH = path.join(DB_DIR, 'inventario_ana.db');

// Crear directorio si no existe
if (!fs.existsSync(DB_DIR)) {
  fs.mkdirSync(DB_DIR, { recursive: true });
  console.log('✓ Directorio database/ creado');
}

// Conectar a la base de datos
const db = new sqlite3.Database(DB_PATH, (err) => {
  if (err) {
    console.error('Error al conectar con la base de datos:', err);
    process.exit(1);
  }
  console.log('✓ Conectado a la base de datos SQLite');
});

// Crear tabla de infraestructuras
const crearTablaInfraestructuras = () => {
  const sql = `
    CREATE TABLE IF NOT EXISTS infraestructuras (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      codigo TEXT UNIQUE NOT NULL,
      nombre TEXT NOT NULL,
      tipo TEXT NOT NULL,

      region TEXT NOT NULL,
      provincia TEXT NOT NULL,
      distrito TEXT NOT NULL,

      coordenada_este REAL,
      coordenada_norte REAL,
      zona_utm INTEGER,
      altitud REAL,

      cuenca_hidrografica TEXT,
      subcuenca TEXT,
      cuerpo_agua TEXT,

      capacidad REAL,
      unidad_capacidad TEXT,
      longitud REAL,
      ancho REAL,
      altura REAL,
      area_influencia REAL,
      material_construccion TEXT,

      anio_construccion INTEGER,
      titular_propietario TEXT,
      operador_actual TEXT,
      uso_principal TEXT,
      licencia_agua TEXT,

      estado_conservacion TEXT,
      estado_operativo TEXT,
      fecha_ultima_inspeccion TEXT,
      observaciones TEXT,

      fecha_registro TEXT NOT NULL,
      fecha_actualizacion TEXT,
      usuario_registro TEXT,

      CONSTRAINT tipo_valido CHECK (tipo IN (
        'Presa/Represa', 'Bocatoma', 'Canal Principal', 'Túnel',
        'Sifón', 'Acueducto', 'Desarenador', 'Estructura de Medición'
      )),
      CONSTRAINT estado_valido CHECK (estado_conservacion IN (
        'Muy Bueno', 'Bueno', 'Regular', 'Malo', 'Muy Malo'
      ))
    )
  `;

  return new Promise((resolve, reject) => {
    db.run(sql, (err) => {
      if (err) reject(err);
      else {
        console.log('✓ Tabla infraestructuras creada');
        resolve();
      }
    });
  });
};

// Crear índices para mejorar rendimiento
const crearIndices = () => {
  const indices = [
    'CREATE INDEX IF NOT EXISTS idx_tipo ON infraestructuras(tipo)',
    'CREATE INDEX IF NOT EXISTS idx_region ON infraestructuras(region)',
    'CREATE INDEX IF NOT EXISTS idx_cuenca ON infraestructuras(cuenca_hidrografica)',
    'CREATE INDEX IF NOT EXISTS idx_estado ON infraestructuras(estado_conservacion)',
    'CREATE INDEX IF NOT EXISTS idx_codigo ON infraestructuras(codigo)'
  ];

  const promesas = indices.map(sql => {
    return new Promise((resolve, reject) => {
      db.run(sql, (err) => {
        if (err) reject(err);
        else resolve();
      });
    });
  });

  return Promise.all(promesas).then(() => {
    console.log('✓ Índices creados');
  });
};

// Insertar datos de ejemplo
const insertarDatosEjemplo = () => {
  const ejemplos = [
    {
      codigo: 'ANA-LIM-PRES-0001',
      nombre: 'Presa San Lorenzo',
      tipo: 'Presa/Represa',
      region: 'Lima',
      provincia: 'Cañete',
      distrito: 'San Vicente',
      coordenada_este: 370000,
      coordenada_norte: 8580000,
      zona_utm: 18,
      altitud: 2450,
      cuenca_hidrografica: 'Cuenca del río Cañete',
      cuerpo_agua: 'Río Cañete',
      capacidad: 15000000,
      unidad_capacidad: 'm³',
      altura: 45,
      material_construccion: 'Concreto',
      anio_construccion: 1985,
      titular_propietario: 'Junta de Usuarios Cañete',
      operador_actual: 'Comisión de Regantes',
      uso_principal: 'Agrícola',
      estado_conservacion: 'Bueno',
      estado_operativo: 'Operativo'
    },
    {
      codigo: 'ANA-CUZ-BOCA-0001',
      nombre: 'Bocatoma Muyurina',
      tipo: 'Bocatoma',
      region: 'Cusco',
      provincia: 'Cusco',
      distrito: 'San Sebastián',
      coordenada_este: 185000,
      coordenada_norte: 8505000,
      zona_utm: 19,
      altitud: 3200,
      cuenca_hidrografica: 'Cuenca del Vilcanota',
      cuerpo_agua: 'Río Vilcanota',
      capacidad: 3.5,
      unidad_capacidad: 'm³/s',
      longitud: 35,
      ancho: 8,
      material_construccion: 'Concreto',
      anio_construccion: 2005,
      titular_propietario: 'SEDACUSCO S.A.',
      operador_actual: 'SEDACUSCO S.A.',
      uso_principal: 'Poblacional',
      estado_conservacion: 'Muy Bueno',
      estado_operativo: 'Operativo'
    },
    {
      codigo: 'ANA-ARE-CANA-0001',
      nombre: 'Canal Principal La Irrigación',
      tipo: 'Canal Principal',
      region: 'Arequipa',
      provincia: 'Arequipa',
      distrito: 'Sachaca',
      coordenada_este: 230000,
      coordenada_norte: 8195000,
      zona_utm: 19,
      altitud: 2320,
      cuenca_hidrografica: 'Cuenca del Chili',
      cuerpo_agua: 'Río Chili',
      capacidad: 5.2,
      unidad_capacidad: 'm³/s',
      longitud: 12500,
      ancho: 3.5,
      material_construccion: 'Concreto',
      anio_construccion: 1998,
      titular_propietario: 'AUTODEMA',
      operador_actual: 'AUTODEMA',
      uso_principal: 'Agrícola',
      area_influencia: 3500,
      estado_conservacion: 'Regular',
      estado_operativo: 'Operativo'
    }
  ];

  const sql = `
    INSERT OR IGNORE INTO infraestructuras (
      codigo, nombre, tipo, region, provincia, distrito,
      coordenada_este, coordenada_norte, zona_utm, altitud,
      cuenca_hidrografica, cuerpo_agua, capacidad, unidad_capacidad,
      longitud, ancho, altura, area_influencia, material_construccion,
      anio_construccion, titular_propietario, operador_actual, uso_principal,
      estado_conservacion, estado_operativo, fecha_registro, usuario_registro
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `;

  const promesas = ejemplos.map(ej => {
    return new Promise((resolve, reject) => {
      const fecha = new Date().toISOString();
      db.run(sql, [
        ej.codigo, ej.nombre, ej.tipo, ej.region, ej.provincia, ej.distrito,
        ej.coordenada_este, ej.coordenada_norte, ej.zona_utm, ej.altitud,
        ej.cuenca_hidrografica, ej.cuerpo_agua, ej.capacidad, ej.unidad_capacidad,
        ej.longitud || null, ej.ancho || null, ej.altura || null, ej.area_influencia || null,
        ej.material_construccion, ej.anio_construccion, ej.titular_propietario,
        ej.operador_actual, ej.uso_principal, ej.estado_conservacion,
        ej.estado_operativo, fecha, 'Sistema'
      ], (err) => {
        if (err) reject(err);
        else resolve();
      });
    });
  });

  return Promise.all(promesas).then(() => {
    console.log('✓ Datos de ejemplo insertados');
  });
};

// Ejecutar inicialización
(async () => {
  try {
    await crearTablaInfraestructuras();
    await crearIndices();
    await insertarDatosEjemplo();
    console.log('\n✓ Base de datos inicializada correctamente\n');
  } catch (error) {
    console.error('Error al inicializar la base de datos:', error);
  } finally {
    db.close((err) => {
      if (err) console.error('Error al cerrar la base de datos:', err);
      else console.log('✓ Conexión cerrada');
    });
  }
})();
