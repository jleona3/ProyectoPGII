<?php
class Rol extends Conectar {

    /* TODO: Listar registros por código */
    public function getRolCod($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID */
    public function getRolId($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID */
    public function deleteRol($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function insertRol($creado_por, $rol_nom, $modificado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_ROL_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $creado_por);
        $query->bindValue(2, $rol_nom);
        $query->bindValue(3, $modificado_por);
        $query->execute();
    }

    /* TODO: Actualizar registro por ID con MODIFICADO_POR automático */
    public function updateRol($rol_id, $creado_por, $rol_nom, $modificado_por, $id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_ROL_01 ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->bindValue(2, $rol_nom);
        $query->bindValue(3, $modificado_por);
        $query->bindValue(4, $id_estado);
        $query->execute();
    }

    /* TODO: Listar todos */
    public function getRolTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_ROL_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Validar en el backend (PHP) si el nombre ya existe */
    public function existeROL_NOM($rol_nom, $rol_id = null) {
        $conectar = parent::Conexion();
        if ($rol_id) {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_ROL WHERE ROL_NOM = ? AND ROL_ID <> ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $rol_nom);
            $query->bindValue(2, $rol_id);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_ROL WHERE ROL_NOM = ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $rol_nom);
        }
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }
}
?>