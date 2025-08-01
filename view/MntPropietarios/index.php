<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["ID_USER"])) {
?>
<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <title>Trasciende | Propietarios</title>
    <?php require_once("../html/head.php"); ?>
        <style>
        .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="layout-wrapper">

        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Título -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Mantenimiento de Propietarios</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item active">Propietarios</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <button type="button" id="btn-nuevo" class="btn btn-primary rounded-pill">
                                        <i class="ri-add-line"></i> Nuevo Propietario
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Foto</th>
                                                <th>Nombre Completo</th>
                                                <th>DPI</th>
                                                <th>Email</th>
                                                <th>Teléfono</th>
                                                <th>Torre</th>
                                                <th>Nivel</th>
                                                <th>Apto</th>
                                                <th>Rol</th>
                                                <th>Estado</th>
                                                <th>Fecha Creación</th>
                                                <th>Creado Por</th>
                                                <th>Modificado Por</th>
                                                <th>Fecha Modificación</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once("../html/footer.php"); ?>
        </div>
    </div>

    <!-- Modal para Crear/Editar Propietarios -->
    <?php require_once("modalPropietarios.php"); ?>

        <?php require_once("../html/js.php"); ?>
    <script type="text/javascript" src="mntPropietarios.js"></script>
</body>
</html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/");
}
?>