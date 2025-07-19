<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/ContAptoAgua.php");

    /* TODO: Inicializando Clase */
    $contAptoAgua = new ContAptoAgua();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_cont_apto_agua"])) {
                    // Guardar
                    $contAptoAgua->insertContAptoAgua(
                        $_POST["id_apto"], $_POST["id_cont_prin_agua"], $_POST["fe_desde"], $_POST["fe_hasta"], 
                        $_POST["reserva_en_cisterna_m3"], $_POST["lectura_inicial"], $_POST["lectura_final"], 
                        $_POST["saldo_anterior"], $_POST["observaciones"]
                    );
                } else {
                    // Editar
                    $contAptoAgua->updateContAptoAgua(
                        $_POST["id_cont_apto_agua"], $_POST["id_apto"], $_POST["id_cont_prin_agua"], $_POST["fe_desde"],
                        $_POST["fe_hasta"], $_POST["reserva_en_cisterna_m3"], $_POST["lectura_inicial"], 
                        $_POST["lectura_final"], $_POST["saldo_anterior"], $_POST["observaciones"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar registros por ID_APTO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_apto = $_POST["id_apto"];
                $datos = $contAptoAgua->getContAptoAguaByApto($id_apto);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["fe_desde"];
                    $sub_array[] = $row["fe_hasta"];
                    $sub_array[] = $row["reserva_en_cisterna_m3"];
                    $sub_array[] = $row["lectura_inicial"];
                    $sub_array[] = $row["lectura_final"];
                    $sub_array[] = $row["saldo_anterior"];
                    $sub_array[] = $row["observaciones"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_cont_apto_agua"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_cont_apto_agua"] . ")'>Eliminar</button>";
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
                $datos = $contAptoAgua->getContAptoAguaById($_POST["id_cont_apto_agua"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_cont_apto_agua" => $row["id_cont_apto_agua"],
                            "id_apto" => $row["id_apto"],
                            "id_cont_prin_agua" => $row["id_cont_prin_agua"],
                            "fe_desde" => $row["fe_desde"],
                            "fe_hasta" => $row["fe_hasta"],
                            "reserva_en_cisterna_m3" => $row["reserva_en_cisterna_m3"],
                            "lectura_inicial" => $row["lectura_inicial"],
                            "lectura_final" => $row["lectura_final"],
                            "saldo_anterior" => $row["saldo_anterior"],
                            "observaciones" => $row["observaciones"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el registro"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar registro por ID */
        case "eliminar":
            try {
                $contAptoAgua->deleteContAptoAgua($_POST["id_cont_apto_agua"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
