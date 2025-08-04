function init() {
    // TODO: Guardar/editar
    $("#mantto-form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mantto-form")[0]);
    var id_tipo_pago = $("#id_tipo_pago").val();
    $.ajax({
        url: "../../controller/categoriaTipoPago.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
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
            $('#modal-ManttoTipoPago').modal('hide');

            Swal.fire({
                title: "¡Éxito!",
                text: id_tipo_pago === "" ?
                    "El método de pago ha sido creado correctamente." :
                    "El método de pago ha sido actualizado correctamente.",
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
                exportOptions: { columns: [0,1,2,3,4,5] }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: { columns: [0,1,2,3,4,5] }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                exportOptions: { columns: [0,1,2,3,4,5] }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Reporte Métodos de Pago - Trasciende La Parroquia',
                exportOptions: { columns: [0,1,2,3,4,5] },
                customize: function (doc) {
                    // TODO: Título principal
                    doc.content.splice(0, 0, {
                        text: 'Trasciende La Parroquia',
                        fontSize: 18,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 10]
                    });
                    // TODO: Subtítulo con fecha
                    let hoy = new Date();
                    let fecha = hoy.getDate().toString().padStart(2, '0') + '-' + 
                                (hoy.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                                hoy.getFullYear();
                    doc.content.splice(1, 0, {
                        text: 'Reporte Métodos de Pago - Generado el: ' + fecha,
                        fontSize: 11,
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });

                    // TODO: Formato tabla
                    var table = doc.content[3];
                    table.layout = {
                        hLineWidth: function () { return 0.5; },
                        vLineWidth: function () { return 0.5; },
                        hLineColor: function () { return '#ccc'; },
                        vLineColor: function () { return '#ccc'; },
                        paddingLeft: function () { return 4; },
                        paddingRight: function () { return 4; }
                    };
                    table.table.widths = ['5%','25%','20%','15%','15%','20%'];

                    // TODO: Encabezados
                    var body = table.table.body;
                    body[0].forEach(function (cell, index) {
                        if ([0, 2, 5].includes(index)) {
                            cell.alignment = 'center';
                        } else {
                            cell.alignment = 'left';
                        }
                        cell.fillColor = '#0f5854';
                        cell.color = 'white';
                        cell.bold = true;
                    });
                    // TODO: Contenido
                    for (var i = 1; i < body.length; i++) {
                        body[i].forEach(function (cell, index) {
                            if ([0, 2, 5].includes(index)) {
                                cell.alignment = 'center';
                            } else {
                                cell.alignment = 'left';
                            }
                        });
                    }

                    doc.defaultStyle.fontSize = 10;

                    // TODO: Pie de página
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
            url: "../../controller/categoriaTipoPago.php?op=listar",
            type: "post"
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
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

function editar(id_tipo_pago) {
    $.post("../../controller/categoriaTipoPago.php?op=mostrarID", { id_tipo_pago: id_tipo_pago }, function (data) {
        data = JSON.parse(data);
        $('#id_tipo_pago').val(data.ID_TIPO_PAGO);
        $('#nombre_tipopago').val(data.NOMBRE_TIPOPAGO);
        $('#creado_por').val(data.CREADO_POR);
        $('#modificado_por').val(data.MODIFICADO_POR);
        $('#fe_modificacion').val(data.FE_MODIFICACION);
    });
    $('#lbl-titulo').html('Editar Método de Pago');
    $('#modal-ManttoTipoPago').modal('show');
}

function eliminar(id_tipo_pago) {
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
            $.post("../../controller/categoriaTipoPago.php?op=eliminar", { id_tipo_pago: id_tipo_pago }, function (data) {});
            $('#table_data').DataTable().ajax.reload();
            Swal.fire({
                title: "¡Eliminado!",
                text: "El método de pago ha sido eliminado.",
                confirmButtonColor: "#329948ff",
                icon: "success"
            });
        }
    });
}

// TODO: Botón nuevo
$(document).on("click", "#btn-nuevo", function () {
    $('#id_tipo_pago').val('');
    $('#nombre_tipopago').val('');
    $('#lbl-titulo').html('Nuevo Método de Pago');
    $("#mantto-form")[0].reset();
    $('#modal-ManttoTipoPago').modal('show');
});

init();