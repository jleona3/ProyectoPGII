<?php
class CategoriaServicio extends Conectar {

    /* TODO: Listar registros por código */
    public function getCategoriaServicioCod($codigo) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_SERVICIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar registro por ID específico */
    public function getCategoriaServicioId($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_SERVICIO_01 ?"; // SP para obtener por ID
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID */
    public function deleteCategoriaServicio($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_SERVICIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function insertCategoriaServicio($codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_SERVICIO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->bindValue(2, $descripcion);
        $query->execute();
    }

    /* TODO: Actualizar datos */
    public function updateCategoriaServicio($id_servicio, $codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_SERVICIO_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->bindValue(2, $codigo);
        $query->bindValue(3, $descripcion);
        $query->execute();
    }
}
?>