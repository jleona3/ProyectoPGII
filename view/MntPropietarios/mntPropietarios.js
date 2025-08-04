// Lógica JS (listar, guardar, editar, eliminar propietarios)

function init() {
    $("#propietario-form").on("submit", function (e) {
        e.preventDefault();
        guardaryeditar();
    });

    // Preview dinámico de imagen
    $("#foto_perfil").change(function () {
        const file = this.files[0];
        if (file) {
            // Validar tamaño (1MB máximo)
            const maxSize = 1 * 1024 * 1024; // 1MB
            if (file.size > maxSize) {
                Swal.fire("Imagen demasiado grande", "El archivo no debe superar 1MB.", "warning");
                $(this).val(""); // Limpiar el input
                return;
            }

            // Validar dimensiones
            const img = new Image();
            const objectUrl = URL.createObjectURL(file);
            img.onload = function () {
                if (this.width > 1280 || this.height > 1200) {
                    Swal.fire("Dimensiones inválidas", "La imagen no debe exceder 1280x1200 píxeles.", "warning");
                    $("#foto_perfil").val(""); // Limpiar el input
                    $("#preview-foto").hide();
                }else{

            const reader = new FileReader();
            reader.onload = function (e) {
                $("#preview-foto").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
        URL.revokeObjectURL(objectUrl);
            };
            img.src = objectUrl;
        }
    });

    // Cargar combos
    cargarEstados();
    cargarRoles();
    cargarApartamentos();

}

/* ===========================================
VALIDAR FORMULARIO
=========================================== */
function validarFormulario() {
    let dpi = $("#dpi").val().trim();
    let email = $("#email").val().trim();
    let telefono = $("#telefono").val().trim();

    if (!dpi || dpi.length < 13) {
        Swal.fire("Error", "El DPI es obligatorio y debe tener al menos 13 dígitos.", "error");
        return false;
    }
    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        Swal.fire("Error", "Debe ingresar un correo válido.", "error");
        return false;
    }
    if (!telefono || telefono.length < 8) {
        Swal.fire("Error", "Debe ingresar un teléfono válido.", "error");
        return false;
    }
    return true;
}

/* ===========================================
CARGAR ESTADOS
=========================================== */
function cargarEstados(preselectedId = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "../../controller/estado.php?op=listar_todos",
            type: "GET",
            success: function (response) {
                let data = JSON.parse(response);
                let options = "<option value=''>Seleccione</option>";
                data.forEach(function (item) {
                    options += `<option value="${item.ID_ESTADO}">${item.DESCRIPCION}</option>`;
                });
                $("#id_estado").html(options);
                if (preselectedId) $("#id_estado").val(preselectedId);
                resolve();
            },
            error: function () { reject("Error al cargar estados"); }
        });
    });
}

/* ===========================================
CARGAR ROLES
=========================================== */
function cargarRoles(preselectedId = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "../../controller/rol.php?op=listar_combo",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let options = "<option value=''>Seleccione</option>";
                data.forEach(function (item) {
                    options += `<option value="${item.ROL_ID}">${item.ROL_NOM}</option>`;
                });
                $("#rol_id").html(options);
                if (preselectedId) $("#rol_id").val(preselectedId);
                resolve();
            },
            error: function () { reject("Error al cargar roles"); }
        });
    });
}

/* ===========================================
CARGAR APARTAMENTOS
=========================================== */
function cargarApartamentos(preselectedId = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "../../controller/condominio.php?op=listar_todos_simple",
            type: "GET",
            success: function (response) {
                let data = JSON.parse(response);
                let options = "<option value=''>Seleccione</option>";
                data.forEach(function (item) {
                    options += `<option value="${item.ID_APTO}">Torre ${item.NUM_TORRE} - Nivel ${item.NIVEL} - Apto ${item.NUM_APTO}</option>`;
                });
                $("#id_apto").html(options);
                if (preselectedId) $("#id_apto").val(preselectedId);
                resolve();
            },
            error: function () { reject("Error al cargar apartamentos"); }
        });
    });
}

