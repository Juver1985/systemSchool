<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Periodo.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminPeriodoController {
    private $periodoModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->periodoModel = new Periodo($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'anio' => trim($_POST['anio'] ?? date('Y')),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
                'fecha_fin' => trim($_POST['fecha_fin'] ?? '')
            ];

            if (empty($datos['nombre']) || empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) {
                $this->setAlert('warning', 'Campos incompletos', 'Debe completar todos los campos');
            } else {
                $resultado = $this->periodoModel->crear($datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Periodo creado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_periodos.php");
        exit;
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_periodo'] ?? null;
            $datos = [
                'anio' => trim($_POST['anio'] ?? date('Y')),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
                'fecha_fin' => trim($_POST['fecha_fin'] ?? '')
            ];

            if ($id && !empty($datos['nombre'])) {
                $resultado = $this->periodoModel->actualizar($id, $datos);
                if ($resultado === true) {
                    $this->setAlert('success', 'Éxito', 'Periodo actualizado correctamente');
                } else {
                    $this->setAlert('error', 'Error', $resultado);
                }
            }
        }
        header("Location: ../views/dashboard/admin_periodos.php");
        exit;
    }

    public function toggleCierre() {
        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? 0;
        if ($id !== null) {
            $nuevo_estado = $estado == 1 ? 0 : 1;
            $resultado = $this->periodoModel->toggleCierre($id, $nuevo_estado);
            if ($resultado === true) {
                $msg = $nuevo_estado == 1 ? 'Periodo cerrado' : 'Periodo abierto';
                $this->setAlert('success', 'Éxito', $msg);
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_periodos.php");
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $resultado = $this->periodoModel->eliminar($id);
            if ($resultado === true) {
                $this->setAlert('success', 'Éxito', 'Periodo eliminado correctamente');
            } else {
                $this->setAlert('error', 'Error', $resultado);
            }
        }
        header("Location: ../views/dashboard/admin_periodos.php");
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

$controller = new AdminPeriodoController();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;
    case 'editar':
        $controller->editar();
        break;
    case 'toggleCierre':
        $controller->toggleCierre();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        header("Location: ../views/dashboard/admin_periodos.php");
        exit;
}
?>
