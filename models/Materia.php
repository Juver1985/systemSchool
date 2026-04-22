<?php
class Materia {
    private $conn;
    private $tabla = "materias";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id_materia = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (nombre, intensidad_horaria) VALUES (:nombre, :intensidad)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":intensidad", $datos['intensidad_horaria']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET nombre = :nombre, intensidad_horaria = :intensidad WHERE id_materia = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":intensidad", $datos['intensidad_horaria']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_materia = :id";
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