/* ===========================================
GUARDAR / EDITAR
=========================================== */
function guardaryeditar() {
    var formData = new FormData($("#propietario-form")[0]);
    var id_user = $("#id_user").val();

    $.ajax({
        url: "../../controller/propietario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",  // <--- Asegúrate de que esto esté
        success: function (response) {
            let data;
            try {
                data = JSON.parse(response);
            } catch (e) {
                console.error("Respuesta inválida del servidor:", response);
                Swal.fire("Error", "El servidor devolvió una respuesta inválida.", "error");
                return;
            }

            if (data.status === "error") {
                Swal.fire("¡Atención!", data.message, "error");
                return;
            }

            $('#table_data').DataTable().ajax.reload(null, false);
            $('#modal-Propietario').modal('hide');
            Swal.fire("¡Éxito!", id_user === "" ? "El propietario ha sido registrado." : "El propietario ha sido actualizado.", "success");
        },
        error: function (xhr) {
            console.error("Error en el servidor:", xhr.responseText);
            Swal.fire("Error", "No se pudo guardar el propietario.", "error");
        }
    });
}

/* ===========================================
LISTAR EN DATATABLE
=========================================== */
$(document).ready(function () {
    // Si no es administrador, ocultar el botón "Nuevo" y el encabezado "Eliminar"
    if (ROL_ID != 1) {
        $("#btn-nuevo").hide(); // Oculta botón "Nuevo"
        $("#table_data thead th:last-child").hide(); // Oculta el encabezado "Eliminar"
    }

    // Definir columnas dinámicamente
    let columns = [
        { data: "ID_USER" },
        { data: "FOTO_PERFIL" },
        { data: "NOMBRE_COMPLETO" },
        { data: "DPI" },
        { data: "EMAIL" },
        { data: "TELEFONO" },
        { data: "NUM_TORRE" },
        { data: "NIVEL" },
        { data: "NUM_APTO" },
        { data: "ROL_NOMBRE" },
        { data: "NOMBRE_ESTADO" },
        { data: "FE_CREACION" },
        { data: "CREADO_POR" },
        { data: "MODIFICADO_POR" },
        { data: "FE_MODIFICACION" },
        { data: "EDITAR" }
    ];

    // Si es administrador, agregamos la columna Eliminar
    if (ROL_ID == 1) { 
        columns.push({ data: "ELIMINAR" });
    }

    $('#table_data').DataTable({
        "processing": false,
        "serverSide": false,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', text: 'Copiar', exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10,11,12,13,14] } },
            { extend: 'excelHtml5', text: 'Excel', exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10,11,12,13,14] } },
            { extend: 'csvHtml5', text: 'CSV',  exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10,11,12,13,14] } },
            { 
                extend: 'pdfHtml5', 
                text: 'PDF', 
                orientation: 'landscape', 
                pageSize: 'A4', 
                title: 'Reporte de Propietarios - Trasciende La Parroquia',
                exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11] }, /* Solo las columnas necesarias */
                customize: function (doc) {
                    // ENCABEZADO
                    doc.content.splice(0, 0, {
                        text: 'Trasciende La Parroquia',
                        fontSize: 18,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 10]
                    });
                    // FECHA
                    var fechaHoy = new Date();
                    var dia = ("0" + fechaHoy.getDate()).slice(-2);
                    var mes = ("0" + (fechaHoy.getMonth() + 1)).slice(-2);
                    var anio = fechaHoy.getFullYear();
                    var fechaFormateada = dia + "-" + mes + "-" + anio;
                    doc.content.splice(1, 0, {
                        text: 'Reporte de Propietarios - Generado el: ' + fechaFormateada,
                        fontSize: 11,
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });

                    // FORMATO TABLA
                    var table = doc.content[3];
                    table.layout = {
                        hLineWidth: function () { return 0.5; },
                        vLineWidth: function () { return 0.5; },
                        hLineColor: function () { return '#ccc'; },
                        vLineColor: function () { return '#ccc'; },
                        paddingLeft: function () { return 4; },
                        paddingRight: function () { return 4; }
                    };
                    // Ancho de columnas (ajustado)
                    table.table.widths = [
                        '20%', // Nombre
                        '10%', // DPI
                        '17%', // Email
                        '8%',  // Teléfono
                        '5%',  // Torre
                        '5%',  // Nivel
                        '5%',  // Apto
                        '8%',  // Rol
                        '8%',  // Estado
                        '14%', // Fecha creación
                    ];

                    // ENCABEZADOS
                    var body = table.table.body;
                    body[0].forEach(function (cell, index) {
                        cell.fillColor = '#27AE60';
                        cell.color = 'white';
                        cell.bold = true;
                        if ([1,3,4,5,6,9].includes(index)) { 
                            cell.alignment = 'center';
                        } else { 
                            cell.alignment = 'left';
                        }
                    });

                    // Celdas: alineaciones personalizadas
                    for (var i = 1; i < body.length; i++) {
                        body[i].forEach(function (cell, index) {
                            if ([1,3,4,5,6,9].includes(index)) { 
                                cell.alignment = 'center';
                            } else { 
                                cell.alignment = 'left';
                            }
                        });
                    }

                    // Fuente
                    doc.defaultStyle.fontSize = 9;

                    // PIE DE PÁGINA
                    doc.footer = function (currentPage, pageCount) {
                        return {
                            columns: [
                                { text: 'Trasciende La Parroquia', alignment: 'left', margin: [40, 0] },
                                { text: 'Página ' + currentPage.toString() + ' de ' + pageCount, alignment: 'right', margin: [0, 0, 40] }
                            ],
                            fontSize: 9
                        };
                    };
                }
            },
            { extend: 'colvis', text: 'Columnas', postfixButtons: ['colvisRestore'] } 
        ],
        
        "ajax": {
            url: "../../controller/propietario.php?op=listar",
            type: "post",
            dataSrc: function (json) {
                // Si no es administrador, eliminamos el contenido de la columna eliminar
                if (ROL_ID != 1) {
                    json.aaData.forEach(function (row) { row.ELIMINAR = ''; });
                }
                return json.aaData;
            }
        },
        
        "columns": columns,
        "bDestroy": true,
        "responsive": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "columnDefs": [
            { "className": "text-center", "targets": [0] } // Ajusta si cambia el índice
        ],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });
});

