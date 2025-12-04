<?php
/**
 * Clase principal de la aplicación - Router
 */

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Verificar si es una petición a la API
        if (isset($url[0]) && $url[0] === 'api') {
            $this->handleApi($url);
            return;
        }

        // Cargar controlador
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Cargar método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Cargar parámetros
        $this->params = $url ? array_values($url) : [];

        // Llamar al método con parámetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    protected function handleApi($url) {
        require_once APP_PATH . '/controllers/ApiController.php';
        $apiController = new ApiController();

        // Remover 'api' del array
        array_shift($url);

        if (empty($url)) {
            $apiController->index();
            return;
        }

        $resource = $url[0];
        $id = isset($url[1]) ? $url[1] : null;

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ($id) {
                    $apiController->show($resource, $id);
                } else {
                    $apiController->index($resource);
                }
                break;
            case 'POST':
                $apiController->store($resource);
                break;
            case 'PUT':
                $apiController->update($resource, $id);
                break;
            case 'DELETE':
                $apiController->destroy($resource, $id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
        }
    }
}
