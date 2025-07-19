<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/CategoriaServicio.php");

    /* TODO: Inicializando Clase */
    $categoriaServicio = new CategoriaServicio();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_servicio"])) {
                    // Guardar
                    $categoriaServicio->insertCategoriaServicio($_POST["codigo"], $_POST["descripcion"]);
                } else {
                    // Editar
                    $categoriaServicio->updateCategoriaServicio($_POST["id_servicio"], $_POST["codigo"], $_POST["descripcion"]);
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
                $datos = $categoriaServicio->getCategoriaServicioCod($codigo);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["codigo"];
                    $sub_array[] = $row["descripcion"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_servicio"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_servicio"] . ")'>Eliminar</button>";
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
                $datos = $categoriaServicio->getCategoriaServicioId($_POST["id_servicio"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_servicio" => $row["id_servicio"],
                            "codigo" => $row["codigo"],
                            "descripcion" => $row["descripcion"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el servicio"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar registro por ID */
        case "eliminar":
            try {
                $categoriaServicio->deleteCategoriaServicio($_POST["id_servicio"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
