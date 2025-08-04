<?php $esAdmin = ($_SESSION["ROL_ID"] == 1); ?>

<style>
/* Simular readonly en selects */
.select-readonly {
    pointer-events: none;
    background-color: #e9ecef;
}
</style>

<div id="modal-Propietario" class="modal fade" tabindex="-1" aria-labelledby="lbl-titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" id="propietario-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="lbl-titulo"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Ocultos -->
                    <input type="hidden" name="id_user" id="id_user"/>
                    <input type="hidden" name="foto_actual" id="foto_actual"/>
                    <input type="hidden" name="creado_por" id="creado_por" value="<?php echo $_SESSION["NOMBRES"]; ?>">

                    <div class="row gy-3">
                        <!-- Nombres -->
                        <div class="col-lg-6">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nombres" 
                                name="nombres"
                                <?php echo !$esAdmin ? 'readonly placeholder="Solo editable por administrador"' : ''; ?> 
                                required 
                            />
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar los nombres.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-lg-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="apellidos" 
                                name="apellidos"
                                <?php echo !$esAdmin ? 'readonly placeholder="Solo editable por administrador"' : ''; ?> 
                                required 
                            />
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar los apellidos.</small>
                            <?php endif; ?>
                        </div>

                        <!-- DPI -->
                        <div class="col-lg-6">
                            <label for="dpi" class="form-label">DPI</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="dpi" 
                                name="dpi" 
                                maxlength="13"
                                <?php echo !$esAdmin ? 'readonly placeholder="Solo editable por administrador"' : ''; ?> 
                                required 
                            />
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar el DPI.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Teléfono (siempre editable) -->
                        <div class="col-lg-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="telefono" 
                                name="telefono" 
                                maxlength="15"
                                required 
                            />
                        </div>

                        <!-- Correo -->
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email"
                                autocomplete="username"
                                <?php echo !$esAdmin ? 'readonly placeholder="Solo editable por administrador"' : ''; ?> 
                                required 
                            />
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar el correo.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Contraseña (editable solo para admin con botón mostrar/ocultar) -->
                        <div class="col-lg-6">
                            <label for="pass" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="pass" 
                                    name="pass"
                                    autocomplete="current-password"
                                    <?php echo $_SESSION['ROL_ID'] != 1 ? 'readonly placeholder="Solo editable por administrador"' : ''; ?> 
                                />
                                <?php if ($_SESSION['ROL_ID'] == 1): ?>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePass">
                                        <i class="ri-eye-line" id="togglePassIcon"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if ($_SESSION['ROL_ID'] != 1): ?>
                                <small class="text-muted">
                                    Solo el administrador puede cambiar la contraseña.
                                </small>
                            <?php endif; ?>
                        </div>

                        <!-- Apartamento -->
                        <div class="col-lg-4">
                            <label for="id_apto" class="form-label">Apartamento</label>
                            <select 
                                class="form-select <?php echo !$esAdmin ? 'select-readonly' : ''; ?>" 
                                id="id_apto" 
                                name="id_apto" 
                                required>
                            </select>
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar el apartamento.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Rol -->
                        <div class="col-lg-4">
                            <label for="rol_id" class="form-label">Rol</label>
                            <select 
                                class="form-select <?php echo !$esAdmin ? 'select-readonly' : ''; ?>" 
                                id="rol_id" 
                                name="rol_id" 
                                required>
                            </select>
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar el rol.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Estado -->
                        <div class="col-lg-4">
                            <label for="id_estado" class="form-label">Estado</label>
                            <select 
                                class="form-select <?php echo !$esAdmin ? 'select-readonly' : ''; ?>" 
                                id="id_estado" 
                                name="id_estado" 
                                required>
                            </select>
                            <?php if (!$esAdmin): ?>
                                <small class="text-muted">Solo el administrador puede cambiar el estado.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Foto (siempre editable) -->
                        <div class="col-lg-6">
                            <label for="foto_perfil" class="form-label">Foto de perfil</label>
                            <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*" />
                        </div>
                        <div class="col-lg-6 text-center">
                            <label class="form-label d-block">Vista previa</label>
                            <img id="preview-foto" src="../../uploads/propietarios/default.png" alt="Vista previa" class="rounded-circle border" width="100" height="100" />
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