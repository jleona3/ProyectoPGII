<?php
    require_once("../../models/Menu.php");
    $menu = new Menu();
    $datos = $menu->getMenuPorRol($_SESSION["ROL_ID"]);
?>

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="../../assets/images/logo-trz6.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="../../assets/images/logo-trz6-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="../../assets/images/logo-trz6.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="../../assets/images/nuevo_logo.png" alt="" height="25">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <?php
                    foreach($datos as $row){
                        if($row["MEN_GRUPO"]=="Dashboard" && $row["PERMISO_DETMENU"]=="SI"){
                        ?>
                            <li class="nav-item">
                                <a class="nav-link menu-link is-dashboard" href="<?php echo $row["MEN_RUTA"];?>" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i>
                                <span data-key="t-dashboards"><?php echo $row["MEN_NOM"];?></span>
                                </a>
                            </li>
                        <?php
                        }
                    }
                ?>

                <?php
                // Filtrar ítems del grupo "Categorías" con permiso "SI"
                $catItems = array_filter($datos, function($row){
                    return isset($row['MEN_GRUPO'], $row['PERMISO_DETMENU'])
                        && $row['MEN_GRUPO'] === 'Categorías'
                        && strtoupper(trim($row['PERMISO_DETMENU'])) === 'SI';
                });

                if (!empty($catItems)): 
                    // ID único para el collapse (por si hay varios bloques)
                    $collapseId = "sidebarCategorias";
                ?>
                    <li class="nav-item">
                        <!-- Encabezado: icono + texto (NO colapsa) + flechita a la derecha (SÍ colapsa) -->
                        <div class="d-flex align-items-center justify-content-between nav-link menu-link categoria-header">
                        <span class="d-inline-flex align-items-center gap-2" draggable="false">
                            <i class="ri-folders-line"></i>
                            <span data-key="t-apps" draggable="false">Categorías</span>
                        </span>

                        <button
                            class="btn btn-sm btn-ghost p-0 border-0 collapsed rotate-caret"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?= $collapseId; ?>"
                            aria-expanded="false"
                            aria-controls="<?= $collapseId; ?>"
                            title="Mostrar/Ocultar">
                            <i class="ri-arrow-down-s-line fs-5"></i>
                        </button>
                        </div>

                        <div class="collapse menu-dropdown" id="<?= $collapseId; ?>">
                            <ul class="nav flex-column">
                                <?php foreach ($catItems as $row): 
                                    $ruta = htmlspecialchars($row["MEN_RUTA"] ?? '#', ENT_QUOTES, 'UTF-8');
                                    $nom  = htmlspecialchars($row["MEN_NOM"]  ?? '', ENT_QUOTES, 'UTF-8');

                                    // Icono por opción
                                    switch (strtolower(trim($row['MEN_NOM'] ?? ''))) {
                                        case 'estados':
                                            $icon = 'ri-flag-2-line'; // 🚩 Estados
                                            break;
                                        case 'servicios':
                                            $icon = 'ri-tools-line'; // 🛠 Servicios
                                            break;
                                        case 'tipo pago':
                                            $icon = 'ri-money-dollar-circle-line'; // 💰 Tipo Pago
                                            break;
                                        default:
                                            $icon = 'ri-file-list-3-line'; // 📋 Por defecto
                                    }
                                ?>
                                    <li class="nav-item">
                                        <a href="<?= $ruta; ?>" class="nav-link">
                                            <i class="<?= $icon; ?>"></i> <?= $nom; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                

                <?php
                // Filtrar ítems del grupo "Administradores" con permiso "SI"
                $adminItems = array_filter($datos, function($row){
                    return isset($row['MEN_GRUPO'], $row['PERMISO_DETMENU'])
                        && $row['MEN_GRUPO'] === 'Administradores'
                        && strtoupper(trim($row['PERMISO_DETMENU'])) === 'SI';
                });

                if (!empty($adminItems)): ?>
                    <li class="menu-title">
                        <i class="ri-more-fill"></i> <span data-key="t-pages">Administradores</span>
                    </li>

                    <?php foreach ($adminItems as $row):
                        // Sanitizar
                        $ruta = htmlspecialchars($row["MEN_RUTA"] ?? '#', ENT_QUOTES, 'UTF-8');
                        $nom  = htmlspecialchars($row["MEN_NOM"]  ?? '', ENT_QUOTES, 'UTF-8');

                        // Elegir ícono según MEN_NOM
                        switch (strtolower(trim($row['MEN_NOM'] ?? ''))) {
                            case 'proveedores':
                                $icon = 'ri-store-2-line'; // 🏬 Icono de tienda/proveedor
                                break;
                            case 'roles':
                                $icon = 'ri-rocket-line'; // 🚀 Icono de roles/perfiles
                                break;
                            default:
                                $icon = 'ri-file-list-3-line'; // 📋 Icono por defecto
                        }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= $ruta; ?>">
                                <i class="<?= $icon; ?>"></i> <span><?= $nom; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                

                <?php
                // Filtrar ítems del grupo "Propietarios" con permiso "SI"
                $propItems = array_filter($datos, function($row){
                    return isset($row['MEN_GRUPO'], $row['PERMISO_DETMENU'])
                        && $row['MEN_GRUPO'] === 'Propietarios'
                        && strtoupper(trim($row['PERMISO_DETMENU'])) === 'SI';
                });

                if (!empty($propItems)): ?>
                    <li class="menu-title">
                        <i class="ri-more-fill"></i> <span data-key="t-pages">Propietarios</span>
                    </li>

                    <?php foreach ($propItems as $row):
                        // Sanitizar
                        $ruta = htmlspecialchars($row["MEN_RUTA"] ?? '#', ENT_QUOTES, 'UTF-8');
                        $nom  = htmlspecialchars($row["MEN_NOM"]  ?? '', ENT_QUOTES, 'UTF-8');

                        // Elegir ícono según MEN_NOM
                        switch (strtolower(trim($row['MEN_NOM'] ?? ''))) {
                            case 'apartamentos':
                                $icon = 'ri-building-2-line'; // 🏢 Icono de edificio
                                break;
                            case 'propietarios':
                                $icon = 'ri-user-star-line'; // 👤⭐ Icono de usuario destacado
                                break;
                            case 'pago mantenimiento':
                                $icon = 'ri-money-dollar-circle-line'; // 💰 Icono de pago/dinero
                                break;
                            case 'autorización ingreso':
                                $icon = 'ri-shield-keyhole-line'; // 🔑 Escudo con llave
                                break;
                            case 'alquiler apartamentos':
                                $icon = 'ri-home-4-line'; // 🏠 Icono de casa
                                break;
                            case 'lectura propietario':
                                $icon = 'ri-file-search-line'; // 📄🔍 Icono de búsqueda de documento
                                break;
                            case 'incidencias':
                                $icon = 'ri-alert-line'; // ⚠️ Icono de alerta
                                break;
                            default:
                                $icon = 'ri-file-list-3-line'; // 📋 Icono por defecto
                        }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= $ruta; ?>">
                                <i class="<?= $icon; ?>"></i> <span><?= $nom; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php
                // Filtrar ítems del grupo "Mantenimiento" con permiso "SI"
                $mantItems = array_filter($datos, function($row){
                    return isset($row['MEN_GRUPO'], $row['PERMISO_DETMENU'])
                        && $row['MEN_GRUPO'] === 'Mantenimiento'
                        && strtoupper(trim($row['PERMISO_DETMENU'])) === 'SI';
                });

                if (!empty($mantItems)): ?>
                    <li class="menu-title">
                        <i class="ri-more-fill"></i> <span data-key="t-components">Mantenimiento</span>
                    </li>

                    <?php foreach ($mantItems as $row):
                        // Sanitizar
                        $ruta = htmlspecialchars($row["MEN_RUTA"] ?? '#', ENT_QUOTES, 'UTF-8');
                        $nom  = htmlspecialchars($row["MEN_NOM"]  ?? '', ENT_QUOTES, 'UTF-8');

                        // Asignar icono según MEN_NOM
                        $icon = 'ri-file-list-3-line'; // default
                        if (strcasecmp($nom, 'Lectura Principal') === 0) {
                            $icon = 'ri-booklet-line'; // 📘 Ícono tipo libro/lista
                        }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= $ruta; ?>">
                                <i class="<?= $icon; ?>"></i> <span><?= $nom; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php
                // Filtrar ítems del grupo "Seguridad" con permiso "SI"
                $secItems = array_filter($datos, function($row){
                    return isset($row['MEN_GRUPO'], $row['PERMISO_DETMENU'])
                        && $row['MEN_GRUPO'] === 'Seguridad'
                        && strtoupper(trim($row['PERMISO_DETMENU'])) === 'SI';
                });

                if (!empty($secItems)): ?>
                    <li class="menu-title">
                        <i class="ri-more-fill"></i> <span data-key="t-components">Seguridad</span>
                    </li>

                    <?php foreach ($secItems as $row):
                        // Sanitizar
                        $ruta = htmlspecialchars($row["MEN_RUTA"] ?? '#', ENT_QUOTES, 'UTF-8');
                        $nom  = htmlspecialchars($row["MEN_NOM"]  ?? '', ENT_QUOTES, 'UTF-8');

                        // Ícono por nombre (ajusta/añade según vayas creando opciones)
                        switch (strtolower(trim($row['MEN_NOM'] ?? ''))) {
                            case 'autorización ingreso':
                                $icon = 'ri-shield-keyhole-line';   // 🔑 control de acceso
                                break;
                            case 'incidencias':
                                $icon = 'ri-alert-line';            // ⚠️ incidentes
                                break;
                            case 'visitantes':
                                $icon = 'ri-user-follow-line';      // 👥 visitantes
                                break;
                            case 'bitácora accesos':
                                $icon = 'ri-clipboard-list-line';   // 📋 bitácora
                                break;
                            case 'cámaras':
                            case 'camaras':
                                $icon = 'ri-camera-lens-line';      // 📷 cámaras
                                break;
                            default:
                                $icon = 'ri-shield-line';           // 🛡️ por defecto
                        }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= $ruta; ?>">
                                <i class="<?= $icon; ?>"></i> <span><?= $nom; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>