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
        $sql = "SELECT id_usuario, nombres, apellidos, email, rol, activo, created_at FROM " . $this->tabla . " ORDER BY created_at DESC";
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
            return true;
        } catch (Exception $e) {
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
            $sql = "DELETE FROM " . $this->tabla . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return "Error al eliminar: " . $e->getMessage();
        }
    }
}
?>