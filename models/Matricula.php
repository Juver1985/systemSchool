<?php
class Matricula {
    private $conn;
    private $tabla = "matriculas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodas() {
        $sql = "SELECT m.*, u.nombres, u.apellidos, c.nombre as curso_nombre, c.anio
                FROM " . $this->tabla . " m
                JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                JOIN usuarios u ON e.id_usuario = u.id_usuario
                JOIN cursos c ON m.id_curso = c.id_curso
                ORDER BY c.anio DESC, u.apellidos ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function matricular($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (id_estudiante, id_curso, fecha_matricula, estado) 
                    VALUES (:id_estudiante, :id_curso, CURDATE(), 'ACTIVA')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_estudiante", $datos['id_estudiante']);
            $stmt->bindParam(":id_curso", $datos['id_curso']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET estado = :estado WHERE id_matricula = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_matricula = :id";
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
