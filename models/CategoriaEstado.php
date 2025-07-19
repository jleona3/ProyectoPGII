<?php
class CategoriaEstado extends Conectar {

    /* TODO: Listar registros por código */
    public function getCategoriaEstadoCod($codigo) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID */
    public function getCategoriaEstadoId($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID */
    public function deleteCategoriaEstado($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function insertCategoriaEstado($codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_ESTADO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->bindValue(2, $descripcion);
        $query->execute();
    }

    /* ✅ Actualizar registro por ID */
    public function updateCategoriaEstado($id_estado, $codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_ESTADO_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->bindValue(2, $codigo);
        $query->bindValue(3, $descripcion);
        $query->execute();
    }
}
?>
