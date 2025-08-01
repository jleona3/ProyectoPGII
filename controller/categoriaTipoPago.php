<?php
/* TODO: Llamando Clases */
require_once("../config/conexion.php");
require_once("../models/CategoriaTipoPago.php");

/* TODO: Inicializando Clase */
$categoriaTipoPago = new CategoriaTipoPago();

switch ($_GET["op"]) {

    /* ===========================================
       TODO: GUARDAR O EDITAR
       =========================================== */
    case "guardaryeditar":
        try {
            if (empty($_POST["id_tipo_pago"])) {
                // Validar duplicidad
                if ($categoriaTipoPago->existeNombre($_POST["nombre_tipopago"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del tipo de pago ya existe."]);
                    exit();
                }
                $categoriaTipoPago->insertCategoriaTipoPago($_POST["creado_por"], $_POST["nombre_tipopago"]);
            } else {
                if ($categoriaTipoPago->existeNombre($_POST["nombre_tipopago"], $_POST["id_tipo_pago"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del tipo de pago ya existe."]);
                    exit();
                }
                $categoriaTipoPago->updateCategoriaTipoPago(
                    $_POST["id_tipo_pago"],
                    $_POST["nombre_tipopago"],
                    $_POST["modificado_por"]
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
        $datos = $categoriaTipoPago->getCategoriaTipoPagoTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ID_TIPO_PAGO"];
            $sub_array[] = $row["NOMBRE_TIPOPAGO"];
            $sub_array[] = $row["FE_CREACION"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"];
            $sub_array[] = $row["FE_MODIFICACION"];
            $sub_array[] = '<button type="button" onClick="editar('.$row["ID_TIPO_PAGO"].')" id="'.$row["ID_TIPO_PAGO"].'" class="btn btn-warning btn-icon"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ID_TIPO_PAGO"].')" id="'.$row["ID_TIPO_PAGO"].'" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>';
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
       TODO: MOSTRAR INFORMACIÓN PARA EDICIÓN
       =========================================== */
    case "mostrarID":
        try {
            $datos = $categoriaTipoPago->getCategoriaTipoPagoId($_POST["id_tipo_pago"]);
            if (is_array($datos) && count($datos) > 0) {
                $output = [
                    "ID_TIPO_PAGO" => $datos["ID_TIPO_PAGO"],
                    "NOMBRE_TIPOPAGO" => $datos["NOMBRE_TIPOPAGO"],
                    "CREADO_POR" => $datos["CREADO_POR"],
                    "MODIFICADO_POR" => $datos["MODIFICADO_POR"],
                    "FE_MODIFICACION" => $datos["FE_MODIFICACION"]
                ];
                echo json_encode($output);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el tipo de pago"]);
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
            $categoriaTipoPago->deleteCategoriaTipoPago($_POST["id_tipo_pago"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;
}
?>