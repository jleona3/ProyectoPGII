<?php
class CategoriaServicio extends Conectar {

    /* ===========================================
       OBTENER REGISTRO POR ID
    =========================================== */
    public function getCategoriaServicioId($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_SERVICIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* ===========================================
       LISTAR TODOS LOS REGISTROS
    =========================================== */
    public function getCategoriaServicioTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_SERVICIO_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
       ELIMINAR REGISTRO POR ID
    =========================================== */
    public function deleteCategoriaServicio($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_SERVICIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
    }

    /* ===========================================
       INSERTAR NUEVO REGISTRO
    =========================================== */
    public function insertCategoriaServicio($creado_por, $servicio, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_SERVICIO_01 ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $creado_por);
        $query->bindValue(2, $servicio);
        $query->bindValue(3, $descripcion);
        $query->execute();
    }

    /* ===========================================
       ACTUALIZAR REGISTRO EXISTENTE
    =========================================== */
    public function updateCategoriaServicio($id_servicio, $creado_por, $servicio, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_SERVICIO_01 ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->bindValue(2, $creado_por);
        $query->bindValue(3, $servicio);
        $query->bindValue(4, $descripcion);
        // Usuario logueado como MODIFICADO_POR
        $modificado_por = isset($_SESSION["NOMBRES"]) ? $_SESSION["NOMBRES"] : "Sistema";
        $query->bindValue(5, $modificado_por);
        $query->execute();
    }

    /* ===========================================
       VALIDAR DUPLICIDAD DE SERVICIO
    =========================================== */
    public function existeServicio($servicio, $id_servicio = null) {
        $conectar = parent::Conexion();
        if ($id_servicio) {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_SERVICIO WHERE SERVICIO = ? AND ID_SERVICIO != ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $servicio);
            $query->bindValue(2, $id_servicio);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_SERVICIO WHERE SERVICIO = ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $servicio);
        }
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }
}
?>