<?php
require_once("../config/conexion.php");
require_once("../models/Rol.php");

$rol = new Rol();

switch ($_GET["op"]) {

    /* ===========================================
    TODO: LISTAR TODOS LOS ROLES (para selects)
    =========================================== */
    case "listar_todos":
        try {
            $datos = $rol->getRolesTodos();
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: GUARDAR O EDITAR ROL
    =========================================== */
    case "guardaryeditar":
        try {
            if (empty($_POST["rol_id"])) {
                $rol->insertRol($_POST["rol_nom"], $_POST["estado"]);
            } else {
                $rol->updateRol($_POST["rol_id"], $_POST["rol_nom"], $_POST["estado"]);
            }
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: LISTAR ROLES POR ESTADO (para DataTable)
    =========================================== */
    case "listar":
        try {
            $estado = $_POST["estado"];
            $datos = $rol->getRolPorEstado($estado);
            $data = [];
            foreach ($datos as $row) {
                $sub_array = [];
                $sub_array[] = $row["rol_nom"];
                $sub_array[] = $row["estado"] == 1 ? 'Activo' : 'Inactivo';
                $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["rol_id"] . ")'>Editar</button>";
                $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["rol_id"] . ")'>Eliminar</button>";
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

    /* ===========================================
    TODO: MOSTRAR ROL POR ID
    =========================================== */
    case "mostrar":
        try {
            $datos = $rol->getRolPorId($_POST["rol_id"]);
            if (is_array($datos) && count($datos) > 0) {
                $output = [];
                foreach ($datos as $row) {
                    $output = [
                        "rol_id" => $row["rol_id"],
                        "rol_nom" => $row["rol_nom"],
                        "estado" => $row["estado"]
                    ];
                }
                echo json_encode($output);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el rol"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: ELIMINAR ROL
    =========================================== */
    case "eliminar":
        try {
            $rol->deleteRol($_POST["rol_id"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;
}
?>