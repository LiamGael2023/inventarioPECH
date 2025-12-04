/**
 * Rutas de la API REST
 * Sistema de Inventario ANA
 */

const express = require('express');
const { body, validationResult } = require('express-validator');
const router = express.Router();
const { InfraestructuraValidator } = require('../models/infraestructura');

// Middleware para obtener la base de datos desde app.locals
const getDb = (req) => req.app.locals.db;

// ==================== INFRAESTRUCTURAS ====================

/**
 * GET /api/infraestructuras
 * Listar todas las infraestructuras
 */
router.get('/infraestructuras', (req, res) => {
  const db = getDb(req);
  const { tipo, region, cuenca, estado } = req.query;

  let sql = 'SELECT * FROM infraestructuras WHERE 1=1';
  const params = [];

  if (tipo) {
    sql += ' AND tipo = ?';
    params.push(tipo);
  }
  if (region) {
    sql += ' AND region = ?';
    params.push(region);
  }
  if (cuenca) {
    sql += ' AND cuenca_hidrografica = ?';
    params.push(cuenca);
  }
  if (estado) {
    sql += ' AND estado_conservacion = ?';
    params.push(estado);
  }

  sql += ' ORDER BY fecha_registro DESC';

  db.all(sql, params, (err, rows) => {
    if (err) {
      console.error('Error al consultar infraestructuras:', err);
      return res.status(500).json({ error: 'Error al consultar la base de datos' });
    }
    res.json({
      total: rows.length,
      data: rows
    });
  });
});

/**
 * GET /api/infraestructuras/:id
 * Obtener una infraestructura específica
 */
router.get('/infraestructuras/:id', (req, res) => {
  const db = getDb(req);
  const { id } = req.params;

  db.get('SELECT * FROM infraestructuras WHERE id = ?', [id], (err, row) => {
    if (err) {
      console.error('Error al consultar infraestructura:', err);
      return res.status(500).json({ error: 'Error al consultar la base de datos' });
    }
    if (!row) {
      return res.status(404).json({ error: 'Infraestructura no encontrada' });
    }
    res.json(row);
  });
});

/**
 * POST /api/infraestructuras
 * Crear nueva infraestructura
 */
router.post('/infraestructuras', [
  body('codigo').notEmpty().withMessage('El código es obligatorio'),
  body('nombre').notEmpty().withMessage('El nombre es obligatorio'),
  body('tipo').notEmpty().withMessage('El tipo es obligatorio'),
  body('region').notEmpty().withMessage('La región es obligatoria'),
  body('provincia').notEmpty().withMessage('La provincia es obligatoria'),
  body('distrito').notEmpty().withMessage('El distrito es obligatorio')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }

  const db = getDb(req);
  const data = req.body;

  // Validaciones personalizadas
  if (data.zona_utm && data.coordenada_este && data.coordenada_norte) {
    if (!InfraestructuraValidator.validarCoordenadas(
      data.coordenada_este,
      data.coordenada_norte,
      data.zona_utm
    )) {
      return res.status(400).json({ error: 'Coordenadas UTM inválidas para Perú' });
    }
  }

  if (!InfraestructuraValidator.validarTipo(data.tipo)) {
    return res.status(400).json({ error: 'Tipo de infraestructura inválido' });
  }

  if (data.estado_conservacion && !InfraestructuraValidator.validarEstado(data.estado_conservacion)) {
    return res.status(400).json({ error: 'Estado de conservación inválido' });
  }

  const sql = `
    INSERT INTO infraestructuras (
      codigo, nombre, tipo, region, provincia, distrito,
      coordenada_este, coordenada_norte, zona_utm, altitud,
      cuenca_hidrografica, subcuenca, cuerpo_agua,
      capacidad, unidad_capacidad, longitud, ancho, altura, area_influencia,
      material_construccion, anio_construccion, titular_propietario,
      operador_actual, uso_principal, licencia_agua,
      estado_conservacion, estado_operativo, fecha_ultima_inspeccion,
      observaciones, fecha_registro, usuario_registro
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `;

  const fecha = new Date().toISOString();
  const params = [
    data.codigo, data.nombre, data.tipo, data.region, data.provincia, data.distrito,
    data.coordenada_este || null, data.coordenada_norte || null, data.zona_utm || null,
    data.altitud || null, data.cuenca_hidrografica || null, data.subcuenca || null,
    data.cuerpo_agua || null, data.capacidad || null, data.unidad_capacidad || null,
    data.longitud || null, data.ancho || null, data.altura || null, data.area_influencia || null,
    data.material_construccion || null, data.anio_construccion || null,
    data.titular_propietario || null, data.operador_actual || null, data.uso_principal || null,
    data.licencia_agua || null, data.estado_conservacion || null, data.estado_operativo || null,
    data.fecha_ultima_inspeccion || null, data.observaciones || null, fecha,
    data.usuario_registro || 'Sistema'
  ];

  db.run(sql, params, function(err) {
    if (err) {
      console.error('Error al insertar infraestructura:', err);
      if (err.message.includes('UNIQUE constraint failed')) {
        return res.status(400).json({ error: 'El código de infraestructura ya existe' });
      }
      return res.status(500).json({ error: 'Error al crear la infraestructura' });
    }
    res.status(201).json({
      message: 'Infraestructura creada exitosamente',
      id: this.lastID
    });
  });
});

/**
 * PUT /api/infraestructuras/:id
 * Actualizar infraestructura
 */
