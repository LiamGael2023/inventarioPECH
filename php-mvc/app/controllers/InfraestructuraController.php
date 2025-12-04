<?php
/**
 * Controlador de Infraestructuras
 */

class InfraestructuraController extends Controller {

    private $infraestructuraModel;

    public function __construct() {
        $this->infraestructuraModel = $this->model('Infraestructura');
    }

    /**
     * Listado de infraestructuras
     */
    public function index() {
        $filters = [
            'tipo' => $_GET['tipo'] ?? '',
            'region' => $_GET['region'] ?? '',
            'cuenca' => $_GET['cuenca'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        $infraestructuras = $this->infraestructuraModel->search($filters);
        $regiones = $this->infraestructuraModel->getRegiones();
        $cuencas = $this->infraestructuraModel->getCuencas();

        $data = [
            'title' => 'Inventario de Infraestructuras',
            'infraestructuras' => $infraestructuras,
            'filters' => $filters,
            'regiones' => $regiones,
            'cuencas' => $cuencas,
            'tipos' => Infraestructura::TIPOS,
            'estados' => Infraestructura::ESTADOS_CONSERVACION
        ];

        $this->view('infraestructura/index', $data);
    }

    /**
     * Ver detalle
     */
    public function ver($id) {
        $infraestructura = $this->infraestructuraModel->getById($id);

        if (!$infraestructura) {
            $this->redirect('infraestructura');
            return;
        }

        $data = [
            'title' => 'Detalle de Infraestructura',
            'infraestructura' => $infraestructura
        ];

        $this->view('infraestructura/ver', $data);
    }

    /**
     * Formulario de creación
     */
    public function crear() {
        $data = [
            'title' => 'Registrar Nueva Infraestructura',
            'tipos' => Infraestructura::TIPOS,
            'estados' => Infraestructura::ESTADOS_CONSERVACION,
            'usos' => Infraestructura::USOS_PRINCIPALES,
            'materiales' => Infraestructura::MATERIALES
        ];

        $this->view('infraestructura/formulario', $data);
    }

    /**
     * Formulario de edición
     */
    public function editar($id) {
        $infraestructura = $this->infraestructuraModel->getById($id);

        if (!$infraestructura) {
            $this->redirect('infraestructura');
            return;
        }

        $data = [
            'title' => 'Editar Infraestructura',
            'infraestructura' => $infraestructura,
            'tipos' => Infraestructura::TIPOS,
            'estados' => Infraestructura::ESTADOS_CONSERVACION,
            'usos' => Infraestructura::USOS_PRINCIPALES,
            'materiales' => Infraestructura::MATERIALES
        ];

        $this->view('infraestructura/formulario', $data);
    }

    /**
     * Estadísticas
     */
    public function estadisticas() {
        $stats = $this->infraestructuraModel->getEstadisticas();

        $data = [
            'title' => 'Estadísticas del Inventario',
            'stats' => $stats
        ];

        $this->view('infraestructura/estadisticas', $data);
    }
}
