<?php
class Menu extends Conectar {

    /* TODO: Listar registros por código */
    public function getMenuPorRol($rol_id) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_MENU_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $rol_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_menu_habilitar($id_detmenu) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_MENU_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_detmenu);
        $query->execute();
        //return $query->fetchAll(PDO::FETCH_ASSOC);
        return $query->rowCount();
    }

    public function update_menu_deshabilitar($id_detmenu) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_MENU_02 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_detmenu);
        $query->execute();
        //return $query->fetchAll(PDO::FETCH_ASSOC);
        return $query->rowCount();
    }

    /* TODO: Listar todos */
    public function getMenuTodos() {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_ROL_TODOS";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>