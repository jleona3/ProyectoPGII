<?php
class Usuario extends Conectar {

    /* TODO: 1. Listar usuarios por estado */
    public function getUsuariosPorEstado($id_estado) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_L_USUARIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_estado);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: 2. Obtener usuario por ID */
    public function getUsuarioPorId($id_user) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_O_USUARIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /* TODO: 3. Eliminar usuario por ID */
    public function deleteUsuario($id_user) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_D_USUARIO_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id_user);
        $query->execute();
    }

    /* TODO: 4. Insertar nuevo usuario */
    public function insertUsuario(
        $id_apto,
        $email,
        $pass,
        $nombres,
        $apellidos,
        $dpi,
        $telefono,        
        $foto_perfil,
        $id_estado,
        $rol_id
    ) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_I_USUARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1,  $id_apto);
        $query->bindValue(2,  $email);
        $query->bindValue(3,  $pass);
        $query->bindValue(4,  $nombres);
        $query->bindValue(5,  $apellidos);
        $query->bindValue(6,  $dpi);
        $query->bindValue(7,  $telefono);        
        $query->bindValue(8,  $foto_perfil,   PDO::PARAM_LOB);
        $query->bindValue(9, $id_estado);
        $query->bindValue(10, $rol_id);
        $query->execute();
    }

    /* TODO: 5. Actualizar usuario por ID */
    public function updateUsuario(
        $id_user,
        $id_apto,
        $email,
        $pass,
        $nombres,
        $apellidos,
        $dpi,
        $telefono,        
        $foto_perfil,
        $id_estado,
        $rol_id
    ) {
        $conectar = parent::Conexion();
        $sql = "EXEC SP_U_USUARIO_01 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1,  $id_user);
        $query->bindValue(2,  $id_apto);
        $query->bindValue(3,  $email);
        $query->bindValue(4,  $pass);
        $query->bindValue(5,  $nombres);
        $query->bindValue(6,  $apellidos);
        $query->bindValue(7,  $dpi);
        $query->bindValue(8,  $telefono);        
        $query->bindValue(9,  $foto_perfil,   PDO::PARAM_LOB);
        $query->bindValue(10, $id_estado);
        $query->bindValue(11, $rol_id);
        $query->execute();
    }

    /* TODO: 6. Acceso al Sistema */
    public function login() {
        $conectar = parent::Conexion();

        if (isset($_POST["enviar"])) {
            $correo = $_POST["email"];
            $pass = $_POST["pass"];

            if (empty($correo) || empty($pass)) {
                exit();
            } else {
                $sql = "EXEC SP_L_USUARIO_03 ?, ?";
                $query = $conectar->prepare($sql);
                $query->bindValue(1, $correo);
                $query->bindValue(2, $pass);
                $query->execute();

                $resultado = $query->fetch();
                if (is_array($resultado) && count($resultado) > 0) {
                    $_SESSION["ID_USER"] = $resultado["ID_USER"];
                    $_SESSION["NOMBRES"] = $resultado["NOMBRES"];
                    $_SESSION["APELLIDOS"] = $resultado["APELLIDOS"];
                    $_SESSION["EMAIL"] = $resultado["EMAIL"];

                    // ✅ Redirección a la vista home
                    header("Location:" . Conectar::ruta() . "view/home/");
                    exit();
                } else {
                    echo "<script>alert('Correo o contraseña incorrectos');</script>";
                }
            }
        }
    }
}
?>