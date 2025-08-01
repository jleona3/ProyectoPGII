<?php
require_once("../config/conexion.php");
require_once("../models/Condominio.php");

$condominio = new Condominio();

switch ($_GET["op"]) {

    /* ===========================================
    TODO: LISTAR APARTAMENTOS (para selects)
    =========================================== */
    case "listar_todos_simple":
        try {
            $datos = $condominio->getApartamentosTodos();
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: GUARDAR O EDITAR APARTAMENTO
    =========================================== */
    case "guardaryeditar":
        try {
            if (empty($_POST["id_apto"])) {
                $condominio->insertCondominio(
                    $_POST["num_torre"],
                    $_POST["nivel"],
                    $_POST["num_apto"],
                    $_POST["metros_m2"],
                    $_POST["id_estado"],
                    $_POST["creado_por"]
                );
            } else {
                $condominio->updateCondominio(
                    $_POST["id_apto"],
                    $_POST["num_torre"],
                    $_POST["nivel"],
                    $_POST["num_apto"],
                    $_POST["metros_m2"],
                    $_POST["id_estado"],
                    $_SESSION["NOMBRES"]
                );
            }
            echo json_encode(["status" => "success"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: LISTAR APARTAMENTOS (para DataTable)
    =========================================== */
    case "listar":
        $datos = $condominio->getCondominioTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ID_APTO"];
            $sub_array[] = $row["NUM_TORRE"];
            $sub_array[] = $row["NIVEL"];
            $sub_array[] = $row["NUM_APTO"];
            $sub_array[] = $row["METROS_M2"];
            $color = "secondary";
            switch (strtolower($row["NOMBRE_ESTADO"])) {
                case "disponible": $color = "success"; break;
                case "ocupado": $color = "danger"; break;
                case "mantenimiento": $color = "warning"; break;
            }
            $sub_array[] = '<span class="badge bg-'.$color.'">'.$row["NOMBRE_ESTADO"].'</span>';
            $sub_array[] = $row["FE_CREACION"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"];
            $sub_array[] = $row["FE_MODIFICACION"];
            $sub_array[] = '<button type="button" onClick="editar('.$row["ID_APTO"].')" class="btn btn-warning btn-icon"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ID_APTO"].')" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>';
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

    /* ===========================================
    TODO: OBTENER APARTAMENTO POR ID
    =========================================== */
    case "mostrarID":
        try {
            $datos = $condominio->getCondominioId($_POST["id_apto"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode($datos);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el registro"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
    TODO: ELIMINAR APARTAMENTO
    =========================================== */
    case "eliminar":
        try {
            $condominio->deleteCondominio($_POST["id_apto"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;
}
?>