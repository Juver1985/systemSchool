<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Asignacion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminAsignacionController {
    private $asignacionModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->asignacionModel = new Asignacion($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_docente_usuario' => $_POST['id_docente'] ?? '',
                'id_materia' => $_POST['id_materia'] ?? '',
                'id_curso' => $_POST['id_curso'] ?? '',
                'anio' => $_POST['anio'] ?? date('Y')
            ];

            if (empty($datos['id_docente_usuario']) || empty($datos['id_materia']) || empty($datos['id_curso'])) {
                $this->setAlert('warning', 'Campos incompletos', 'Debe seleccionar todos los campos');
            } else {
                $resultado = $this->asignacionModel->asignar($datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Docente asignado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_asignaciones.php");
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $resultado = $this->asignacionModel->eliminar($id);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Asignación eliminada correctamente');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_asignaciones.php");
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

$controller = new AdminAsignacionController();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        header("Location: ../views/dashboard/admin_asignaciones.php");
        exit;
}
?>
