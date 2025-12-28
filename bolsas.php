<?php
session_start();
/* error_reporting(0);
date_default_timezone_set("America/bogota"); */
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Planta de Desposte | Bolsas</title>
    <?php include('encabezado.php'); ?> 
    <style>
        table th, table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
    <?php include('menu.php'); ?>
    <?php include('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Administrar Bolsas</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <center>
                                    <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevaBolsa" title="Agregar Nueva Bolsa" id="crearBolsa">AGREGAR BOLSA</a>
                                </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Capacidad</th>
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

    
    <div class="modal fade" id="modalNuevaBolsa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR BOLSA</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="formBolsa">
                        <div class="row">

                            <input type="hidden" id="id">

                            <div class="col-md-12 mb-2">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input class="form-control" type="text" name="descripcion" autocomplete="off" required id="descripcion" placeholder="Ingrese la descripción" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                <div class="alert alert-danger" role="alert" id="descripcionE" style="display: none">
                                    <i class="bi bi-exclamation-triangle"></i> Debe ingresar la descripción
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="capacidad" class="form-label">Capacidad (kg)</label>
                                <input class="form-control" type="number" name="capacidad" autocomplete="off" required id="capacidad" placeholder="Ingrese la capacidad" step="0.01">
                                <div class="alert alert-danger" role="alert" id="capacidadE" style="display: none">
                                    <i class="bi bi-exclamation-triangle"></i> Debe ingresar la capacidad
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Seleccione el Status</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                                <div class="alert alert-danger" role="alert" id="statusE" style="display: none">
                                    <i class="bi bi-exclamation-triangle"></i> Debe seleccionar el status
                                </div>
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Bolsa" id="btnNuevaBolsa">Guardar</button>
                                <button type="button" class="btn btn-primary" style="display:none;" rel="tooltip" data-placement="bottom" title="Editar Bolsa" id="btnEditBolsa">Editar</button>
                                <button class="btn btn-danger" data-bs-dismiss="modal" style="margin-left: 10px !important;">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('scripts.php'); ?>
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
                    url: "tablas/TablaBolsas.php",
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
            $('#jtable_filter').find('label').find('input').attr("name", "input");
        });

        $('#crearBolsa').click(function() {
            $('#titulo').text('AGREGAR BOLSA');
            $("#formBolsa").trigger('reset');
            $(".alert").hide();
            $('#btnNuevaBolsa').attr("type", "submit");
            $('#btnNuevaBolsa').show();
            $('#btnEditBolsa').attr("type", "button");
            $('#btnEditBolsa').hide();
        });

        function validarFormulario() {
            $(".alert").hide();

            $("#formBolsa").find(":valid").each(function() {
                $("#" + $(this).attr("id")).removeClass("is-invalid");
            });
                
            if ($("#formBolsa").find(":invalid").length > 0) {
                $("#formBolsa").find(":invalid").each(function() {
                    $(this).addClass("is-invalid");
                    $("#" + $(this).attr("id") + "E").show();
                    return false;
                });
                return false;
            }

            return true;
        }

        $('#btnNuevaBolsa').click(function(e) {
            e.preventDefault();
            if (validarFormulario()) { 
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        agregarBolsa: $("#formBolsa").serializeArray()
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            $('#jtable').DataTable().ajax.reload();
                            $("#modalNuevaBolsa").modal('hide');
                            Swal.fire({
                                title: 'Bolsa registrada correctamente',
                                text: '',
                                icon: 'success',
                                timer: 1300,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al registrar la bolsa',
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function buscarBolsa(id) {
            $('#id').val(id);
            $('#titulo').text('EDITAR BOLSA #' + id);
            $("#modalNuevaBolsa").modal("show");
            $("#formBolsa").trigger('reset');
            $(".alert").hide();
            $('#btnNuevaBolsa').attr("type", "button");
            $('#btnNuevaBolsa').hide();
            $('#btnEditBolsa').attr("type", "submit");
            $('#btnEditBolsa').show();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    traerBolsa: id
                },
                success: function(data) {
                    if (data.status === "success") {
                        var bolsa = data.data;
                        $('#descripcion').val(bolsa.descripcion);
                        $('#capacidad').val(bolsa.capacidad);
                        $('#status').val(bolsa.status);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al buscar la bolsa',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        $("#btnEditBolsa").click(function(e) {
            e.preventDefault();
            if (validarFormulario()) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        editBolsa: $("#formBolsa").serializeArray(),
                        id: $('#id').val()
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $("#modalNuevaBolsa").modal('hide');
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                            title: 'Bolsa modificada satisfactoriamente',
                            text: '',
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al modificar la bolsa',
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function eliminarBolsa(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará la bolsa seleccionada',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'controlador/controlador.php',
                        data: {
                            eliminarBolsa: id
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                $('#jtable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: 'Bolsa eliminada correctamente',
                                    text: '',
                                    icon: 'success',
                                    timer: 1300,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al eliminar la bolsa',
                                icon: 'error',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }
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
</body>
</html>
