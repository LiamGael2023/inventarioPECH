import axios from 'axios';

const API_BASE_URL = '/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor para manejar errores
api.interceptors.response.use(
  response => response,
  error => {
    console.error('API Error:', error);
    return Promise.reject(error);
  }
);

// Servicios de Infraestructura
export const infraestructuraService = {
  // Obtener todas las infraestructuras
  getAll: (filtros = {}) => {
    const params = new URLSearchParams();
    if (filtros.tipo) params.append('tipo', filtros.tipo);
    if (filtros.region) params.append('region', filtros.region);
    if (filtros.cuenca) params.append('cuenca', filtros.cuenca);
    if (filtros.estado) params.append('estado', filtros.estado);

    return api.get(`/infraestructuras?${params.toString()}`);
  },

  // Obtener una infraestructura por ID
  getById: (id) => {
    return api.get(`/infraestructuras/${id}`);
  },

  // Crear nueva infraestructura
  create: (data) => {
    return api.post('/infraestructuras', data);
  },

  // Actualizar infraestructura
  update: (id, data) => {
    return api.put(`/infraestructuras/${id}`, data);
  },

  // Eliminar infraestructura
  delete: (id) => {
    return api.delete(`/infraestructuras/${id}`);
  },
};

// Servicios de Estadísticas
export const estadisticasService = {
  getGeneral: () => {
    return api.get('/estadisticas');
  },
};

// Servicios de Catálogos
export const catalogoService = {
  getAll: () => {
    return api.get('/catalogo');
  },
};

export default api;
