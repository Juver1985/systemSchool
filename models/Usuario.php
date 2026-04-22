<?php
class Usuario {
    private $conn;
    private $tabla = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function existeCorreo($email) {
        $sql = "SELECT id_usuario FROM " . $this->tabla . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function obtenerPorEmail($email) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE email = :email AND activo = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($datos) {
        try {
            $this->conn->beginTransaction();

            $sqlUsuario = "INSERT INTO usuarios
                (nombres, apellidos, email, password_hash, rol, activo, created_at)
                VALUES
                (:nombres, :apellidos, :email, :password_hash, :rol, 1, NOW())";

            $stmtUsuario = $this->conn->prepare($sqlUsuario);
            $stmtUsuario->bindParam(":nombres", $datos['nombres']);
            $stmtUsuario->bindParam(":apellidos", $datos['apellidos']);
            $stmtUsuario->bindParam(":email", $datos['email']);
            $stmtUsuario->bindParam(":password_hash", $datos['password_hash']);
            $stmtUsuario->bindParam(":rol", $datos['rol']);
            $stmtUsuario->execute();

            $id_usuario = $this->conn->lastInsertId();

            if ($datos['rol'] === 'estudiante') {
                $sqlEstudiante = "INSERT INTO estudiantes
                    (id_usuario, codigo_estudiantil, fecha_nacimiento, grado_actual)
                    VALUES
                    (:id_usuario, :codigo_estudiantil, :fecha_nacimiento, :grado_actual)";

                $stmtEstudiante = $this->conn->prepare($sqlEstudiante);
                $stmtEstudiante->bindParam(":id_usuario", $id_usuario);
                $stmtEstudiante->bindParam(":codigo_estudiantil", $datos['codigo_estudiantil']);
                $stmtEstudiante->bindParam(":fecha_nacimiento", $datos['fecha_nacimiento']);
                $stmtEstudiante->bindParam(":grado_actual", $datos['grado_actual']);
                $stmtEstudiante->execute();
            }

            if ($datos['rol'] === 'acudiente') {
                $sqlAcudiente = "INSERT INTO acudientes
                    (id_usuario, telefono)
                    VALUES
                    (:id_usuario, :telefono)";

                $stmtAcudiente = $this->conn->prepare($sqlAcudiente);
                $stmtAcudiente->bindParam(":id_usuario", $id_usuario);
                $stmtAcudiente->bindParam(":telefono", $datos['telefono']);
                $stmtAcudiente->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return "Error al registrar: " . $e->getMessage();
        }
    }

    public function obtenerTodos() {
        $sql = "SELECT u.*, e.codigo_estudiantil, e.fecha_nacimiento, e.grado_actual, a.telefono 
                FROM usuarios u
                LEFT JOIN estudiantes e ON u.id_usuario = e.id_usuario
                LEFT JOIN acudientes a ON u.id_usuario = a.id_usuario
                ORDER BY u.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_usuario) {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id_usuario, $datos) {
        try {
            $this->conn->beginTransaction();

            // Actualizar tabla principal
            $sql = "UPDATE " . $this->tabla . " 
                    SET nombres = :nombres, apellidos = :apellidos, rol = :rol";
            
            if (!empty($datos['password_hash'])) {
                $sql .= ", password_hash = :password_hash";
            }
            
            $sql .= " WHERE id_usuario = :id_usuario";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombres", $datos['nombres']);
            $stmt->bindParam(":apellidos", $datos['apellidos']);
            $stmt->bindParam(":rol", $datos['rol']);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            
            if (!empty($datos['password_hash'])) {
                $stmt->bindParam(":password_hash", $datos['password_hash']);
            }
            
            $stmt->execute();

            // Si es estudiante, actualizar tabla estudiantes si existen datos
            if ($datos['rol'] === 'estudiante' && isset($datos['codigo_estudiantil'])) {
                $sqlEst = "UPDATE estudiantes SET codigo_estudiantil = :code, 
                           fecha_nacimiento = :f_nac, grado_actual = :grado 
                           WHERE id_usuario = :id_u";
                $stmtEst = $this->conn->prepare($sqlEst);
                $stmtEst->bindParam(":code", $datos['codigo_estudiantil']);
                $stmtEst->bindParam(":f_nac", $datos['fecha_nacimiento']);
                $stmtEst->bindParam(":grado", $datos['grado_actual']);
                $stmtEst->bindParam(":id_u", $id_usuario);
                $stmtEst->execute();
            }

            // Si es acudiente, actualizar tabla acudientes
            if ($datos['rol'] === 'acudiente' && isset($datos['telefono'])) {
                $sqlAcu = "UPDATE acudientes SET telefono = :tel WHERE id_usuario = :id_u";
                $stmtAcu = $this->conn->prepare($sqlAcu);
                $stmtAcu->bindParam(":tel", $datos['telefono']);
                $stmtAcu->bindParam(":id_u", $id_usuario);
                $stmtAcu->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return "Error al actualizar: " . $e->getMessage();
        }
    }

    public function cambiarEstado($id_usuario, $activo) {
        try {
            $sql = "UPDATE " . $this->tabla . " SET activo = :activo WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":activo", $activo, PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error al cambiar estado: " . $e->getMessage();
        }
    }

    public function eliminar($id_usuario) {
        try {
            $this->conn->beginTransaction();

            // 1. Caso Estudiante: Limpieza profunda
            $stmtEst = $this->conn->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = :id");
            $stmtEst->execute([':id' => $id_usuario]);
            $est = $stmtEst->fetch(PDO::FETCH_ASSOC);

            if ($est) {
                $id_e = $est['id_estudiante'];
                // Borrar notas -> matrículas -> relación acudiente -> estudiante
                $this->conn->prepare("DELETE FROM notas WHERE id_matricula IN (SELECT id_matricula FROM matriculas WHERE id_estudiante = :id)")->execute([':id' => $id_e]);
                $this->conn->prepare("DELETE FROM matriculas WHERE id_estudiante = :id")->execute([':id' => $id_e]);
                $this->conn->prepare("DELETE FROM acudiente_estudiante WHERE id_estudiante = :id")->execute([':id' => $id_e]);
                $this->conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = :id")->execute([':id' => $id_e]);
            }

            // 2. Caso Docente: Limpieza de asignaciones y evaluaciones
            $stmtDmc = $this->conn->prepare("SELECT id FROM docente_materia_curso WHERE id_docente_usuario = :id");
            $stmtDmc->execute([':id' => $id_usuario]);
            $dmcs = $stmtDmc->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dmcs as $dmc) {
                $id_dmc = $dmc['id'];
                // Borrar notas -> evaluaciones -> asignación
                $this->conn->prepare("DELETE FROM notas WHERE id_evaluacion IN (SELECT id_evaluacion FROM evaluaciones WHERE id_docente_materia_curso = :id)")->execute([':id' => $id_dmc]);
                $this->conn->prepare("DELETE FROM evaluaciones WHERE id_docente_materia_curso = :id")->execute([':id' => $id_dmc]);
                $this->conn->prepare("DELETE FROM docente_materia_curso WHERE id = :id")->execute([':id' => $id_dmc]);
            }

            // 3. Caso Acudiente
            $stmtAcu = $this->conn->prepare("SELECT id_acudiente FROM acudientes WHERE id_usuario = :id");
            $stmtAcu->execute([':id' => $id_usuario]);
            $acu = $stmtAcu->fetch(PDO::FETCH_ASSOC);
            if ($acu) {
                $this->conn->prepare("DELETE FROM acudiente_estudiante WHERE id_acudiente = :id")->execute([':id' => $acu['id_acudiente']]);
                $this->conn->prepare("DELETE FROM acudientes WHERE id_acudiente = :id")->execute([':id' => $acu['id_acudiente']]);
            }

            // 4. Finalmente borrar el usuario
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return "Error al eliminar: " . $e->getMessage();
        }
    }
}
?>