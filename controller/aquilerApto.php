<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/AlquilerApto.php");

    /* TODO: Inicializando Clase */
    $alquiler = new Alquiler();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_alquiler_apto"])) {
                    // Guardar
                    $alquiler->insertAlquiler($_POST["id_user"], $_POST["fe_inicio"], $_POST["fe_final"], 
                        $_POST["nom_arrendatario"], $_POST["ape_arrendatario"], $_POST["monto"], 
                        $_POST["dpi_arrendatario"], $_POST["foto_dpi"], $_POST["id_estado"], $_POST["observaciones"]);
                } else {
                    // Editar
                    $alquiler->updateAlquiler($_POST["id_alquiler_apto"], $_POST["id_user"], $_POST["fe_inicio"], $_POST["fe_final"], 
                        $_POST["nom_arrendatario"], $_POST["ape_arrendatario"], $_POST["monto"], $_POST["dpi_arrendatario"], 
                        $_POST["foto_dpi"], $_POST["id_estado"], $_POST["observaciones"]);
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listado de Registro según Alquiler Apto, formato JSON para DataTable JS */
        case "listar":
            try {
                // Asegúrarse recibir ambos parámetros correctamente
                $id_user = $_POST["id_user"];
                $id_estado = $_POST["id_estado"];

                // Llamar al método con los dos parámetros
                $datos = $alquiler->getAlquilerPorUsuarioEstado($id_user, $id_estado);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["nom_arrendatario"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_alquiler_apto"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_alquiler_apto"] . ")'>Eliminar</button>";
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
                $datos = $alquiler->getAlquilerPorId($_POST["id_alquiler_apto"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_alquiler_apto" => $row["id_alquiler_apto"],
                            "id_user" => $row["id_user"],
                            "fe_inicio" => $row["fe_inicio"],
                            "fe_final" => $row["fe_final"],
                            "nom_arrendatario" => $row["nom_arrendatario"],
                            "ape_arrendatario" => $row["ape_arrendatario"],
                            "monto" => $row["monto"],
                            "dpi_arrendatario" => $row["dpi_arrendatario"],
                            "foto_dpi" => $row["foto_dpi"],
                            "id_estado" => $row["id_estado"],
                            "observaciones" => $row["observaciones"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el alquiler"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Cambiar estado a 0 del registro */
        case "eliminar":
            try {
                $alquiler->deleteAlquiler($_POST["id_alquiler_apto"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
