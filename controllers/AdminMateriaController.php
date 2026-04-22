<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Materia.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminMateriaController {
    private $materiaModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->materiaModel = new Materia($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'intensidad_horaria' => trim($_POST['intensidad_horaria'] ?? 0)
            ];

            if (empty($datos['nombre'])) {
                $this->setAlert('warning', 'Campo incompleto', 'El nombre de la materia es obligatorio');
            } else {
                $resultado = $this->materiaModel->crear($datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Materia creada correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_materias.php");
        exit;
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_materia'] ?? null;
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'intensidad_horaria' => trim($_POST['intensidad_horaria'] ?? 0)
            ];

            if ($id && !empty($datos['nombre'])) {
                $resultado = $this->materiaModel->actualizar($id, $datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Materia actualizada correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_materias.php");
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $resultado = $this->materiaModel->eliminar($id);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Materia eliminada correctamente');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_materias.php");
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

$controller = new AdminMateriaController();
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
        header("Location: ../views/dashboard/admin_materias.php");
        exit;
}
?>
