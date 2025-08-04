<?php
/* TODO: Llamando Clases */
require_once("../config/conexion.php");
require_once("../models/Rol.php");

/* TODO: Inicializando Clase */
$rol = new Rol();

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

switch ($_GET["op"]) {
    /* ===========================================
       TODO: GUARDAR O EDITAR REGISTRO
       =========================================== */
    case "guardaryeditar":
        try {
            if (empty($_POST["id_rol"])) {
                // Nuevo
                if ($rol->existeROL_NOM($_POST["rol_nom"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del rol ya existe."]);
                    exit();
                }
                $rol->insertRol(
                    $_POST["creado_por"],
                    $_POST["rol_nom"],
                    $_POST["modificado_por"]
                );
            } else {
                // Editar
                if ($rol->existeROL_NOM($_POST["rol_nom"], $_POST["id_rol"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del rol ya existe."]);
                    exit();
                }
                $rol->updateRol(
                    $_POST["id_rol"],
                    $_POST["creado_por"],
                    $_POST["rol_nom"],
                    $_POST["modificado_por"],
                    $_POST["id_estado"]
                );
            }
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       TODO: LISTAR REGISTROS PARA DATATABLE
       =========================================== */
    case "listar":
        $datos = $rol->getRolTodos();
        $data = [];
        foreach ($datos as $row) {
            $sub_array = [];
            $sub_array[] = $row["ROL_ID"];
            $sub_array[] = $row["ROL_NOM"];
            $sub_array[] = isset($row["ID_ESTADO"]) ? ($row["ID_ESTADO"] == 1 ? "Activo" : "Inactivo") : "Desconocido";
            $sub_array[] = isset($row["FE_CREA"]) ? $row["FE_CREA"] : "";
            $sub_array[] = isset($row["CREADO_POR"]) ? $row["CREADO_POR"] : "";
            $sub_array[] = isset($row["MODIFICADO_POR"]) ? $row["MODIFICADO_POR"] : "";
            $sub_array[] = isset($row["FE_MODIFICACION"]) ? $row["FE_MODIFICACION"] : "";
            $sub_array[] = '<button type="button" onClick="editar('.$row["ROL_ID"].')" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = ($_SESSION['ROL_ID'] == 1)
                ? '<button type="button" onClick="eliminar('.$row["ROL_ID"].')" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>'
                : '';
            $data[] = $sub_array;
        }
        echo json_encode([
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ]);
        break;

    /* ===========================================
       TODO: MOSTRAR INFORMACIÓN PARA EDICIÓN (por ID)
       =========================================== */
    case "mostrarID":
        try {
            $datos = $rol->getRolId($_POST["ROL_ID"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode($datos);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el rol"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       TODO: ELIMINAR REGISTRO
       =========================================== */
    case "eliminar":
        try {
            $resultado = $rol->deleteRol($_POST["rol_id"]);
            echo json_encode(["status" => "success", "message" => "Rol eliminado correctamente."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       TODO: LISTAR TODOS (OPCIONAL EXTRA)
       =========================================== */
    case "listar_todos":
        $datos = $rol->getRolTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ROL_ID"];
            $sub_array[] = $row["ROL_NOM"];
            $sub_array[] = $row["FEC_CREA"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"]; 
            $sub_array[] = $row["FE_MODIFICACION"];
            $sub_array[] = '<button type="button" onClick="editar('.$row["ROL_ID"].')" id="'.$row["ROL_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ROL_ID"].')" id="'.$row["ROL_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
            $data[] = $sub_array;
        }
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

        case "listar_combo":
            $datos = $rol->getRolTodos();
            $roles = [];
            foreach ($datos as $row) {
                $roles[] = [
                    "ROL_ID" => $row["ROL_ID"],
                    "ROL_NOM" => $row["ROL_NOM"]
                ];
            }
            echo json_encode($roles);
            break;
}
?>