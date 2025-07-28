<?php
/* TODO: Llamando Clases */
require_once("../config/conexion.php");
require_once("../models/CategoriaEstado.php");

/* TODO: Inicializando Clase */
$categoriaEstado = new CategoriaEstado();

switch ($_GET["op"]) {
    /* ===========================================
       TODO: GUARDAR O EDITAR REGISTRO
       =========================================== */
    case "guardaryeditar":
        try {
            // Validar duplicidad antes de guardar
            if (empty($_POST["id_estado"])) {
                // Si es nuevo
                if ($categoriaEstado->existeDescripcion($_POST["descripcion"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del estado ya existe."]);
                    exit();
                }
                $categoriaEstado->insertCategoriaEstado($_POST["creado_por"], $_POST["descripcion"]);
            } else {
                // Si es edición
                if ($categoriaEstado->existeDescripcion($_POST["descripcion"], $_POST["id_estado"])) {
                    echo json_encode(["status" => "error", "message" => "El nombre del estado ya existe."]);
                    exit();
                }
                $categoriaEstado->updateCategoriaEstado(
                    $_POST["id_estado"],
                    $_POST["creado_por"],
                    $_POST["descripcion"],
                    $_POST["modificado_por"]
                );
            }
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;


    /* ===========================================
       TODO: LISTAR REGISTROS PARA DATATABLE
       =========================================== */
    case "listar":
        $datos = $categoriaEstado->getCategoriaEstadoTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ID_ESTADO"];
            $sub_array[] = $row["DESCRIPCION"];
            $sub_array[] = $row["FE_CREACION"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"];    // Nuevo
            $sub_array[] = $row["FE_MODIFICACION"];   // Nuevo
            // Botones
            $sub_array[] = '<button type="button" onClick="editar('.$row["ID_ESTADO"].')" id="'.$row["ID_ESTADO"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ID_ESTADO"].')" id="'.$row["ID_ESTADO"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
       TODO: MOSTRAR INFORMACIÓN PARA EDICIÓN (por ID)
       =========================================== */
    case "mostrarID":
        try {
            $datos = $categoriaEstado->getCategoriaEstadoId($_POST["id_estado"]);
            if (is_array($datos) && count($datos) > 0) {
                $output = [
                    "ID_ESTADO" => $datos["ID_ESTADO"],
                    "CREADO_POR" => $datos["CREADO_POR"],                        
                    "DESCRIPCION" => $datos["DESCRIPCION"],
                    "MODIFICADO_POR" => $datos["MODIFICADO_POR"],       // Nuevo
                    "FE_MODIFICACION" => $datos["FE_MODIFICACION"]      // Nuevo
                ];
                echo json_encode($output);
            } else {
                echo json_encode(["status" => "error", "message" => "No se encontró el estado"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       TODO: ELIMINAR REGISTRO
       =========================================== */
    case "eliminar":
        try {
            $categoriaEstado->deleteCategoriaEstado($_POST["id_estado"]);
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    /* ===========================================
       TODO: LISTAR TODOS (OPCIONAL EXTRA)
       =========================================== */
    case "listar_todos":
        $datos = $categoriaEstado->getCategoriaEstadoTodos();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ID_ESTADO"];
            $sub_array[] = $row["DESCRIPCION"];
            $sub_array[] = $row["FE_CREACION"];
            $sub_array[] = $row["CREADO_POR"];
            $sub_array[] = $row["MODIFICADO_POR"]; 
            $sub_array[] = $row["FE_MODIFICACION"];
            $sub_array[] = '<button type="button" onClick="editar('.$row["ID_ESTADO"].')" id="'.$row["ID_ESTADO"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["ID_ESTADO"].')" id="'.$row["ID_ESTADO"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
}
?>
