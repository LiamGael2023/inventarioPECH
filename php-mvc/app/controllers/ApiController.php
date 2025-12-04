<?php
/**
 * Controlador API REST
 * Endpoints para gestión de infraestructuras
 */

class ApiController extends Controller {

    private $infraestructuraModel;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        $this->infraestructuraModel = $this->model('Infraestructura');
    }

    /**
     * GET /api o GET /api/infraestructuras
     */
    public function index($resource = null) {
        if ($resource === null || $resource === 'infraestructuras') {
            $filters = [
                'tipo' => $_GET['tipo'] ?? '',
                'region' => $_GET['region'] ?? '',
                'cuenca' => $_GET['cuenca'] ?? '',
                'estado' => $_GET['estado'] ?? ''
            ];

            $infraestructuras = $this->infraestructuraModel->search($filters);

            $this->json([
                'success' => true,
                'total' => count($infraestructuras),
                'data' => $infraestructuras
            ]);
        } elseif ($resource === 'estadisticas') {
            $stats = $this->infraestructuraModel->getEstadisticas();
            $this->json([
                'success' => true,
                'data' => $stats
            ]);
        } elseif ($resource === 'catalogos') {
            $this->json([
                'success' => true,
                'data' => [
                    'tipos' => Infraestructura::TIPOS,
                    'estados' => Infraestructura::ESTADOS_CONSERVACION,
                    'usos' => Infraestructura::USOS_PRINCIPALES,
                    'materiales' => Infraestructura::MATERIALES
                ]
            ]);
        } else {
            $this->json(['error' => 'Recurso no encontrado'], 404);
        }
    }

    /**
     * GET /api/infraestructuras/:id
     */
    public function show($resource, $id) {
        if ($resource !== 'infraestructuras') {
            $this->json(['error' => 'Recurso no encontrado'], 404);
            return;
        }

        $infraestructura = $this->infraestructuraModel->getById($id);

        if (!$infraestructura) {
            $this->json(['error' => 'Infraestructura no encontrada'], 404);
            return;
        }

        $this->json([
            'success' => true,
            'data' => $infraestructura
        ]);
    }

    /**
     * POST /api/infraestructuras
     */
    public function store($resource) {
        if ($resource !== 'infraestructuras') {
            $this->json(['error' => 'Recurso no encontrado'], 404);
            return;
        }

        $data = $this->getJsonInput();

        // Validaciones
        $errores = $this->validarDatos($data);
        if (!empty($errores)) {
            $this->json(['error' => 'Datos inválidos', 'errores' => $errores], 400);
            return;
        }

        // Verificar código único
        if ($this->infraestructuraModel->codigoExiste($data['codigo'])) {
            $this->json(['error' => 'El código ya existe'], 400);
            return;
        }

        // Preparar datos para inserción
        $insertData = $this->prepararDatos($data);

        try {
            $id = $this->infraestructuraModel->insert($insertData);
            $this->json([
                'success' => true,
                'message' => 'Infraestructura registrada exitosamente',
                'id' => $id
            ], 201);
        } catch (Exception $e) {
            $this->json(['error' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUT /api/infraestructuras/:id
     */
    public function update($resource, $id) {
        if ($resource !== 'infraestructuras') {
            $this->json(['error' => 'Recurso no encontrado'], 404);
            return;
        }

        if (!$id) {
            $this->json(['error' => 'ID requerido'], 400);
            return;
        }

        $infraestructura = $this->infraestructuraModel->getById($id);
        if (!$infraestructura) {
            $this->json(['error' => 'Infraestructura no encontrada'], 404);
            return;
        }

        $data = $this->getJsonInput();

        // Validaciones
        $errores = $this->validarDatos($data, false);
        if (!empty($errores)) {
            $this->json(['error' => 'Datos inválidos', 'errores' => $errores], 400);
            return;
        }

        // Preparar datos para actualización
        $updateData = $this->prepararDatos($data, false);

        try {
            $this->infraestructuraModel->update($id, $updateData);
            $this->json([
                'success' => true,
                'message' => 'Infraestructura actualizada exitosamente'
            ]);
        } catch (Exception $e) {
            $this->json(['error' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/infraestructuras/:id
     */
    public function destroy($resource, $id) {
        if ($resource !== 'infraestructuras') {
            $this->json(['error' => 'Recurso no encontrado'], 404);
            return;
        }

        if (!$id) {
            $this->json(['error' => 'ID requerido'], 400);
            return;
        }

        $infraestructura = $this->infraestructuraModel->getById($id);
        if (!$infraestructura) {
            $this->json(['error' => 'Infraestructura no encontrada'], 404);
            return;
        }

        try {
            $this->infraestructuraModel->delete($id);
            $this->json([
                'success' => true,
                'message' => 'Infraestructura eliminada exitosamente'
            ]);
        } catch (Exception $e) {
            $this->json(['error' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validar datos de entrada
     */
    private function validarDatos($data, $esNuevo = true) {
        $errores = [];

        // Campos obligatorios
        if ($esNuevo) {
            if (empty($data['codigo'])) {
                $errores[] = 'El código es obligatorio';
            } elseif (!$this->infraestructuraModel->validarCodigo($data['codigo'])) {
                $errores[] = 'Formato de código inválido (debe ser ANA-XXX-XXXX-0000)';
            }
        }

        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es obligatorio';
        }

        if (empty($data['tipo']) || !in_array($data['tipo'], Infraestructura::TIPOS)) {
            $errores[] = 'Tipo de infraestructura inválido';
        }

        if (empty($data['region'])) {
            $errores[] = 'La región es obligatoria';
        }

        if (empty($data['provincia'])) {
            $errores[] = 'La provincia es obligatoria';
        }

        if (empty($data['distrito'])) {
            $errores[] = 'El distrito es obligatorio';
        }

        // Validar coordenadas si están presentes
        if (!empty($data['coordenada_este']) && !empty($data['coordenada_norte']) && !empty($data['zona_utm'])) {
            if (!$this->infraestructuraModel->validarCoordenadas(
                $data['coordenada_este'],
                $data['coordenada_norte'],
                $data['zona_utm']
            )) {
                $errores[] = 'Coordenadas UTM inválidas para Perú';
            }
        }

        return $errores;
    }

    /**
     * Preparar datos para inserción/actualización
     */
    private function prepararDatos($data, $incluirCodigo = true) {
        $prepared = [];

        if ($incluirCodigo && !empty($data['codigo'])) {
            $prepared['codigo'] = $this->sanitize($data['codigo']);
        }

        $campos = [
            'nombre', 'tipo', 'region', 'provincia', 'distrito',
            'coordenada_este', 'coordenada_norte', 'zona_utm', 'altitud',
            'cuenca_hidrografica', 'subcuenca', 'cuerpo_agua',
            'capacidad', 'unidad_capacidad', 'longitud', 'ancho', 'altura',
            'area_influencia', 'material_construccion', 'anio_construccion',
            'titular_propietario', 'operador_actual', 'uso_principal',
            'licencia_agua', 'estado_conservacion', 'estado_operativo',
            'fecha_ultima_inspeccion', 'observaciones'
        ];

        foreach ($campos as $campo) {
            if (isset($data[$campo])) {
                $value = $data[$campo];
                $prepared[$campo] = $value === '' ? null : $this->sanitize($value);
            }
        }

        return $prepared;
    }
}
