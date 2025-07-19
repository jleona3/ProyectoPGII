<?php
class Condominio extends Conectar {

    /* TODO: Listar registros por ID_ESTADO */
    public function getCondominiosPorEstado($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CONDOMINIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener un condominio por ID_APTO */
    public function getCondominioPorId($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CONDOMINIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar condominio por ID_APTO */
    public function deleteCondominio($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CONDOMINIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->execute();
    }

    /* TODO: Insertar nuevo condominio */
    public function insertCondominio($num_torre, $nivel, $num_apto, $metros_m2, $id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CONDOMINIO_01 ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $num_torre);
        $query->bindValue(2, $nivel);
        $query->bindValue(3, $num_apto);
        $query->bindValue(4, $metros_m2);
        $query->bindValue(5, $id_estado);
        $query->execute();
    }

    /* TODO: Actualizar condominio por ID_APTO */
    public function updateCondominio($id_apto, $num_torre, $nivel, $num_apto, $metros_m2, $id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CONDOMINIO_01 ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->bindValue(2, $num_torre);
        $query->bindValue(3, $nivel);
        $query->bindValue(4, $num_apto);
        $query->bindValue(5, $metros_m2);
        $query->bindValue(6, $id_estado);
        $query->execute();
    }
}
?>
