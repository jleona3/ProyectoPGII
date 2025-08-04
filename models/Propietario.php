<?php
class Propietario extends Conectar {

    /* ===========================================
    TODO: OBTENER PROPIETARIO POR ID
    =========================================== */
    public function getPropietarioId($id_user) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_USUARIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: LISTAR TODOS LOS PROPIETARIOS
    =========================================== */  
    public function getPropietarioTodos($id_user = null) {
        $conectar = parent::Conexion();
        if ($id_user) {
            $sql = "SELECT u.*, r.ROL_NOM, e.DESCRIPCION AS ESTADO_DESC, c.NUM_TORRE, c.NIVEL, c.NUM_APTO
                    FROM TRZ6_USUARIO u
                    INNER JOIN TRZ6_ROL r ON u.ROL_ID = r.ROL_ID
                    INNER JOIN TRZ6_CAT_ESTADO e ON u.ID_ESTADO = e.ID_ESTADO
                    LEFT JOIN TRZ6_CONDOMINIO c ON u.ID_APTO = c.ID_APTO
                    WHERE u.ID_USER = ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $id_user);
        } else {
            $sql = "SELECT u.*, r.ROL_NOM, e.DESCRIPCION AS ESTADO_DESC, c.NUM_TORRE, c.NIVEL, c.NUM_APTO
                    FROM TRZ6_USUARIO u
                    INNER JOIN TRZ6_ROL r ON u.ROL_ID = r.ROL_ID
                    INNER JOIN TRZ6_CAT_ESTADO e ON u.ID_ESTADO = e.ID_ESTADO
                    LEFT JOIN TRZ6_CONDOMINIO c ON u.ID_APTO = c.ID_APTO";
            $query = $conectar->prepare($sql);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: ELIMINAR PROPIETARIO
    =========================================== */
    public function deletePropietario($id_user) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_USUARIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->execute();
    }

    /* ===========================================
    TODO: INSERTAR PROPIETARIO
    =========================================== */
    public function insertPropietario($id_apto, $email, $nombres, $apellidos, $dpi, $telefono, $foto_perfil, $id_estado, $rol_id, $pass, $creado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_USUARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto, PDO::PARAM_INT);
        $query->bindValue(2, $email);
        $query->bindValue(3, $pass);
        $query->bindValue(4, $nombres);
        $query->bindValue(5, $apellidos);
        $query->bindValue(6, $dpi);
        $query->bindValue(7, $telefono);
        $query->bindValue(8, $foto_perfil); // solo el nombre del archivo
        $query->bindValue(9, $id_estado, PDO::PARAM_INT);
        $query->bindValue(10, $rol_id, PDO::PARAM_INT);
        $query->bindValue(11, $creado_por);
        $query->execute();
    }

    /* ===========================================
    TODO: ACTUALIZAR PROPIETARIO
    =========================================== */
    public function updatePropietario($id_user, $id_apto, $email, $nombres, $apellidos, $dpi, $telefono, $foto_perfil, $id_estado, $rol_id, $pass, $modificado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_USUARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->bindValue(2, $id_apto, PDO::PARAM_INT);
        $query->bindValue(3, $email);
        $query->bindValue(4, $pass);
        $query->bindValue(5, $nombres);
        $query->bindValue(6, $apellidos);
        $query->bindValue(7, $dpi);
        $query->bindValue(8, $telefono);
        $query->bindValue(9, $foto_perfil);
        $query->bindValue(10, $id_estado, PDO::PARAM_INT);
        $query->bindValue(11, $rol_id, PDO::PARAM_INT);
        $query->bindValue(12, $modificado_por);
        $query->execute();
    }

    /* ===========================================
    TODO: LOGIN
    =========================================== */
    public function login() {
        $conectar = parent::Conexion();

        if (isset($_POST["enviar"])) {
            $correo = $_POST["email"];
            $pass = $_POST["pass"];

            if (empty($correo) || empty($pass)) {
                echo "<script>alert('Debe ingresar correo y contraseña');</script>";
                return;
            }

            $sql = "EXEC SP_L_USUARIO_03 ?, ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $correo, PDO::PARAM_STR);
            $query->bindValue(2, $pass, PDO::PARAM_STR);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            if ($resultado && isset($resultado["ID_USER"])) {
                $_SESSION["ID_USER"] = $resultado["ID_USER"];
                $_SESSION["NOMBRES"] = $resultado["NOMBRES"];
                $_SESSION["APELLIDOS"] = $resultado["APELLIDOS"];
                $_SESSION["EMAIL"] = $resultado["EMAIL"];
                $_SESSION["ROL_ID"] = $resultado["ROL_ID"];
                $_SESSION["FOTO_PERFIL"] = $resultado["FOTO_PERFIL"];

                $sqlRol = "SELECT ROL_NOM FROM TRZ6_ROL WHERE ROL_ID = ?";
                $queryRol = $conectar->prepare($sqlRol);
                $queryRol->bindValue(1, $resultado["ROL_ID"], PDO::PARAM_INT);
                $queryRol->execute();
                $rol = $queryRol->fetch(PDO::FETCH_ASSOC);
                $_SESSION["ROL_NOMBRE"] = $rol ? $rol["ROL_NOM"] : "Sin Rol";

                header("Location:" . Conectar::ruta() . "view/home/");
                exit();
            } else {
                echo "<script>alert('Correo o contraseña incorrectos');</script>";
            }
        }
    }
}
?>