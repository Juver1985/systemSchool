<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Evaluacion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class DocenteEvaluacionController {
    private $evaluacionModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->evaluacionModel = new Evaluacion($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_dmc' => $_POST['id_dmc'],
                'id_periodo' => $_POST['id_periodo'],
                'titulo' => trim($_POST['titulo']),
                'tipo' => $_POST['tipo'],
                'fecha' => $_POST['fecha'],
                'porcentaje' => $_POST['porcentaje']
            ];

            $resultado = $this->evaluacionModel->crear($datos);
            if ($resultado === true) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Evaluación creada'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => $resultado];
            }
        }
        header("Location: ../views/dashboard/docente.php?id_dmc=" . $_POST['id_dmc']);
        exit;
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_evaluacion'];
            $datos = [
                'id_periodo' => $_POST['id_periodo'],
                'titulo' => trim($_POST['titulo']),
                'tipo' => $_POST['tipo'],
                'fecha' => $_POST['fecha'],
                'porcentaje' => $_POST['porcentaje']
            ];

            $resultado = $this->evaluacionModel->actualizar($id, $datos);
            if ($resultado === true) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Evaluación actualizada'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => $resultado];
            }
        }
        header("Location: ../views/dashboard/docente.php?id_dmc=" . $_POST['id_dmc']);
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'];
        $id_dmc = $_GET['id_dmc'];
        $resultado = $this->evaluacionModel->eliminar($id);
        if ($resultado === true) {
            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Evaluación eliminada'];
        } else {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => $resultado];
        }
        header("Location: ../views/dashboard/docente.php?id_dmc=" . $id_dmc);
        exit;
    }
}

$controller = new DocenteEvaluacionController();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear': $controller->crear(); break;
    case 'editar': $controller->editar(); break;
    case 'eliminar': $controller->eliminar(); break;
}
?>
