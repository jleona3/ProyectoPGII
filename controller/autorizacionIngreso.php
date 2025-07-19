<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/AutorizacionIngreso.php");

    /* TODO: Inicializando Clase */
    $autorizacion = new AutorizacionIngreso();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_auto_ingreso"])) {
                    // Guardar
                    $autorizacion->insertAutorizacion(
                        $_POST["id_user"], $_POST["nombres"], $_POST["apellidos"], $_POST["dpi"], $_POST["fe_visita"],
                        $_POST["tot_adultos"], $_POST["tot_jovenes"], $_POST["tot_menores"], $_POST["foto_dpi"], $_POST["observaciones"]
                    );
                } else {
                    // Editar
                    $autorizacion->updateAutorizacion(
                        $_POST["id_auto_ingreso"], $_POST["id_user"], $_POST["nombres"], $_POST["apellidos"], $_POST["dpi"], $_POST["fe_visita"],
                        $_POST["tot_adultos"], $_POST["tot_jovenes"], $_POST["tot_menores"], $_POST["foto_dpi"], $_POST["observaciones"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar registros según ID_USER, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_user = $_POST["id_user"];
                $datos = $autorizacion->getAutorizacionesPorUsuario($id_user);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["nombres"];
                    $sub_array[] = $row["apellidos"];
                    $sub_array[] = $row["dpi"];
                    $sub_array[] = $row["fe_visita"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_auto_ingreso"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_auto_ingreso"] . ")'>Eliminar</button>";
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
                $datos = $autorizacion->getAutorizacionPorId($_POST["id_auto_ingreso"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_auto_ingreso" => $row["id_auto_ingreso"],
                            "id_user" => $row["id_user"],
                            "nombres" => $row["nombres"],
                            "apellidos" => $row["apellidos"],
                            "dpi" => $row["dpi"],
                            "fe_visita" => $row["fe_visita"],
                            "tot_adultos" => $row["tot_adultos"],
                            "tot_jovenes" => $row["tot_jovenes"],
                            "tot_menores" => $row["tot_menores"],
                            "foto_dpi" => $row["foto_dpi"],
                            "observaciones" => $row["observaciones"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró la autorización"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar autorización por ID */
        case "eliminar":
            try {
                $autorizacion->deleteAutorizacion($_POST["id_auto_ingreso"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>