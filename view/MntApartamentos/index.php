<!-- Página principal con DataTable -->

<?php
require_once("../../config/conexion.php");
    if(isset($_SESSION["ID_USER"])){
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
    
<head>
    <title>Trasciende | Apartamentos </title>
    <?php require_once("../html/head.php"); ?>
</head>
<body>
    <div id="layout-wrapper">

        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Mantenimiento de Apartamentos</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento de</a></li>
                                        <li class="breadcrumb-item active">Apartamentos</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                        <button type="button" id="btn-nuevo" class="btn btn-primary rounded-pill">
                                            <i class="ri-add-line"></i> Nuevo Apartamento
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Torre</th>
                                                <th>Nivel</th>
                                                <th># Apto</th>
                                                <th>Metros²</th>
                                                <th>Estado</th>
                                                <th>Fecha Creación</th>
                                                <th>Creado Por</th>
                                                <th>Modificado Por</th>
                                                <th>Fecha Modificación</th>
                                                <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                                    <th>Editar</th>
                                                    <th>Eliminar</th>
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

    <!-- Modal -->
    <?php require_once("modalApartamentos.php"); ?>

    <?php require_once("../html/js.php"); ?>
    <script>
        const ROL_ID = <?php echo (int)$_SESSION['ROL_ID']; ?>;
    </script>
    <script type="text/javascript" src="mntApartamentos.js"></script>
</body>
</html>
<?php
    }else{
        header("Location:".Conectar::ruta()."view/404/");
    }
?>