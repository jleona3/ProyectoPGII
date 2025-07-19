<?php
class Rol extends Conectar {

    /* TODO: Listar roles por estado */
    public function getRolPorEstado($estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener rol por ID */
    public function getRolPorId($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar rol por ID */
    public function deleteRol($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_ROL_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
    }

    /* TODO: Insertar nuevo rol */
    public function insertRol($rol_nom, $estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_ROL_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_nom);
        $query->bindValue(2, $estado);
        $query->execute();
    }

    /* TODO: Actualizar rol existente */
    public function updateRol($rol_id, $rol_nom, $estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_ROL_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->bindValue(2, $rol_nom);
        $query->bindValue(3, $estado);
        $query->execute();
    }
}
?>
