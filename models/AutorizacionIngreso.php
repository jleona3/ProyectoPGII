<?php
class AutorizacionIngreso extends Conectar {

    /* TODO: Listar registros por ID_USER */
    public function getAutorizacionesPorUsuario($id_user) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_AUTO_INGRESO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener una autorización por ID */
    public function getAutorizacionPorId($id_auto_ingreso) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_AUTO_INGRESO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_auto_ingreso);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar autorización por ID */
    public function deleteAutorizacion($id_auto_ingreso) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_AUTO_INGRESO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_auto_ingreso);
        $query->execute();
    }

    /* TODO: Insertar nueva autorización */
    public function insertAutorizacion($id_user, $nombres, $apellidos, $dpi, $fe_visita, $tot_adultos, $tot_jovenes, $tot_menores, $foto_dpi, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_AUTO_INGRESO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->bindValue(2, $nombres);
        $query->bindValue(3, $apellidos);
        $query->bindValue(4, $dpi);
        $query->bindValue(5, $fe_visita);
        $query->bindValue(6, $tot_adultos);
        $query->bindValue(7, $tot_jovenes);
        $query->bindValue(8, $tot_menores);
        $query->bindValue(9, $foto_dpi, PDO::PARAM_LOB);
        $query->bindValue(10, $observaciones);
        $query->execute();
    }

    /* TODO: Actualizar autorización */
    public function updateAutorizacion($id_auto_ingreso, $id_user, $nombres, $apellidos, $dpi, $fe_visita, $tot_adultos, $tot_jovenes, $tot_menores, $foto_dpi, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_AUTO_INGRESO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_auto_ingreso);
        $query->bindValue(2, $id_user);
        $query->bindValue(3, $nombres);
        $query->bindValue(4, $apellidos);
        $query->bindValue(5, $dpi);
        $query->bindValue(6, $fe_visita);
        $query->bindValue(7, $tot_adultos);
        $query->bindValue(8, $tot_jovenes);
        $query->bindValue(9, $tot_menores);
        $query->bindValue(10, $foto_dpi, PDO::PARAM_LOB);
        $query->bindValue(11, $observaciones);
        $query->execute();
    }
}
?>