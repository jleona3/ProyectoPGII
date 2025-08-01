<?php
    require_once("config/conexion.php");

    if(isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
        require_once("models/Propietario.php");
        $usuario = new Propietario();
        $usuario->login();
    }
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Iniciar Sesión | Trasciende - La Parroquia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/logo-trz6.ico">
    <meta content="Condominio La Parroquia" name="description" />
    <meta content="Trasciende" name="author" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="assets/js/layout.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles" style="background-image: url('assets/images/nuevo-fondo.jpg');">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="index.html" class="d-inline-block auth-logo">
                                <img src="assets/images/nuevo_logo.png" alt="" height="35">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Condominio La Parroquia</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4 card-bg-fill">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">¡Bienvenido!</h5>
                                <p class="text-muted">Acceder a Trasciende</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form action="" method="post" id="login_form" autocomplete="on">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese Correo Electrónico" required autocomplete="email">
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="auth-pass-reset-basic.html" class="text-muted">¿Has olvidado tu contraseña?</a>
                                        </div>
                                        <label class="form-label" for="password">Contraseña</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input" name="pass" id="pass" placeholder="Ingrese Contraseña" required autocomplete="current-password">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Recuérdame</label>
                                    </div>

                                    <div class="mt-4">
                                        <input type="hidden" name="enviar" value="si">
                                        <button class="btn btn-success w-100" type="submit">Acceder</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> Trasciende La Parroquia <br>15 avenida 07-47 zona 6, Guatemala
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- JAVASCRIPT -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/libs/particles.js/particles.js"></script>
<script src="assets/js/pages/particles.app.js"></script>
<!-- Elimina esta línea -->
<!-- <script src="assets/js/pages/password-addon.init.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="index.js"></script>

<script>
    document.getElementById("password-addon").addEventListener("click", function () {
        const passwordInput = document.getElementById("pass");
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);

        // Alternar el ícono si quieres (opcional)
        this.querySelector("i").classList.toggle("ri-eye-fill");
        this.querySelector("i").classList.toggle("ri-eye-off-fill");
    });
</script>

<!-- Script para limpiar campos y evitar reenvío -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("login_form");
        if (form) {
            form.addEventListener("submit", function () {
                // Esperamos brevemente antes de limpiar por si hay redirección
                setTimeout(() => {
                    document.getElementById("email").value = "";
                    document.getElementById("pass").value = "";
                }, 100);
            });
        }
    });
</script>

</body>
</html>