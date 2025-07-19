<?php
class ContPrinAgua extends Conectar {

    /* TODO: Listar todos los registros por ID_SERVICIO */
    public function getContPrinAguaByServicio($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_CONT_PRIN_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro por ID_CONT_PRIN_AGUA */
    public function getContPrinAguaById($id_cont_prin_agua) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_CONT_PRIN_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_prin_agua);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por ID_CONT_PRIN_AGUA */
    public function deleteContPrinAgua($id_cont_prin_agua) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_CONT_PRIN_AGUA_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_prin_agua);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro ID_CONT_PRIN_AGUA */
    public function insertContPrinAgua($id_proveedor, $id_servicio, $fecha_ingreso, $numero_boleta, $monto, $lectura_inicial, $lectura_final, $inventario_final_m3, $observaciones, $foto_boleta) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_CONT_PRIN_AGUA_01 ?,?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_proveedor);
        $query->bindValue(2, $id_servicio);
        $query->bindValue(3, $fecha_ingreso);
        $query->bindValue(4, $numero_boleta);
        $query->bindValue(5, $monto);
        $query->bindValue(6, $lectura_inicial);
        $query->bindValue(7, $lectura_final);
        $query->bindValue(8, $inventario_final_m3);
        $query->bindValue(9, $observaciones);
        $query->bindValue(10, $foto_boleta);
        $query->execute();
    }

    /* TODO: Actualizar registro ID_CONT_PRIN_AGUA */
    public function updateContPrinAgua($id_cont_prin_agua, $id_proveedor, $id_servicio, $fecha_ingreso, $numero_boleta, $monto, $lectura_inicial, $lectura_final, $inventario_final_m3, $observaciones, $foto_boleta) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_CONT_PRIN_AGUA_01 ?,?,?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_cont_prin_agua);
        $query->bindValue(2, $id_proveedor);
        $query->bindValue(3, $id_servicio);
        $query->bindValue(4, $fecha_ingreso);
        $query->bindValue(5, $numero_boleta);
        $query->bindValue(6, $monto);
        $query->bindValue(7, $lectura_inicial);
        $query->bindValue(8, $lectura_final);
        $query->bindValue(9, $inventario_final_m3);
        $query->bindValue(10, $observaciones);
        $query->bindValue(11, $foto_boleta);
        $query->execute();
    }
}
?>