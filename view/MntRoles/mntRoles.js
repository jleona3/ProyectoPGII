function init() {
    $("#mantto-form").on("submit", function (e) {
        guardaryeditar(e);
    });

    // Evitar warning aria-hidden
    $('#modal-ManttoRoles').on('shown.bs.modal', function () {
        $(this).removeAttr('aria-hidden');
    });
}

function guardaryeditar(e){
    e.preventDefault();
    //console.log("ID del rol al guardar:", $("#id_rol").val());
    var formData = new FormData($("#mantto-form")[0]);
    var rol_id = $("#id_rol").val(); 
    $.ajax({
        url: "../../controller/rol.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(data){
            if (data.status === "error") {
                Swal.fire({
                    title: "¡Atención!",
                    text: data.message,
                    confirmButtonColor: "#d33",
                    icon: "error"
                });
                return;
            }
            $('#table_data').DataTable().ajax.reload(null, false);
            $('#modal-ManttoRoles').modal('hide');
            Swal.fire({
                title: "¡Éxito!",
                text: rol_id === "" ? 
                      "El rol ha sido creado correctamente." : 
                      "El rol ha sido actualizado correctamente.",
                confirmButtonColor: "#329948ff",
                icon: "success"
            });
        }
    });
}

$(document).ready(function () {
    $('#table_data').DataTable({
        "processing": false,
        "serverSide": false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copiar',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-6
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-6
                }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-6
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Reporte de Roles - Trasciende La Parroquia',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Solo las primeras 6 columnas
                },
                customize: function (doc) {
                    // =======================
                    // ENCABEZADO
                    // =======================
                    doc.content.splice(0, 0, {
                        text: 'Trasciende La Parroquia',
                        fontSize: 18,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 10]
                    });

                    // Obtener fecha en formato dd-mm-aaaa
                    var fechaHoy = new Date();
                    var dia = ("0" + fechaHoy.getDate()).slice(-2);
                    var mes = ("0" + (fechaHoy.getMonth() + 1)).slice(-2);
                    var anio = fechaHoy.getFullYear();
                    var fechaFormateada = dia + "-" + mes + "-" + anio;

                    doc.content.splice(1, 0, {
                        text: 'Reporte de Roles - Generado el: ' + fechaFormateada,
                        fontSize: 11,
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });

                    // =======================
                    // AJUSTES DE TABLA
                    // =======================
                    var table = doc.content[3];
                    table.layout = {
                        hLineWidth: function () { return 0.5; },
                        vLineWidth: function () { return 0.5; },
                        hLineColor: function () { return '#ccc'; },
                        vLineColor: function () { return '#ccc'; },
                        paddingLeft: function () { return 4; },
                        paddingRight: function () { return 4; }
                    };
                    table.table.widths = ['5%', '15%', '10%', '18%', '17%', '17%', '18%']; // Anchos personalizados

                    // =======================
                    // ALINEACIÓN DE COLUMNAS
                    // =======================
                    var body = table.table.body;

                    // Encabezados (primera fila)
                    body[0].forEach(function (cell, index) {
                        if ([0, 3, 6].includes(index)) {
                            cell.alignment = 'center'; // Primera, tercera y última
                        } else {
                            cell.alignment = 'left'; // Segunda, cuarta y quinta
                        }
                        cell.fillColor = '#1A2E4F'; // Fondo gris suave para el encabezado
                        cell.bold = true;
                    });

                    // Contenido de filas
                    for (var i = 1; i < body.length; i++) {
                        body[i].forEach(function (cell, index) {
                            if ([0, 3, 6].includes(index)) {
                                cell.alignment = 'center';
                            } else {
                                cell.alignment = 'left';
                            }
                        });
                    }

                    // Fuente más pequeña
                    doc.defaultStyle.fontSize = 10;

                    // =======================
                    // PIE DE PÁGINA
                    // =======================
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
            }
        ],
        "ajax": {
            url: "../../controller/rol.php?op=listar",
            type: "post"
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        // ======= AQUI CENTRAMOS LAS COLUMNAS DE BOTONES =======
        "columnDefs": [
            { "className": "text-center", "targets": [0] },     // Centrar columna 0
            { "className": "text-left", "targets": [7, 8] }     // Alinear a la izquierda columnas 7 y 8
        ],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(Filtrando de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });
});


function editar(rol_id) {
    $.post("../../controller/rol.php?op=mostrarID", { ROL_ID: rol_id }, function (data) {
        if (data.status === "error") {
            Swal.fire({ title: "Error", text: data.message, icon: "error" });
            return;
        }
        $('#id_rol').val(data.ROL_ID);
        $('#rol_nom').val(data.ROL_NOM);
        $('#id_estado').val(data.ID_ESTADO);
        $('#creado_por').val(data.CREADO_POR);
        $('#modificado_por').val(data.MODIFICADO_POR || '');
        $('#fe_modificacion').val(data.FE_MODIFICACION || '');
        $('#lbl-titulo').html('Editar Registro');
        $('#modal-ManttoRoles').modal('show');
    }, "json");
}

function eliminar(rol_id) {
    swal.fire({
        title: "¿Estás seguro?",
        text: "¡Ya no podrás revertir esto!",
        icon: "warning",
        confirmButtonText: "Sí",
        confirmButtonColor: "#4480c5ff",
        cancelButtonColor: "#d33",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/rol.php?op=eliminar", { rol_id: rol_id }, function (data) {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            Swal.fire({
                title: "¡Eliminado!",
                text: "El rol ha sido eliminado correctamente.",
                confirmButtonColor: "#329948ff",
                icon: "success"
            });
        }
    });
}

// TODO: Nuevo registro
$(document).on("click", "#btn-nuevo", function () {
    $('#id_rol').val('');
    $('#rol_nom').val('');
    $('#id_estado').val(1);
    $('#lbl-titulo').html('Nuevo Registro');
    $("#mantto-form")[0].reset();
    $('#modal-ManttoRoles').modal('show');
});

init();