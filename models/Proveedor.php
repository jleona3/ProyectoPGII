<?php
class Proveedor extends Conectar {

    /* TODO: Listar proveedores por ID_SERVICIO */
    public function getProveedorByServicio($id_servicio) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_PROVEEDOR_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener proveedor por ID_PROVEEDOR */
    public function getProveedorById($id_proveedor) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_PROVEEDOR_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_proveedor);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar proveedor por ID_PROVEEDOR */
    public function deleteProveedor($id_proveedor) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_PROVEEDOR_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_proveedor);
        $query->execute();
    }

    /* TODO: Insertar nuevo proveedor */
    public function insertProveedor($id_servicio, $nit_proveedor, $razon_social, $telefono, $correo, $nom_contacto, $cel_contacto, $correo_contacto, $direccion, $ubicacion_mapa) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_PROVEEDOR_01 ?,?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_servicio);
        $query->bindValue(2, $nit_proveedor);
        $query->bindValue(3, $razon_social);
        $query->bindValue(4, $telefono);
        $query->bindValue(5, $correo);
        $query->bindValue(6, $nom_contacto);
        $query->bindValue(7, $cel_contacto);
        $query->bindValue(8, $correo_contacto);
        $query->bindValue(9, $direccion);
        $query->bindValue(10, $ubicacion_mapa);
        $query->execute();
    }

    /* TODO: Actualizar proveedor */
    public function updateProveedor($id_proveedor, $id_servicio, $nit_proveedor, $razon_social, $telefono, $correo, $nom_contacto, $cel_contacto, $correo_contacto, $direccion, $ubicacion_mapa) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_PROVEEDOR_01 ?,?,?,?,?,?,?,?,?,?,?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_proveedor);
        $query->bindValue(2, $id_servicio);
        $query->bindValue(3, $nit_proveedor);
        $query->bindValue(4, $razon_social);
        $query->bindValue(5, $telefono);
        $query->bindValue(6, $correo);
        $query->bindValue(7, $nom_contacto);
        $query->bindValue(8, $cel_contacto);
        $query->bindValue(9, $correo_contacto);
        $query->bindValue(10, $direccion);
        $query->bindValue(11, $ubicacion_mapa);
        $query->execute();
    }
}
?>
