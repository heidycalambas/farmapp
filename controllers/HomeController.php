<?php
/**
 * Controlador Principal
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Categoria.php';

class HomeController {
    public function index() {
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->obtenerTodas();
        require_once __DIR__ . '/../views/home/index.php';
    }
}

