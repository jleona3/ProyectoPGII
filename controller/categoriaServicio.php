<?php
/* ===========================================
   CARGAR DEPENDENCIAS
=========================================== */
require_once("../config/conexion.php");
require_once("../models/CategoriaServicio.php");

/* ===========================================
   INICIALIZAR CLASE
=========================================== */
$categoriaServicio = new CategoriaServicio();

switch ($_GET["op"]) {

    /* ===========================================
       GUARDAR O EDITAR REGISTRO
    =========================================== */
    case "guardaryeditar":
        try {
            if (empty($_POST["id_servicio"])) {
                // Nuevo
                if ($categoriaServicio->existeServicio($_POST["servicio"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del servicio ya existe."]);
                    exit();
                }
                $categoriaServicio->insertCategoriaServicio($_POST["creado_por"], $_POST["servicio"], $_POST["descripcion"]);
            } else {
                // Editar
                if ($categoriaServicio->existeServicio($_POST["servicio"], $_POST["id_servicio"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del servicio ya existe."]);
                    exit();
                }
                $categoriaServicio->updateCategoriaServicio(
                    $_POST["id_servicio"],
                    $_POST["creado_por"],
                    $_POST["servicio"],
                    $_POST["descripcion"]
                );
            }
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       LISTAR REGISTROS PARA DATATABLE
    =========================================== */
    case "listar":
        $datos = $categoriaServicio->getCategoriaServicioTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ID_SERVICIO"];
            $sub_array[] = $row["SERVICIO"];
            $sub_array[] = $row["DESCRIPCION"];
            $sub_array[] = $row["FE_CREACION"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"];
            $sub_array[] = $row["FE_MODIFICACION"];
            // Botones
            $sub_array[] = '<button type="button" onClick="editar('.$row["ID_SERVICIO"].')" id="'.$row["ID_SERVICIO"].'" class="btn btn-warning btn-icon"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ID_SERVICIO"].')" id="'.$row["ID_SERVICIO"].'" class="btn btn-danger btn-icon"><i class="ri-delete-bin-5-line"></i></button>';
            $data[] = $sub_array;
        }
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    /* ===========================================
       OBTENER REGISTRO POR ID
    =========================================== */
    case "mostrarID":
        try {
            $datos = $categoriaServicio->getCategoriaServicioId($_POST["id_servicio"]);
            if (is_array($datos) && count($datos) > 0) {
                $output = [
                    "ID_SERVICIO" => $datos["ID_SERVICIO"],
                    "SERVICIO" => $datos["SERVICIO"],
                    "DESCRIPCION" => $datos["DESCRIPCION"],
                    "CREADO_POR" => $datos["CREADO_POR"],
                    "FE_CREACION" => $datos["FE_CREACION"],
                    "MODIFICADO_POR" => $datos["MODIFICADO_POR"],
                    "FE_MODIFICACION" => $datos["FE_MODIFICACION"]
                ];
                echo json_encode($output);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el servicio"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       ELIMINAR REGISTRO
    =========================================== */
    case "eliminar":
        try {
            $categoriaServicio->deleteCategoriaServicio($_POST["id_servicio"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       LISTAR TODOS (OPCIONAL)
    =========================================== */
    case "listar_todos":
        $datos = $categoriaServicio->getCategoriaServicioTodos();
        echo json_encode($datos);
        break;
}
?>