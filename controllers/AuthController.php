<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['alert'] = [
                'icon' => 'warning',
                'title' => 'Campos incompletos',
                'text' => 'Debe ingresar correo y contraseña'
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Correo inválido',
                'text' => 'Ingrese un correo electrónico válido'
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        $database = new Database();
        $db = $database->conectar();

        $usuarioModel = new Usuario($db);
        $usuario = $usuarioModel->obtenerPorEmail($email);

        if (!$usuario) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Usuario no encontrado',
                'text' => 'El correo no está registrado o está inactivo'
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        if (!password_verify($password, $usuario['password_hash'])) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Contraseña incorrecta',
                'text' => 'Verifique sus credenciales'
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id_usuario' => $usuario['id_usuario'],
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol']
        ];

        switch ($usuario['rol']) {
            case 'administrador':
                header("Location: ../views/dashboard/admin.php");
                exit;

            case 'docente':
                header("Location: ../views/dashboard/docente.php");
                exit;

            case 'estudiante':
                header("Location: ../views/dashboard/estudiante.php");
                exit;

            case 'acudiente':
                header("Location: ../views/dashboard/acudiente.php");
                exit;

            default:
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Rol no válido',
                    'text' => 'No se pudo determinar el acceso del usuario'
                ];
                header("Location: ../views/usuarios/login.php");
                exit;
        }
    }

    public function logout() {  #cerrar sesion
        session_unset();
        session_destroy();
        header("Location: ../views/usuarios/login.php");
        exit;
    }
}

$controller = new AuthController();

$accion = $_GET['accion'] ?? 'login';

if ($accion === 'logout') {
    $controller->logout();
} else {
    $controller->login();
}
?>