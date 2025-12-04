-- Base de datos para Sistema de Inventario ANA
-- MySQL/MariaDB

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS inventario_ana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_ana;

-- Tabla de infraestructuras
CREATE TABLE IF NOT EXISTS infraestructuras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    -- DATOS GENERALES
    codigo VARCHAR(50) UNIQUE NOT NULL COMMENT 'Código único ANA',
    nombre VARCHAR(255) NOT NULL,
    tipo ENUM(
        'Presa/Represa',
        'Bocatoma',
        'Canal Principal',
        'Túnel',
        'Sifón',
        'Acueducto',
        'Desarenador',
        'Estructura de Medición'
    ) NOT NULL,

    -- UBICACIÓN GEOGRÁFICA
    region VARCHAR(100) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    distrito VARCHAR(100) NOT NULL,

    -- COORDENADAS UTM
    coordenada_este DECIMAL(12, 2) DEFAULT NULL,
    coordenada_norte DECIMAL(12, 2) DEFAULT NULL,
    zona_utm TINYINT DEFAULT NULL COMMENT 'Zona UTM: 17, 18 o 19',
    altitud DECIMAL(8, 2) DEFAULT NULL COMMENT 'Altitud en msnm',

    -- HIDROGRAFÍA
    cuenca_hidrografica VARCHAR(255) DEFAULT NULL,
    subcuenca VARCHAR(255) DEFAULT NULL,
    cuerpo_agua VARCHAR(255) DEFAULT NULL COMMENT 'Río, quebrada, lago',

    -- CARACTERÍSTICAS TÉCNICAS
    capacidad DECIMAL(15, 2) DEFAULT NULL,
    unidad_capacidad VARCHAR(20) DEFAULT 'm³' COMMENT 'm³, m³/s, l/s',
    longitud DECIMAL(10, 2) DEFAULT NULL COMMENT 'Metros',
    ancho DECIMAL(10, 2) DEFAULT NULL COMMENT 'Metros',
    altura DECIMAL(10, 2) DEFAULT NULL COMMENT 'Metros',
    area_influencia DECIMAL(10, 2) DEFAULT NULL COMMENT 'Hectáreas',
    material_construccion ENUM(
        'Concreto',
        'Tierra',
        'Mampostería',
        'Metálico',
        'Mixto',
        'Otro'
    ) DEFAULT NULL,

    -- INFORMACIÓN ADMINISTRATIVA
    anio_construccion YEAR DEFAULT NULL,
    titular_propietario VARCHAR(255) DEFAULT NULL,
    operador_actual VARCHAR(255) DEFAULT NULL,
    uso_principal ENUM(
        'Agrícola',
        'Poblacional',
        'Minero',
        'Energético',
        'Industrial',
        'Acuícola',
        'Recreacional',
        'Uso Múltiple'
    ) DEFAULT NULL,
    licencia_agua VARCHAR(100) DEFAULT NULL,

    -- ESTADO Y OPERACIÓN
    estado_conservacion ENUM(
        'Muy Bueno',
        'Bueno',
        'Regular',
        'Malo',
        'Muy Malo'
    ) DEFAULT NULL,
    estado_operativo ENUM(
        'Operativo',
        'Inoperativo',
        'En mantenimiento'
    ) DEFAULT 'Operativo',
    fecha_ultima_inspeccion DATE DEFAULT NULL,
    observaciones TEXT DEFAULT NULL,

    -- METADATOS
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuario_registro VARCHAR(100) DEFAULT 'Sistema',

    -- ÍNDICES
    INDEX idx_tipo (tipo),
    INDEX idx_region (region),
    INDEX idx_cuenca (cuenca_hidrografica),
    INDEX idx_estado (estado_conservacion),
    INDEX idx_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo
INSERT INTO infraestructuras (
    codigo, nombre, tipo, region, provincia, distrito,
    coordenada_este, coordenada_norte, zona_utm, altitud,
    cuenca_hidrografica, cuerpo_agua, capacidad, unidad_capacidad,
    altura, material_construccion, anio_construccion,
    titular_propietario, operador_actual, uso_principal,
    estado_conservacion, estado_operativo
) VALUES
(
    'ANA-LIM-PRES-0001',
    'Presa San Lorenzo',
    'Presa/Represa',
    'Lima',
    'Cañete',
    'San Vicente',
    370000.00,
    8580000.00,
    18,
    2450.00,
    'Cuenca del río Cañete',
    'Río Cañete',
    15000000.00,
    'm³',
    45.00,
    'Concreto',
    1985,
    'Junta de Usuarios Cañete',
    'Comisión de Regantes',
    'Agrícola',
    'Bueno',
    'Operativo'
),
(
    'ANA-CUZ-BOCA-0001',
    'Bocatoma Muyurina',
    'Bocatoma',
    'Cusco',
    'Cusco',
    'San Sebastián',
    185000.00,
    8505000.00,
    19,
    3200.00,
    'Cuenca del Vilcanota',
    'Río Vilcanota',
    3.50,
    'm³/s',
    NULL,
    'Concreto',
    2005,
    'SEDACUSCO S.A.',
    'SEDACUSCO S.A.',
    'Poblacional',
    'Muy Bueno',
    'Operativo'
),
(
    'ANA-ARE-CANA-0001',
    'Canal Principal La Irrigación',
    'Canal Principal',
    'Arequipa',
    'Arequipa',
    'Sachaca',
    230000.00,
    8195000.00,
    19,
    2320.00,
    'Cuenca del Chili',
    'Río Chili',
    5.20,
    'm³/s',
    NULL,
    'Concreto',
    1998,
    'AUTODEMA',
    'AUTODEMA',
    'Agrícola',
    'Regular',
    'Operativo'
);

-- Tabla de usuarios (opcional para futuras mejoras)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    rol ENUM('admin', 'operador', 'consulta') DEFAULT 'consulta',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario administrador por defecto (password: admin123)
INSERT INTO usuarios (username, password, nombre_completo, email, rol) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin@ana.gob.pe', 'admin');
