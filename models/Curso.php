<?php
class Curso {
    private $conn;
    private $tabla = "cursos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY anio DESC, grado ASC, nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id_curso = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (nombre, grado, jornada, anio) VALUES (:nombre, :grado, :jornada, :anio)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":grado", $datos['grado']);
            $stmt->bindParam(":jornada", $datos['jornada']);
            $stmt->bindParam(":anio", $datos['anio']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET nombre = :nombre, grado = :grado, jornada = :jornada, anio = :anio WHERE id_curso = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":grado", $datos['grado']);
            $stmt->bindParam(":jornada", $datos['jornada']);
            $stmt->bindParam(":anio", $datos['anio']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_curso = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function contarActivos() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->tabla . " WHERE anio = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'];
    }
}
?>
