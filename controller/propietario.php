<?php
require_once("../config/conexion.php");
require_once("../models/Propietario.php");

$propietario = new Propietario();

switch ($_GET["op"]) {

    /* ===========================================
    TODO: GUARDAR O EDITAR PROPIETARIO
    =========================================== */
    case "guardaryeditar":
        try {
            $foto_perfil = "";
            if (!empty($_FILES["foto_perfil"]["name"])) {
                $uploadDir = "../uploads/propietarios/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $foto_perfil = time() . "_" . basename($_FILES["foto_perfil"]["name"]);
                move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $uploadDir . $foto_perfil);
            } else {
                $foto_perfil = isset($_POST["foto_actual"]) ? $_POST["foto_actual"] : "";
            }

            if (empty($_POST["id_user"])) {
                // Insertar
                $propietario->insertPropietario(
                    $_POST["id_apto"],
                    $_POST["email"],
                    $_POST["nombres"],
                    $_POST["apellidos"],
                    $_POST["dpi"],
                    $_POST["telefono"],
                    $foto_perfil,
                    $_POST["id_estado"],
                    $_POST["rol_id"],
                    $_POST["pass"],
                    $_POST["creado_por"]
                );
            } else {
                // Actualizar
                $propietario->updatePropietario(
                    $_POST["id_user"],
                    $_POST["id_apto"],
                    $_POST["email"],
                    $_POST["nombres"],
                    $_POST["apellidos"],
                    $_POST["dpi"],
                    $_POST["telefono"],
                    $foto_perfil,
                    $_POST["id_estado"],
                    $_POST["rol_id"],
                    $_SESSION["NOMBRES"]
                );
            }
            echo json_encode(["status" => "success"]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        break;

    /* ===========================================
    TODO: LISTAR PROPIETARIOS PARA DATATABLE
    =========================================== */
    case "listar":
        try {
            $datos = $propietario->getPropietarioTodos();
            $data = array();
            foreach ($datos as $row) {
                $foto = !empty($row["FOTO_PERFIL"]) ? $row["FOTO_PERFIL"] : 'default.png';
                $sub_array = array(
                    "ID_USER" => $row["ID_USER"],
                    "FOTO_PERFIL" => '<img src="../../uploads/propietarios/'.$foto.'" width="40" height="40" class="rounded-circle">',
                    "NOMBRE_COMPLETO" => $row["NOMBRES"]." ".$row["APELLIDOS"],
                    "DPI" => $row["DPI"],
                    "EMAIL" => $row["EMAIL"],
                    "TELEFONO" => $row["TELEFONO"],
                    "NUM_TORRE" => $row["NUM_TORRE"],
                    "NIVEL" => $row["NIVEL"],
                    "NUM_APTO" => $row["NUM_APTO"],
                    "ROL_NOMBRE" => $row["ROL_NOMBRE"],
                    "NOMBRE_ESTADO" => '<span class="badge bg-'.($row["NOMBRE_ESTADO"] == "Activo" ? "success" : "danger").'">'.$row["NOMBRE_ESTADO"].'</span>',
                    "FE_CREACION" => $row["FE_CREACION"],
                    "CREADO_POR" => $row["CREADO_POR"],
                    "MODIFICADO_POR" => $row["MODIFICADO_POR"],
                    "FE_MODIFICACION" => $row["FE_MODIFICACION"],
                    "EDITAR" => '<button type="button" onClick="editar('.$row["ID_USER"].')" class="btn btn-warning btn-icon"><i class="ri-edit-2-line"></i></button>',
                    "ELIMINAR" => '<button type="button" onClick="eliminar('.$row["ID_USER"].')" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>'
                );
                $data[] = $sub_array;
            }
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: OBTENER PROPIETARIO POR ID
    =========================================== */
    case "mostrarID":
        try {
            $datos = $propietario->getPropietarioId($_POST["id_user"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode($datos);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el propietario"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: ELIMINAR PROPIETARIO
    =========================================== */
    case "eliminar":
        try {
            $propietario->deletePropietario($_POST["id_user"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;
    
    case "login":
        try {
            $email = $_POST["email"];
            $pass = $_POST["pass"];

            // Consulta para autenticar usuario
            $datos = $propietario->login($email, $pass); // Nueva función en el modelo

            if (is_array($datos) && count($datos) > 0) {
                // Guardamos los datos en sesión
                $_SESSION["ID_USER"] = $datos["ID_USER"];
                $_SESSION["NOMBRES"] = $datos["NOMBRES"];
                $_SESSION["FOTO_PERFIL"] = $datos["FOTO_PERFIL"];
                $_SESSION["ROL_NOMBRE"] = $datos["ROL_NOMBRE"];

                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Usuario o contraseña incorrectos"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;
}
?>