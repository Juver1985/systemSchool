<?php
class Evaluacion {
    private $conn;
    private $tabla = "evaluaciones";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerPorAsignacion($id_dmc) {
        $sql = "SELECT e.*, p.nombre as periodo_nombre 
                FROM " . $this->tabla . " e
                JOIN periodos p ON e.id_periodo = p.id_periodo
                WHERE e.id_docente_materia_curso = :id_dmc
                ORDER BY e.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_dmc", $id_dmc);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id_evaluacion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO " . $this->tabla . " 
                    (id_docente_materia_curso, id_periodo, titulo, tipo, fecha, porcentaje) 
                    VALUES (:id_dmc, :id_periodo, :titulo, :tipo, :fecha, :porcentaje)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_dmc", $datos['id_dmc']);
            $stmt->bindParam(":id_periodo", $datos['id_periodo']);
            $stmt->bindParam(":titulo", $datos['titulo']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":porcentaje", $datos['porcentaje']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE " . $this->tabla . " 
                    SET id_periodo = :id_periodo, titulo = :titulo, tipo = :tipo, 
                        fecha = :fecha, porcentaje = :porcentaje 
                    WHERE id_evaluacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_periodo", $datos['id_periodo']);
            $stmt->bindParam(":titulo", $datos['titulo']);
            $stmt->bindParam(":tipo", $datos['tipo']);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":porcentaje", $datos['porcentaje']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_evaluacion = :id";
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
