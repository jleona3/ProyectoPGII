<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/Incidencia.php");

    /* TODO: Inicializando Clase */
    $incidencia = new Incidencia();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_incidencia"])) {
                    // Guardar
                    $incidencia->insertIncidencia(
                        $_POST["id_apto"], $_POST["id_reportado_por"], $_POST["fe_reporte"], $_POST["descripcion_general"], 
                        $_POST["foto_evidencia"]
                    );
                } else {
                    // Editar
                    $incidencia->updateIncidencia(
                        $_POST["id_incidencia"], $_POST["id_apto"], $_POST["id_reportado_por"], $_POST["fe_reporte"], 
                        $_POST["descripcion_general"], $_POST["foto_evidencia"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar incidencias por ID_APTO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_apto = $_POST["id_apto"];
                $datos = $incidencia->getIncidenciasByApto($id_apto);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["fe_reporte"];
                    $sub_array[] = $row["descripcion_general"];
                    $sub_array[] = "<img src='" . $row["foto_evidencia"] . "' width='100' height='100'>"; // Muestra la foto de la evidencia
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_incidencia"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_incidencia"] . ")'>Eliminar</button>";
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

        /* TODO: Mostrar información de incidencia según su ID */
        case "mostrar":
            try {
                $datos = $incidencia->getIncidenciaById($_POST["id_incidencia"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_incidencia" => $row["id_incidencia"],
                            "id_apto" => $row["id_apto"],
                            "id_reportado_por" => $row["id_reportado_por"],
                            "fe_reporte" => $row["fe_reporte"],
                            "descripcion_general" => $row["descripcion_general"],
                            "foto_evidencia" => $row["foto_evidencia"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró la incidencia"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar incidencia por ID */
        case "eliminar":
            try {
                $incidencia->deleteIncidencia($_POST["id_incidencia"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
