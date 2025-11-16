<?php
/**
 * Autoloader simple para clases
 * FarmApp
 */

spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/models/',
        BASE_PATH . '/controllers/',
        BASE_PATH . '/utils/',
        BASE_PATH . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

