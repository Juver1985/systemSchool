<?php
class Asignacion {
    private $conn;
    private $tabla = "docente_materia_curso";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodas() {
        $sql = "SELECT dmc.*, u.nombres, u.apellidos, m.nombre as materia_nombre, c.nombre as curso_nombre
                FROM " . $this->tabla . " dmc
                JOIN usuarios u ON dmc.id_docente_usuario = u.id_usuario
                JOIN materias m ON dmc.id_materia = m.id_materia
                JOIN cursos c ON dmc.id_curso = c.id_curso
                ORDER BY dmc.anio DESC, u.apellidos ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function asignar($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (id_docente_usuario, id_materia, id_curso, anio) 
                    VALUES (:id_docente, :id_materia, :id_curso, :anio)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_docente", $datos['id_docente_usuario']);
            $stmt->bindParam(":id_materia", $datos['id_materia']);
            $stmt->bindParam(":id_curso", $datos['id_curso']);
            $stmt->bindParam(":anio", $datos['anio']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
