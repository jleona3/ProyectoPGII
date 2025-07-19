<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/Condominio.php");

    /* TODO: Inicializando Clase */
    $condominio = new Condominio();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_apto"])) {
                    // Guardar
                    $condominio->insertCondominio(
                        $_POST["num_torre"], $_POST["nivel"], $_POST["num_apto"], $_POST["metros_m2"], $_POST["id_estado"]
                    );
                } else {
                    // Editar
                    $condominio->updateCondominio(
                        $_POST["id_apto"], $_POST["num_torre"], $_POST["nivel"], $_POST["num_apto"], $_POST["metros_m2"], $_POST["id_estado"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar registros por ID_ESTADO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_estado = $_POST["id_estado"];
                $datos = $condominio->getCondominiosPorEstado($id_estado);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["num_torre"];
                    $sub_array[] = $row["nivel"];
                    $sub_array[] = $row["num_apto"];
                    $sub_array[] = $row["metros_m2"];
                    $sub_array[] = $row["estado"] == 1 ? 'Activo' : 'Inactivo';
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_apto"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_apto"] . ")'>Eliminar</button>";
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
                $datos = $condominio->getCondominioPorId($_POST["id_apto"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_apto" => $row["id_apto"],
                            "num_torre" => $row["num_torre"],
                            "nivel" => $row["nivel"],
                            "num_apto" => $row["num_apto"],
                            "metros_m2" => $row["metros_m2"],
                            "id_estado" => $row["id_estado"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el condominio"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar registro por ID */
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
