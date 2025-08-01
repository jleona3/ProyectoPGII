<?php
class CategoriaTipoPago extends Conectar {

    /* ===========================================
       TODO: Listar registros por ID
       =========================================== */
    public function getCategoriaTipoPagoCod($id_tipo_pago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
       TODO: Obtener registro por ID
       =========================================== */
    public function getCategoriaTipoPagoId($id_tipo_pago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* ===========================================
       TODO: Eliminar registro
       =========================================== */
    public function deleteCategoriaTipoPago($id_tipo_pago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_TIPO_PAGO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->execute();
    }

    /* ===========================================
       TODO: Insertar nuevo registro
       =========================================== */
    public function insertCategoriaTipoPago($creado_por, $nombre_tipopago) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_TIPO_PAGO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $creado_por);
        $query->bindValue(2, $nombre_tipopago);
        $query->execute();
    }

    /* ===========================================
       TODO: Actualizar registro
       =========================================== */
    public function updateCategoriaTipoPago($id_tipo_pago, $nombre_tipopago, $modificado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_TIPO_PAGO_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_tipo_pago);
        $query->bindValue(2, $nombre_tipopago);
        $query->bindValue(3, $modificado_por);
        $query->execute();
    }

    /* ===========================================
       TODO: Listar todos los registros
       =========================================== */
    public function getCategoriaTipoPagoTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_TIPO_PAGO_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
       TODO: Validar nombre único
       =========================================== */
    public function existeNombre($nombre_tipopago, $id_tipo_pago = null) {
        $conectar = parent::Conexion();
        if ($id_tipo_pago) {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_TIPO_PAGO WHERE NOMBRE_TIPOPAGO = ? AND ID_TIPO_PAGO != ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $nombre_tipopago);
            $query->bindValue(2, $id_tipo_pago);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_TIPO_PAGO WHERE NOMBRE_TIPOPAGO = ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $nombre_tipopago);
        }
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }
}
?>