router.put('/infraestructuras/:id', (req, res) => {
  const db = getDb(req);
  const { id } = req.params;
  const data = req.body;

  // Verificar que existe
  db.get('SELECT id FROM infraestructuras WHERE id = ?', [id], (err, row) => {
    if (err) {
      return res.status(500).json({ error: 'Error al consultar la base de datos' });
    }
    if (!row) {
      return res.status(404).json({ error: 'Infraestructura no encontrada' });
    }

    const sql = `
      UPDATE infraestructuras SET
        nombre = ?, tipo = ?, region = ?, provincia = ?, distrito = ?,
        coordenada_este = ?, coordenada_norte = ?, zona_utm = ?, altitud = ?,
        cuenca_hidrografica = ?, subcuenca = ?, cuerpo_agua = ?,
        capacidad = ?, unidad_capacidad = ?, longitud = ?, ancho = ?, altura = ?,
        area_influencia = ?, material_construccion = ?, anio_construccion = ?,
        titular_propietario = ?, operador_actual = ?, uso_principal = ?,
        licencia_agua = ?, estado_conservacion = ?, estado_operativo = ?,
        fecha_ultima_inspeccion = ?, observaciones = ?, fecha_actualizacion = ?
      WHERE id = ?
    `;

    const fecha = new Date().toISOString();
    const params = [
      data.nombre, data.tipo, data.region, data.provincia, data.distrito,
      data.coordenada_este || null, data.coordenada_norte || null, data.zona_utm || null,
      data.altitud || null, data.cuenca_hidrografica || null, data.subcuenca || null,
      data.cuerpo_agua || null, data.capacidad || null, data.unidad_capacidad || null,
      data.longitud || null, data.ancho || null, data.altura || null,
      data.area_influencia || null, data.material_construccion || null,
      data.anio_construccion || null, data.titular_propietario || null,
      data.operador_actual || null, data.uso_principal || null, data.licencia_agua || null,
      data.estado_conservacion || null, data.estado_operativo || null,
      data.fecha_ultima_inspeccion || null, data.observaciones || null, fecha, id
    ];

    db.run(sql, params, (err) => {
      if (err) {
        console.error('Error al actualizar infraestructura:', err);
        return res.status(500).json({ error: 'Error al actualizar la infraestructura' });
      }
      res.json({ message: 'Infraestructura actualizada exitosamente' });
    });
  });
});

/**
 * DELETE /api/infraestructuras/:id
 * Eliminar infraestructura
 */
router.delete('/infraestructuras/:id', (req, res) => {
  const db = getDb(req);
  const { id } = req.params;

  db.run('DELETE FROM infraestructuras WHERE id = ?', [id], function(err) {
    if (err) {
      console.error('Error al eliminar infraestructura:', err);
      return res.status(500).json({ error: 'Error al eliminar la infraestructura' });
    }
    if (this.changes === 0) {
      return res.status(404).json({ error: 'Infraestructura no encontrada' });
    }
    res.json({ message: 'Infraestructura eliminada exitosamente' });
  });
});

// ==================== ESTADÍSTICAS ====================

/**
 * GET /api/estadisticas
 * Obtener estadísticas generales
 */
router.get('/estadisticas', (req, res) => {
  const db = getDb(req);

  const queries = {
    total: 'SELECT COUNT(*) as count FROM infraestructuras',
    porTipo: 'SELECT tipo, COUNT(*) as count FROM infraestructuras GROUP BY tipo',
    porRegion: 'SELECT region, COUNT(*) as count FROM infraestructuras GROUP BY region ORDER BY count DESC LIMIT 10',
    porEstado: 'SELECT estado_conservacion, COUNT(*) as count FROM infraestructuras WHERE estado_conservacion IS NOT NULL GROUP BY estado_conservacion',
    porUso: 'SELECT uso_principal, COUNT(*) as count FROM infraestructuras WHERE uso_principal IS NOT NULL GROUP BY uso_principal'
  };

  const resultados = {};

  // Ejecutar todas las consultas
  Promise.all([
    new Promise((resolve, reject) => {
      db.get(queries.total, (err, row) => {
        if (err) reject(err);
        else resolve({ total: row.count });
      });
    }),
    new Promise((resolve, reject) => {
      db.all(queries.porTipo, (err, rows) => {
        if (err) reject(err);
        else resolve({ porTipo: rows });
      });
    }),
    new Promise((resolve, reject) => {
      db.all(queries.porRegion, (err, rows) => {
        if (err) reject(err);
        else resolve({ porRegion: rows });
      });
    }),
    new Promise((resolve, reject) => {
      db.all(queries.porEstado, (err, rows) => {
        if (err) reject(err);
        else resolve({ porEstado: rows });
      });
    }),
    new Promise((resolve, reject) => {
      db.all(queries.porUso, (err, rows) => {
        if (err) reject(err);
        else resolve({ porUso: rows });
      });
    })
  ])
  .then(results => {
    results.forEach(result => Object.assign(resultados, result));
    res.json(resultados);
  })
  .catch(err => {
    console.error('Error al obtener estadísticas:', err);
    res.status(500).json({ error: 'Error al obtener estadísticas' });
  });
});

/**
 * GET /api/catalogo
 * Obtener catálogos para formularios
 */
router.get('/catalogo', (req, res) => {
  const { TIPOS_INFRAESTRUCTURA, ESTADOS_CONSERVACION, USOS_PRINCIPALES, MATERIALES } = require('../models/infraestructura');

  res.json({
    tipos: Object.values(TIPOS_INFRAESTRUCTURA),
    estados: Object.values(ESTADOS_CONSERVACION),
    usos: Object.values(USOS_PRINCIPALES),
    materiales: Object.values(MATERIALES)
  });
});

module.exports = router;
