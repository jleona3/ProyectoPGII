<?php
require_once("../config/conexion.php");
require_once("../models/Menu.php");

$menu = new Menu();

switch ($_GET["op"]) {
    case "listar":
        // Espera que SP_L_MENU_01 devuelva: MEN_NOM, ID_DETMENU, PERMISO_DETMENU
        $datos = $menu->getMenuPorRol($_POST["rol_id"] ?? null);
        $data  = [];

        foreach ($datos as $row) {
            $perm = isset($row["PERMISO_DETMENU"]) ? strtoupper(trim($row["PERMISO_DETMENU"])) : 'NO';
            $idDet = $row["ID_DETMENU"] ?? 0;

            $sub_array = [];
            $sub_array[] = htmlspecialchars($row["MEN_NOM"] ?? '', ENT_QUOTES, 'UTF-8');

            if ($perm === "SI") {
                $sub_array[] =
                    '<button type="button" onClick="deshabilitar('.$idDet.')" 
                    class="btn btn-success btn-label waves-effect waves-light rounded-pill" 
                    title="Actualmente con permiso">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>SI
                    </button>';
            } else {
                $sub_array[] =
                    '<button type="button" onClick="habilitar('.$idDet.')" 
                    class="btn btn-danger btn-label waves-effect waves-light rounded-pill" 
                    title="Actualmente sin permiso">
                        <i class="ri-close-circle-line label-icon align-middle fs-16 me-2"></i>NO
                    </button>';
            }

            $data[] = $sub_array;
        }

        echo json_encode([
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ]);
        break;

    case "habilitar":
        header('Content-Type: application/json; charset=utf-8');
        $af = $menu->update_menu_habilitar($_POST["id_detmenu"]);
        echo json_encode(["status" => "success", "affected" => $af]);
        break;

    case "deshabilitar":
        header('Content-Type: application/json; charset=utf-8');
        $af = $menu->update_menu_deshabilitar($_POST["id_detmenu"]);
        echo json_encode(["status" => "success", "affected" => $af]);
        break;
}
?>
