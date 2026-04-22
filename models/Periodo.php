<?php
class Periodo {
    private $conn;
    private $tabla = "periodos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY anio DESC, nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id_periodo = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " (anio, nombre, fecha_inicio, fecha_fin, cerrado) 
                    VALUES (:anio, :nombre, :inicio, :fin, 0)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":anio", $datos['anio']);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":inicio", $datos['fecha_inicio']);
            $stmt->bindParam(":fin", $datos['fecha_fin']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET anio = :anio, nombre = :nombre, 
                    fecha_inicio = :inicio, fecha_fin = :fin WHERE id_periodo = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":anio", $datos['anio']);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":inicio", $datos['fecha_inicio']);
            $stmt->bindParam(":fin", $datos['fecha_fin']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function toggleCierre($id, $estado) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET cerrado = :estado WHERE id_periodo = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_periodo = :id";
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
