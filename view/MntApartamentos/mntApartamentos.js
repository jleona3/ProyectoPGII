// Lógica JS (listar, guardar, editar, eliminar)

function init() {
    // Guardar/Editar
    $("#condominio-form").on("submit", function (e) {
        guardaryeditar(e);
    });

    // Cargar estados al iniciar
    cargarEstados();
}

function cargarEstados(preselectedId = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "../../controller/estado.php?op=listar_todos",
            type: "GET",
            success: function(response) {
                let data = JSON.parse(response);
                let options = "<option value=''>Seleccione</option>";
                data.forEach(function(item) {
                    options += `<option value="${item.ID_ESTADO}">${item.DESCRIPCION}</option>`;
                });
                $("#id_estado").html(options);

                if (preselectedId) {
                    $("#id_estado").val(preselectedId);
                }

                resolve();
            },
            error: function() {
                reject("Error al cargar los estados");
            }
        });
    });
}


function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#condominio-form")[0]);
    var id_apto = $("#id_apto").val();
    $.ajax({
        url: "../../controller/condominio.php?op=guardaryeditar",
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
                    icon: "error",
                    confirmButtonColor: "#d33"
                });
                return;
            }

            $('#table_data').DataTable().ajax.reload(null, false);
            $('#modal-Condominio').modal('hide');

            Swal.fire({
                title: "¡Éxito!",
                text: id_apto === "" ? "El apartamento ha sido registrado correctamente." : "El apartamento ha sido actualizado correctamente.",
                icon: "success",
                confirmButtonColor: "#329948ff"
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
                exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9] }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9] }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9] }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Reporte de Apartamentos - Trasciende La Parroquia',
                exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9] },
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
                        text: 'Reporte de Apartamentos - Generado el: ' + fechaFormateada,
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
                    table.table.widths = ['4%', '6%', '6%', '8%', '7%', '13%', '15%', '13%', '13%', '15%'];

                    // ALINEACIÓN Y ESTILO
                    var body = table.table.body;
                    body[0].forEach(function (cell, index) {
                        if ([0,1,2,3,4,6,9].includes(index)) {  // Centrar
                            cell.alignment = 'center'; 
                        } else {                                // Izquierda
                            cell.alignment = 'left';
                        }
                        cell.fillColor = '#27AE60';
                        cell.color = 'white';
                        cell.bold = true;
                    });
                    for (var i = 1; i < body.length; i++) {
                        body[i].forEach(function (cell, index) {
                            if ([0,1,2,3,4,6,9].includes(index)) {
                                cell.alignment = 'center';
                            } else {
                                cell.alignment = 'left';
                            }
                        });
                    }

                    // Fuente general
                    doc.defaultStyle.fontSize = 10;

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
            }
        ],
        "ajax": {
            url: "../../controller/condominio.php?op=listar",
            type: "post"
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        // ======= AQUI CENTRAMOS LAS COLUMNAS DE BOTONES =======
        "columnDefs": [
            { "className": "text-center", "targets": [0,1,2,3,4] } // Ajusta si cambia el índice
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


function editar(id_apto) {
    $.post("../../controller/condominio.php?op=mostrarID", { id_apto: id_apto }, function (data) {
        data = JSON.parse(data);

        $('#id_apto').val(data.ID_APTO);
        $('#num_torre').val(data.NUM_TORRE);
        $('#nivel').val(data.NIVEL);
        $('#num_apto').val(data.NUM_APTO);
        $('#metros_m2').val(data.METROS_M2);

        cargarEstados(data.ID_ESTADO).then(() => {
            $('#lbl-titulo').html('Editar Apartamento');
            $('#modal-Condominio').modal('show');
        });
    });
}



function eliminar(id_apto) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/condominio.php?op=eliminar", { id_apto: id_apto }, function (data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $('#table_data').DataTable().ajax.reload();
                    Swal.fire("¡Eliminado!", "El apartamento ha sido eliminado.", "success");
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            });
        }
    });
}

$(document).on("click", "#btn-nuevo", function () {
    $('#condominio-form')[0].reset();
    $('#id_apto').val('');
    cargarEstados().then(() => {
        $('#lbl-titulo').html('Nuevo Apartamento');
        $('#modal-Condominio').modal('show');
    });
});

init();