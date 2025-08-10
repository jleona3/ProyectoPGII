<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["ID_USER"])) {
?>
<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <title>Trasciende | Roles</title>
    <?php require_once("../html/head.php"); ?>
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
                                <h4 class="mb-sm-0">Mantenimiento de Roles</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento de</a></li>
                                        <li class="breadcrumb-item active">Roles</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                        <button type="button" id="btn-nuevo" class="btn btn-primary rounded-pill">
                                            <i class="ri-add-line"></i> Nuevo Rol
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre Rol</th>
                                                <th>Estado</th>
                                                <th>Fecha Creación</th>
                                                <th>Creado Por</th>
                                                <th>Modificado Por</th>
                                                <th>Fecha Modificación</th>
                                                <th class="text-center" style="width:50px;">Permiso</th>
                                                <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                                    <th class="text-center" style="width:50px;">Editar</th>
                                                <?php endif; ?>
                                                <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                                    <th class="text-center" style="width:50px;">Eliminar</th>
                                                <?php endif; ?>
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
    <?php require_once("modalRoles.php"); ?>

    <?php require_once("modalPermisos.php"); ?>

    <?php require_once("../html/js.php"); ?>

    <script>
        const ROL_ID = <?php echo $_SESSION['ROL_ID']; ?>;
    </script>
    <script type="text/javascript" src="mntRoles.js"></script>

</body>
</html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/");
}
?>