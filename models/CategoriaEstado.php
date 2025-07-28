<?php
class CategoriaEstado extends Conectar {

    /* TODO: Listar registros por código */
    public function getCategoriaEstadoCod($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID */
    public function getCategoriaEstadoId($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID */
    public function deleteCategoriaEstado($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CAT_ESTADO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function insertCategoriaEstado($id_estado, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CAT_ESTADO_01 ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->bindValue(2, $descripcion);
        $query->execute();
    }

    /* TODO: Actualizar registro por ID con MODIFICADO_POR automático */
    public function updateCategoriaEstado($id_estado, $creado_por, $descripcion) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CAT_ESTADO_01 ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->bindValue(2, $creado_por);
        $query->bindValue(3, $descripcion);
        // TODO: Se toma el usuario logueado desde la sesión como MODIFICADO_POR
        $modificado_por = isset($_SESSION["NOMBRES"]) ? $_SESSION["NOMBRES"] : "Sistema";
        $query->bindValue(4, $modificado_por);
        $query->execute();
    }

    /* TODO: Listar todos */
    public function getCategoriaEstadoTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CAT_ESTADO_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Validar en el backend (PHP) si el nombre ya existe */
    public function existeDescripcion($descripcion, $id_estado = null) {
        $conectar = parent::Conexion();
        if ($id_estado) {
            // Si es edición, excluimos el registro actual
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_ESTADO WHERE DESCRIPCION = ? AND ID_ESTADO != ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $descripcion);
            $query->bindValue(2, $id_estado);
        } else {
            // Si es nuevo, solo validamos por descripción
            $sql = "SELECT COUNT(*) AS total FROM TRZ6_CAT_ESTADO WHERE DESCRIPCION = ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $descripcion);
        }
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

}
?>
