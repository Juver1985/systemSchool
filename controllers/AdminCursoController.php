<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Curso.php';

// Verificación de seguridad de rol
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminCursoController {
    private $cursoModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->cursoModel = new Curso($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'grado' => trim($_POST['grado'] ?? ''),
                'jornada' => trim($_POST['jornada'] ?? 'Mañana'),
                'anio' => trim($_POST['anio'] ?? date('Y'))
            ];

            if (empty($datos['nombre']) || empty($datos['grado'])) {
                $this->setAlert('warning', 'Campos incompletos', 'Debe completar todos los campos obligatorios');
            } else {
                $resultado = $this->cursoModel->crear($datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Curso creado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_cursos.php");
        exit;
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_curso'] ?? null;
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'grado' => trim($_POST['grado'] ?? ''),
                'jornada' => trim($_POST['jornada'] ?? 'Mañana'),
                'anio' => trim($_POST['anio'] ?? date('Y'))
            ];

            if ($id && !empty($datos['nombre'])) {
                $resultado = $this->cursoModel->actualizar($id, $datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Curso actualizado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_cursos.php");
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $resultado = $this->cursoModel->eliminar($id);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Curso eliminado correctamente');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_cursos.php");
        exit;
    }

    private function setAlert($icon, $title, $text) {
        $_SESSION['alert'] = [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ];
    }
}

$controller = new AdminCursoController();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;
    case 'editar':
        $controller->editar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        header("Location: ../views/dashboard/admin_cursos.php");
        exit;
}
?>
