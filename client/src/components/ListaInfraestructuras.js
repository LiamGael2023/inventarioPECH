import React, { useState, useEffect } from 'react';
import { infraestructuraService, catalogoService } from '../services/api';

const ListaInfraestructuras = ({ onEdit }) => {
  const [infraestructuras, setInfraestructuras] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filtros, setFiltros] = useState({
    tipo: '',
    region: '',
    estado: ''
  });
  const [catalogos, setCatalogos] = useState({
    tipos: [],
    estados: []
  });

  useEffect(() => {
    cargarCatalogos();
    cargarInfraestructuras();
  }, []);

  const cargarCatalogos = async () => {
    try {
      const response = await catalogoService.getAll();
      setCatalogos(response.data);
    } catch (error) {
      console.error('Error al cargar catálogos:', error);
    }
  };

  const cargarInfraestructuras = async (filtrosActuales = filtros) => {
    setLoading(true);
    try {
      const response = await infraestructuraService.getAll(filtrosActuales);
      setInfraestructuras(response.data.data);
    } catch (error) {
      console.error('Error al cargar infraestructuras:', error);
      alert('Error al cargar infraestructuras');
    } finally {
      setLoading(false);
    }
  };

  const handleFiltroChange = (e) => {
    const { name, value } = e.target;
    const nuevosFiltros = { ...filtros, [name]: value };
    setFiltros(nuevosFiltros);
    cargarInfraestructuras(nuevosFiltros);
  };

  const limpiarFiltros = () => {
    const filtrosVacios = { tipo: '', region: '', estado: '' };
    setFiltros(filtrosVacios);
    cargarInfraestructuras(filtrosVacios);
  };

  const handleEliminar = async (id, nombre) => {
    if (!window.confirm(`¿Está seguro de eliminar "${nombre}"?`)) {
      return;
    }

    try {
      await infraestructuraService.delete(id);
      alert('Infraestructura eliminada exitosamente');
      cargarInfraestructuras();
    } catch (error) {
      console.error('Error al eliminar:', error);
      alert('Error al eliminar la infraestructura');
    }
  };

  const obtenerBadgeEstado = (estado) => {
    const clases = {
      'Muy Bueno': 'badge-success',
      'Bueno': 'badge-success',
      'Regular': 'badge-warning',
      'Malo': 'badge-danger',
      'Muy Malo': 'badge-danger'
    };
    return clases[estado] || 'badge-info';
  };

  if (loading) {
    return <div className="loading">Cargando inventario...</div>;
  }

  return (
    <div>
      <div className="card">
        <h2>Filtros de Búsqueda</h2>
        <div className="filters">
          <div className="filter-group">
            <label>Tipo de Infraestructura</label>
            <select name="tipo" value={filtros.tipo} onChange={handleFiltroChange}>
              <option value="">Todos</option>
              {catalogos.tipos.map(tipo => (
                <option key={tipo} value={tipo}>{tipo}</option>
              ))}
            </select>
          </div>
          <div className="filter-group">
            <label>Región</label>
            <input
              type="text"
              name="region"
              value={filtros.region}
              onChange={handleFiltroChange}
              placeholder="Ej: Lima, Cusco..."
            />
          </div>
          <div className="filter-group">
            <label>Estado de Conservación</label>
            <select name="estado" value={filtros.estado} onChange={handleFiltroChange}>
              <option value="">Todos</option>
              {catalogos.estados.map(estado => (
                <option key={estado} value={estado}>{estado}</option>
              ))}
            </select>
          </div>
          <div style={{ display: 'flex', alignItems: 'flex-end' }}>
            <button className="btn btn-secondary" onClick={limpiarFiltros}>
              Limpiar Filtros
            </button>
          </div>
        </div>
      </div>

      <div className="card">
        <h2>Inventario de Infraestructuras ({infraestructuras.length})</h2>

        {infraestructuras.length === 0 ? (
          <div className="empty-state">
            <div className="empty-state-icon">📋</div>
            <p>No se encontraron infraestructuras con los filtros aplicados</p>
          </div>
        ) : (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Ubicación</th>
                  <th>Cuenca</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                {infraestructuras.map(infra => (
                  <tr key={infra.id}>
                    <td><strong>{infra.codigo}</strong></td>
                    <td>{infra.nombre}</td>
                    <td>{infra.tipo}</td>
                    <td>{infra.distrito}, {infra.provincia}<br/><small>{infra.region}</small></td>
                    <td>{infra.cuenca_hidrografica || '-'}</td>
                    <td>
                      <span className={`badge ${obtenerBadgeEstado(infra.estado_conservacion)}`}>
                        {infra.estado_conservacion || 'No especificado'}
                      </span>
                    </td>
                    <td>
                      <div className="action-buttons">
                        <button
                          className="btn btn-sm btn-primary"
                          onClick={() => onEdit(infra)}
                        >
                          Editar
                        </button>
                        <button
                          className="btn btn-sm btn-danger"
                          onClick={() => handleEliminar(infra.id, infra.nombre)}
                        >
                          Eliminar
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};

export default ListaInfraestructuras;
