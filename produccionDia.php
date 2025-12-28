<?php
    session_start();
    if (!isset($_SESSION["usuario"])) {
        header("Location: cerrarsesion.php");
        exit();
    }
    require_once("modelo/funciones.php");
    $semana = semana_activa();
    $fechaInicio = $semana["fecha_inicio"]; // fecha_inicio
    $fechaFin    = $semana["fecha_fin"]; // fecha_fin

    // Definición de días de la semana (sin Domingo y LunesSD si no los quieres mostrar)
    $diasSemana = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado', 'Domingo', 'LunesSD');
    $diasConFechas = array();

    // Asignar nombre a cada día de la semana en base a fecha_inicio
    $fechaTmp = $fechaInicio;
    foreach ($diasSemana as $nombreDia) {
        $diasConFechas[$nombreDia] = date('d', strtotime($fechaTmp));
        $fechaTmp = date('Y-m-d', strtotime($fechaTmp . ' +1 days'));
    }

    /* print_r($diasConFechas);
    exit; */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produccion Dia | Programacion Canales</title>
    <?php include_once('encabezado.php'); ?> 
    <style>
        #TRegistroProduccion tr th, #TRegistroProduccion tr td {
            padding: 0.2rem !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
        }

        #TProduccion tr th, #TProduccion tr td {
            padding: 0.2rem !important;
        }

        .bg-success-light {
            background-color: #8fd24e !important;
        }

        .bg-warning-light {
            background-color: #fbc108 !important;
        }

        .text-danger {
            color:rgb(255, 0, 0) !important;
        }

        .modal-backdrop.fade.show {
            display: none !important;
        }

        .fondo-modal {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
    </style>

    <style>
        #modalCantidadProduccion .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        
        #modalCantidadProduccion .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            border-color: #667eea;
        }
        
        #modalCantidadProduccion .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        #modalCantidadProduccion .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        #modalCantidadProduccion .input-group-text {
            border-radius: 10px 0 0 10px;
        }
        
        #modalCantidadProduccion .form-control {
            border-radius: 0 10px 10px 0;
            border-left: none;
        }
        
        #modalCantidadProduccion .bg-light {
            background-color: #f8f9fa !important;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>
    <?php include_once('menu.php'); ?> 
    <?php include_once('menuizquierda.php'); ?> 
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Produccion Dia</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="align-items-center mt-2 mb-2 text-center gap-2 w-100">
                                <button class="btn btn-warning" style="font-weight: 700 !important;" title="Agregar Nueva Produccion" id="crearProduccion">AGREGAR PRODUCCION</button>
                                <div class="text-danger text-center" id="mensajeSemana" style="display: none;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Ya existe datos de la semana.
                                </div>
                            </div>

                            <table id="TProduccion" class="table table-bordered table-striped table-hover w-100 mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Semana</th>
                                        <th>Canales Cerdo</th>
                                        <th>Canales Res</th>
                                        <!-- <th>Produccion Cerdo</th>
                                        <th>Produccion Res</th> -->
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <section class="section">
    </main>

    <div class="modal fade fondo-modal" id="modalProduccion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalProduccionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center ms-4" id="modalProduccionLabel">Agregar Produccion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="TRegistroProduccion" class="table table-bordered table-striped table-hover w-100 mb-0">
                                <thead>
                                    <tr>
                                        <th colspan="4">Total Canales Semana</th>
                                        <th>Sede / Fecha</th>
                                        <th>Lunes / <?= $diasConFechas["Lunes"] ?></th>
                                        <th>Martes / <?= $diasConFechas["Martes"] ?></th>
                                        <th>Miercoles / <?= $diasConFechas["Miercoles"] ?></th>
                                        <th>Jueves / <?= $diasConFechas["Jueves"] ?></th>
                                        <th>Viernes / <?= $diasConFechas["Viernes"] ?></th>
                                        <th>Sabado / <?= $diasConFechas["Sabado"] ?></th>
                                        <th>&nbsp;</th>
                                        <th>Porcentaje Cerdo</th>
                                        <th>Porcentaje Res</th>
                                    </tr>
                                    <tr>
                                        <th>Cerdo</th>
                                        <th>Part.</th>
                                        <th>Res</th>
                                        <th>Part.</th>
                                        <th>&nbsp;</th>
                                        <th class="bg-success-light">Cerdo</th>
                                        <th class="bg-warning-light">Res</th>
                                        <th class="bg-success-light">Cerdo</th>
                                        <th class="bg-warning-light">Res</th>
                                        <th class="bg-success-light">Cerdo</th>
                                        <th class="bg-warning-light">Res</th>
                                        <th>&nbsp;</th>
                                        <th class="bg-success-light">&nbsp;</th>
                                        <th class="bg-warning-light">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" id="semanaTalTal" class="text-danger" style="background-color: transparent !important;">Semana Tal-Tal</th>
                                        <th class="text-danger" style="background-color: transparent !important;">Fecha Distribucion</th>
                                        <th style="background-color: transparent !important;">Martes / <?= $diasConFechas["Martes"] ?></th>
                                        <th style="background-color: transparent !important;">Miercoles / <?= $diasConFechas["Miercoles"] ?></th>
                                        <th style="background-color: transparent !important;">Jueves / <?= $diasConFechas["Jueves"] ?></th>
                                        <th style="background-color: transparent !important;">Viernes / <?= $diasConFechas["Viernes"] ?></th>
                                        <th style="background-color: transparent !important;">Sabado / <?= $diasConFechas["Sabado"] ?></th>
                                        <th style="background-color: transparent !important;">Lunes / <?= $diasConFechas["LunesSD"] ?></th>
                                        <th style="background-color: transparent !important;">&nbsp;</th>
                                        <th style="background-color: transparent !important;">&nbsp;</th>
                                        <th style="background-color: transparent !important;">&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-2">
                    <!-- <button type="button" id="GuardarProduccion" class="btn btn-primary">Guardar</button>
                    <button type="button" id="EditarProduccion" class="btn btn-primary" style="display: none;">Editar</button> -->
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Cantidad Producción -->
    <div class="modal fade fondo-modal" id="modalCantidadProduccion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCantidadProduccionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title w-100 text-center" id="modalCantidadProduccionLabel">
                        <i class="bi bi-clipboard-data-fill me-2"></i>
                        Agregar Cantidad de Producción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2 pb-2">
                    <div class="text-center mb-1">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light shadow-sm" style="width: 80px; height: 80px; background-color: #a9a9a954 !important;">
                            <i class="bi bi-calculator-fill text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <form id="formCantidadProduccion">
                        <input type="hidden" name="idProduccion" id="idProduccion">

                        <div class="mb-1">
                            <label for="cantidadProduccion" class="form-label fw-bold">
                                <i class="bi bi-hash text-primary me-1"></i>
                                Cantidad de Producción
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white border-0">
                                    <i class="bi bi-box-seam-fill"></i>
                                </span>
                                <input type="number" class="form-control border-primary" name="cantidadProduccion" id="cantidadProduccion" placeholder="Ingrese la cantidad..." required min="0" step="1" autocomplete="off" autofocus>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Ingrese la cantidad de unidades producidas
                            </div>
                        </div>
                        
                        <input type="hidden" name="sedeProduccion" id="sedeProduccion">
                        <input type="hidden" name="semanaProduccion" id="semanaProduccion">
                        <input type="hidden" name="diaSemanaProduccion" id="diaSemanaProduccion">
                        <input type="hidden" name="especieProduccion" id="especieProduccion">

                        <button type="submit" class="d-none" id="submitCantidadProduccion"></button>
                    </form>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-2 pb-4">
                    <button type="button" class="btn btn-success btn-lg px-4 shadow-sm" id="btnGuardarCantidad">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg px-4 shadow-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>    

    <?php include_once('scripts.php'); ?> 
    <script>
        $(document).ready(function () {
            $("#crearProduccion").click(function (e) { 
                e.preventDefault();
                $("#modalProduccion").modal("show");
                $("#TRegistroProduccion").DataTable({
                    processing: true,
                    bDestroy: true,
                    paging: false,
                    searching: false,
                    orderCellsTop: true,
                    info: false,
                    ordering: false,
                    ajax: {
                        url: "tablas/TablaProduccionDiaRegistro.php",
                        type: "post",
                        dataSrc: function (json) {
                            $("#modalProduccionLabel").html("PRODUCCION * DIA <span class='text-danger'>SEMANA " + json.semanaPlanta + " - " + json.semana + "</span>");
                            $("#semanaTalTal").html("SEMANA " + json.semanaPlanta + " - " + json.semana);
                            return json.data;
                        }
                    },
                    columnDefs: [
                        { targets: 0, className: 'bg-success-light' },
                        { targets: 2, className: 'bg-warning-light' },
                        { targets: 5, className: 'bg-success-light' },
                        { targets: 6, className: 'bg-warning-light' },
                        { targets: 7, className: 'bg-success-light' },
                        { targets: 8, className: 'bg-warning-light' },
                        { targets: 9, className: 'bg-success-light' },
                        { targets: 10, className: 'bg-warning-light' },
                        { targets: 12, className: 'bg-success-light' },
                        { targets: 13, className: 'bg-warning-light' }
                    ],
                    language: {
                        "searchPlaceholder": "Ingrese caracter",
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": " _START_ a _END_ de _TOTAL_ registros",
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
            });

            $("#TProduccion").DataTable({
                processing: true,
                bDestroy: true,
                ajax: {
                    url: "tablas/TablaProduccionDia.php",
                    type: "post",
                    dataSrc: function (json) {
                        return json.data;
                    }
                },
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_ registros",
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

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    ValidarDatosSemana: true
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "warning") {
                        $("#crearProduccion").prop("disabled", true);
                        $("#mensajeSemana").show();
                    } else {
                        $("#crearProduccion").prop("disabled", false);
                        $("#mensajeSemana").hide();
                    }
                }
            });
        });

        function AgregarProduccionDia(sede, semana, diaSemana, especie, maximo) {
            console.log("sede: " + sede + " semana: " + semana + " diaSemana: " + diaSemana + " especie: " + especie);
            
            $("#idProduccion").val("");
            $("#sedeProduccion").val(sede);
            $("#semanaProduccion").val(semana);
            $("#diaSemanaProduccion").val(diaSemana);
            $("#especieProduccion").val(especie);
            
            $("#cantidadProduccion").val("").removeClass("is-invalid is-valid").attr("max", maximo);
            $("#modalCantidadProduccion").modal("show");
            
            setTimeout(function() {
                $("#cantidadProduccion").focus();
            }, 500);
        }

        $("#submitCantidadProduccion").click(function (e) { 
            e.preventDefault();
            $("#btnGuardarCantidad").click();
        });

        $("#btnGuardarCantidad").click(function (e) { 
            e.preventDefault();
            var idProduccion = $("#idProduccion").val();
            var accion = idProduccion == "" ? "insertar" : "editar";
            var cantidad = $("#cantidadProduccion").val();
            var maximo = $("#cantidadProduccion").attr("max");
            var sede = $("#sedeProduccion").val();
            var semana = $("#semanaProduccion").val();
            var diaSemana = $("#diaSemanaProduccion").val();
            var especie = $("#especieProduccion").val();
            
            if (cantidad === "" || cantidad <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Por favor ingrese una cantidad válida',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            if (cantidad > parseInt(maximo)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'La cantidad no puede ser mayor a la cantidad total de canales: ' + maximo,
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            var datos = {
                idProduccion: idProduccion,
                sede: sede,
                semana: semana,
                diaSemana: diaSemana,
                especie: especie,
                cantidad: cantidad
            };
            
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    enviarProduccionDia: datos,
                    accion: accion
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $("#modalCantidadProduccion").modal("hide");
                        $("#TRegistroProduccion").DataTable().ajax.reload();
                        $("#TProduccion").DataTable().ajax.reload();
                    }

                    Swal.fire({
                        icon: response.status,
                        title: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error al agregar la produccion dia",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        function editarProduccionDia(id, cantidad, maximo) {
            console.log("Editando produccion dia con id: " + id);
            
            // Guardar datos en campos hidden
            $("#idProduccion").val(id);
            $("#sedeProduccion").val("");
            $("#semanaProduccion").val("");
            $("#diaSemanaProduccion").val("");
            $("#especieProduccion").val("");
            
            $("#cantidadProduccion").val(cantidad).removeClass("is-invalid is-valid").attr("max", maximo);
            $("#modalCantidadProduccion").modal("show");
            
            setTimeout(function() {
                $("#cantidadProduccion").focus();
            }, 500);
        }

        function EditarProduccionDia(semana) {
            $("#modalProduccion").modal("show");
            $("#TRegistroProduccion").DataTable({
                processing: true,
                bDestroy: true,
                paging: false,
                searching: false,
                orderCellsTop: true,
                info: false,
                ordering: false,
                ajax: {
                    url: "tablas/TablaProduccionDiaRegistro.php",
                    type: "post",
                    data: {
                        semana: semana
                    },
                    dataSrc: function (json) {
                        $("#modalProduccionLabel").html("PRODUCCION * DIA <span class='text-danger'>SEMANA " + json.semanaPlanta + " - " + json.semana + "</span>");
                        $("#semanaTalTal").html("SEMANA " + json.semanaPlanta + " - " + json.semana);
                        return json.data;
                    }
                },
                columnDefs: [
                    { targets: 0, className: 'bg-success-light' },
                    { targets: 2, className: 'bg-warning-light' },
                    { targets: 5, className: 'bg-success-light' },
                    { targets: 6, className: 'bg-warning-light' },
                    { targets: 7, className: 'bg-success-light' },
                    { targets: 8, className: 'bg-warning-light' },
                    { targets: 9, className: 'bg-success-light' },
                    { targets: 10, className: 'bg-warning-light' },
                    { targets: 12, className: 'bg-success-light' },
                    { targets: 13, className: 'bg-warning-light' }
                ],
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_ registros",
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
        }

        function EliminarProduccionDia(semana) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: '¿Estás seguro de querer eliminar los datos de la semana?',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#dc3545',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'si, Eliminar'
            }).then((result) => {
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        EliminarProduccionDia: semana
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == "success") {
                            $("#TProduccion").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error al eliminar la produccion dia",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
        }
    </script>
</body>
</html>