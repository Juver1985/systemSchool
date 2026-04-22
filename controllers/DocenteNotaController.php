<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Nota.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class DocenteNotaController {
    private $notaModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->notaModel = new Nota($db);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_evaluacion = $_POST['id_evaluacion'];
            $notas = $_POST['notas'] ?? []; // Array: [id_matricula => valor]
            $observaciones = $_POST['observaciones'] ?? [];

            $exito = true;
            $error = "";

            foreach ($notas as $id_matricula => $valor) {
                if ($valor !== "") {
                    $obs = $observaciones[$id_matricula] ?? "";
                    $res = $this->notaModel->guardarNota($id_evaluacion, $id_matricula, $valor, $obs);
                    if ($res !== true) {
                        $exito = false;
                        $error = $res;
                    }
                }
            }

            if ($exito) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Notas guardadas correctamente'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => $error];
            }
            
            header("Location: ../views/dashboard/docente_notas.php?id_evaluacion=" . $id_evaluacion);
            exit;
        }
    }
}

$controller = new DocenteNotaController();
$accion = $_GET['accion'] ?? '';

if ($accion === 'guardar') {
    $controller->guardar();
}
?>
