<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
/* error_reporting(0); */
date_default_timezone_set("America/bogota"); 
include("modelo/funciones.php");
$listaResponsables = listaResponsables();
$listaConductores = listaConductores();
$listaPlacas = listaPlacas();
$listaDestinos = listaDestinos();
$items = items_despacho_panificacion();
$bolsas = traer_bolsas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planta de Panificación | Despacho</title>
    <?php include_once("encabezado.php");?>
    <style>
        .modal-header h6 {
            margin-bottom: 0px;
        }

        #jtable td, #jtable th {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
    <?php include_once("menu.php");?>
    <?php include_once("menuizquierda.php");?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Despacho</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <?php if ($_SESSION["usuario"] != "1106781852") : ?>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA</a>
                                <?php endif; ?>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Id Tipo</th>
                                        <th>Fecha Registro<br>Tipo Despacho</th>
                                        <th>Destino<br>Lotes</th>
                                        <!-- <th>Nro Panes</th> -->
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


    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;" aria-label="Close"></button>
                    <h6 class="w-100" style="font-weight: bold;" id="titulo">AGREGAR GUIA</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">

                            <input type="hidden" name="idGuia" id="idGuia">

                            <div class="col-md-6 mb-2">
                                <label for="fechaRegistro" class="form-label" style="font-weight: bold;">Fecha de Registro</label>
                                <input type="date" class="form-control" autocomplete="off" id="fechaRegistro" name="fechaRegistro" placeholder="Ingrese fecha Registro">
                                <div class="alert alert-danger" role="alert" id="fechaRegistroE" style="display: none;">
                                    <strong>Error: </strong>Debe ingresar la fecha de registro
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="tipoDespacho" class="form-label" style="font-weight: bold;">Tipo de Despacho</label>
                                <select class="form-control" id="tipoDespacho" name="tipoDespacho">
                                    <option value="">Seleccione el Tipo de Despacho</option>
                                    <!-- <option value="HARINAS">Harinas</option>
                                    <option value="PANES">Panes</option> -->
                                    <option value="PRODUCTO TERMINADO PANIFICADORA">Producto Terminado Panificadora</option>
                                    <option value="INSUMOS PARA PRODUCCION INTERNA">Insumos para Produccion Interna</option>
                                    <option value="INSUMO PARA SEDES">Insumo para Sedes</option>
                                </select>
                                <div class="alert alert-danger" role="alert" id="tipoDespachoE" style="display: none">
                                    <strong>Error: </strong>Debe seleccionar el tipo de despacho
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="responsable" class="form-label" style="font-weight: bold;">Responsable Despacho</label>
                                <select class="form-control" id="responsable" name="responsable" disabled>
                                    <option value="">Seleccione Responsable de Despacho</option>
                                    <?php foreach ($listaResponsables as $perm): ?>
                                        <option value="<?= $perm["cedula"] ?>"><?= $perm["nombres"] . " - " . $perm["cedula"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
                                    <strong>Error: </strong>Debe de ingresar el responsable
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="destino" class="form-label" style="font-weight: bold;">Destino</label>
                                <select class="form-control" id="destino" name="destino">
                                    <option value="">Seleccione Destino de Despacho</option>
                                    <?php foreach ($listaDestinos as $perm) : ?>
                                        <option value="<?= $perm["id"] ?>"><?= $perm["empresa"] . " - " . $perm["sede"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="destinoE" style="display: none">
                                    <strong>Error: </strong>Debe seleccinar el destino del despacho
                                </div>
                            </div>

                            <div class="col-md-6 mb-2" name="DivTransporte">
                                <label for="conductor" class="form-label" style="font-weight: bold;">Nombre del Conductor</label>
                                <select class="form-control" id="conductor" name="conductor">
                                    <option value="">Seleccione el Conductor de Despacho</option>
                                    <?php foreach ($listaConductores as $perm) : ?>
                                        <option value="<?= $perm["cedula"] ?>"><?= $perm["nombres"] . " - " . $perm["cedula"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="conductorE" style="display: none">
                                    <strong>Error: </strong>Debe seleccinar el conductor
                                </div>
                            </div>

                            <div class="col-md-6 mb-2" name="DivTransporte">
                                <label for="placa" class="form-label" style="font-weight: bold;">Placa Vehiculo</label>
                                <select class="form-control" id="placa" name="placa">
                                    <option value="">Seleccione la Placa del Vehiculo</option>
                                    <?php foreach ($listaPlacas as $perm) : ?>
                                        <option value="<?= $perm["placa"] ?>"><?= $perm["placa"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="placaE" style="display: none">
                                    <strong>Error: </strong>Debe seleccinar la placa del vehiculo
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="observaciones" class="form-label" style="font-weight: bold;">Observaciones</label>
                                <input type="text" class="form-control" autocomplete="off" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
                            </div>

                            <div class="col-md-12 mt-3 text-center" name="DivTransporte">
                                <label style="font-weight: bold;">CUMPLIMIENTO CONDICIONES HIGIÉNICO SANITARIAS DEL VEHÍCULO DE TRANSPORTE</label>
                            </div>

                            <div class="col-md-6" name="DivTransporte">
                                <input type="checkbox" class="form-check-input" id="checkbox1" name="checkbox1" value="1">&nbsp;
                                <label for="checkbox1">¿Vehiculo Limpio?</label>
                            </div>

                            <div class="col-md-6" name="DivTransporte">
                                <input type="checkbox" class="form-check-input" id="checkbox2" name="checkbox2" value="1">&nbsp;
                                <label for="checkbox2" style="font-size: 14px">¿Ausencia de Sustancias Peligrosas?</label>
                            </div>

                            <div class="col-md-6" name="DivTransporte">
                                <input type="checkbox" class="form-check-input" id="checkbox3" name="checkbox3" value="1">&nbsp;
                                <label for="checkbox3">¿Libre de Olores Fuertes?</label>
                            </div>

                            <div class="col-md-6" name="DivTransporte">
                                <input type="checkbox" class="form-check-input" id="checkbox4" name="checkbox4" value="1">&nbsp;
                                <label for="checkbox4" style="font-size: 14px">¿Usa Estibas?</label>
                            </div>

                            <div class="col-md-6" name="DivTransporte">
                                <input type="checkbox" class="form-check-input" id="checkbox5" name="checkbox5" value="1">&nbsp;
                                <label for="checkbox5">¿Ausente de Plagas?</label>
                            </div>

                            <div class="col-md-12" style="text-align: center;">
                                <button type="submit" class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
                                <button type="submit" class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalItems" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;" aria-label="Close"></button>
                    <h6 style="font-weight: bold;" class="w-100" id="tituloI">Consecutivo Guia: 1</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">

                            <input type="hidden" id="idItem">
                            <input type="hidden" id="tipo_despacho">
                            
                            <div class="col-md-2 mb-2">
                                <label for="item" class="form-label" style="font-weight: bold;">Item</label>
                                <select name="item" id="item" class="form-select">
                                    <option value="">Seleccione un item</option>
                                    <?php foreach ($items as $item) : ?>
                                        <option value="<?= $item["item"] ?>"><?= $item["descripcion"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="itemE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar un item
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label for="loteItem" class="form-label" style="font-weight: bold;">Lote</label>
                                <input type="text" name="loteItem" id="loteItem" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                                <div class="alert alert-danger" role="alert" id="loteItemE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar el lote
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="tipoBolsa" class="form-label" style="font-weight: bold;">Tipo de Bolsa</label>
                                <select name="tipoBolsa" id="tipoBolsa" class="form-select">
                                    <option value="">Seleccione el tipo de bolsa</option>
                                    <?php foreach ($bolsas as $bolsa) : ?>
                                        <option value="<?= $bolsa["id"] ?>"><?= $bolsa["descripcion"] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="alert alert-danger" role="alert" id="tipoBolsaE" style="display: none">
                                    <strong>Error: </strong>Debe seleccionar el tipo de bolsa
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label for="unidades" class="form-label" style="font-weight: bold;">Unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control" autocomplete="off" placeholder="Ingrese las unidades">
                                <div class="alert alert-danger" role="alert" id="unidadesE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar las unidades
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="kilos" class="form-label" style="font-weight: bold;">Kilos</label>
                                <input type="number" name="kilos" id="kilos" class="form-control" autocomplete="off" disabled placeholder="Ingrese los kilos">
                                <div class="alert alert-danger" role="alert" id="kilosE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar los kilos
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label for="canastillas" class="form-label" style="font-weight: bold;">Canastillas</label>
                                <input type="number" name="canastillas" id="canastillas" class="form-control" autocomplete="off" disabled placeholder="Ingrese las canastillas">
                                <div class="alert alert-danger" role="alert" id="canastillasE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar las canastillas
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label for="caastillasBase" class="form-label" style="font-weight: bold;">Canastillas Base</label>
                                <input type="number" name="caastillasBase" id="caastillasBase" class="form-control" autocomplete="off" placeholder="Ingrese las canastillas base">
                                <div class="alert alert-danger" role="alert" id="caastillasBaseE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar las canastillas base
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label for="fechaVencimiento" class="form-label" style="font-weight: bold;">Fecha de Vencimiento</label>
                                <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control" autocomplete="off" placeholder="Ingrese la fecha de vencimiento">
                                <div class="alert alert-danger" role="alert" id="fechaVencimientoE" style="display: none">
                                    <strong>Error: </strong>Debe ingresar la fecha de vencimiento
                                </div>
                            </div>

                            <?php if ($_SESSION["usuario"] != "1106781852") : ?>
                            <div class="col-md-12 mt-3 mb-2 text-center">
                                <button type="submit" class="btn btn-primary" rel="tooltip" data-placement="bottom" id="AggItem">Guardar</button>
                                <button type="submit" class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" id="EditItem">Editar</button>
                                <button type="button" class="btn btn-danger" style="margin-right: 1rem !important;" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="TItems" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Lote</th>
                                        <th>Tipo de Bolsa</th>
                                        <th>Unidades</th>
                                        <th>Kilos</th>
                                        <th>Canastillas</th>
                                        <th>Canastillas Base</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Fecha Proceso</th>
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
		</div>
	</div>
    
    <?php require_once("scripts.php");?>
<script>
    $(document).ready(function () {
        $("#jtable").DataTable({
            "responsive": true,
            "bDestroy": true,
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [
                {
                    "targets": 4,
                    "visible": <?php echo $_SESSION["usuario"] == "1106781852" ? "false" : "true"; ?>
                }
            ],
            "ajax": {
                url: "tablas/TablaDespachoPanificacion.php",
                type: "post"
            },
            "language": {
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
		$("#jtable_filter input, #jtable_length select").addClass("form-control");
        $('#jtable_filter').find('label').find('input').attr("name", "input")
    });

    function limpiarCampos() {
        var fecha = new Date();
        if (fecha.getDay() === 6) {
            fecha.setDate(fecha.getDate() + ((1 + 7 - fecha.getDay()) % 7));
        } else {
            fecha.setDate(fecha.getDate() + 1);
        }
        $("#fechaRegistro").val(fecha.toISOString().split('T')[0]);
        $("#tipoDespacho").val("");
        $("#responsable").val("<?= $_SESSION["usuario"] ?>");
        $("#destino").val("");
        $("#conductor").val("");
        $("#placa").val("");
        $("#observaciones").val("");
        $("#checkbox1").prop("checked", false);
        $("#checkbox2").prop("checked", false);
        $("#checkbox3").prop("checked", false);
        $("#checkbox4").prop("checked", false);
        $("#checkbox5").prop("checked", false);
        $("#fechaRegistroE").css("display", "none");
        $("#tipoDespachoE").css("display", "none");
        $("#responsableE").css("display", "none");
        $("#destinoE").css("display", "none");
        $("#conductorE").css("display", "none");
        $("#placaE").css("display", "none");
    }

    $("#crearProveedor").click(function (e) { 
        e.preventDefault();
        $("#titulo").text("Agregar Guia");
        limpiarCampos();
        $("#btnEditProveedor").css("display", "none");
        $("#btnNuevoProveedor").css("display", "initial");
    });

    $("#tipoDespacho").change(function () {
        if ($(this).val() == "PRODUCTO TERMINADO PANIFICADORA" || $(this).val() == "INSUMO PARA SEDES") {
            console.log("PANES");
            $("#destino").val("");
            $("[name=DivTransporte]").show();
        } else if ($(this).val() == "INSUMOS PARA PRODUCCION INTERNA") {
            console.log("HARINAS");
            $("#destino").val("14");
            $("[name=DivTransporte]").hide();
        } else {
            $("#destino").val("");
            $("[name=DivTransporte]").show();
        }
    });

    function validaciones() {
        $("#fechaRegistroE").css("display", "none");
        $("#tipoDespachoE").css("display", "none");
        $("#responsableE").css("display", "none");
        $("#destinoE").css("display", "none");
        $("#conductorE").css("display", "none");
        $("#placaE").css("display", "none");

        if ($("#fechaRegistro").val() == "" || $("#fechaRegistro").val() == null) {
            $("#fechaRegistroE").css("display", "block");
            return false;
        }

        if ($("#tipoDespacho").val() == "") {
            $("#tipoDespachoE").css("display", "block");
            return false;
        }

        if ($("#responsable").val() == "") {
            $("#responsableE").css("display", "block");
            return false;
        }

        if ($("#destino").val() == "") {
            $("#destinoE").css("display", "block");
            return false;
        }

        if ($("#tipoDespacho").val() != "INSUMOS PARA PRODUCCION INTERNA") {
            if ($("#conductor").val() == "") {
                $("#conductorE").css("display", "block");
                return false;
            }

            if ($("#placa").val() == "") {
                $("#placaE").css("display", "block");
                return false;
            }
        }

        return true;
    }

    $("#btnNuevoProveedor").click(function (e) { 
        e.preventDefault();
        if (validaciones()) {
            $("#btnNuevoProveedor").prop('disabled', true);
            datos = {
                fechaRegistro: $("#fechaRegistro").val(),
                tipoDespacho: $("#tipoDespacho").val(),
                responsable: $("#responsable").val(),
                destino: $("#destino").val(),
                conductor: $("#conductor").val(),
                placa: $("#placa").val(),
                observaciones: $("#observaciones").val(),
                vehiculoLimpio: $("#checkbox1").is(":checked") ? 1 : 0,
                sustancias: $("#checkbox2").is(":checked") ? 1 : 0,
                olores: $("#checkbox3").is(":checked") ? 1 : 0,
                estibas: $("#checkbox4").is(":checked") ? 1 : 0,
                plagas: $("#checkbox5").is(":checked") ? 1 : 0,
            }
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    AggPanificacion: datos
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $("#modalNuevoProveedor").modal("hide");
                        $("#jtable").DataTable().ajax.reload();
                        Swal.fire({
                            title: data.message,
                            text: "",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    $("#btnNuevoProveedor").prop('disabled', false);
                },
                error: function (data) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Error al agregar el despacho",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $("#btnNuevoProveedor").prop('disabled', false);
                }
            });
        }
    });

    function TraerGuia(id) {
        $("#titulo").text("Editar Guia");
        $("#idGuia").val(id);
        limpiarCampos();
        $("#btnNuevoProveedor").css("display", "none");
        $("#btnEditProveedor").css("display", "initial");
        $.ajax({
            type: "POST",
            url: "controlador/controlador.php",
            data: {
                TraerDespachoP: id
            },
            dataType: "json",
            success: function (data) {
                if (data.status == "success") {
                    var despacho = data.data;
                    $("#fechaRegistro").val(despacho.fecha_registro);
                    $("#tipoDespacho").val(despacho.tipo_despacho);
                    if (despacho.tipo_despacho == "INSUMOS PARA PRODUCCION INTERNA") {
                        $("[name=DivTransporte]").hide();
                    } else {
                        $("[name=DivTransporte]").show();
                    }
                    $("#responsable").val(despacho.responsable);
                    $("#destino").val(despacho.destino);
                    $("#conductor").val(despacho.conductor);
                    $("#placa").val(despacho.placa);
                    $("#observaciones").val(despacho.observaciones);
                    $("#checkbox1").prop("checked", despacho.check1 == 1);
                    $("#checkbox2").prop("checked", despacho.check2 == 1);
                    $("#checkbox3").prop("checked", despacho.check3 == 1);
                    $("#checkbox4").prop("checked", despacho.check4 == 1);
                    $("#checkbox5").prop("checked", despacho.check5 == 1);
                } else {
                    Swal.fire({
                        title: "¡Error!",
                        text: data.message,
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function (data) {
                Swal.fire({
                    title: "¡Error!",
                    text: "Error al traer el despacho",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    $("#btnEditProveedor").click(function (e) { 
        e.preventDefault();
        if (validaciones()) {
            $("#btnEditProveedor").prop('disabled', true);
            datos = {
                fechaRegistro: $("#fechaRegistro").val(),
                tipoDespacho: $("#tipoDespacho").val(),
                responsable: $("#responsable").val(),
                destino: $("#destino").val(),
                conductor: $("#conductor").val(),
                placa: $("#placa").val(),
                observaciones: $("#observaciones").val(),
                vehiculoLimpio: $("#checkbox1").is(":checked") ? 1 : 0,
                sustancias: $("#checkbox2").is(":checked") ? 1 : 0,
                olores: $("#checkbox3").is(":checked") ? 1 : 0,
                estibas: $("#checkbox4").is(":checked") ? 1 : 0,
                plagas: $("#checkbox5").is(":checked") ? 1 : 0,
                id: $("#idGuia").val()
            }
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    EditarDespachoP: datos
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $("#modalNuevoProveedor").modal("hide");
                        $("#jtable").DataTable().ajax.reload();
                        Swal.fire({
                            title: data.message,
                            text: "",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    $("#btnEditProveedor").prop('disabled', false);
                },
                error: function (data) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Error al editar el despacho",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $("#btnEditProveedor").prop('disabled', false);
                }
            });
        }
    });

    function limpiarCamposItems() {
        $("#item").val("");
        $("#loteItem").val("");
        $("#tipoBolsa").val("");
        $("#unidades").val("");
        $("#kilos").val("");
        $("#canastillas").val("");
        $("#caastillasBase").val("");
        $("#fechaVencimiento").val("");
        $("#itemE").css("display", "none");
        $("#loteItemE").css("display", "none");
        $("#tipoBolsaE").css("display", "none");
        $("#kilosE").css("display", "none");
        $("#unidadesE").css("display", "none");
        $("#canastillasE").css("display", "none");
        $("#caastillasBaseE").css("display", "none");
        $("#fechaVencimientoE").css("display", "none");
    }

    function camposPanes() {
        $("#item").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("#loteItem").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("#unidades").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("#canastillas").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("#caastillasBase").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("label[for='fechaVencimiento']").text("Fecha de Vencimiento");
        $("#fechaVencimiento").removeAttr("max");
        $("#fechaVencimiento").closest("div").removeClass("col-md-3").addClass("col-md-2");
        $("#tipoBolsa").closest("div").hide();
        $("#kilos").closest("div").hide();
    }

    function camposHarinas() {
        $("#item").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("#loteItem").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("#unidades").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("#canastillas").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("#caastillasBase").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("label[for='fechaVencimiento']").text("Fecha de Proceso");
        $("#fechaVencimiento").attr("max", new Date().toISOString().split('T')[0]);
        $("#fechaVencimiento").closest("div").removeClass("col-md-2").addClass("col-md-3");
        $("#tipoBolsa").closest("div").show();
        $("#kilos").closest("div").show();
    }

    function traerItems(id) {
        $.ajax({
            type: "POST",
            url: "controlador/controlador.php",
            data: {
                TraerItemsDP: id
            },
            dataType: "json",
            success: function (data) {
                if (data.status == "success") {
                    var items = data.data;
                    $("#item").empty();
                    $("#item").append("<option value=''>Seleccione un item</option>");
                    for (var i = 0; i < items.length; i++) {
                        $("#item").append("<option value='" + items[i].item + "'>" + items[i].descripcion + "</option>");
                    }
                } else {
                    Swal.fire({
                        title: "¡Error!",
                        text: data.message,
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function (data) {
                Swal.fire({
                    title: "¡Error!",
                    text: "Error al traer los items",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    function abrirModal(id, tipo_despacho) {
        $("#tituloI").html("Guia #" + id + " - " + tipo_despacho + "<br>Agregar Item");
        $("#idGuia").val(id);
        $("#tipo_despacho").val(tipo_despacho);
        limpiarCamposItems();
        if (tipo_despacho == "PRODUCTO TERMINADO PANIFICADORA") {
            console.log("entro panes");
            camposPanes();
            /* $('#TItems thead th:eq(8)').text('Fecha Vencimiento'); */
        } else {
            console.log("entro harinas");
            camposHarinas();
            /* $('#TItems thead th:eq(8)').text('Fecha Proceso'); */
        }
        if (tipo_despacho == "INSUMO PARA SEDES"){
            $("#canastillas").prop("disabled", true);
        }else{
            $("#canastillas").prop("disabled", false);
        }
        $("#TItems").DataTable({
            "responsive": true,
            "bDestroy": true,
            "ajax": {
                url: "tablas/TablaDespachoPanificacionItems.php",
                type: "post",
                data: {
                    id: id
                }
            },
            "columnDefs": [
                {
                    "targets": [3, 5, 9], // Índices de las columnas Tipo de Bolsa y Kilos
                    "visible": tipo_despacho != "PRODUCTO TERMINADO PANIFICADORA"
                },
                {
                    "targets": [8],
                    "visible": tipo_despacho == "PRODUCTO TERMINADO PANIFICADORA"
                }
            ],
            "language": {
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
        
        $("#EditItem").hide();
        $("#AggItem").show();
        $("#TItems").css("width", "100%");
		$("#TItems_filter input, #TItems_length select").addClass("form-control");
        $('#TItems_filter').find('label').find('input').attr("name", "input")
        traerItems(id);
    }

    async function validacionesItems() {
        $("#itemE").css("display", "none");
        $("#loteItemE").css("display", "none");
        $("#tipoBolsaE").css("display", "none");
        $("#kilosE").css("display", "none");
        $("#unidadesE").css("display", "none");
        $("#canastillasE").css("display", "none");
        $("#caastillasBaseE").css("display", "none");
        $("#fechaVencimientoE").css("display", "none");

        if ($("#item").val() == "") {
            $("#itemE").css("display", "block");
            return false;
        }

        if ($("#loteItem").val() == "" || $("#loteItem").val() == null) {
            $("#loteItemE").css("display", "block");
            return false;
        }

        if ($("#tipo_despacho").val() != "PRODUCTO TERMINADO PANIFICADORA") {
            if ($("#tipoBolsa").val() == "" || $("#tipoBolsa").val() == null) {
                $("#tipoBolsaE").css("display", "block");
                return false;
            }
        }

        if ($("#unidades").val() == "" || $("#unidades").val() == 0) {
            $("#unidadesE").css("display", "block");
            return false;
        }

        if ($("#tipo_despacho").val() != "PRODUCTO TERMINADO PANIFICADORA") {
            if ($("#kilos").val() == "" || $("#kilos").val() == 0) {
                $("#kilosE").css("display", "block");
                return false;
            }
        }

        if ($("#canastillas").val() == "" || $("#canastillas").val() < 0) {
            $("#canastillasE").css("display", "block");
            return false;
        }

        if ($("#caastillasBase").val() == "" || $("#caastillasBase").val() < 0) {
            $("#caastillasBaseE").css("display", "block");
            return false;
        }

        if ($("#tipo_despacho").val() != "PRODUCTO TERMINADO PANIFICADORA") {
            if ($("#fechaVencimiento").val() == "" || $("#fechaVencimiento").val() == null) {
                $("#fechaVencimientoE").html("<strong>Error: </strong>Debe ingresar la fecha de Proceso");
                $("#fechaVencimientoE").css("display", "block");
                return false;
            }
            if ($("#fechaVencimiento").val() > new Date().toISOString().split('T')[0]) {
                $("#fechaVencimientoE").html("<strong>Error: </strong>La fecha de Proceso no puede ser mayor a la fecha actual");
                $("#fechaVencimientoE").css("display", "block");
                return false;
            }
        } else {
            if ($("#fechaVencimiento").val() == "" || $("#fechaVencimiento").val() == null) {
                $("#fechaVencimientoE").html("<strong>Error: </strong>Debe ingresar la fecha de Vencimiento");
                $("#fechaVencimientoE").css("display", "block");
                return false;
            }
        }

        return true;
    }

    $("#unidades").on("input", function (e) { 
        e.preventDefault();
        console.log($("#tipo_despacho").val());
        console.log($("#tipoBolsa").val());
        if ($("#tipo_despacho").val() == "INSUMOS PARA PRODUCCION INTERNA" || $("#tipo_despacho").val() == "INSUMO PARA SEDES" && $("#tipoBolsa").val() != "") {
            console.log("entro");
            if ($("#tipo_despacho").val() == "INSUMO PARA SEDES"){
                let unidades = parseInt($("#unidades").val());
                let canastillas = 0;
                if (!isNaN(unidades) && unidades > 0) {
                    canastillas = Math.floor(unidades / 4);
                }
                $("#canastillas").val(canastillas);
            }
            try {
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        TraerCapacidad: $("#tipoBolsa").val()
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == "success") {
                            const capacidad = response.data.capacidad;
                            $("#kilos").val(capacidad * $("#unidades").val());
                        } else {
                            Swal.fire({
                                title: "¡Error!",
                                text: response.message,
                                icon: "error",
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return false;
                        }
                    },
                    error: function (data) {
                        Swal.fire({
                            title: "¡Error!",
                            text: "Error al traer la capacidad",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        return false;
                    }
                });
            } catch (error) {
                Swal.fire({
                    title: "¡Error!",
                    text: "Error al traer la capacidad",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        } else if ($("#tipo_despacho").val() == "INSUMOS PARA PRODUCCION INTERNA" || $("#tipo_despacho").val() == "INSUMO PARA SEDES" && $("#tipoBolsa").val() == "") {
            console.log("no entro");
            Swal.fire({
                title: "¡Error!",
                text: "Debe seleccionar un tipo de bolsa",
                icon: "error",
                timer: 1500,
                showConfirmButton: false
            });
        }
    });

    $("#AggItem").click(async function (e) { 
        e.preventDefault();
        if (await validacionesItems()) {
            $("#AggItem").prop('disabled', true);
            datos = {
                item: $("#item").val(),
                lote: $("#loteItem").val(),
                tipoBolsa: $("#tipoBolsa").val(),
                unidades: $("#unidades").val(),
                kilos: $("#kilos").val(),
                canastillas: $("#canastillas").val(),
                caastillasBase: $("#caastillasBase").val(),
                fechaVencimiento: $("#fechaVencimiento").val(),
                idGuia: $("#idGuia").val(),
                tipoDespacho: $("#tipo_despacho").val()
            }
            console.log('datos enviados:', datos);
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    AggItemDespachoP: datos
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $("#TItems").DataTable().ajax.reload();
                        $("#jtable").DataTable().ajax.reload();
                        limpiarCamposItems();
                        traerItems($("#idGuia").val());
                        Swal.fire({
                            title: data.message,
                            text: "",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    $("#AggItem").prop('disabled', false);
                },
                error: function (data) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Error al agregar el item",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $("#AggItem").prop('disabled', false);
                }
            });
        }
    });

    function TraerItem(id) {
        $("#tituloI").text("Editar Item");
        $("#idItem").val(id);
        limpiarCamposItems();
        if ($("#tipo_despacho").val() == "INSUMO PARA SEDES"){
            $("#canastillas").prop("disabled", false);
        }else{
            $("#canastillas").prop("disabled", false);
        }
        $("#AggItem").hide();
        $("#EditItem").show();
        $.ajax({
            type: "POST",
            url: "controlador/controlador.php",
            data: {
                TraerItemDespachoP: id
            },
            dataType: "json",
            success: function (data) {
                if (data.status == "success") {
                    console.log(data.data);
                    $("#item").val(data.data.item);
                    $("#loteItem").val(data.data.lote);
                    $("#unidades").val(data.data.unidades);
                    $("#canastillas").val(data.data.canastillas);
                    $("#caastillasBase").val(data.data.canastillas_base);
                    $("#fechaVencimiento").val(data.data.fecha_venci);
                    $("#tipoBolsa").val(data.data.tipo_bolsa);
                    $("#kilos").val(data.data.kilos);
                } else {
                    Swal.fire({
                        title: "¡Error!",
                        text: data.message,
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function (data) {
                Swal.fire({
                    title: "¡Error!",
                    text: "Error al traer el item",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    $("#EditItem").click(async function (e) { 
        e.preventDefault();
        if (await validacionesItems()) {
            $("#EditItem").prop('disabled', true);
            datos = {
                item: $("#item").val(),
                lote: $("#loteItem").val(),
                unidades: $("#unidades").val(),
                canastillas: $("#canastillas").val(),
                caastillasBase: $("#caastillasBase").val(),
                fechaVencimiento: $("#fechaVencimiento").val(),
                idItem: $("#idItem").val(),
                tipoBolsa: $("#tipoBolsa").val(),
                kilos: $("#kilos").val(),
                tipoDespacho: $("#tipo_despacho").val()
            }
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    EditarItemDP: datos
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        $("#tituloI").text("Agregar Item");
                        limpiarCamposItems();
                        if ($("#tipo_despacho").val() == "INSUMO PARA SEDES"){
                            $("#canastillas").prop("disabled", true);
                        }else{
                            $("#canastillas").prop("disabled", false);
                        }
                        $("#EditItem").hide();
                        $("#AggItem").show();
                        $("#TItems").DataTable().ajax.reload();
                        $("#jtable").DataTable().ajax.reload();
                        Swal.fire({
                            title: data.message,
                            text: "",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    $("#EditItem").prop('disabled', false);
                },
                error: function (data) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Error al Editar el item",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $("#EditItem").prop('disabled', false);
                }
            })
        }
    });

    function EliminarItem(id) {
        Swal.fire({
            title: "¿Está seguro?",
            text: "¿Está seguro que desea eliminar el item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        EliminarItemDP: id
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == "success") {
                            $("#TItems").DataTable().ajax.reload();
                            $("#jtable").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "¡Error!",
                                text: data.message,
                                icon: "error",
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (data) {
                        Swal.fire({
                            title: "¡Error!",
                            text: "Error al eliminar el item",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
            }
        })
    }
</script>
<script>
    let timeout;

    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(logout, 1200000);
    }

    function logout() {
        
        window.location.href = "cerrarsesion.php";
    }
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
</script>
</body>
</html>
