<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/PagoMantto.php");

    /* TODO: Inicializando Clase */
    $pagoMantto = new PagoMantto();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_pago_mantto"])) {
                    // Guardar
                    $pagoMantto->insertPago(
                        $_POST["id_user"], $_POST["id_tipo_pago"], $_POST["monto"], $_POST["fe_pago"], 
                        $_POST["id_estado"], $_POST["comprobante"]
                    );
                } else {
                    // Editar
                    $pagoMantto->updatePago(
                        $_POST["id_pago_mantto"], $_POST["id_user"], $_POST["id_tipo_pago"], $_POST["monto"], 
                        $_POST["fe_pago"], $_POST["id_estado"], $_POST["comprobante"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar pagos por ID_USER e ID_ESTADO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_user = $_POST["id_user"];
                $id_estado = $_POST["id_estado"];
                $datos = $pagoMantto->getPagosByUsuarioEstado($id_user, $id_estado);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["fe_pago"];
                    $sub_array[] = $row["monto"];
                    $sub_array[] = $row["comprobante"];
                    $sub_array[] = $row["estado"] == 1 ? 'Activo' : 'Inactivo';
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_pago_mantto"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_pago_mantto"] . ")'>Eliminar</button>";
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

        /* TODO: Mostrar información de pago según su ID */
        case "mostrar":
            try {
                $datos = $pagoMantto->getPagoById($_POST["id_pago_mantto"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_pago_mantto" => $row["id_pago_mantto"],
                            "id_user" => $row["id_user"],
                            "id_tipo_pago" => $row["id_tipo_pago"],
                            "monto" => $row["monto"],
                            "fe_pago" => $row["fe_pago"],
                            "id_estado" => $row["id_estado"],
                            "comprobante" => $row["comprobante"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el pago"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar pago por ID */
        case "eliminar":
            try {
                $pagoMantto->deletePago($_POST["id_pago_mantto"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
