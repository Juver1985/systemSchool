<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Matricula.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminMatriculaController {
    private $matriculaModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->matriculaModel = new Matricula($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_estudiante' => $_POST['id_estudiante'] ?? '',
                'id_curso' => $_POST['id_curso'] ?? ''
            ];

            if (empty($datos['id_estudiante']) || empty($datos['id_curso'])) {
                $this->setAlert('warning', 'Campos incompletos', 'Debe seleccionar estudiante y curso');
            } else {
                $resultado = $this->matriculaModel->matricular($datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Estudiante matriculado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_matriculas.php");
        exit;
    }

    public function toggleEstado() {
        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? 'ACTIVA';
        if ($id) {
            $nuevo_estado = $estado === 'ACTIVA' ? 'RETIRADA' : 'ACTIVA';
            $resultado = $this->matriculaModel->cambiarEstado($id, $nuevo_estado);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Estado de matrícula actualizado');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_matriculas.php");
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $resultado = $this->matriculaModel->eliminar($id);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Matrícula eliminada correctamente');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_matriculas.php");
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

$controller = new AdminMatriculaController();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;
    case 'toggleEstado':
        $controller->toggleEstado();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        header("Location: ../views/dashboard/admin_matriculas.php");
        exit;
}
?>
