function init() {
    // TODO: Guardar/Editar al enviar el formulario
    $("#mantto-form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData($("#mantto-form")[0]);
    var id_servicio = $("#id_servicio").val(); 
    $.ajax({
        url: "../../controller/categoriaServicio.php?op=guardaryeditar",
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
            // Diferimos la recarga para evitar reflow inmediato al cerrar modal
            /*setTimeout(() => {
                $('#table_data').DataTable().ajax.reload(null, false);
            }, 0);*/

            $('#modal-ManttoServicios').modal('hide');

            Swal.fire({
                title: "¡Éxito!",
                text: id_servicio === "" ? 
                      "El servicio ha sido creado correctamente." : 
                      "El servicio ha sido actualizado correctamente.",
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
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-7
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-7
                }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]  // Solo las columnas 1-7
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Reporte de Servicios - Trasciende La Parroquia',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Solo columnas visibles
                },
                customize: function (doc) {
                    // TODO: ENCABEZADO
                    doc.content.splice(0, 0, {
                        text: 'Trasciende La Parroquia',
                        fontSize: 18,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 10]
                    });
                    // TODO: Obtener fecha en formato dd-mm-aaaa
                    var fechaHoy = new Date();
                    var dia = ("0" + fechaHoy.getDate()).slice(-2);
                    var mes = ("0" + (fechaHoy.getMonth() + 1)).slice(-2);
                    var anio = fechaHoy.getFullYear();
                    var fechaFormateada = dia + "-" + mes + "-" + anio;

                    doc.content.splice(1, 0, {
                        text: 'Reporte de Servicios - Generado el: ' + fechaFormateada,
                        fontSize: 11,
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });

                    // TODO: FORMATO TABLA
                    var table = doc.content[3];
                    table.layout = {
                        hLineWidth: function () { return 0.5; },
                        vLineWidth: function () { return 0.5; },
                        hLineColor: function () { return '#ccc'; },
                        vLineColor: function () { return '#ccc'; },
                        paddingLeft: function () { return 4; },
                        paddingRight: function () { return 4; }
                    };
                    table.table.widths = ['3%', '15%', '25%', '15%', '15%', '12%', '15%'];

                    // TODO: ALINEACIÓN DE COLUMNAS
                    var body = table.table.body;
                    body[0].forEach(function (cell, index) {
                        if ([0, 3, 6].includes(index)) {
                            cell.alignment = 'center'; 
                        } else {
                            cell.alignment = 'left';
                        }
                        cell.fillColor = '#27AE60';
                        cell.color = 'white';
                        cell.bold = true;
                    });
                    for (var i = 1; i < body.length; i++) {
                        body[i].forEach(function (cell, index) {
                            if ([0, 3, 6].includes(index)) {
                                cell.alignment = 'center';
                            } else {
                                cell.alignment = 'left';
                            }
                        });
                    }

                    // TODO: Fuente
                    doc.defaultStyle.fontSize = 10;

                    // TODO: PIE DE PÁGINA
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
            url: "../../controller/categoriaServicio.php?op=listar",
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

function editar(id_servicio) {
    $.post("../../controller/categoriaServicio.php?op=mostrarID", { id_servicio: id_servicio }, function (data) {
        data = JSON.parse(data);

        $('#id_servicio').val(data.ID_SERVICIO);
        $('#servicio').val(data.SERVICIO);
        $('#descripcion').val(data.DESCRIPCION);
        $('#creado_por').val(data.CREADO_POR);
    });

    $('#lbl-titulo').html('Editar Servicio');
    $('#modal-ManttoServicios').modal('show');
}

function eliminar(id_servicio) {
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
            $.post("../../controller/categoriaServicio.php?op=eliminar", { id_servicio: id_servicio }, function (data) {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            Swal.fire({
                title: "¡Eliminado!",
                text: "El servicio ha sido eliminado.",
                confirmButtonColor: "#329948ff",
                icon: "success"
            });
        }
    });
}

$(document).on("click", "#btn-nuevo", function () {
    $('#id_servicio').val('');
    $('#servicio').val('');
    $('#descripcion').val('');
    $('#lbl-titulo').html('Nuevo Servicio');
    $("#mantto-form")[0].reset();
    $('#modal-ManttoServicios').modal('show');
});

init();