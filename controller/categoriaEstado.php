<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/CategoriaEstado.php");

    /* TODO: Inicializando Clase */
    $categoriaEstado = new CategoriaEstado();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_estado"])) {
                    // Guardar
                    $categoriaEstado->insertCategoriaEstado($_POST["codigo"], $_POST["descripcion"]);
                } else {
                    // Editar
                    $categoriaEstado->updateCategoriaEstado($_POST["id_estado"], $_POST["codigo"], $_POST["descripcion"]);
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
                $datos = $categoriaEstado->getCategoriaEstadoCod($codigo);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["codigo"];
                    $sub_array[] = $row["descripcion"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_estado"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_estado"] . ")'>Eliminar</button>";
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
                $datos = $categoriaEstado->getCategoriaEstadoId($_POST["id_estado"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_estado" => $row["id_estado"],
                            "codigo" => $row["codigo"],
                            "descripcion" => $row["descripcion"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el estado"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar registro por ID */
        case "eliminar":
            try {
                $categoriaEstado->deleteCategoriaEstado($_POST["id_estado"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>