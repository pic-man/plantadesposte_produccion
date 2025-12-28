<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitacora Lotes</title>
    <?php include "encabezado.php"; ?> 
    <!-- <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css"> -->
    <style>
        #jtable td {
            padding: 0rem 0.5rem 0rem 0.5rem;
        }

        #jtable td:nth-child(3) {
            padding: 0.3rem 0.5rem 0rem 0.5rem;
        }

        #TablaRecepcion_wrapper .row:nth-child(1) .col-sm-6:nth-child(2) {
            display: none !important;
        }

        #TablaDesprese_wrapper  .row:nth-child(1) .col-sm-6:nth-child(2) {
            display: none !important;
        }

        #TablaDespacho_wrapper  .row:nth-child(1) .col-sm-6:nth-child(2) {
            display: none !important;
        }

        #jtable_filter {
            display: none;
        }

        #jtable_length {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?> 
    <?php include "menuizquierda.php"; ?> 

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Bitacora Lotes</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <!-- <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalExcepcion" title="Agregar Nueva Excepción" id="crearExcepcion">AGREGAR EXCEPCION</a>
                            </center> -->
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Desprese | Despresado</th>
                                        <th>Despacho</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <tr>
                                        <th><input type="search" name="loteSearch" class="form-control form-control-sm" placeholder="Lote"></th>
                                        <th><input type="search" name="despreseSearch" class="form-control form-control-sm" placeholder="Desprese"></th>
                                        <th><input type="search" name="despachoSearch" class="form-control form-control-sm" placeholder="Despacho"></th>
                                        <th><input type="search" name="cantidadSearch" class="form-control form-control-sm" placeholder="Cantidad"></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="modalVerLotes" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="modalVerLotesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h1 class="modal-title fs-5 w-100" id="titulo">Lotes</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <table id="TablaRecepcion" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th colspan="6">Recepcion</th>
                                    </tr>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Fecha</th>
                                        <th>No. Guia</th>
                                        <th>No. Pollos</th>
                                        <th>Tipo Pollo</th>
                                        <th>Kilos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Recepción:</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="TotalRecepcionUnidades">0</th>
                                        <th>&nbsp;</th>
                                        <th id="TotalRecepcionKilos">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-md-12 mb-2">
                            <table id="TablaDesprese" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th colspan="8">Desprese / Despresado</th>
                                    </tr>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Lote</th>
                                        <th>Fecha</th>
                                        <th>No. Guia</th>
                                        <th>No. Pollos</th>
                                        <th>Tipo Pollo</th>
                                        <th>Tipo Uso</th>
                                        <th>Kilos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Desprese:</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="TotalDespreseUnidades">0</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="TotalDespreseKilos">0.00</th>
                                    </tr>
                                    <tr>
                                        <th>Saldo</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th id="saldoUnidades"></th>
                                        <td></td>
                                        <td></td>
                                        <th id="saldoKilos"></th>
                                    </tr>
                                    <tr>
                                        <th>Porcentaje Mejora:</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="porcentajeMejora">0.00%</th>
                                    </tr>
                                    
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table id="TablaDespacho" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th colspan="6">Despacho</th>
                                    </tr>
                                    <tr>
                                        <th>Tipo Guia</th>
                                        <th>Guia Desprese</th>
                                        <th>Fecha</th>
                                        <th>No. Guia</th>
                                        <th>No. Pollos</th>
                                        <th>Kilos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Despacho:</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="TotalDespachoUnidades">0</th>
                                        <th id="TotalDespachoKilos">0.00</th>
                                    </tr>
                                    <tr>
                                        <th>% Merma:</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th id="porcentajeMerma">0.00%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "scripts.php"; ?> 
    <!-- <script src="js/select2.min.js"></script> -->
    <script>
        /* let TotalKilos = 0;
        let TotalUnidades = 0; */

        $(document).ready(function () {
            $("#jtable").DataTable({
                responsive: true,
                processing: true,
                bDestroy: true,
                orderCellsTop: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "tablas/TablaBitacoraLotes.php",
                    type: "POST"
                },
                initComplete: function () {
                    const api = this.api();

                    api.columns().every(function(index) {
                        const column = this;

                        const input = $('tr:eq(1) th:eq(' + index + ') input', api.table().header());

                        input.on('input', function() {
                            const value = this.value;
                            if (column.search() !== value) {
                                column.search(value).draw();
                            }
                        });
                    });
                },
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Consultar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": ">>",
                        "sNext": ">",
                        "sPrevious": "<"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
            $("#jtable").css("width", "100%");
        });

        function verLote(lote, modulo) {
            $("#modalVerLotes").modal("show");
            $("#titulo").text("Lote: " + lote);

            $("#TablaRecepcion").DataTable({
                responsive: true,
                processing: true,
                bDestroy: true,
                paging: false,
                info: false,
                searching: true,
                orderCellsTop: true,
                order: [[1, "asc"]],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4]
                }],
                ajax: {
                    url: "tablas/TablaBitacoraRecepcion.php",
                    type: "POST",
                    data: function(d) {
                        d.lote = lote;
                    },
                    dataSrc: function (json) {
                        $("#TotalRecepcionKilos").text(parseFloat(json.TotalKilos).toFixed(2));
                        $("#TotalRecepcionUnidades").text(parseFloat(json.TotalUnidades).toFixed(0));
                        /* TotalKilos = json.TotalKilos;
                        TotalUnidades = json.TotalUnidades; */
                        return json.data;
                    }
                },
                initComplete: function () {
                    var div = $("<div>", {
                        class: "d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mb-2",
                        id: "divBuscador",
                    })

                    $('#TablaRecepcion_wrapper').children().eq(0).append(div);
                    $('#divBuscador').append('<div class="d-flex align-items-center"><label for="buscador" class="form-label fw-bold mb-0 me-2">Consultar:</label><input type="search" name="buscador" id="buscador" class="form-control form-control-sm" placeholder="Buscar"></div>');
                    
                    // Calcular totales después de que se complete la carga
                    setTimeout(calcularTotales, 200);
                },
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Consultar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": ">>",
                        "sNext": ">",
                        "sPrevious": "<"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
            $("#TablaRecepcion").css("width", "100%");
            
            $("#TablaDesprese").DataTable({
                responsive: true,
                processing: true,
                bDestroy: true,
                paging: false,
                info: false,
                searching: true,
                orderCellsTop: true,
                order: [[2, "asc"]],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5, 6]
                }],
                ajax: {
                    url: "tablas/TablaBitacoraDesprese.php",
                    type: "POST",
                    data: function(d) {
                        d.lote = lote;
                    },
                    dataSrc: function (json) {
                        /* $("#saldoUnidades").text((TotalUnidades - json.TotalUnidades).toFixed(0));
                        $("#saldoKilos").text( (TotalKilos - json.TotalKilos).toFixed(2)); */
                        $("#TotalDespreseUnidades").text(parseFloat(json.TotalUnidades).toFixed(0));
                        $("#TotalDespreseKilos").text(parseFloat(json.TotalKilos).toFixed(2));
                        return json.data;
                    }
                },
                initComplete: function () {
                    // Calcular totales después de que se complete la carga
                    setTimeout(calcularTotales, 200);
                },
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Consultar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": ">>",
                        "sNext": ">",
                        "sPrevious": "<"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
            $("#TablaDesprese").css("width", "100%");

            $("#TablaDespacho").DataTable({
                responsive: true,
                processing: true,
                bDestroy: true,
                paging: false,
                info: false,
                searching: true,
                orderCellsTop: true,
                order: [[3, "asc"]],
                /* columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4]
                }], */
                ajax: {
                    url: "tablas/TablaBitacoraDespacho.php",
                    type: "POST",
                    data: function(d) {
                        d.lote = lote;
                    },
                    dataSrc: function (json) {
                        $("#TotalDespachoUnidades").text(json.TotalUnidades);
                        $("#TotalDespachoKilos").text(json.TotalKilos);
                        return json.data;
                    }
                },

                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Consultar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": ">>",
                        "sNext": ">",
                        "sPrevious": "<"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
            $("#TablaDespacho").css("width", "100%");
        }

        // Función para calcular totales de kilos de ambas tablas
        function calcularTotales() {
            var totalRecepcion = 0;
            var totalRecepcionUnidades = 0;

            var totalDesprese = 0;
            var totalDespreseUnidades = 0;

            var totalDespacho = 0;
            var totalDespachoUnidades = 0;
            
            // Calcular total de kilos de la tabla de recepción (columna 5 - índice 5)
            $("#TablaRecepcion tbody tr").each(function() {
                var kilosText = $(this).find("td:eq(5)").text().trim();
                if (kilosText && kilosText !== "Total" && kilosText !== "") {
                    var kilos = parseFloat(kilosText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(kilos)) {
                        totalRecepcion += kilos;
                    }
                }

                var unidadesText = $(this).find("td:eq(3)").text().trim();
                if (unidadesText && unidadesText !== "Total" && unidadesText !== "") {
                    var unidades = parseFloat(unidadesText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(unidades)) {
                        totalRecepcionUnidades += unidades;
                    }
                }
            });
            
            // Calcular total de kilos de la tabla de desprese (columna 7 - índice 7)
            $("#TablaDesprese tbody tr").each(function() {
                var kilosText = $(this).find("td:eq(7)").text().trim();
                if (kilosText && kilosText !== "Total" && kilosText !== "") {
                    var kilos = parseFloat(kilosText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(kilos)) {
                        totalDesprese += kilos;
                    }
                }

                var unidadesText = $(this).find("td:eq(4)").text().trim();
                if (unidadesText && unidadesText !== "Total" && unidadesText !== "") {
                    var unidades = parseFloat(unidadesText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(unidades)) {
                        totalDespreseUnidades += unidades;
                    }
                }
            });

            $("#TablaDespacho tbody tr").each(function() {
                var unidadesText = $(this).find("td:eq(4)").text().trim();
                if (unidadesText && unidadesText !== "Total" && unidadesText !== "") {
                    var unidades = parseFloat(unidadesText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(unidades)) {
                        totalDespachoUnidades += unidades;
                    }
                }

                var kilosText = $(this).find("td:eq(5)").text().trim();
                if (kilosText && kilosText !== "Total" && kilosText !== "") {
                    var kilos = parseFloat(kilosText.replace(/[^\d.-]/g, ''));
                    if (!isNaN(kilos)) {
                        console.log(kilos);
                        totalDespacho += kilos;
                    }
                }
            });
            
            // Calcular saldo
            var saldoKilos = totalRecepcion - totalDesprese;
            var saldoUnidades = totalRecepcionUnidades - totalDespreseUnidades;
            
            // Actualizar los campos de totales en las tablas
            $("#saldoKilos").text(saldoKilos.toFixed(2));
            $("#saldoUnidades").text(saldoUnidades.toFixed(0));

            var porcentajeMejora = (totalDesprese - totalRecepcion) / totalRecepcion * 100;
            var porcentajeMerma = (totalDesprese - totalDespacho) / totalDespacho * 100;

            $("#porcentajeMejora").text(porcentajeMejora.toFixed(2) + "%");
            $("#porcentajeMerma").text(porcentajeMerma.toFixed(2) + "%");
        }

        $(document).on("input", "#buscador", function() {
            $("#TablaRecepcion").DataTable().search($(this).val()).draw();
            $("#TablaDesprese").DataTable().search($(this).val()).draw();
            $("#TablaDespacho").DataTable().search($(this).val()).draw();
            
            // Recalcular totales después de filtrar
            setTimeout(calcularTotales, 200);
        });
    </script>
</body>
</html>
