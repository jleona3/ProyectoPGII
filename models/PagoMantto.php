<?php
class PagoMantto extends Conectar {

    /* TODO: Listar pagos por ID_USER e ID_ESTADO */
    public function getPagosByUsuarioEstado($id_user, $id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_PAGO_MANTTO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->bindValue(2, $id_estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener pago por ID_PAGO_MANTTO */
    public function getPagoById($id_pago_mantto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_PAGO_MANTTO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_pago_mantto);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar pago por ID_PAGO_MANTTO */
    public function deletePago($id_pago_mantto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_PAGO_MANTTO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_pago_mantto);
        $query->execute();
    }

    /* TODO: Insertar nuevo pago */
    public function insertPago($id_user, $id_tipo_pago, $monto, $fe_pago, $id_estado, $comprobante) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_PAGO_MANTTO_01 ?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->bindValue(2, $id_tipo_pago);
        $query->bindValue(3, $monto);
        $query->bindValue(4, $fe_pago);
        $query->bindValue(5, $id_estado);
        $query->bindValue(6, $comprobante);
        $query->execute();
    }

    /* TODO: Actualizar pago */
    public function updatePago($id_pago_mantto, $id_user, $id_tipo_pago, $monto, $fe_pago, $id_estado, $comprobante) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_PAGO_MANTTO_01 ?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_pago_mantto);
        $query->bindValue(2, $id_user);
        $query->bindValue(3, $id_tipo_pago);
        $query->bindValue(4, $monto);
        $query->bindValue(5, $fe_pago);
        $query->bindValue(6, $id_estado);
        $query->bindValue(7, $comprobante);
        $query->execute();
    }
}
?>
