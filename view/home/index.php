<?php
session_start();

if (!isset($_SESSION["ID_USER"])) {
    header("Location: ../../index.php");
    exit();
}
?>
<div class="text-center mt-2">
    <h5 class="text-primary">¡Bienvenido, <?php echo $_SESSION["EMAIL"]; ?>!</h5>
    <p class="text-muted">Has ingresado correctamente al sistema</p>
</div>

