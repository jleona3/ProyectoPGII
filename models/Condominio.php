<?php
class Condominio extends Conectar {

    /* ===========================================
    TODO: OBTENER TODOS LOS APARTAMENTOS (para selects)
    =========================================== */
    public function getApartamentosTodos() {
        $conectar = parent::Conexion();
        $sql = "SELECT ID_APTO, NUM_TORRE, NIVEL, NUM_APTO FROM TRZ6_CONDOMINIO";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: OBTENER APARTAMENTO POR ID
    =========================================== */
    public function getCondominioId($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CONDOMINIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: LISTAR TODOS LOS APARTAMENTOS
    =========================================== */
    public function getCondominioTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CONDOMINIO_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: ELIMINAR APARTAMENTO
    =========================================== */
    public function deleteCondominio($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CONDOMINIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto, PDO::PARAM_INT);
        $query->execute();
    }

    /* ===========================================
    TODO: INSERTAR APARTAMENTO
    =========================================== */
    public function insertCondominio($num_torre, $nivel, $num_apto, $metros_m2, $id_estado, $creado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CONDOMINIO_01 ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $num_torre, PDO::PARAM_INT);
        $query->bindValue(2, $nivel, PDO::PARAM_INT);
        $query->bindValue(3, $num_apto, PDO::PARAM_INT);
        $query->bindValue(4, $metros_m2, PDO::PARAM_INT);
        $query->bindValue(5, $id_estado, PDO::PARAM_INT);
        $query->bindValue(6, $creado_por);
        $query->execute();
    }

    /* ===========================================
    TODO: ACTUALIZAR APARTAMENTO
    =========================================== */
    public function updateCondominio($id_apto, $num_torre, $nivel, $num_apto, $metros_m2, $id_estado, $modificado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CONDOMINIO_01 ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto, PDO::PARAM_INT);
        $query->bindValue(2, $num_torre, PDO::PARAM_INT);
        $query->bindValue(3, $nivel, PDO::PARAM_INT);
        $query->bindValue(4, $num_apto, PDO::PARAM_INT);
        $query->bindValue(5, $metros_m2, PDO::PARAM_INT);
        $query->bindValue(6, $id_estado, PDO::PARAM_INT);
        $query->bindValue(7, $modificado_por);
        $query->execute();
    }
}
?>