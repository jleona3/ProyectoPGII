<?php
class Incidencia extends Conectar {

    /* TODO: Listar incidencias por ID_APTO */
    public function getIncidenciasByApto($id_apto) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_INCIDENCIA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener incidencia por ID_INCIDENCIA */
    public function getIncidenciaById($id_incidencia) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_INCIDENCIA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_incidencia);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar incidencia por ID_INCIDENCIA */
    public function deleteIncidencia($id_incidencia) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_INCIDENCIA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_incidencia);
        $query->execute();
    }

    /* TODO: Insertar nueva incidencia */
    public function insertIncidencia($id_apto, $id_reportado_por, $fe_reporte, $descripcion_general, $foto_evidencia) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_INCIDENCIA_01 ?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto);
        $query->bindValue(2, $id_reportado_por);
        $query->bindValue(3, $fe_reporte);
        $query->bindValue(4, $descripcion_general);
        $query->bindValue(5, $foto_evidencia);
        $query->execute();
    }

    /* TODO: Actualizar incidencia */
    public function updateIncidencia($id_incidencia, $id_apto, $id_reportado_por, $fe_reporte, $descripcion_general, $foto_evidencia) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_INCIDENCIA_01 ?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_incidencia);
        $query->bindValue(2, $id_apto);
        $query->bindValue(3, $id_reportado_por);
        $query->bindValue(4, $fe_reporte);
        $query->bindValue(5, $descripcion_general);
        $query->bindValue(6, $foto_evidencia);
        $query->execute();
    }
}
?>
