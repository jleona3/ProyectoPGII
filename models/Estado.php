<?php
class Estado extends Conectar {

    /* ===========================================
       LISTAR TODOS LOS ESTADOS
    =========================================== */
    public function getEstadoTodos() {
        $conectar = parent::Conexion();
        $sql = "SELECT ID_ESTADO, DESCRIPCION FROM TRZ6_CAT_ESTADO ORDER BY DESCRIPCION";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
