<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/Usuario.php");

    /* TODO: Inicializando Clase */
    $usuario = new Usuario();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_user"])) {
                    // Guardar
                    $usuario->insertUsuario(
                        $_POST["id_apto"], $_POST["email"], $_POST["nombres"], $_POST["apellidos"], $_POST["dpi"], 
                        $_POST["telefono"], $_POST["password_hash"], $_POST["password_salt"], $_POST["foto_perfil"], 
                        $_POST["id_estado"], $_POST["rol_id"]
                    );
                } else {
                    // Editar
                    $usuario->updateUsuario(
                        $_POST["id_user"], $_POST["id_apto"], $_POST["email"], $_POST["nombres"], $_POST["apellidos"], 
                        $_POST["dpi"], $_POST["telefono"], $_POST["password_hash"], $_POST["password_salt"], 
                        $_POST["foto_perfil"], $_POST["id_estado"], $_POST["rol_id"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar usuarios por ID_ESTADO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_estado = $_POST["id_estado"];
                $datos = $usuario->getUsuariosPorEstado($id_estado);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["nombres"];
                    $sub_array[] = $row["apellidos"];
                    $sub_array[] = $row["email"];
                    $sub_array[] = $row["telefono"];
                    $sub_array[] = $row["dpi"];
                    $sub_array[] = $row["rol_id"];
                    $sub_array[] = $row["estado"] == 1 ? 'Activo' : 'Inactivo';
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_user"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_user"] . ")'>Eliminar</button>";
                    $data[] = $sub_array;
                }
                $results = [
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                ];
                echo json_encode($results);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Mostrar información de usuario según su ID */
        case "mostrar":
            try {
                $datos = $usuario->getUsuarioPorId($_POST["id_user"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_user" => $row["id_user"],
                            "id_apto" => $row["id_apto"],
                            "email" => $row["email"],
                            "nombres" => $row["nombres"],
                            "apellidos" => $row["apellidos"],
                            "dpi" => $row["dpi"],
                            "telefono" => $row["telefono"],
                            "password_hash" => $row["password_hash"],
                            "password_salt" => $row["password_salt"],
                            "foto_perfil" => $row["foto_perfil"],
                            "id_estado" => $row["id_estado"],
                            "rol_id" => $row["rol_id"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el usuario"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar usuario por ID */
        case "eliminar":
            try {
                $usuario->deleteUsuario($_POST["id_user"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
