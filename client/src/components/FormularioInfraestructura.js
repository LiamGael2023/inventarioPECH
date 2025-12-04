import React, { useState, useEffect } from 'react';
import { catalogoService } from '../services/api';

const FormularioInfraestructura = ({ infraestructura, onSubmit, onCancel }) => {
  const [catalogos, setCatalogos] = useState({
    tipos: [],
    estados: [],
    usos: [],
    materiales: []
  });

  const [formData, setFormData] = useState({
    codigo: '',
    nombre: '',
    tipo: '',
    region: '',
    provincia: '',
    distrito: '',
    coordenada_este: '',
    coordenada_norte: '',
    zona_utm: '',
    altitud: '',
    cuenca_hidrografica: '',
    subcuenca: '',
    cuerpo_agua: '',
    capacidad: '',
    unidad_capacidad: 'm³',
    longitud: '',
    ancho: '',
    altura: '',
    area_influencia: '',
    material_construccion: '',
    anio_construccion: '',
    titular_propietario: '',
    operador_actual: '',
    uso_principal: '',
    licencia_agua: '',
    estado_conservacion: '',
    estado_operativo: 'Operativo',
    fecha_ultima_inspeccion: '',
    observaciones: '',
    ...infraestructura
  });

  useEffect(() => {
    cargarCatalogos();
  }, []);

  const cargarCatalogos = async () => {
    try {
      const response = await catalogoService.getAll();
      setCatalogos(response.data);
    } catch (error) {
      console.error('Error al cargar catálogos:', error);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <form onSubmit={handleSubmit}>
      <div className="card">
        <h3>Datos Generales</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Código ANA *</label>
            <input
              type="text"
              name="codigo"
              value={formData.codigo}
              onChange={handleChange}
              placeholder="Ej: ANA-LIM-PRES-0001"
              required
              disabled={infraestructura?.id}
            />
            <small>Formato: ANA-REGIÓN-TIPO-NÚMERO</small>
          </div>
          <div className="form-group">
            <label>Nombre *</label>
            <input
              type="text"
              name="nombre"
              value={formData.nombre}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label>Tipo de Infraestructura *</label>
            <select
              name="tipo"
              value={formData.tipo}
              onChange={handleChange}
              required
            >
              <option value="">Seleccionar...</option>
              {catalogos.tipos.map(tipo => (
                <option key={tipo} value={tipo}>{tipo}</option>
              ))}
            </select>
          </div>
        </div>
      </div>

      <div className="card">
        <h3>Ubicación Geográfica</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Región *</label>
            <input
              type="text"
              name="region"
              value={formData.region}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label>Provincia *</label>
            <input
              type="text"
              name="provincia"
              value={formData.provincia}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label>Distrito *</label>
            <input
              type="text"
              name="distrito"
              value={formData.distrito}
              onChange={handleChange}
              required
            />
          </div>
        </div>

        <h4 style={{marginTop: '20px', marginBottom: '15px'}}>Coordenadas UTM</h4>
        <div className="form-row">
          <div className="form-group">
            <label>Este (m)</label>
            <input
              type="number"
              step="0.01"
              name="coordenada_este"
              value={formData.coordenada_este}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Norte (m)</label>
            <input
              type="number"
              step="0.01"
              name="coordenada_norte"
              value={formData.coordenada_norte}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Zona UTM</label>
            <select
              name="zona_utm"
              value={formData.zona_utm}
              onChange={handleChange}
            >
              <option value="">Seleccionar...</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
            </select>
          </div>
          <div className="form-group">
            <label>Altitud (msnm)</label>
            <input
              type="number"
              step="0.1"
              name="altitud"
              value={formData.altitud}
              onChange={handleChange}
            />
          </div>
        </div>
      </div>

      <div className="card">
        <h3>Hidrografía</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Cuenca Hidrográfica</label>
            <input
              type="text"
              name="cuenca_hidrografica"
              value={formData.cuenca_hidrografica}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Subcuenca</label>
            <input
              type="text"
              name="subcuenca"
              value={formData.subcuenca}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Cuerpo de Agua</label>
            <input
              type="text"
              name="cuerpo_agua"
              value={formData.cuerpo_agua}
              onChange={handleChange}
              placeholder="Río, quebrada, lago..."
            />
          </div>
        </div>
      </div>

      <div className="card">
        <h3>Características Técnicas</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Capacidad</label>
            <input
              type="number"
              step="0.01"
              name="capacidad"
              value={formData.capacidad}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Unidad</label>
            <select
              name="unidad_capacidad"
              value={formData.unidad_capacidad}
              onChange={handleChange}
            >
              <option value="m³">m³</option>
              <option value="m³/s">m³/s</option>
              <option value="l/s">l/s</option>
            </select>
          </div>
          <div className="form-group">
            <label>Longitud (m)</label>
            <input
              type="number"
              step="0.01"
              name="longitud"
              value={formData.longitud}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Ancho (m)</label>
            <input
              type="number"
              step="0.01"
              name="ancho"
              value={formData.ancho}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Altura (m)</label>
            <input
              type="number"
              step="0.01"
              name="altura"
              value={formData.altura}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Área de Influencia (ha)</label>
            <input
              type="number"
              step="0.01"
              name="area_influencia"
              value={formData.area_influencia}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Material de Construcción</label>
            <select
              name="material_construccion"
              value={formData.material_construccion}
              onChange={handleChange}
            >
              <option value="">Seleccionar...</option>
              {catalogos.materiales.map(material => (
                <option key={material} value={material}>{material}</option>
              ))}
            </select>
          </div>
          <div className="form-group">
            <label>Año de Construcción</label>
            <input
              type="number"
              name="anio_construccion"
              value={formData.anio_construccion}
              onChange={handleChange}
              min="1900"
              max={new Date().getFullYear()}
            />
          </div>
        </div>
      </div>

      <div className="card">
        <h3>Información Administrativa</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Titular/Propietario</label>
            <input
              type="text"
              name="titular_propietario"
              value={formData.titular_propietario}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Operador Actual</label>
            <input
              type="text"
              name="operador_actual"
              value={formData.operador_actual}
              onChange={handleChange}
            />
          </div>
          <div className="form-group">
            <label>Uso Principal</label>
            <select
              name="uso_principal"
              value={formData.uso_principal}
              onChange={handleChange}
            >
              <option value="">Seleccionar...</option>
              {catalogos.usos.map(uso => (
                <option key={uso} value={uso}>{uso}</option>
              ))}
            </select>
          </div>
          <div className="form-group">
            <label>N° Licencia de Agua</label>
            <input
              type="text"
              name="licencia_agua"
              value={formData.licencia_agua}
              onChange={handleChange}
            />
          </div>
        </div>
      </div>

      <div className="card">
        <h3>Estado y Operación</h3>
        <div className="form-row">
          <div className="form-group">
            <label>Estado de Conservación</label>
            <select
              name="estado_conservacion"
              value={formData.estado_conservacion}
              onChange={handleChange}
            >
              <option value="">Seleccionar...</option>
              {catalogos.estados.map(estado => (
                <option key={estado} value={estado}>{estado}</option>
              ))}
            </select>
          </div>
          <div className="form-group">
            <label>Estado Operativo</label>
            <select
              name="estado_operativo"
              value={formData.estado_operativo}
              onChange={handleChange}
            >
              <option value="Operativo">Operativo</option>
              <option value="Inoperativo">Inoperativo</option>
              <option value="En mantenimiento">En mantenimiento</option>
            </select>
          </div>
          <div className="form-group">
            <label>Fecha Última Inspección</label>
            <input
              type="date"
              name="fecha_ultima_inspeccion"
              value={formData.fecha_ultima_inspeccion}
              onChange={handleChange}
            />
          </div>
        </div>
        <div className="form-group">
          <label>Observaciones</label>
          <textarea
            name="observaciones"
            value={formData.observaciones}
            onChange={handleChange}
            rows="4"
          />
        </div>
      </div>

      <div className="btn-group">
        <button type="submit" className="btn btn-primary">
          {infraestructura?.id ? 'Actualizar' : 'Registrar'} Infraestructura
        </button>
        {onCancel && (
          <button type="button" className="btn btn-secondary" onClick={onCancel}>
            Cancelar
          </button>
        )}
      </div>
    </form>
  );
};

export default FormularioInfraestructura;
