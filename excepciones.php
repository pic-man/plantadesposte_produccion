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
    <title>Excepciones</title>
    <?php include "encabezado.php"; ?> 
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
    <style>
        #jtable td {
            padding: 0rem 0.5rem 0rem 0.5rem;
        }

        #jtable td:nth-child(5) {
            padding: 0.3rem 0.5rem 0rem 0.5rem;
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?> 
    <?php include "menuizquierda.php"; ?> 

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Excepciones</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalExcepcion" title="Agregar Nueva Excepción" id="crearExcepcion">AGREGAR EXCEPCION</a>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sede</th>
                                        <th>Item</th>
                                        <th>Proceso</th>
                                        <th>Acciones</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modalExcepcion" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="modalExcepcionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h1 class="modal-title fs-5 w-100" id="modalExcepcionLabel">Agregar Excepción</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formExcepcion">
                    <!-- max-height: 70vh;  -->
                    <div class="modal-body" style="overflow-y: auto;">
                        <div class="row">

                            <input type="hidden" name="ExcepcionID" id="ExcepcionID">

                            <div class="col-md-12 mb-2">
                                <label for="destino" class="form-label fw-bold">Destino</label>
                                <select name="destino" id="destino" class="form-select select2" required>
                                    <option value="">Seleccione un destino</option>
                                </select>
                                <div class="alert alert-danger" id="destinoError" style="display: none;">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Ingrese un destino
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="item" class="form-label fw-bold">Item</label>
                                <select name="item" id="item" class="form-select select2" required>
                                    <option value="">Seleccione un item</option>
                                </select>
                                <div class="alert alert-danger" id="itemError" style="display: none;">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Ingrese un item
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="proceso" class="form-label fw-bold">Proceso</label>
                                <select name="proceso" id="proceso" class="form-select" required>
                                    <option value="">Seleccione un proceso</option>
                                    <option value="Marinado">Marinado</option>
                                    <option value="Sin Marinar">Sin Marinar</option>
                                </select>
                                <div class="alert alert-danger" id="procesoError" style="display: none;">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Ingrese un proceso
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary" id="btnGuardarExcepcion">Guardar</button>
                        <button type="button" class="btn btn-primary" id="btnEditarExcepcion" style="display: none;">Editar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "scripts.php"; ?> 
    <script src="js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#jtable").DataTable({
                responsive: true,
                processing: true,
                bDestroy: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "tablas/TablaExcepciones.php",
                    type: "POST"
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

            $("#destino").select2({
                theme: "bootstrap4",
                width: "100%",
                placeholder: "Seleccione un destino",
                allowClear: true,
                dropdownAutoWidth: true,
                dropdownParent: $("#modalExcepcion"),
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });

            $("#item").select2({
                theme: "bootstrap4",
                width: "100%",
                placeholder: "Seleccione un item",
                allowClear: true,
                dropdownAutoWidth: true,
                dropdownParent: $("#modalExcepcion"),
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });

            // Traer destinos
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    TraerDestinos: true
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        console.log(response);
                        $("#destino").html("");
                        $("#destino").append("<option value=''>Seleccione un destino</option>");
                        response.data.forEach(function (item) {
                            $("#destino").append("<option value='" + item.id + "'>" + item.empresa + " - " + item.sede + "</option>");
                        });
                    } else {
                        console.log(response);
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });

            // Traer items
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    TraerItemsSelect: true
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        console.log(response);
                        $("#item").html("");
                        $("#item").append("<option value=''>Seleccione un item</option>");
                        response.data.forEach(function (item) {
                            $("#item").append("<option value='" + item.item + "'>" + item.descripcion + "</option>");
                        });
                    } else {
                        console.log(response);
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

        $("#crearExcepcion").click(function (e) { 
            e.preventDefault();
            $("#ExcepcionID").val("");
            $("#modalExcepcionLabel").text("Agregar Excepción");
            $("#formExcepcion")[0].reset();
            $(".alert").hide();
            $("#formExcepcion").find("input, select").removeClass("is-invalid is-valid");
            $("#formExcepcion").find(".select2").each(function() {
                $(this).next('.select2-container').find('.select2-selection').removeClass("is-invalid is-valid");
                $(this).val("").trigger("change");
            });
            $("#btnGuardarExcepcion").attr("type", "submit");
            $("#btnGuardarExcepcion").show();
            $("#btnEditarExcepcion").attr("type", "button");
            $("#btnEditarExcepcion").hide();
        });

        function validarFormulario() {
            $(".alert").hide();
            $("#formExcepcion").find("input, select").removeClass("is-invalid is-valid");

            $("#formExcepcion").find("input:visible, select:visible").each(function () {
                const $element = $(this);
                
                if (this.checkValidity()) {
                    $element.addClass("is-valid");
                    
                    if ($element.hasClass('select2')) {
                        const $select2Container = $element.next('.select2-container');
                        $select2Container.find('.select2-selection').removeClass("is-invalid").addClass("is-valid");
                    }
                }
            });

            const invalidInputs = $("#formExcepcion").find("input:visible, select:visible").filter(function() {
                return !this.checkValidity();
            });

            if (invalidInputs.length > 0) {
                invalidInputs.each(function () {
                    const $element = $(this);
                    $element.addClass("is-invalid");
                    
                    if ($element.hasClass('select2')) {
                        const $select2Container = $element.next('.select2-container');
                        $select2Container.find('.select2-selection').removeClass("is-valid").addClass("is-invalid");
                    }
                    
                    $("#" + $element.attr("id") + "Error").show();
                    return false;
                });
                invalidInputs.first().focus();
                return false;
            }

            return true;
        }

        $("#btnGuardarExcepcion").click(function (e) { 
            e.preventDefault();
            if (validarFormulario()) {
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        GuardarExcepcion: $("#formExcepcion").serializeArray()
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == "success") {
                            $("#modalExcepcion").modal("hide");
                            $("#jtable").DataTable().ajax.reload();
                            Swal.fire({
                                icon: "success",
                                title: "Excepción guardada correctamente",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Error al guardar la excepción",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Error al guardar la excepción",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });

        function TraerExcepcion(id) {
            $("#ExcepcionID").val(id);
            $("#modalExcepcionLabel").text("Editar Excepción #" + id);
            $("#formExcepcion")[0].reset();
            $(".alert").hide();
            $("#formExcepcion").find("input, select").removeClass("is-invalid is-valid");
            $("#formExcepcion").find(".select2").each(function() {
                $(this).next('.select2-container').find('.select2-selection').removeClass("is-invalid is-valid");
                $(this).val("").trigger("change");
            });
            $("#btnGuardarExcepcion").attr("type", "button");
            $("#btnGuardarExcepcion").hide();
            $("#btnEditarExcepcion").attr("type", "submit");
            $("#btnEditarExcepcion").show();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    TraerExcepcion: id
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $("#modalExcepcion").modal("show");
                        var items = response.data;
                        $("#destino").val(items.destino).trigger("change.select2");
                        $("#item").val(items.item).trigger("change.select2");
                        $("#proceso").val(items.proceso).trigger("change");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function (response) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error al traer la excepción",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }

        $("#btnEditarExcepcion").click(function (e) { 
            e.preventDefault();
            if (validarFormulario()) {
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        EditarExcepcion: $("#formExcepcion").serializeArray()
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == "success") {
                            $("#modalExcepcion").modal("hide");
                            $("#jtable").DataTable().ajax.reload();
                            Swal.fire({
                                icon: "success",
                                title: "Excepción editada correctamente",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Error al editar la excepción",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Error al editar la excepción",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });

        function EliminarExcepcion(id) {
            Swal.fire({
                title: "¿Estás seguro de eliminar esta excepción?",
                text: "Esta acción no se puede deshacer",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            EliminarExcepcion: id
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.status == "success") {
                                $("#jtable").DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    title: "Excepción eliminada correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Error al eliminar la excepción",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function (response) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Error al eliminar la excepción",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>