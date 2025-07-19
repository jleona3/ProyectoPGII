<?php
class CategoriaTipoPago extends Conectar {

    /* TODO: Listar registros por código */
    public function getCategoriaTipoPagoCod($codigo) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID */
    public function getCategoriaTipoPagoId($id_tipo_pago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID */
    public function deleteCategoriaTipoPago($id_tipo_pago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function insertCategoriaTipoPago($codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_TIPO_PAGO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $codigo);
        $query->bindValue(2, $descripcion);
        $query->execute();
    }

    /* ✅ Actualizar registro por ID */
    public function updateCategoriaTipoPago($id_tipo_pago, $codigo, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_TIPO_PAGO_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->bindValue(2, $codigo);
        $query->bindValue(3, $descripcion);
        $query->execute();
    }
}
?>
