<?php
class Propietario extends Conectar {

    /* ===========================================
    TODO: OBTENER PROPIETARIO POR ID
    =========================================== */
    public function getPropietarioId($id_user) {
        $conectar = parent::Conexion();
        $sql = "SELECT 
                    U.ID_USER,
                    U.NOMBRES,
                    U.APELLIDOS,
                    U.EMAIL,
                    U.DPI,
                    U.TELEFONO,
                    U.FOTO_PERFIL,
                    U.PASS,
                    U.ID_ESTADO,
                    U.ROL_ID,
                    U.ID_APTO,
                    C.NUM_TORRE,
                    C.NIVEL,
                    C.NUM_APTO,
                    R.ROL_NOM AS ROL_NOMBRE,
                    E.DESCRIPCION AS NOMBRE_ESTADO,
                    U.FE_CREACION,
                    U.CREADO_POR,
                    U.MODIFICADO_POR,
                    U.FE_MODIFICACION
                FROM TRZ6_USUARIO U
                INNER JOIN TRZ6_CONDOMINIO C ON U.ID_APTO = C.ID_APTO
                INNER JOIN TRZ6_ROL R ON U.ROL_ID = R.ROL_ID
                INNER JOIN TRZ6_CAT_ESTADO E ON U.ID_ESTADO = E.ID_ESTADO
                WHERE U.ID_USER = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: LISTAR TODOS LOS PROPIETARIOS
    =========================================== */
    public function getPropietarioTodos() {
        $conectar = parent::Conexion();
        $sql = "SELECT 
                    U.ID_USER,
                    U.NOMBRES,
                    U.APELLIDOS,
                    U.EMAIL,
                    U.DPI,
                    U.TELEFONO,
                    U.FOTO_PERFIL,
                    U.FE_CREACION,
                    U.CREADO_POR,
                    U.MODIFICADO_POR,
                    U.FE_MODIFICACION,
                    C.NUM_TORRE,
                    C.NIVEL,
                    C.NUM_APTO,
                    R.ROL_NOM AS ROL_NOMBRE,
                    E.DESCRIPCION AS NOMBRE_ESTADO
                FROM TRZ6_USUARIO U
                INNER JOIN TRZ6_CONDOMINIO C ON U.ID_APTO = C.ID_APTO
                INNER JOIN TRZ6_ROL R ON U.ROL_ID = R.ROL_ID
                INNER JOIN TRZ6_CAT_ESTADO E ON U.ID_ESTADO = E.ID_ESTADO
                ORDER BY U.ID_USER DESC";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================================
    TODO: ELIMINAR PROPIETARIO
    =========================================== */
    public function deletePropietario($id_user) {
        $conectar = parent::Conexion();
        $sql = "DELETE FROM TRZ6_USUARIO WHERE ID_USER = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->execute();
    }

    /* ===========================================
    TODO: INSERTAR PROPIETARIO
    =========================================== */
    public function insertPropietario($id_apto, $email, $nombres, $apellidos, $dpi, $telefono, $foto_perfil, $id_estado, $rol_id, $pass, $creado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_PROPIETARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_apto, PDO::PARAM_INT);
        $query->bindValue(2, $email);
        $query->bindValue(3, $nombres);
        $query->bindValue(4, $apellidos);
        $query->bindValue(5, $dpi);
        $query->bindValue(6, $telefono);
        $query->bindValue(7, $foto_perfil);
        $query->bindValue(8, $id_estado, PDO::PARAM_INT);
        $query->bindValue(9, $rol_id, PDO::PARAM_INT);
        $query->bindValue(10, $pass);
        $query->bindValue(11, $creado_por);
        $query->execute();
    }

    /* ===========================================
    TODO: ACTUALIZAR PROPIETARIO
    =========================================== */
    public function updatePropietario($id_user, $id_apto, $email, $nombres, $apellidos, $dpi, $telefono, $foto_perfil, $id_estado, $rol_id, $modificado_por) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_PROPIETARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user, PDO::PARAM_INT);
        $query->bindValue(2, $id_apto, PDO::PARAM_INT);
        $query->bindValue(3, $email);
        $query->bindValue(4, $nombres);
        $query->bindValue(5, $apellidos);
        $query->bindValue(6, $dpi);
        $query->bindValue(7, $telefono);
        $query->bindValue(8, $foto_perfil);
        $query->bindValue(9, $id_estado, PDO::PARAM_INT);
        $query->bindValue(10, $rol_id, PDO::PARAM_INT);
        $query->bindValue(11, $modificado_por);
        $query->execute();
    }

    /* TODO: 6. Login */
    public function login() {
        $conectar = parent::Conexion();

        if (isset($_POST["enviar"])) {
            $correo = $_POST["email"];
            $pass = $_POST["pass"];

            if (empty($correo) || empty($pass)) {
                echo "<script>alert('Debe ingresar correo y contraseña');</script>";
                return;
            }

            // Primero: autenticar usuario
            $sql = "EXEC SP_L_USUARIO_03 ?, ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $correo, PDO::PARAM_STR);
            $query->bindValue(2, $pass, PDO::PARAM_STR);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            if ($resultado && isset($resultado["ID_USER"])) {
                // Guardar datos principales
                $_SESSION["ID_USER"] = $resultado["ID_USER"];
                $_SESSION["NOMBRES"] = $resultado["NOMBRES"];
                $_SESSION["APELLIDOS"] = $resultado["APELLIDOS"];
                $_SESSION["EMAIL"] = $resultado["EMAIL"];
                $_SESSION["ROL_ID"] = $resultado["ROL_ID"];
                $_SESSION["FOTO_PERFIL"] = $resultado["FOTO_PERFIL"];

                // Segundo: consultar el nombre del rol
                $sqlRol = "SELECT ROL_NOM FROM TRZ6_ROL WHERE ROL_ID = ?";
                $queryRol = $conectar->prepare($sqlRol);
                $queryRol->bindValue(1, $resultado["ROL_ID"], PDO::PARAM_INT);
                $queryRol->execute();
                $rol = $queryRol->fetch(PDO::FETCH_ASSOC);
                $_SESSION["ROL_NOMBRE"] = $rol ? $rol["ROL_NOM"] : "Sin Rol";

                // Redirección
                header("Location:" . Conectar::ruta() . "view/home/");
                exit();
            } else {
                echo "<script>alert('Correo o contraseña incorrectos');</script>";
            }
        }
    }
}
?>