/**
 * Modelo de Infraestructura Mayor según normativa ANA
 * Autoridad Nacional del Agua - Perú
 */

const TIPOS_INFRAESTRUCTURA = {
  PRESA: 'Presa/Represa',
  BOCATOMA: 'Bocatoma',
  CANAL: 'Canal Principal',
  TUNEL: 'Túnel',
  SIFON: 'Sifón',
  ACUEDUCTO: 'Acueducto',
  DESARENADOR: 'Desarenador',
  MEDICION: 'Estructura de Medición'
};

const ESTADOS_CONSERVACION = {
  MUY_BUENO: 'Muy Bueno',
  BUENO: 'Bueno',
  REGULAR: 'Regular',
  MALO: 'Malo',
  MUY_MALO: 'Muy Malo'
};

const USOS_PRINCIPALES = {
  AGRICOLA: 'Agrícola',
  POBLACIONAL: 'Poblacional',
  MINERO: 'Minero',
  ENERGETICO: 'Energético',
  INDUSTRIAL: 'Industrial',
  ACUICOLA: 'Acuícola',
  RECREACIONAL: 'Recreacional',
  MULTIPLE: 'Uso Múltiple'
};

const MATERIALES = {
  CONCRETO: 'Concreto',
  TIERRA: 'Tierra',
  MAMPOSTERIA: 'Mampostería',
  METALICO: 'Metálico',
  MIXTO: 'Mixto',
  OTRO: 'Otro'
};

/**
 * Esquema de datos para Infraestructura Mayor
 */
const InfraestructuraSchema = {
  // DATOS GENERALES
  id: 'INTEGER PRIMARY KEY AUTOINCREMENT',
  codigo: 'TEXT UNIQUE NOT NULL', // Código único ANA
  nombre: 'TEXT NOT NULL',
  tipo: 'TEXT NOT NULL', // TIPOS_INFRAESTRUCTURA

  // UBICACIÓN GEOGRÁFICA
  region: 'TEXT NOT NULL',
  provincia: 'TEXT NOT NULL',
  distrito: 'TEXT NOT NULL',

  // COORDENADAS UTM
  coordenada_este: 'REAL',
  coordenada_norte: 'REAL',
  zona_utm: 'INTEGER', // Zona UTM (17, 18, 19 para Perú)
  altitud: 'REAL', // msnm

  // HIDROGRAFÍA
  cuenca_hidrografica: 'TEXT',
  subcuenca: 'TEXT',
  cuerpo_agua: 'TEXT', // Río, quebrada, lago asociado

  // CARACTERÍSTICAS TÉCNICAS
  capacidad: 'REAL', // m³ o m³/s según tipo
  unidad_capacidad: 'TEXT', // m³, m³/s, l/s
  longitud: 'REAL', // metros
  ancho: 'REAL', // metros
  altura: 'REAL', // metros
  area_influencia: 'REAL', // hectáreas
  material_construccion: 'TEXT', // MATERIALES

  // INFORMACIÓN ADMINISTRATIVA
  anio_construccion: 'INTEGER',
  titular_propietario: 'TEXT',
  operador_actual: 'TEXT',
  uso_principal: 'TEXT', // USOS_PRINCIPALES
  licencia_agua: 'TEXT', // Número de licencia

  // ESTADO Y OPERACIÓN
  estado_conservacion: 'TEXT', // ESTADOS_CONSERVACION
  estado_operativo: 'TEXT', // Operativo, Inoperativo, En mantenimiento
  fecha_ultima_inspeccion: 'TEXT',
  observaciones: 'TEXT',

  // METADATOS
  fecha_registro: 'TEXT NOT NULL',
  fecha_actualizacion: 'TEXT',
  usuario_registro: 'TEXT'
};

/**
 * Validación de datos según normativa ANA
 */
class InfraestructuraValidator {
  static validarCodigo(codigo) {
    // Formato: ANA-REGIÓN-TIPO-NÚMERO
    const regex = /^ANA-[A-Z]{2,3}-[A-Z]{2,4}-\d{4}$/;
    return regex.test(codigo);
  }

  static validarCoordenadas(este, norte, zona) {
    // Validar rangos para Perú
    const zonas_validas = [17, 18, 19];
    if (!zonas_validas.includes(zona)) return false;

    if (este < 0 || este > 1000000) return false;
    if (norte < 8000000 || norte > 10000000) return false;

    return true;
  }

  static validarTipo(tipo) {
    return Object.values(TIPOS_INFRAESTRUCTURA).includes(tipo);
  }

  static validarEstado(estado) {
    return Object.values(ESTADOS_CONSERVACION).includes(estado);
  }

  static validarUso(uso) {
    return Object.values(USOS_PRINCIPALES).includes(uso);
  }

  static validarAnio(anio) {
    const anioActual = new Date().getFullYear();
    return anio >= 1900 && anio <= anioActual;
  }
}

module.exports = {
  InfraestructuraSchema,
  TIPOS_INFRAESTRUCTURA,
  ESTADOS_CONSERVACION,
  USOS_PRINCIPALES,
  MATERIALES,
  InfraestructuraValidator
};
