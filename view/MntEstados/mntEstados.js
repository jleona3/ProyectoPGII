function init() {
    // TODO: Al enviar el formulario (guardar/editar)
    $("#mantto-form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData($("#mantto-form")[0]);
    var id_estado = $("#id_estado").val(); 
    $.ajax({
        url: "../../controller/categoriaEstado.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response){
            var data = JSON.parse(response);

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
            $('#modal-Mantto').modal('hide');

            Swal.fire({
                title: "¡Éxito!",
                text: id_estado === "" ? 
                      "El registro ha sido creado correctamente." : 
                      "El registro ha sido actualizado correctamente.",
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
                text: 'Copiar'
            },
            {
                extend: 'excelHtml5',
                text: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: 'CSV'
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Reporte de Estados - Trasciende La Parroquia',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Solo las primeras 6 columnas
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
                    doc.content.splice(1, 0, {
                        text: 'Reporte de Estados - Generado el: ' + new Date().toLocaleDateString(),
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
                    table.table.widths = ['10%', '14%', '18%', '20%', '20%', '18%']; // Anchos personalizados

                    // =======================
                    // ALINEACIÓN DE COLUMNAS
                    // =======================
                    var body = table.table.body;

                    // Encabezados (primera fila)
                    body[0].forEach(function (cell, index) {
                        if ([0, 2, 5].includes(index)) {
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
                            if ([0, 2, 5].includes(index)) {
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
            url: "../../controller/categoriaEstado.php?op=listar",
            type: "post"
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
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



function editar(id_estado) {
    $.post("../../controller/categoriaEstado.php?op=mostrarID", { id_estado: id_estado }, function (data) {
        data = JSON.parse(data);

        // TODO: Llenamos el formulario con los datos actuales
        $('#id_estado').val(data.ID_ESTADO);
        $('#descripcion').val(data.DESCRIPCION);
        $('#creado_por').val(data.CREADO_POR);
        $('#modificado_por').val(data.MODIFICADO_POR); // Nuevo: último usuario que modificó
        $('#fe_modificacion').val(data.FE_MODIFICACION); // Nuevo: última fecha de modificación
    });

    // TODO: Cambiamos el título del modal
    $('#lbl-titulo').html('Editar Registro');

    // TODO: Mostramos el modal
    $('#modal-Mantto').modal('show');
}

function eliminar(id_estado) {
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
            $.post("../../controller/categoriaEstado.php?op=eliminar", { id_estado: id_estado }, function (data) {
                console.log(data);
            });

            // TODO: Recargar tabla después de eliminar
            $('#table_data').DataTable().ajax.reload();

            Swal.fire({
                title: "¡Eliminado!",
                text: "Tu registro ha sido eliminado.",
                confirmButtonColor: "#329948ff",
                icon: "success"
            });
        }
    });
}

// TODO: Nuevo registro
$(document).on("click", "#btn-nuevo", function () {
    $('#id_estado').val('');
    $('#descripcion').val('');
    $('#lbl-titulo').html('Nuevo Registro');
    $("#mantto-form")[0].reset();
    $('#modal-Mantto').modal('show');
});

init();
