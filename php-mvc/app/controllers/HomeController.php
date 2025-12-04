<?php
/**
 * Controlador Home - Vista principal
 */

class HomeController extends Controller {

    public function index() {
        $data = [
            'title' => 'Sistema de Inventario ANA',
            'appName' => APP_NAME,
            'version' => APP_VERSION
        ];

        $this->view('home/index', $data);
    }
}
