<?php $esAdmin = ($_SESSION["ROL_ID"] == 1); ?>
<style>
.select-readonly { pointer-events: none; background-color:#e9ecef; }
</style>

<!-- Modal para agregar/editar -->
<div id="modal-Condominio" class="modal fade" tabindex="-1" aria-labelledby="lbl-titulo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="condominio-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="lbl-titulo"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Ocultos -->
                    <input type="hidden" name="id_apto" id="id_apto"/>
                    <input type="hidden" name="creado_por" id="creado_por" value="<?php echo $_SESSION["NOMBRES"]; ?>">

                    <div class="row gy-2">
                        <div class="col-lg-6">
                            <label for="num_torre" class="form-label">Torre</label>
                            <input type="number" class="form-control" id="num_torre" name="num_torre" required min="1" <?php echo !$esAdmin ? 'readonly' : ''; ?> />
                        </div>
                        <div class="col-lg-6">
                            <label for="nivel" class="form-label">Nivel</label>
                            <input type="number" class="form-control" id="nivel" name="nivel" required min="1" <?php echo !$esAdmin ? 'readonly' : ''; ?> />
                        </div>
                        <div class="col-lg-6">
                            <label for="num_apto" class="form-label">Número Apartamento</label>
                            <input type="number" class="form-control" id="num_apto" name="num_apto" required min="1" <?php echo !$esAdmin ? 'readonly' : ''; ?> />
                        </div>
                        <div class="col-lg-6">
                            <label for="metros_m2" class="form-label">Metros²</label>
                            <input type="number" class="form-control" id="metros_m2" name="metros_m2" required min="1" <?php echo !$esAdmin ? 'readonly' : ''; ?> />
                        </div>
                        <div class="col-lg-12">
                            <label for="id_estado" class="form-label">Estado</label>
                            <select class="form-select <?php echo !$esAdmin ? 'select-readonly' : ''; ?>" id="id_estado" name="id_estado" required></select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" <?php echo !$esAdmin ? 'disabled' : ''; ?>>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>