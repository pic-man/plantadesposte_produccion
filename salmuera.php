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
    <title>Planta de Desposte | Salmuera</title>
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
            <h1>Administrar Salmuera</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <center>
                                    <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevaSalmuera" title="Agregar Nueva Salmuera" id="crearSalmuera">AGREGAR SALMUERA</a>
                                </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Concentración</th>
                                        <th>Ingredientes</th>
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

    
    <div class="modal fade" id="modalNuevaSalmuera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR SALMUERA</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="formSalmuera">
                        <div class="row">

                            <input type="hidden" id="id">

                            <div class="col-md-12 mb-2">
                                <label for="concentracion" class="form-label">Concentración</label>
                                <input class="form-control" type="text" name="concentracion" autocomplete="off" required id="concentracion" placeholder="Ingrese la concentración">
                                <div class="alert alert-danger" role="alert" id="concentracionE" style="display: none">
                                    <i class="bi bi-exclamation-triangle"></i> Debe ingresar la concentración
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="ingredientes" class="form-label">Ingredientes</label>
                                <input class="form-control" type="text" name="ingredientes" autocomplete="off" required id="ingredientes" placeholder="Ingrese los ingredientes">
                                <div class="alert alert-danger" role="alert" id="ingredientesE" style="display: none">
                                    <i class="bi bi-exclamation-triangle"></i> Debe ingresar los ingredientes
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
                                <button type="submit" class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Salmuera" id="btnNuevaSalmuera">Guardar</button>
                                <button type="button" class="btn btn-primary" style="display:none;" rel="tooltip" data-placement="bottom" title="Editar Salmuera" id="btnEditSalmuera">Editar</button>
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
                    url: "tablas/TablaSalmuera.php",
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

        $('#crearSalmuera').click(function() {
            $('#titulo').text('AGREGAR SALMUERA');
            $("#formSalmuera").trigger('reset');
            $(".alert").hide();
            $('#btnNuevaSalmuera').attr("type", "submit");
            $('#btnNuevaSalmuera').show();
            $('#btnEditSalmuera').attr("type", "button");
            $('#btnEditSalmuera').hide();
        });

        function validarFormulario() {
            $(".alert").hide();

            $("#formSalmuera").find(":valid").each(function() {
                $("#" + $(this).attr("id")).removeClass("is-invalid");
            });
                
            if ($("#formSalmuera").find(":invalid").length > 0) {
                $("#formSalmuera").find(":invalid").each(function() {
                    $(this).addClass("is-invalid");
                    $("#" + $(this).attr("id") + "E").show();
                    $(this).focus();
                    return false;
                });
                return false;
            }

            return true;
        }

        $('#btnNuevaSalmuera').click(function(e) {
            e.preventDefault();
            if (validarFormulario()) { 
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        agregarSalmuera: $("#formSalmuera").serializeArray()
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            $('#jtable').DataTable().ajax.reload();
                            $("#modalNuevaSalmuera").modal('hide');
                            Swal.fire({
                                title: 'Salmuera registrada correctamente',
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
                            text: 'Error al registrar la salmuera',
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function TraerSalmuera(id) {
            $('#id').val(id);
            $('#titulo').text('EDITAR SALMUERA #' + id);
            $("#modalNuevaSalmuera").modal("show");
            $("#formSalmuera").trigger('reset');
            $(".alert").hide();
            $('#btnNuevaSalmuera').attr("type", "button");
            $('#btnNuevaSalmuera').hide();
            $('#btnEditSalmuera').attr("type", "submit");
            $('#btnEditSalmuera').show();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    traerSalmuera: id
                },
                success: function(data) {
                    if (data.status === "success") {
                        var salmuera = data.data;
                        $('#concentracion').val(salmuera.concentracion);
                        $('#ingredientes').val(salmuera.ingredientes);
                        $('#status').val(salmuera.status);
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
                        text: 'Error al buscar la salmuera',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        $("#btnEditSalmuera").click(function(e) {
            e.preventDefault();
            if (validarFormulario()) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        editSalmuera: $("#formSalmuera").serializeArray(),
                        id: $('#id').val()
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $("#modalNuevaSalmuera").modal('hide');
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Salmuera modificada satisfactoriamente',
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
                            text: 'Error al modificar la salmuera',
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function EliminarSalmuera(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará la salmuera seleccionada',
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
                            eliminarSalmuera: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == "success") {
                                $('#jtable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: 'Salmuera eliminada correctamente',
                                    text: '',
                                    icon: 'success',
                                    timer: 1300,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error1',
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
                                text: 'Error al eliminar la salmuera',
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