/* ===========================================
EDITAR
=========================================== */
function editar(id_user) {
    $.post("../../controller/propietario.php?op=mostrarID", { id_user: id_user }, function (data) {
        data = JSON.parse(data);
        $('#id_user').val(data.ID_USER);
        $('#nombres').val(data.NOMBRES);
        $('#apellidos').val(data.APELLIDOS);
        $('#dpi').val(data.DPI);
        $('#telefono').val(data.TELEFONO);
        $('#email').val(data.EMAIL);
        $('#pass').val(data.PASS);
        $('#foto_actual').val(data.FOTO_PERFIL);
        if (data.FOTO_PERFIL) {
            $("#preview-foto").attr("src", `../../uploads/propietarios/${data.FOTO_PERFIL}`).show();
        } else {
            $("#preview-foto").attr("src", `../../uploads/propietarios/default.png`).show();
        }
        Promise.all([
            cargarEstados(data.ID_ESTADO),
            cargarRoles(data.ROL_ID),
            cargarApartamentos(data.ID_APTO)
        ]).then(() => {
            $('#lbl-titulo').html('Editar Propietario');
            $('#modal-Propietario').modal('show');
        });
    });
}

/* ===========================================
ELIMINAR
=========================================== */
function eliminar(id_user) {
    if (ROL_ID != 1) {
        Swal.fire("Acceso denegado", "No tienes permisos para eliminar propietarios.", "warning");
        return;
    }
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/propietario.php?op=eliminar", { id_user: id_user }, function (data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $('#table_data').DataTable().ajax.reload();
                    Swal.fire("¡Eliminado!", "El propietario ha sido eliminado.", "success");
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            });
        }
    });
}

/* ===========================================
NUEVO PROPIETARIO
=========================================== */
$(document).on("click", "#btn-nuevo", function () {

    // Bloquear si no es administrador
    if (ROL_ID != 1) {
        Swal.fire("Acceso denegado", "No tienes permisos para agregar propietarios.", "warning");
        return;
    }

    $('#propietario-form')[0].reset();
    $('#id_user').val('');
    $('#preview-foto').attr("src", "../../uploads/propietarios/default.png").show();
    Promise.all([
        cargarEstados(),
        cargarRoles(),
        cargarApartamentos()
    ]).then(() => {
        $('#lbl-titulo').html('Nuevo Propietario');
        $('#modal-Propietario').modal('show');
    });
});

init();

// Mostrar/Ocultar contraseña (solo si el botón existe)
document.addEventListener('DOMContentLoaded', function () {
    const togglePass = document.getElementById('togglePass');
    if (togglePass) {
        togglePass.addEventListener('click', function () {
            const passInput = document.getElementById('pass');
            const icon = document.getElementById('togglePassIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                passInput.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        });
    }
});

