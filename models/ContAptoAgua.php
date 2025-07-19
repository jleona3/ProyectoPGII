<?php
class ContAptoAgua extends Conectar {

    /* TODO: Listar todos los registros por ID_APTO */
    public function getContAptoAguaByApto($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CONT_APTO_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID_CONT_APTO_AGUA */
    public function getContAptoAguaById($id_cont_apto_agua) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CONT_APTO_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_apto_agua);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID_CONT_APTO_AGUA */
    public function deleteContAptoAgua($id_cont_apto_agua) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CONT_APTO_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_apto_agua);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro ID_CONT_APTO_AGUA */
    public function insertContAptoAgua($id_apto, $id_cont_prin_agua, $fe_desde, $fe_hasta, $reserva_en_cisterna_m3, $lectura_inicial, $lectura_final, $saldo_anterior, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CONT_APTO_AGUA_01 ?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->bindValue(2, $id_cont_prin_agua);
        $query->bindValue(3, $fe_desde);
        $query->bindValue(4, $fe_hasta);
        $query->bindValue(5, $reserva_en_cisterna_m3);
        $query->bindValue(6, $lectura_inicial);
        $query->bindValue(7, $lectura_final);
        $query->bindValue(8, $saldo_anterior);
        $query->bindValue(9, $observaciones);
        $query->execute();
    }

    /* TODO: Actualizar registro ID_CONT_APTO_AGUA */
    public function updateContAptoAgua($id_cont_apto_agua, $id_apto, $id_cont_prin_agua, $fe_desde, $fe_hasta, $reserva_en_cisterna_m3, $lectura_inicial, $lectura_final, $saldo_anterior, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CONT_APTO_AGUA_01 ?,?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_apto_agua);
        $query->bindValue(2, $id_apto);
        $query->bindValue(3, $id_cont_prin_agua);
        $query->bindValue(4, $fe_desde);
        $query->bindValue(5, $fe_hasta);
        $query->bindValue(6, $reserva_en_cisterna_m3);
        $query->bindValue(7, $lectura_inicial);
        $query->bindValue(8, $lectura_final);
        $query->bindValue(9, $saldo_anterior);
        $query->bindValue(10, $observaciones);
        $query->execute();
    }
}
?>
