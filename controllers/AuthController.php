<?php
/**
 * Controlador de Autenticación
 * FarmApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../utils/Auth.php';

class AuthController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Por favor completa todos los campos';
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            }
            
            $usuario = $this->usuarioModel->login($email, $password);
            
            if ($usuario) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['success'] = 'Bienvenido, ' . $usuario['nombre'];
                
                // Redirigir según rol
                if ($usuario['rol_nombre'] === 'Administrador') {
                    header('Location: ' . BASE_URL . '/index.php?action=admin_dashboard');
                } elseif ($usuario['rol_nombre'] === 'Farmacéutico') {
                    header('Location: ' . BASE_URL . '/index.php?action=farmaceutico_dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/index.php?action=catalogo');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Email o contraseña incorrectos';
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }
    
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];
            
            // Validaciones
            if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['password'])) {
                $_SESSION['error'] = 'Por favor completa todos los campos obligatorios';
                header('Location: ' . BASE_URL . '/index.php?action=registro');
                exit;
            }
            
            if (strlen($datos['password']) < PASSWORD_MIN_LENGTH) {
                $_SESSION['error'] = 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres';
                header('Location: ' . BASE_URL . '/index.php?action=registro');
                exit;
            }
            
            if ($this->usuarioModel->existeEmail($datos['email'])) {
                $_SESSION['error'] = 'Este email ya está registrado';
                header('Location: ' . BASE_URL . '/index.php?action=registro');
                exit;
            }
            
            if ($this->usuarioModel->registrar($datos)) {
                $_SESSION['success'] = 'Registro exitoso. Por favor inicia sesión';
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar usuario';
                header('Location: ' . BASE_URL . '/index.php?action=registro');
                exit;
            }
        } else {
            require_once __DIR__ . '/../views/auth/registro.php';
        }
    }
    
    public function logout() {
        Auth::logout();
    }
    
    public function perfil() {
        Auth::requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];
            
            // Validaciones
            if (empty($datos['nombre']) || empty($datos['email'])) {
                $_SESSION['error'] = 'Nombre y email son obligatorios';
                header('Location: ' . BASE_URL . '/index.php?action=perfil');
                exit;
            }
            
            // Verificar si el email ya existe en otro usuario
            if ($this->usuarioModel->existeEmail($datos['email'], Auth::id())) {
                $_SESSION['error'] = 'Este email ya está en uso por otro usuario';
                header('Location: ' . BASE_URL . '/index.php?action=perfil');
                exit;
            }
            
            // Si hay nueva contraseña, validarla
            if (!empty($datos['password'])) {
                if (strlen($datos['password']) < PASSWORD_MIN_LENGTH) {
                    $_SESSION['error'] = 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres';
                    header('Location: ' . BASE_URL . '/index.php?action=perfil');
                    exit;
                }
            } else {
                // Si no hay nueva contraseña, no actualizarla
                unset($datos['password']);
            }
            
            if ($this->usuarioModel->actualizar(Auth::id(), $datos)) {
                // Actualizar datos en sesión
                $usuario = $this->usuarioModel->obtenerPorId(Auth::id());
                $_SESSION['usuario'] = $usuario;
                $_SESSION['success'] = 'Perfil actualizado correctamente';
                header('Location: ' . BASE_URL . '/index.php?action=perfil');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
                header('Location: ' . BASE_URL . '/index.php?action=perfil');
                exit;
            }
        } else {
            $usuario = $this->usuarioModel->obtenerPorId(Auth::id());
            require_once __DIR__ . '/../views/auth/perfil.php';
        }
    }
}

