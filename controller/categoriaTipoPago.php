<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/CategoriaTipoPago.php");

    /* TODO: Inicializando Clase */
    $categoriaTipoPago = new CategoriaTipoPago();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_tipo_pago"])) {
                    // Guardar
                    $categoriaTipoPago->insertCategoriaTipoPago($_POST["codigo"], $_POST["descripcion"]);
                } else {
                    // Editar
                    $categoriaTipoPago->updateCategoriaTipoPago($_POST["id_tipo_pago"], $_POST["codigo"], $_POST["descripcion"]);
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar registros por código, formato JSON para DataTable JS */
        case "listar":
            try {
                $codigo = $_POST["codigo"];
                $datos = $categoriaTipoPago->getCategoriaTipoPagoCod($codigo);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["codigo"];
                    $sub_array[] = $row["descripcion"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_tipo_pago"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_tipo_pago"] . ")'>Eliminar</button>";
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

        /* TODO: Mostrar información de registro según su ID */
        case "mostrar":
            try {
                $datos = $categoriaTipoPago->getCategoriaTipoPagoId($_POST["id_tipo_pago"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_tipo_pago" => $row["id_tipo_pago"],
                            "codigo" => $row["codigo"],
                            "descripcion" => $row["descripcion"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el tipo de pago"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar registro por ID */
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
