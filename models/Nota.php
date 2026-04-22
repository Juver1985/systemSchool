<?php
class Nota {
    private $conn;
    private $tabla = "notas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerEstudiantesPorEvaluacion($id_evaluacion) {
        $sql = "SELECT e.id_estudiante, m.id_matricula, u.nombres, u.apellidos, n.valor, n.observacion, n.id_nota
                FROM evaluaciones ev
                JOIN docente_materia_curso dmc ON ev.id_docente_materia_curso = dmc.id
                JOIN matriculas m ON dmc.id_curso = m.id_curso
                JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                JOIN usuarios u ON e.id_usuario = u.id_usuario
                LEFT JOIN notas n ON n.id_evaluacion = ev.id_evaluacion AND n.id_matricula = m.id_matricula
                WHERE ev.id_evaluacion = :id_evaluacion
                ORDER BY u.apellidos ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_evaluacion", $id_evaluacion);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarNota($id_evaluacion, $id_matricula, $valor, $observacion) {
        try {
            // Verificar si ya existe
            $sqlCheck = "SELECT id_nota FROM " . $this->tabla . " 
                         WHERE id_evaluacion = :id_eval AND id_matricula = :id_mat";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->bindParam(":id_eval", $id_evaluacion);
            $stmtCheck->bindParam(":id_mat", $id_matricula);
            $stmtCheck->execute();
            $existente = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existente) {
                $sql = "UPDATE " . $this->tabla . " SET valor = :valor, observacion = :obs 
                        WHERE id_nota = :id_nota";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":id_nota", $existente['id_nota']);
            } else {
                $sql = "INSERT INTO " . $this->tabla . " (id_evaluacion, id_matricula, valor, observacion) 
                        VALUES (:id_eval, :id_mat, :valor, :obs)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":id_eval", $id_evaluacion);
                $stmt->bindParam(":id_mat", $id_matricula);
            }
            
            $stmt->bindParam(":valor", $valor);
            $stmt->bindParam(":obs", $observacion);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
