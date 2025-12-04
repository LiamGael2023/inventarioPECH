import React, { useState, useEffect } from 'react';
import { estadisticasService } from '../services/api';

const Estadisticas = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    cargarEstadisticas();
  }, []);

  const cargarEstadisticas = async () => {
    setLoading(true);
    try {
      const response = await estadisticasService.getGeneral();
      setStats(response.data);
    } catch (error) {
      console.error('Error al cargar estadísticas:', error);
      alert('Error al cargar estadísticas');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="loading">Cargando estadísticas...</div>;
  }

  if (!stats) {
    return <div className="alert alert-error">No se pudieron cargar las estadísticas</div>;
  }

  return (
    <div>
      <div className="stats-grid">
        <div className="stat-card">
          <h3>Total de Infraestructuras</h3>
          <div className="number">{stats.total}</div>
        </div>
        <div className="stat-card">
          <h3>Tipos Registrados</h3>
          <div className="number">{stats.porTipo?.length || 0}</div>
        </div>
        <div className="stat-card">
          <h3>Regiones Cubiertas</h3>
          <div className="number">{stats.porRegion?.length || 0}</div>
        </div>
      </div>

      <div className="card">
        <h2>Infraestructuras por Tipo</h2>
        {stats.porTipo && stats.porTipo.length > 0 ? (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  <th>Tipo de Infraestructura</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                </tr>
              </thead>
              <tbody>
                {stats.porTipo.map(item => (
                  <tr key={item.tipo}>
                    <td>{item.tipo}</td>
                    <td><strong>{item.count}</strong></td>
                    <td>
                      <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                        <div style={{
                          flex: 1,
                          height: '20px',
                          backgroundColor: '#e5e7eb',
                          borderRadius: '4px',
                          overflow: 'hidden'
                        }}>
                          <div style={{
                            width: `${(item.count / stats.total) * 100}%`,
                            height: '100%',
                            backgroundColor: '#3b82f6'
                          }} />
                        </div>
                        <span>{((item.count / stats.total) * 100).toFixed(1)}%</span>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>No hay datos disponibles</p>
        )}
      </div>

      <div className="card">
        <h2>Infraestructuras por Región</h2>
        {stats.porRegion && stats.porRegion.length > 0 ? (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  <th>Región</th>
                  <th>Cantidad</th>
                  <th>Distribución</th>
                </tr>
              </thead>
              <tbody>
                {stats.porRegion.map(item => (
                  <tr key={item.region}>
                    <td>{item.region}</td>
                    <td><strong>{item.count}</strong></td>
                    <td>
                      <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                        <div style={{
                          flex: 1,
                          height: '20px',
                          backgroundColor: '#e5e7eb',
                          borderRadius: '4px',
                          overflow: 'hidden'
                        }}>
                          <div style={{
                            width: `${(item.count / stats.total) * 100}%`,
                            height: '100%',
                            backgroundColor: '#10b981'
                          }} />
                        </div>
                        <span>{((item.count / stats.total) * 100).toFixed(1)}%</span>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>No hay datos disponibles</p>
        )}
      </div>

      <div className="card">
        <h2>Estado de Conservación</h2>
        {stats.porEstado && stats.porEstado.length > 0 ? (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  <th>Estado</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                </tr>
              </thead>
              <tbody>
                {stats.porEstado.map(item => (
                  <tr key={item.estado_conservacion}>
                    <td>
                      <span className={`badge ${
                        item.estado_conservacion === 'Muy Bueno' || item.estado_conservacion === 'Bueno' ? 'badge-success' :
                        item.estado_conservacion === 'Regular' ? 'badge-warning' : 'badge-danger'
                      }`}>
                        {item.estado_conservacion}
                      </span>
                    </td>
                    <td><strong>{item.count}</strong></td>
                    <td>{((item.count / stats.total) * 100).toFixed(1)}%</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>No hay datos disponibles</p>
        )}
      </div>

      <div className="card">
        <h2>Uso Principal del Agua</h2>
        {stats.porUso && stats.porUso.length > 0 ? (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  <th>Uso</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                </tr>
              </thead>
              <tbody>
                {stats.porUso.map(item => (
                  <tr key={item.uso_principal}>
                    <td>{item.uso_principal}</td>
                    <td><strong>{item.count}</strong></td>
                    <td>{((item.count / stats.total) * 100).toFixed(1)}%</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>No hay datos disponibles</p>
        )}
      </div>
    </div>
  );
};

export default Estadisticas;
