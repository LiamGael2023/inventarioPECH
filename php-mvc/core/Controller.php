<?php
/**
 * Controlador Base
 */

class Controller {

    /**
     * Cargar modelo
     */
    protected function model($model) {
        $modelPath = APP_PATH . '/models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Error: Modelo {$model} no encontrado");
        }
    }

    /**
     * Cargar vista
     */
    protected function view($view, $data = []) {
        $viewPath = VIEWS_PATH . '/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            die("Error: Vista {$view} no encontrada");
        }
    }

    /**
     * Redirigir
     */
    protected function redirect($url) {
        header("Location: " . APP_URL . '/' . $url);
        exit;
    }

    /**
     * Respuesta JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Obtener datos POST como JSON
     */
    protected function getJsonInput() {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }

    /**
     * Validar método HTTP
     */
    protected function validateMethod($method) {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            $this->json(['error' => 'Método no permitido'], 405);
        }
    }

    /**
     * Sanitizar entrada
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
            return $data;
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
