<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
//error_reporting(0);
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$years = Years();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planta de Desposte | Semanas</title>
    <?php include_once('encabezado.php'); ?>
    <style>
        #jtable th:nth-child(1),#jtable td:nth-child(1) {
            width: 10%;
        }
        #jtable th:nth-child(2),#jtable td:nth-child(2) {
            width: 10%;
        }
        #jtable th:nth-child(3),#jtable td:nth-child(3) {
            width: 23%;
        }
        #jtable th:nth-child(4),#jtable td:nth-child(4) {
            width: 23%;
        }
        #jtable th:nth-child(5),#jtable td:nth-child(5) {
            width: 23%;
        }
        #jtable th:nth-child(6),#jtable td:nth-child(6) {
            width: 10%;
        }

    </style>
</head>

<body>
    <?php include_once('menu.php'); ?>
    <?php include_once('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Administrar Semanas</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR SEMANA</a>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Año</th>
                                        <th>Semana</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Status</th>
                                        <th>Acción</th>
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
</body>

    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR SEMANA</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" id="id">

                        <div class="col-md-3 mb-3">
                            <label for="year" class="form-label" style="font-weight: bold;">Año</label>
                            <select class="form-select" id="year" name="year">
                                <option value="0">Seleccione un año</option>
                                <?php foreach($years as $year): ?>
                                    <option value="<?= $year[0] ?>"><?= $year[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="alert alert-danger " id="errorYear" style="display: none;">
                                <strong>Error:</strong> Debe ingresar un año
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="semana" class="form-label" style="font-weight: bold;">Semana</label>
                            <input class="form-control" id="semana" name="semana" type="number" required placeholder="Ingrese la semana">
                            <div class="alert alert-danger " id="SemanaError" style="display: none;">
                                <strong>Error:</strong> Debe ingresar la semana
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label" style="font-weight: bold;">Fecha Inicio</label>
                            <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" required placeholder="Ingrese la fecha de inicio">
                            <div class="alert alert-danger " id="FechaInicioError" style="display: none;">
                                <strong>Error:</strong> Debe ingresar una fecha de inicio
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label" style="font-weight: bold;">Fecha Fin</label>
                            <input class="form-control" id="fecha_fin" name="fecha_fin" type="date" required placeholder="Ingrese la fecha de final">
                            <div class="alert alert-danger " id="FechaFinError" style="display: none;">
                                <strong>Error:</strong> Debe ingresar una fecha fin
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label" style="font-weight: bold;">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Seleccione un status</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                            <div class="alert alert-danger " id="errorStatus" style="display: none;">
                                <strong>Error:</strong> Debe ingresar un status
                            </div>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
                            <button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once('scripts.php'); ?>
<script>
    $(document).ready(function() {
        $('#jtable').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "order": [
            [0, 'desc']
            ],
            "ajax": {
            url: "tablas/TablaSemana.php",
            type: "post"
            },
            "language": {
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
        $('#jtable').css('width', '100%');
        $('#jtable_filter input, #jtable_length select').addClass('form-control');
        $('#jtable_filter').find('label').find('input').attr("name", "input")
    });

    $('#crearProveedor').click(function() {
        $('#titulo').text('AGREGAR SEMANA');
        $('#year').prop('selectedIndex', 0);
        $("#semana").val("");
        $("#fecha_inicio").val("");
        $("#fecha_fin").val("");
        $('#status').prop('selectedIndex', 0);
        $('#errorYear').css('display', 'none');
        $('#SemanaError').css('display', 'none');
        $('#FechaInicioError').css('display', 'none');
        $('#FechaFinError').css('display', 'none');
        $('#errorStatus').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'initial');
        $('#btnEditProveedor').css('display', 'none');
    });

    function validaciones() {
        $('#errorYear').css('display', 'none');
        $('#SemanaError').css('display', 'none');
        $('#FechaInicioError').css('display', 'none');
        $('#FechaFinError').css('display', 'none');
        $('#errorStatus').css('display', 'none');

        if ($("#year").val() == "" || $("#year").val() == "0") {
            $('#errorYear').css('display', 'block');
            return 'R2';
        }

        if ($("#semana").val() == "" || $("#year").val() == "0") {
            $('#SemanaError').css('display', 'block');
            return 'R2';
        }

        if ($("#fecha_inicio").val() == "" || $("#fecha_inicio").val() == "0") {
            $('#FechaInicioError').css('display', 'block');
            return 'R2';
        }

        if ($("#fecha_fin").val() == "" || $("#fecha_fin").val() == "0") {
            $('#FechaFinError').css('display', 'block');
            return 'R2';
        }

        if ($("#status").val() == null || $("#status").val() == "0") {
            $('#errorStatus').css('display', 'block');
            return 'R3';
        }


        return "";
    }

    $('#btnNuevoProveedor').click(function() {
        if (validaciones() == "") {
            
            datos = {
                year: $('#year').val(),
                status: $('#status').val(),
                semana: $('#semana').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val()
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    aggSemana: datos
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal("hide");
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Año registrado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    function edit(id) {
        $('#id').val(id);
        $('#titulo').text('EDITAR SEMANA');
        $('#year').prop('selectedIndex', 0);
        $("#semana").val("");
        $("#fecha_inicio").val("");
        $("#fecha_fin").val("");
        $('#status').prop('selectedIndex', 0);
        $('#errorYear').css('display', 'none');
        $('#SemanaError').css('display', 'none');
        $('#FechaInicioError').css('display', 'none');
        $('#FechaFinError').css('display', 'none');
        $('#errorStatus').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'none');
        $('#btnEditProveedor').css('display', 'initial');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                idSemana: $("#id").val()
            },
            success: function(data) {
                $('#year').val(data[0][0]);
                $('#semana').val(data[0][1]);
                $('#fecha_inicio').val(data[0][2]);
                $('#fecha_fin').val(data[0][3]);
                $('#status').val(data[0][4]);
            }
        });
    }

    $('#btnEditProveedor').click(function() {
        if (validaciones() == "") {
            datos = {
                id: $('#id').val(),
                year: $('#year').val(),
                semana: $('#semana').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val(),
                status: $('#status').val()
            };
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    editSemana: datos
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal('hide');
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Año modificado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

</script>
<script>
    let timeout;

    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(logout, 1200000);
    }

    function logout() {
        
        window.location.href = 'cerrarsesion.php';
    }
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
</script>
</html>
