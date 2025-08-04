<?php
require_once("../config/conexion.php");
require_once("../models/Propietario.php");
$propietario = new Propietario();

switch ($_GET["op"]) {

    /* GUARDAR O EDITAR */
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
                $foto_perfil = isset($_POST["foto_actual"]) ? $_POST["foto_actual"] : "default.png";
            }

            $pass = ($_SESSION["ROL_ID"] == 1) ? (!empty($_POST["pass"]) ? $_POST["pass"] : null) : null;

            // Solo el administrador puede modificar los campos bloqueados
            $esAdmin = ($_SESSION["ROL_ID"] == 1);
            $pass = $esAdmin && !empty($_POST["pass"]) ? $_POST["pass"] : null;

            if (empty($_POST["id_user"])) {
                // Insertar
                if (!$esAdmin) {
                    echo json_encode(["status" => "error", "message" => "No tienes permisos para agregar propietarios."]);
                    return;
                }
                $propietario->insertPropietario(
                    $_POST["id_apto"], $_POST["email"], $_POST["nombres"], $_POST["apellidos"], 
                    $_POST["dpi"], $_POST["telefono"], $foto_perfil, $_POST["id_estado"], 
                    $_POST["rol_id"], $pass, $_POST["creado_por"]
                );
            } else {
                // Actualizar
                $propietario->updatePropietario(
                    $_POST["id_user"], $_POST["id_apto"], $_POST["email"], $_POST["nombres"], 
                    $_POST["apellidos"], $_POST["dpi"], $_POST["telefono"], $foto_perfil, 
                    $_POST["id_estado"], $_POST["rol_id"], $pass, $_SESSION["NOMBRES"]
                );
            }

            echo json_encode(["status" => "success"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* LISTAR */
    case "listar":
        $id_user = null;
        if ($_SESSION["ROL_ID"] != 1) { // Si NO es administrador
            $id_user = $_SESSION["ID_USER"]; // Filtramos solo su registro
        }
        $datos = $propietario->getPropietarioTodos($id_user);
        $data = array();
        foreach ($datos as $row) {
            $foto = !empty($row["FOTO_PERFIL"]) ? $row["FOTO_PERFIL"] : 'default.png';

            /* ===========================================
            VALIDACIÓN: Solo mostrar el botón eliminar si el usuario logueado es administrador
            =========================================== */
            $botonEliminar = ($_SESSION["ROL_ID"] == 1)
                ? '<button type="button" onClick="eliminar(' . $row["ID_USER"] . ')" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>': '';
            $sub_array = array(
                "ID_USER" => $row["ID_USER"],
                "FOTO_PERFIL" => '<img src="../../uploads/propietarios/'.$foto.'" width="40" height="40" class="rounded-circle">',
                "NOMBRE_COMPLETO" => $row["NOMBRES"]." ".$row["APELLIDOS"],
                "DPI" => $row["DPI"],
                "EMAIL" => $row["EMAIL"],
                "TELEFONO" => $row["TELEFONO"],
                "NUM_TORRE" => $row["NUM_TORRE"],
                "NIVEL" => $row["NIVEL"],
                "NUM_APTO" => !empty($row["NUM_APTO"]) ? $row["NUM_APTO"] : 'N/A',
                "ROL_NOMBRE" => !empty($row["ROL_NOM"]) ? $row["ROL_NOM"] : 'Sin Rol',
                "NOMBRE_ESTADO" => '<span class="badge bg-'.((!empty($row["ESTADO_DESC"]) && $row["ESTADO_DESC"] == "Activo") ? "success" : "danger").'">'.(!empty($row["ESTADO_DESC"]) ? $row["ESTADO_DESC"] : "Desconocido").'</span>',
                "FE_CREACION" => !empty($row["FE_CREACION"]) ? date("d-m-Y h:i A", strtotime($row["FE_CREACION"])) : 'N/A',
                "CREADO_POR" => !empty($row["CREADO_POR"]) ? $row["CREADO_POR"] : 'N/A',
                "MODIFICADO_POR" => !empty($row["MODIFICADO_POR"]) ? $row["MODIFICADO_POR"] : 'N/A',
                "FE_MODIFICACION" => !empty($row["FE_MODIFICACION"]) ? date("d-m-Y h:i A", strtotime($row["FE_MODIFICACION"])) : 'N/A',
                "EDITAR" => '<button type="button" onClick="editar('.$row["ID_USER"].')" class="btn btn-warning btn-icon"><i class="ri-edit-2-line"></i></button>',
                "ELIMINAR" => ($_SESSION["ROL_ID"] == 1) ? '<button type="button" onClick="eliminar('.$row["ID_USER"].')" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>' : ''
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
        break;

    /* MOSTRAR POR ID */
    case "mostrarID":
        try {
            $id_user = $_POST["id_user"];

            // Si no es admin, solo puede ver su propio registro
            if ($_SESSION["ROL_ID"] != 1 && $_SESSION["ID_USER"] != $id_user) {
                echo json_encode(["status" => "error", "message" => "No tienes permisos para ver este registro."]);
                exit();
            }

            $datos = $propietario->getPropietarioId($id_user);
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
    ELIMINAR
    =========================================== */
    case "eliminar":
        if ($_SESSION["ROL_ID"] != 1) {
            echo json_encode(["status" => "error", "message" => "No tienes permisos para eliminar."]);
            return;
        }
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
            $datos = $propietario->login($email, $pass);

            if (is_array($datos) && count($datos) > 0) {
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