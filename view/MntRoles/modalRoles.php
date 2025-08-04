<div id="modal-ManttoRoles" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbl-titulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantto-form">
                <div class="modal-body">
                    <!-- Campos ocultos -->
                    <input type="hidden" name="id_rol" id="id_rol"/>
                    <input type="hidden" name="creado_por" id="creado_por" value="<?php echo $_SESSION["NOMBRES"]; ?>">
                    <input type="hidden" name="modificado_por" id="modificado_por" value="<?php echo $_SESSION["NOMBRES"]; ?>">

                    <div class="row gy-2">
                        <div class="col-lg-12">
                            <div>
                                <label for="rol_nom" class="form-label">Nombre Rol</label>
                                <input type="text" class="form-control" id="rol_nom" name="rol_nom" autocomplete="off" required/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="id_estado" class="form-label">Estado</label>
                                <select class="form-select" id="id_estado" name="id_estado" required>
                                    <option value="1">Activo</option>
                                    <option value="17">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
