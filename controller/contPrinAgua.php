<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/ContPrinAgua.php");

    /* TODO: Inicializando Clase */
    $contPrinAgua = new ContPrinAgua();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_cont_prin_agua"])) {
                    // Guardar
                    $contPrinAgua->insertContPrinAgua(
                        $_POST["id_proveedor"], $_POST["id_servicio"], $_POST["fecha_ingreso"], $_POST["numero_boleta"], 
                        $_POST["monto"], $_POST["lectura_inicial"], $_POST["lectura_final"], $_POST["inventario_final_m3"], 
                        $_POST["observaciones"], $_POST["foto_boleta"]
                    );
                } else {
                    // Editar
                    $contPrinAgua->updateContPrinAgua(
                        $_POST["id_cont_prin_agua"], $_POST["id_proveedor"], $_POST["id_servicio"], $_POST["fecha_ingreso"], 
                        $_POST["numero_boleta"], $_POST["monto"], $_POST["lectura_inicial"], $_POST["lectura_final"], 
                        $_POST["inventario_final_m3"], $_POST["observaciones"], $_POST["foto_boleta"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar registros por ID_SERVICIO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_servicio = $_POST["id_servicio"];
                $datos = $contPrinAgua->getContPrinAguaByServicio($id_servicio);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["fecha_ingreso"];
                    $sub_array[] = $row["numero_boleta"];
                    $sub_array[] = $row["monto"];
                    $sub_array[] = $row["lectura_inicial"];
                    $sub_array[] = $row["lectura_final"];
                    $sub_array[] = $row["inventario_final_m3"];
                    $sub_array[] = $row["observaciones"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_cont_prin_agua"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_cont_prin_agua"] . ")'>Eliminar</button>";
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
                $datos = $contPrinAgua->getContPrinAguaById($_POST["id_cont_prin_agua"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_cont_prin_agua" => $row["id_cont_prin_agua"],
                            "id_proveedor" => $row["id_proveedor"],
                            "id_servicio" => $row["id_servicio"],
                            "fecha_ingreso" => $row["fecha_ingreso"],
                            "numero_boleta" => $row["numero_boleta"],
                            "monto" => $row["monto"],
                            "lectura_inicial" => $row["lectura_inicial"],
                            "lectura_final" => $row["lectura_final"],
                            "inventario_final_m3" => $row["inventario_final_m3"],
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
                $contPrinAgua->deleteContPrinAgua($_POST["id_cont_prin_agua"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
