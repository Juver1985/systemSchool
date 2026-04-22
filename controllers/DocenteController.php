<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Asignacion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class DocenteController {
    private $asignacionModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
        $this->asignacionModel = new Asignacion($this->db);
    }

    public function obtenerAsignaciones() {
        $id_docente = $_SESSION['usuario']['id_usuario'];
        $sql = "SELECT dmc.*, m.nombre as materia_nombre, c.nombre as curso_nombre, c.jornada
                FROM docente_materia_curso dmc
                JOIN materias m ON dmc.id_materia = m.id_materia
                JOIN cursos c ON dmc.id_curso = c.id_curso
                WHERE dmc.id_docente_usuario = :id_docente
                AND dmc.anio = YEAR(CURDATE())
                ORDER BY c.nombre ASC, m.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_docente", $id_docente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
