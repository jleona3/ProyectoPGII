<?php
class Alquiler extends Conectar {

    /* TODO:  Listar registros por ID_USER e ID_ESTADO */
    public function getAlquilerPorUsuarioEstado($id_user, $id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_ALQUILER_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->bindValue(2, $id_estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO:  Obtener registro por ID_ALQUILER_APTO */
    public function getAlquilerPorId($id_alquiler_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_ALQUILER_02 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_alquiler_apto);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO:  Eliminar registro por ID */
    public function deleteAlquiler($id_alquiler_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_ALQUILER_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_alquiler_apto);
        $query->execute();
    }

    /* TODO:  Insertar nuevo registro */
    public function insertAlquiler($id_user, $fe_inicio, $fe_final, $nom_arrendatario, $ape_arrendatario, $monto, $dpi_arrendatario, $foto_dpi, $id_estado, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_ALQUILER_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->bindValue(2, $fe_inicio);
        $query->bindValue(3, $fe_final);
        $query->bindValue(4, $nom_arrendatario);
        $query->bindValue(5, $ape_arrendatario);
        $query->bindValue(6, $monto);
        $query->bindValue(7, $dpi_arrendatario);
        $query->bindValue(8, $foto_dpi, PDO::PARAM_LOB); // Foto binaria
        $query->bindValue(9, $id_estado);
        $query->bindValue(10, $observaciones);
        $query->execute();
    }

    /* TODO:  Actualizar registro */
    public function updateAlquiler($id_alquiler_apto, $id_user, $fe_inicio, $fe_final, $nom_arrendatario, $ape_arrendatario, $monto, $dpi_arrendatario, $foto_dpi, $id_estado, $observaciones) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_ALQUILER_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_alquiler_apto);
        $query->bindValue(2, $id_user);
        $query->bindValue(3, $fe_inicio);
        $query->bindValue(4, $fe_final);
        $query->bindValue(5, $nom_arrendatario);
        $query->bindValue(6, $ape_arrendatario);
        $query->bindValue(7, $monto);
        $query->bindValue(8, $dpi_arrendatario);
        $query->bindValue(9, $foto_dpi, PDO::PARAM_LOB);
        $query->bindValue(10, $id_estado);
        $query->bindValue(11, $observaciones);
        $query->execute();
    }
}
?>
