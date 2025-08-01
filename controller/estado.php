<?php
require_once("../config/conexion.php");
require_once("../models/Estado.php");

$estado = new Estado();

switch ($_GET["op"]) {
    case "listar_todos":
        $datos = $estado->getEstadoTodos();
        echo json_encode($datos);
        break;
}
?>