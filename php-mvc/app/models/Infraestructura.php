<?php
/**
 * Modelo Infraestructura
 * Gestión de infraestructuras hidráulicas según normativa ANA
 */

class Infraestructura extends Model {

    protected $table = 'infraestructuras';

    // Catálogos según normativa ANA
    const TIPOS = [
        'Presa/Represa',
        'Bocatoma',
        'Canal Principal',
        'Túnel',
        'Sifón',
        'Acueducto',
        'Desarenador',
        'Estructura de Medición'
    ];

    const ESTADOS_CONSERVACION = [
        'Muy Bueno',
        'Bueno',
        'Regular',
        'Malo',
        'Muy Malo'
    ];

    const USOS_PRINCIPALES = [
        'Agrícola',
        'Poblacional',
        'Minero',
        'Energético',
        'Industrial',
        'Acuícola',
        'Recreacional',
        'Uso Múltiple'
    ];

    const MATERIALES = [
        'Concreto',
        'Tierra',
        'Mampostería',
        'Metálico',
        'Mixto',
        'Otro'
    ];

    /**
     * Buscar con filtros
     */
    public function search($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['tipo'])) {
            $sql .= " AND tipo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }

        if (!empty($filters['region'])) {
            $sql .= " AND region LIKE :region";
            $params[':region'] = '%' . $filters['region'] . '%';
        }

        if (!empty($filters['cuenca'])) {
            $sql .= " AND cuenca_hidrografica LIKE :cuenca";
            $params[':cuenca'] = '%' . $filters['cuenca'] . '%';
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND estado_conservacion = :estado";
            $params[':estado'] = $filters['estado'];
        }

        $sql .= " ORDER BY fecha_registro DESC";

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getEstadisticas() {
        $stats = [];

        // Total
        $stats['total'] = $this->count();

        // Por tipo
        $sql = "SELECT tipo, COUNT(*) as count FROM {$this->table} GROUP BY tipo ORDER BY count DESC";
        $stmt = $this->query($sql);
        $stats['porTipo'] = $stmt->fetchAll();

        // Por región
        $sql = "SELECT region, COUNT(*) as count FROM {$this->table} GROUP BY region ORDER BY count DESC LIMIT 10";
        $stmt = $this->query($sql);
        $stats['porRegion'] = $stmt->fetchAll();

        // Por estado de conservación
        $sql = "SELECT estado_conservacion, COUNT(*) as count FROM {$this->table}
                WHERE estado_conservacion IS NOT NULL GROUP BY estado_conservacion";
        $stmt = $this->query($sql);
        $stats['porEstado'] = $stmt->fetchAll();

        // Por uso
        $sql = "SELECT uso_principal, COUNT(*) as count FROM {$this->table}
                WHERE uso_principal IS NOT NULL GROUP BY uso_principal ORDER BY count DESC";
        $stmt = $this->query($sql);
        $stats['porUso'] = $stmt->fetchAll();

        return $stats;
    }

    /**
     * Validar código ANA
     */
    public function validarCodigo($codigo) {
        // Formato: ANA-REGIÓN-TIPO-NÚMERO
        return preg_match('/^ANA-[A-Z]{2,3}-[A-Z]{2,4}-\d{4}$/', $codigo);
    }

    /**
     * Validar coordenadas UTM para Perú
     */
    public function validarCoordenadas($este, $norte, $zona) {
        $zonasValidas = [17, 18, 19];

        if (!in_array($zona, $zonasValidas)) {
            return false;
        }

        if ($este < 0 || $este > 1000000) {
            return false;
        }

        if ($norte < 8000000 || $norte > 10000000) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si código ya existe
     */
    public function codigoExiste($codigo, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE codigo = :codigo";

        if ($excludeId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':codigo', $codigo);

        if ($excludeId) {
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    /**
     * Obtener regiones únicas
     */
    public function getRegiones() {
        $sql = "SELECT DISTINCT region FROM {$this->table} ORDER BY region";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Obtener cuencas únicas
     */
    public function getCuencas() {
        $sql = "SELECT DISTINCT cuenca_hidrografica FROM {$this->table}
                WHERE cuenca_hidrografica IS NOT NULL ORDER BY cuenca_hidrografica";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
