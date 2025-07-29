<div id="modal-Mantto" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbl-titulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantto-form">
                <div class="modal-body">
                    <!-- Ocultos -->
                    <input type="hidden" name="id_servicio" id="id_servicio"/>
                    <input type="hidden" name="creado_por" id="creado_por" value="<?php echo $_SESSION["NOMBRES"]; ?>">

                    <div class="row gy-2">
                        <div class="col-lg-12">
                            <label for="servicio" class="form-label">Nombre del Servicio</label>
                            <input type="text" class="form-control" id="servicio" name="servicio" required/>
                        </div>
                        <div class="col-lg-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>