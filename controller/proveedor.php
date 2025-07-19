<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/Proveedor.php");

    /* TODO: Inicializando Clase */
    $proveedor = new Proveedor();

    switch ($_GET["op"]) {
        /* TODO: Guardar y Editar, Guardar cuando el ID esté vacío y Actualizar cuando se envíe el ID */
        case "guardaryeditar":
            try {
                if (empty($_POST["id_proveedor"])) {
                    // Guardar
                    $proveedor->insertProveedor(
                        $_POST["id_servicio"], $_POST["nit_proveedor"], $_POST["razon_social"], $_POST["telefono"], 
                        $_POST["correo"], $_POST["nom_contacto"], $_POST["cel_contacto"], $_POST["correo_contacto"], 
                        $_POST["direccion"], $_POST["ubicacion_mapa"]
                    );
                } else {
                    // Editar
                    $proveedor->updateProveedor(
                        $_POST["id_proveedor"], $_POST["id_servicio"], $_POST["nit_proveedor"], $_POST["razon_social"], 
                        $_POST["telefono"], $_POST["correo"], $_POST["nom_contacto"], $_POST["cel_contacto"], 
                        $_POST["correo_contacto"], $_POST["direccion"], $_POST["ubicacion_mapa"]
                    );
                }
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Listar proveedores por ID_SERVICIO, formato JSON para DataTable JS */
        case "listar":
            try {
                $id_servicio = $_POST["id_servicio"];
                $datos = $proveedor->getProveedorByServicio($id_servicio);
                $data = [];
                foreach ($datos as $row) {
                    $sub_array = [];
                    $sub_array[] = $row["nit_proveedor"];
                    $sub_array[] = $row["razon_social"];
                    $sub_array[] = $row["telefono"];
                    $sub_array[] = $row["correo"];
                    $sub_array[] = $row["nom_contacto"];
                    $sub_array[] = $row["cel_contacto"];
                    $sub_array[] = $row["correo_contacto"];
                    $sub_array[] = $row["direccion"];
                    $sub_array[] = $row["ubicacion_mapa"];
                    // Agregar botones de "Editar" y "Eliminar" para cada registro
                    $sub_array[] = "<button class='btn btn-warning btn-sm' onclick='editar(" . $row["id_proveedor"] . ")'>Editar</button>";
                    $sub_array[] = "<button class='btn btn-danger btn-sm' onclick='eliminar(" . $row["id_proveedor"] . ")'>Eliminar</button>";
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

        /* TODO: Mostrar información de proveedor según su ID */
        case "mostrar":
            try {
                $datos = $proveedor->getProveedorById($_POST["id_proveedor"]);
                if (is_array($datos) && count($datos) > 0) {
                    $output = [];
                    foreach ($datos as $row) {
                        $output = [
                            "id_proveedor" => $row["id_proveedor"],
                            "id_servicio" => $row["id_servicio"],
                            "nit_proveedor" => $row["nit_proveedor"],
                            "razon_social" => $row["razon_social"],
                            "telefono" => $row["telefono"],
                            "correo" => $row["correo"],
                            "nom_contacto" => $row["nom_contacto"],
                            "cel_contacto" => $row["cel_contacto"],
                            "correo_contacto" => $row["correo_contacto"],
                            "direccion" => $row["direccion"],
                            "ubicacion_mapa" => $row["ubicacion_mapa"]
                        ];
                    }
                    echo json_encode($output);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró el proveedor"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;

        /* TODO: Eliminar proveedor por ID */
        case "eliminar":
            try {
                $proveedor->deleteProveedor($_POST["id_proveedor"]);
                echo json_encode(["status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
    }
?>
