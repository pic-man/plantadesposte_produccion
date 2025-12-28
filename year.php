<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
//error_reporting(0);
date_default_timezone_set("America/bogota");
//require_once('./modelo/funciones.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planta de Desposte | Años</title>
    <?php include_once('encabezado.php'); ?>
    <style>
        #jtable th:nth-child(1),#jtable td:nth-child(1) {
            width: 4%;
            center{
                margin-top: 11px;
            }
        }
        #jtable th,#jtable td {
            width: 20%;center{
                margin-top: 11px;
            }
        }
    </style>
</head>

<body>
    <?php include_once('menu.php'); ?>
    <?php include_once('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Administrar Años</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR AÑO</a>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Año</th>
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR AÑO</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" id="id">

                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label" style="font-weight: bold;">Año</label>
                            <input type="number" class="form-control" id="year" name="year" required placeholder="Ingrese un año">
                            <div class="alert alert-danger " id="errorYear" style="display: none;">
                                <strong>Error:</strong> Debe ingresar un año
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
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
            url: "tablas/TablaYear.php",
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
        $('#titulo').text('AGREGAR AÑO');
        $('#year').val('');
        $('#status').prop('selectedIndex', 0);
        $('#errorYear').css('display', 'none');
        $('#errorStatus').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'initial');
        $('#btnEditProveedor').css('display', 'none');
    });

    function validaciones() {
        $('#errorYear').css('display', 'none');
        $('#errorStatus').css('display', 'none');

        if ($("#year").val() == "" || $("#year").val() == "0") {
            $('#errorYear').css('display', 'block');
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
                status: $('#status').val()
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    aggYear: datos
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

    function editYear(id) {
        $('#id').val(id);
        $('#titulo').text('EDITAR AÑO');
        $('#year').val('');
        $('#status').prop('selectedIndex', 0);
        $('#errorYear').css('display', 'none');
        $('#errorStatus').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'none');
        $('#btnEditProveedor').css('display', 'initial');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                idYear: $("#id").val()
            },
            success: function(data) {
                $('#year').val(data[0][0]);
                $('#status').val(data[0][1]);
            }
        });
    }

    $('#btnEditProveedor').click(function() {
        if (validaciones() == "") {
            datos = {
                id: $('#id').val(),
                year: $('#year').val(),
                status: $('#status').val()
            };
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    editYear: datos
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