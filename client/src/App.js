import React, { useState } from 'react';
import './index.css';
import FormularioInfraestructura from './components/FormularioInfraestructura';
import ListaInfraestructuras from './components/ListaInfraestructuras';
import Estadisticas from './components/Estadisticas';
import { infraestructuraService } from './services/api';

function App() {
  const [tabActiva, setTabActiva] = useState('lista');
  const [infraestructuraEditar, setInfraestructuraEditar] = useState(null);
  const [mensaje, setMensaje] = useState(null);

  const mostrarMensaje = (texto, tipo = 'success') => {
    setMensaje({ texto, tipo });
    setTimeout(() => setMensaje(null), 5000);
  };

  const handleSubmitFormulario = async (data) => {
    try {
      if (infraestructuraEditar?.id) {
        await infraestructuraService.update(infraestructuraEditar.id, data);
        mostrarMensaje('Infraestructura actualizada exitosamente');
      } else {
        await infraestructuraService.create(data);
        mostrarMensaje('Infraestructura registrada exitosamente');
      }
      setInfraestructuraEditar(null);
      setTabActiva('lista');
      // Trigger refresh en lista
      window.location.reload();
    } catch (error) {
      console.error('Error al guardar:', error);
      const errorMsg = error.response?.data?.error || 'Error al guardar la infraestructura';
      mostrarMensaje(errorMsg, 'error');
    }
  };

  const handleEditar = (infraestructura) => {
    setInfraestructuraEditar(infraestructura);
    setTabActiva('registro');
  };

  const handleCancelar = () => {
    setInfraestructuraEditar(null);
    setTabActiva('lista');
  };

  const handleNuevoRegistro = () => {
    setInfraestructuraEditar(null);
    setTabActiva('registro');
  };

  return (
    <div className="App">
      <header className="header">
        <h1>Sistema de Inventario de Infraestructura Mayor</h1>
        <p>Autoridad Nacional del Agua (ANA) - Perú</p>
      </header>

      <div className="container">
        {mensaje && (
          <div className={`alert alert-${mensaje.tipo}`}>
            {mensaje.texto}
          </div>
        )}

        <div className="nav-tabs">
          <button
            className={`nav-tab ${tabActiva === 'lista' ? 'active' : ''}`}
            onClick={() => {
              setInfraestructuraEditar(null);
              setTabActiva('lista');
            }}
          >
            📋 Inventario
          </button>
          <button
            className={`nav-tab ${tabActiva === 'registro' ? 'active' : ''}`}
            onClick={handleNuevoRegistro}
          >
            ➕ Nuevo Registro
          </button>
          <button
            className={`nav-tab ${tabActiva === 'estadisticas' ? 'active' : ''}`}
            onClick={() => {
              setInfraestructuraEditar(null);
              setTabActiva('estadisticas');
            }}
          >
            📊 Estadísticas
          </button>
        </div>

        {tabActiva === 'lista' && (
          <ListaInfraestructuras onEdit={handleEditar} />
        )}

        {tabActiva === 'registro' && (
          <div>
            <div className="card">
              <h2>
                {infraestructuraEditar
                  ? `Editar: ${infraestructuraEditar.nombre}`
                  : 'Registrar Nueva Infraestructura'}
              </h2>
              <p style={{ color: '#6b7280', marginBottom: '20px' }}>
                Complete el formulario con la información de la infraestructura mayor según normativa ANA
              </p>
            </div>
            <FormularioInfraestructura
              infraestructura={infraestructuraEditar}
              onSubmit={handleSubmitFormulario}
              onCancel={infraestructuraEditar ? handleCancelar : null}
            />
          </div>
        )}

        {tabActiva === 'estadisticas' && (
          <Estadisticas />
        )}
      </div>

      <footer style={{
        textAlign: 'center',
        padding: '40px 20px',
        color: '#6b7280',
        borderTop: '1px solid #e5e7eb',
        marginTop: '60px'
      }}>
        <p>Sistema de Inventario de Infraestructura Mayor - ANA Perú</p>
        <p style={{ fontSize: '0.9rem', marginTop: '10px' }}>
          Cumple con la normativa de la Autoridad Nacional del Agua
        </p>
      </footer>
    </div>
  );
}

export default App;
