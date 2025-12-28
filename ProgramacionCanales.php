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
    <title>Planta de Desposte | Programacion Canales</title>
    <?php include_once('encabezado.php'); ?>
    <style>
        <?php if ($_SESSION["usuario"] == "ADMINISTRADOR" || $_SESSION["usuario"] == "16757007"): ?>
            #jtable th:nth-child(1),#jtable td:nth-child(1) {
                width: 8%;
                center{
                    margin-top: 11px;
                }
            }
            #jtable th,#jtable td {
                width: 16%;
                center{
                    margin-top: 11px;
                }
            }
            #jtable th:nth-child(6),#jtable td:nth-child(6) {
                width: 28%;
                center{
                    margin-top: 0px;
                }
            }
        <?php else: ?>
            #jtable th:nth-child(1),#jtable td:nth-child(1) {
                width: 4%;
                center{
                    margin-top: 11px;
                }
            }
            #jtable th,#jtable td {
                width: 17%;
                center{
                    margin-top: 11px;
                }
            }
            #jtable th:nth-child(7),#jtable td:nth-child(7) {
                width: 5%;
                center{
                    margin-top: 0px;
                }
            }
        <?php endif;?>
        
        #TCanal th:nth-child(1),#TCanal td:nth-child(1) {
            width: 20%;
        }
        #TCanal th:nth-child(2),#TCanal td:nth-child(2) {
            width: 10%;
        }
        #TCanal th:nth-child(3),#TCanal td:nth-child(3) {
            width: 10%;
        }
        #TCanal th:nth-child(4),#TCanal td:nth-child(4) {
            width: 10%;
        }
        #TCanal th:nth-child(5),#TCanal td:nth-child(5) {
            width: 25%;
        }
        #TCanal th:nth-child(6),#TCanal td:nth-child(6) {
            width: 25%;
        }
        #TRegistros th:nth-child(1),#TRegistros td:nth-child(1) {
            width: 5%;
            center{
                margin-top: 11px;
            }
        }
        #TRegistros th,#TRegistros td {
            width: 14%;
            center{
                margin-top: 11px;
            }
        }
        #TRegistros th:nth-child(8),#TRegistros td:nth-child(8) {
            width: 8%;
            center{
                margin-top: 0px;
            }
        }
        #TValidar th:nth-child(1) {
            center{
                margin-bottom: 6px;
            }
        }
        #TValidar td {
            width: 20%;
            center{
                margin-top: 11px;
            }
        }
        #TValidar td:nth-child(4), #TValidar td:nth-child(5) {
            width: 20%;
            center{
                margin-top: 0px;
            }
        }
        /* #TCambios th:nth-child(1), #TCambios th:nth-child(2), #TCambios th:nth-child(4), #TCambios th:nth-child(6), #TCambios th:nth-child(10), #TCambios th:nth-child(12), #TCambios th:nth-child(14), #TCambios th:nth-child(16), #TCambios th:nth-child(18) {
            center{
                margin-bottom: 6px;
            }
        } */
        #TCambios th:nth-child(1), #TCambios td:nth-child(1) {
            min-width: 150px;
			max-width: 151px;
        }
        #TCambios th, #TCambios td {
            min-width: 113px;
			max-width: 114px;
        }
        #TSugerencias th, #TSugerencias td {
            width: 25%;
        }
        #TCambiosRealizados th, #TCambiosRealizados td {
            width: 25%;
        }
        /* #TCambiosCerdo th:nth-child(1), #TCambiosCerdo th:nth-child(2), #TCambiosCerdo th:nth-child(4), #TCambiosCerdo th:nth-child(6), #TCambiosCerdo th:nth-child(10), #TCambiosCerdo th:nth-child(12), #TCambiosCerdo th:nth-child(14), #TCambiosCerdo th:nth-child(16), #TCambiosCerdo th:nth-child(18) {
            center{
                margin-bottom: 6px;
            }
        } */
        #TCambiosCerdo th:nth-child(1), #TCambiosCerdo td:nth-child(1) {
            min-width: 150px;
			max-width: 151px;
        }
        #TCambiosCerdo th, #TCambiosCerdo td {
            min-width: 113px;
			max-width: 114px;
        }

        .custom-close-btn {
            position: absolute;
            top: 20px; /* Ajusta la distancia desde la parte superior */
            right: 20px; /* Ajusta la distancia desde la derecha */
            z-index: 1; /* Asegura que el botón esté por encima de otros elementos */
        }
    </style>
</head>

<body>
    <?php include_once('menu.php'); ?>
    <?php include_once('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Programacion Canales</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <button href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR CANAL</button>
                                <p style="color: red;display: none" id="ErrorBoton">No hay semana activa </p>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <?php if($_SESSION["usuario"] == "ADMINISTRADOR" || $_SESSION["usuario"] == "16757007"): ?>
                                            <th>Año</th>
                                            <th>Semana</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Registros</th>
                                            <th>Acción</th>
                                        <?php else: ?>
                                            <th>Id</th>
                                            <th>Semana</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Especie</th>
                                            <th>Cantidad</th>
                                            <th>Acción</th>
                                        <?php endif; ?>
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

    <div class="modal fade" id="ModalRegistroSemana" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo1">REGISTROS SEMANA #1</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mb-2">
                            <h5 class="card-title">
                            <table id="TRegistros" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Sede</th>
                                        <th>Semana</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Especie</th>
                                        <th>Cantidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR CANAL</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" id="id">
                        <input type="hidden" id="sede" value="<?= $_SESSION["usuario"] ?>">

                        <div class="col-md-4 mb-2">
                            <label for="semana" class="form-label">Semana</label>
                            <input class="form-control" type="text" name="semana" autocomplete="off" id="semana" disabled placeholder="Ingrese la Semana">
                            <div class="alert alert-danger" role="alert" id="SemanaError" style="display: none">
                                Debe ingresar la sede
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="fecha" class="form-label">Fecha Inicio</label>
                            <input class="form-control" type="date" name="fecha" id="fecha" disabled placeholder="Ingrese la Fecha">
                            <div class="alert alert-danger" role="alert" id="FechaError" style="display: none">
                                Debe ingresar la fecha
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input class="form-control" type="date" name="fechaFin" id="fechaFin" disabled placeholder="Ingrese la Fecha">
                            <div class="alert alert-danger" role="alert" id="fechaFinError" style="display: none">
                                Debe ingresar la fecha
                            </div>
                        </div>

                        <div class="col-md-2 mb-2"></div>

                        <div class="col-md-4 mb-2">
                            <label for="especie" class="form-label">Especie</label>
                            <select class="form-select" name="especie" id="especie">
                                <option value="0">Seleccione una especie</option>
                                <option value="RES">RES</option>
                                <option value="CERDO">CERDO</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="EspecieError" style="display: none">
                                Debe ingresar la especie
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input class="form-control" type="number" id="cantidad" name="cantidad" placeholder="Ingrese la cantidad">
                            <div class="alert alert-danger" role="alert" id="CantidadError" style="display: none">
                                Debe seleccinar la cantidad
                            </div>
                        </div>

                        <div class="col-md-2 mb-2"></div>

                        <div class="col-md-12 mb-2" id="DivTabla" style="display: none;">
                            <h5 class="card-title">
                            <table id="TCanal" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>KL*RES</th>
                                        <th>%PART</th>
                                        <th>T.KILOS</th>
                                        <th>Necesidad/Sobrante</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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

    <div class="modal fade" id="ModalEspecies" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo2">VALIDAR ESPECIES</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" name="semanaV" id="semanaV">

                        <div class="col-md-12">
                            <h5 class="card-title">
                            <table id="TValidar" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th><center>Sede</center></th>
                                        <th>Canales<br>Res</th>
                                        <th>Canales<br>Cerdo</th>
                                        <th>Programancion<br>Final Res</th>
                                        <th>Programancion<br> Final Cerdo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalIntercambioRES" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header row text-center">
                    <div class="col-md-12">
                        <h6 style="display: inline;margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo3">INTERCAMBIO DE ITEMS</h6>
                        <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>                   
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" name="semanaID" id="semanaID">

                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" id="RealizarRES">Realizar Cambios</button>
                            <button type="button" class="btn btn-secondary" id="RevertirRES">Revertir Cambios</button>
                        </div>

                        <div class="col-md-12">
                            <h5 class="card-title">
                            <table id="TCambios" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th style="width:150px"><center>Items</center></th>
                                        <th style="width:180px"><center>LA 5A</center></th>
                                        <th style="width:180px"><center>LA 39</center></th>
                                        <th style="width:180px"><center>PLAZA NORTE</center></th>
                                        <th style="width:180px">CIUDAD JARDIN</th>
                                        <th style="width:180px"><center>CENTRO SUR</center></th>
                                        <th style="width:180px"><center>PALMIRA</center></th>
                                        <th style="width:180px"><center>FLORESTA</center></th>
                                        <th style="width:180px"><center>FLORALIA</center></th>
                                        <th style="width:180px"><center>GUADUALES</center></th>
                                        <th style="width:180px">BOGOTA LA 80</th>
                                        <th style="width:180px">BOGOTA CHIA</th>
                                        <th style="width:180px"><center>Especial</center></th>
                                    </tr>
                                    <tr>
                                        <td style="width:150px">&nbsp;</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="width:180px">&nbsp;</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalIntercambioCerdo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo5">INTERCAMBIO DE ITEMS</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" name="semanaIDCerdo" id="semanaIDCerdo">

                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" id="RealizarCERDO">Realizar Cambios</button>
                            <button type="button" class="btn btn-secondary" id="RevertirCERDO">Revertir Cambios</button>
                        </div>

                        <div class="col-md-12">
                            <h5 class="card-title">
                            <table id="TCambiosCerdo" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th style="width:150px"><center>Items</center></th>
                                        <th style="width:180px"><center>LA 5A</center></th>
                                        <th style="width:180px"><center>LA 39</center></th>
                                        <th style="width:180px"><center>PLAZA NORTE</center></th>
                                        <th style="width:180px">CIUDAD JARDIN</th>
                                        <th style="width:180px"><center>CENTRO SUR</center></th>
                                        <th style="width:180px"><center>PALMIRA</center></th>
                                        <th style="width:180px"><center>FLORESTA</center></th>
                                        <th style="width:180px"><center>FLORALIA</center></th>
                                        <th style="width:180px"><center>GUADUALES</center></th>
                                        <th style="width:180px">BOGOTA LA 80</th>
                                        <th style="width:180px">BOGOTA CHIA</th>
                                        <th style="width:180px"><center>Especial</center></th>
                                    </tr>
                                    <tr>
                                        <td style="width:150px">&nbsp;</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="white-space: pre">NEC     -     SOB</td>
                                        <td style="width:180px">&nbsp;</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCambio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="false" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo4">CAMBIO DE ITEM</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <!-- <input type="hidden" name="" id=""> -->

                        <div class="col-md-12 mb-2" style="text-align:center">
                            <h5 id="tituloCambio">Sede: sede, Item: item, Necesidad: necesidad</h5>
                        </div>

                        <h6 style="text-align: center; font-size: large;font-weight: bold">Sugerencias</h6>

                        <div class="col-md-12 mb-2">
                            <h5 class="card-title">
                            <table id="TSugerencias" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Sede</th>
                                        <th>Item</th>
                                        <th>Disponible</th>
                                        <th>Solucitud</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 mb-2" name="CambiosRealizados">
                            <h6 style="text-align: center; font-size: large;font-weight: bold">Cambios Realizados</h6>

                            <h5 class="card-title">
                            <table id="TCambiosRealizados" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Sede</th>
                                        <th>Item</th>
                                        <th>Enviado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-danger" id="CerrarModal2" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" style="display: none;">
        <div style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.3); z-index: 9999; justify-content: center; align-items: center;">
            <img src="assets/img/cargando.gif" alt="Cargando..." style="max-width: 80px; max-height: 80px;"/>
        </div>
    </div>

    <?php require_once('scripts.php'); ?>
    <script>
        $(document).ready(function() {
            $('#jtable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": false,
                "bDestroy": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    url: "<?php echo ($_SESSION['usuario'] == 'ADMINISTRADOR' || $_SESSION['usuario'] == '16757007') ? 'tablas/TablaProgramacionCanalesAdmin.php' : 'tablas/TablaProgramacionCanales.php'; ?>",
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
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    botonP: "ACTIVO"
                },
                dataType: "json",
                success: function (data) {
                    if (data["message"] == "No hay semanas activas") {
                        $("#ErrorBoton").css("display", "block");
                        $("#crearProveedor").attr("disabled", "true");
                    }else{
                        $("#ErrorBoton").css("display", "none");
                        $("#crearProveedor").removeAttr("disabled");
                    }
                }
            });
        });

        $('#crearProveedor').click(function() {
            $('#titulo').text('AGREGAR CANAL');
            $("#id").val("");
            $('#semana').val("");
            $('#fecha').val('');
            $('#fechaFin').val('');
            $('#especie').prop('selectedIndex', 0);
            $("#especie").removeAttr("disabled");
            $('#cantidad').val('');
            $("#cantidad").removeAttr("disabled");
            $('#SemanaError').css('display', 'none');
            $('#FechaError').css('display', 'none');
            $('#fechaFinError').css('display', 'none');
            $('#EspecieError').css('display', 'none');
            $('#CantidadError').css('display', 'none');
            $("#DivTabla").css("display", "none");
            $('#btnNuevoProveedor').css('display', 'initial');
            $('#btnEditProveedor').css('display', 'none');
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    traerGuia: "1"
                },
                dataType: "json",
                success: function (data) {
                    $('#semana').val(data.semana);
                    $('#fecha').val(data.fecha_inicio);
                    $('#fechaFin').val(data.fecha_fin);
                }
            });
        });

        function validaciones() {
            $('#SemanaError').css('display', 'none');
            $('#FechaError').css('display', 'none');
            $('#fechaFinError').css('display', 'none');
            $('#EspecieError').css('display', 'none');
            $('#CantidadError').css('display', 'none');

            if ($("#semana").val() == "0" || $("#semana").val() == "") {
                $('#SemanaError').css('display', 'block');
                return 'R1';
            }

            if ($("#fecha").val() == null || $("#fecha").val() == "") {
                $('#FechaError').css('display', 'block');
                return 'R2';
            }

            if ($("#fechaFin").val() == null || $("#fechaFin").val() == "") {
                $('#fechaFinError').css('display', 'block');
                return 'R2';
            }

            if ($("#especie").val() == null || $("#especie").val() == "0") {
                $('#EspecieError').css('display', 'block');
                return 'R3';
            }

            if ($("#cantidad").val() == "" || $("#cantidad").val() < 1) {
                $('#CantidadError').css('display', 'block');
                return 'R4';
            }

            return "";
        }

        function validacionesInput() {
            $('#SemanaError').css('display', 'none');
            $('#FechaError').css('display', 'none');
            $('#fechaFinError').css('display', 'none');
            $('#EspecieError').css('display', 'none');
            $('#CantidadError').css('display', 'none');

            if ($("#semana").val() == "0" || $("#semana").val() == "") {
                $('#SemanaError').css('display', 'block');
                return 'R1';
            }

            if ($("#fecha").val() == null || $("#fecha").val() == "") {
                $('#FechaError').css('display', 'block');
                return 'R2';
            }

            if ($("#fechaFin").val() == null || $("#fechaFin").val() == "") {
                $('#fechaFinError').css('display', 'block');
                return 'R2';
            }

            if ($("#especie").val() == null || $("#especie").val() == "0") {
                $('#EspecieError').css('display', 'block');
                return 'R3';
            }

            if ($("#cantidad").val() == "" || $("#cantidad").val() < 1) {
                
                return 'R4';
            }

            return "";
        }

        /* $("#cantidad, #especie").on('input', function() {
            if (validacionesInput() == "") {
                $("#DivTabla").css("display", "block");
                datos = {
                    cantidad: $("#cantidad").val(),
                    especie: $("#especie").val()
                }
                $('#TCanal').DataTable({
                    "responsive": true,
                    "processing": true,
                    "bDestroy": true,
                    "paging": false,
                    "order": [
                        [0, 'asc']
                    ],
                    "ajax": {
                    url: "tablas/TablaItemsCanales.php",
                    data: {
                        datos: datos
                    },
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
                $('#TCanal').css('width', '100%');
                $('#TCanal_filter input, #TCanal_length select').addClass('form-control');
                $('#TCanal_filter').find('label').find('input').attr("name", "input1")
                $('#TCanal').off('blur', "input");
                $("#TCanal").on("blur", "input", function (e) { 
                    e.preventDefault();
                    envio('<?= $_SESSION['usuario'] ?>', $("#id").val(), $(this).attr('name'), $("select[name='"+$(this).attr('name')+"']").val(),$(this).val());
                });
            }else{
                $("#DivTabla").css("display", "none");
            }
        }); */

        function envio(sede, canal, item, select, valor) {
            console.log("la sede es : " + sede + ", el canal es : " + canal + ", item: " + item + ", select: " + select + ", valor: " + valor);

            if (select != "0") {
                if ($("#id").val() == "") {
                    datos = {
                        sede: sede,
                        semana: $("#semana").val(),
                        fecha_inicio: $("#fecha").val(),
                        fechaFin: $("#fechaFin").val(),
                        especie: $("#especie").val(),
                        cantidad: $("#cantidad").val()
                    }

                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            agregarCanal: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            if (typeof data !== 'undefined' && data["type"]  == "RES") {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ya existe un canal para la especie RES',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }else if(typeof data !== 'undefined' && data["type"] == "CERDO"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ya existe un canal para la especie CERDO',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }else{
                                $("#id").val(data["id"]);
                                data = {
                                    sede: sede,
                                    canal: data["id"],
                                    item: item,
                                    select: select,
                                    valor: valor
                                }

                                $.ajax({
                                    type: "POST",
                                    url: "controlador/controlador.php",
                                    data: {
                                        envio: data,
                                    },
                                    dataType: "json",
                                    success: function (data) {
                                        console.log(data);
                                        return false;
                                    }
                                });
                            }
                        },
                        error: function (xhr, status) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al realizar la solicitud',
                                icon: 'error',
                                timer: 1300,
                                showConfirmButton: false
                            });
                            if (status == "error") {
                                return false; // Detiene la ejecución del callback
                            }
                        }
                    });
                }else{
                    data = {
                        sede: sede,
                        canal: canal,
                        item: item,
                        select: select,
                        valor: valor
                    }

                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            envio: data,
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                        }
                    });
                }
            }else{
                Swal.fire({
                    title: 'Error',
                    text: 'Debe seleccionar una necesidad o un sobrante',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        $('#btnNuevoProveedor').click(function() {
            if ($("#id").val() == "") {
                if (validaciones() == "") {
                    
                    datos = {
                        sede: $("#sede").val(),
                        semana: $('#semana').val(),
                        fecha_inicio: $('#fecha').val(),
                        fechaFin: $('#fechaFin').val(),
                        especie: $('#especie').val(),
                        cantidad: $('#cantidad').val()
                    };

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: 'controlador/controlador.php',
                        data: {
                            aggCanal: datos
                        },
                        success: function(data) {
                            if (typeof data !== 'undefined' && data["type"]  == "RES") {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ya existe un canal para la especie RES',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }else if(typeof data !== 'undefined' && data["type"] == "CERDO"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ya existe un canal para la especie CERDO',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }else{
                                $("#modalNuevoProveedor").modal("hide");
                                $('#jtable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: 'Canal registrado satisfactoriamente',
                                    text: '',
                                    icon: 'success',
                                    timer: 1300,
                                    showConfirmButton: false
                                });
                            }
                            
                        }
                    });
                }
            }else{
                $("#modalNuevoProveedor").modal("hide");
                $('#jtable').DataTable().ajax.reload();
                Swal.fire({
                    title: 'Canal registrado satisfactoriamente',
                    text: '',
                    icon: 'success',
                    timer: 1300,
                    showConfirmButton: false
                });
            }
        });

        function edit_canal(id) {
            $('#id').val(id);
            $('#titulo').text('EDITAR CANAL #'+id);
            $('#semana').val("");
            $('#fecha').val('');
            $('#fechaFin').val('');
            $('#especie').prop('selectedIndex', 0);
            $('#cantidad').val('');
            $("#especie").removeAttr("disabled");
            $("#cantidad").removeAttr("disabled");
            $('#SemanaError').css('display', 'none');
            $('#FechaError').css('display', 'none');
            $('#fechaFinError').css('display', 'none');
            $('#EspecieError').css('display', 'none');
            $('#CantidadError').css('display', 'none');
            /* $("#DivTabla").css("display", "block"); */
            $('#btnNuevoProveedor').css('display', 'none');
            $('#btnEditProveedor').css('display', 'initial');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    idCanal: $("#id").val()
                },
                success: function(data) {
                    $('#sede').val(data[0][0]);
                    $('#semana').val(data[0][1]);
                    $('#fecha').val(data[0][2]);
                    $('#fechaFin').val(data[0][3]);
                    $('#especie').val(data[0][4]);
                    $('#cantidad').val(data[0][5]);
                    datos = {
                        cantidad: data[0][5],
                        especie: data[0][4],
                    }
                    /* $('#TCanal').DataTable({
                        "responsive": true,
                        "processing": true,
                        "bDestroy": true,
                        "paging": false,
                        "order": [
                            [0, 'asc']
                        ],
                        "ajax": {
                        url: "tablas/TablaItemsCanales.php",
                        data: {
                            datos: datos
                        },
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
                    $('#TCanal').css('width', '100%');
                    $('#TCanal_filter input, #TCanal_length select').addClass('form-control');
                    $('#TCanal_filter').find('label').find('input').attr("name", "input1") */
                    /* $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            traerItems: $("#id").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            for (let index = 0; index < data.length; index++) {
                                const element = data[index];
                                if (element[1] != "") {
                                    $("select[name='"+element[0]+"']").val("NECESIDAD");
                                    $("input[name='"+element[0]+"']").val(element[1]);
                                }else{
                                    $("select[name='"+element[0]+"']").val("SOBRANTE");
                                    $("input[name='"+element[0]+"']").val(element[2]);
                                }
                            }
                            console.log(data);
                        }
                    });
                    $('#TCanal').off('blur', "input");
                    $("#TCanal").on("blur", "input", function (e) { 
                        e.preventDefault();
                        envio('<?= $_SESSION['usuario'] ?>', $("#id").val(), $(this).attr('name'), $("select[name='"+$(this).attr('name')+"']").val(),$(this).val());
                    }); */
                    
                }
            });
        }

        $('#btnEditProveedor').click(function() {
            if (validaciones() == "") {
                datos = {
                    id: $('#id').val(),
                    sede: $('#sede').val(),
                    semana: $('#semana').val(),
                    fecha_inicio: $('#fecha').val(),
                    fechaFin: $('#fechaFin').val(),
                    especie: $('#especie').val(),
                    cantidad: $('#cantidad').val()
                };
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        editCanal: datos
                    },
                    success: function(data) {
                        $("#modalNuevoProveedor").modal('hide');
                        $('#jtable').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Canal modificado satisfactoriamente',
                            text: '',
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function bloquearCanal(id) {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    bloquearCanal: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#jtable').DataTable().ajax.reload();
                }
            });
        }

        function desbloquearCanal(id) {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    desbloquearCanal: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#jtable').DataTable().ajax.reload();
                }
            });
        }

        function abrirModal(id) {
            $("#modalNuevoProveedor").modal("show");
            $('#id').val(id);
            $('#titulo').text('VER CANAL #'+id);
            $('#semana').val("");
            $('#fecha').val('');
            $('#fechaFin').val('');
            $('#especie').prop('selectedIndex', 0);
            $("#especie").attr("disabled", "true");
            $('#cantidad').val('');
            $("#cantidad").attr("disabled", "true");
            $('#SemanaError').css('display', 'none');
            $('#FechaError').css('display', 'none');
            $('#fechaFinError').css('display', 'none');
            $('#EspecieError').css('display', 'none');
            $('#CantidadError').css('display', 'none');
            $("#DivTabla").css("display", "block");
            $('#btnNuevoProveedor').css('display', 'none');
            $('#btnEditProveedor').css('display', 'none');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    idCanal: $("#id").val()
                },
                success: function(data) {
                    $('#sede').val(data[0][0]);
                    $('#semana').val(data[0][1]);
                    $('#fecha').val(data[0][2]);
                    $('#fechaFin').val(data[0][3]);
                    $('#especie').val(data[0][4]);
                    $('#cantidad').val(data[0][5]);
                    datos = {
                        cantidad: data[0][5],
                        especie: data[0][4],
                        id: id
                    }
                    $('#TCanal').DataTable({
                        "responsive": true,
                        "bDestroy": true,
                        "paging": false,
                        "order": [
                            [0, 'asc']
                        ],
                        "ajax": {
                        url: "tablas/TablaItemsCanalesVer.php",
                        data: {
                            datos: datos
                        },
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
                    $('#TCanal').css('width', '100%');
                    $('#TCanal_filter input, #TCanal_length select').addClass('form-control');
                    $('#TCanal_filter').find('label').find('input').attr("name", "input1")
                    $('#TCanal').off('blur', "input");
                    /* $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            traerItems: $("#id").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            for (let index = 0; index < data.length; index++) {
                                const element = data[index];
                                if (element[1] != "") {
                                    $("select[name='"+element[0]+"']").val("NECESIDAD");
                                    $("input[name='"+element[0]+"']").val(element[1]);
                                }else{
                                    $("select[name='"+element[0]+"']").val("SOBRANTE");
                                    $("input[name='"+element[0]+"']").val(element[2]);
                                }
                            }
                            $("#TCanal input, #TCanal select").each(function () {
                                $(this).attr('disabled', 'true');
                            });
                            console.log(data);
                        }
                    }); */
                }
            });
        }

        function AbrirModalSemana(semana) {
            $("#titulo1").text("REGISTROS SEMANA #" + semana);
            $('#TRegistros').DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "order": [
                    [0, 'asc']
                ],
                "ajax": {
                url: "tablas/TablaRegistrosSemana.php",
                data: {
                    semana: semana
                },
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
            $('#TRegistros').css('width', '100%');
            $('#TRegistros_filter input, #TRegistros_length select').addClass('form-control');
            $('#TRegistros_filter').find('label').find('input').attr("name", "input2")
        }

        function AbrirModalEspecies(semana) {
            $("#titulo2").text("REGISTROS ESPECIES #" + semana);
            $("#semanaV").val(semana);
            $("#TValidar").DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "order": [
                    [0, 'asc']
                ],
                "ajax": {
                url: "tablas/TablaValidarEspecies.php",
                data: {
                    semana: semana
                },
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
            
            })
            $('#TValidar').css('width', '100%');
            $('#TValidar_filter input, #TValidar_length select').addClass('form-control');
            $('#TValidar_filter').find('label').find('input').attr("name", "input3")
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    traerItemsEs: semana
                },
                dataType: "json",
                success: function (data) {
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        $("input[name='"+element[0]+"'][tipo='RES']").val(element[1]);
                        $("input[name='"+element[0]+"'][tipo='CERDO']").val(element[2]);
                    }
                    console.log(data);
                }
            });
            $('#TValidar').off('blur', "input");
            $("#TValidar").on("blur", "input", function (e) { 
                e.preventDefault();
                programancion_final($(this).attr('name'), $("#semanaV").val(), $(this).attr('tipo'),$(this).val());
            });
        }

        function programancion_final(sede, semana, tipo, valor) {
            console.log("sede: "+sede+" semana: "+semana+" tipo: "+tipo+" valor: "+valor);

            if (valor != "" && valor != "0") {
                datos = {
                    sede: sede,
                    semana: semana,
                    tipo: tipo,
                    valor: valor
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        enviarPro: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                    }
                });
            }else {
                $("input[name='"+sede+"'][tipo='"+tipo+"']").css("border", "1px solid rgb(216, 46, 46)");

            }
        }

        function AbrirModalCambioRes(semana) {
            $("#semanaID").val(semana);
            $("#TCambios").DataTable({
                "responsive": false,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "autoWidth": true,
                "searching": false,
                "info": false,
                "columnDefs": [
                    {
                        "targets": [12], // Columna especial (índice 6)
                        "visible": false, // Ocultar la columna
                        "orderDataType": "especial-fila", // Tipo de ordenamiento personalizado
                        "type": "string"
                    },
                    {
                        "targets": [0, 1, 2, 3, , 4, 5, 6, 7, , 8, 9, 10, 11],
                        "orderable": false // No se puede ordenar
                    }
                ],
                "order": [[12, "asc"], [0, "asc"] ], // Ordenar por la columna especial (oculta)
                "ordering": true,
                "ajax": {
                url: "tablas/TablaIntercambioItems.php",
                data: {
                    semana: semana
                },
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
            // Definir el tipo de ordenamiento personalizado
            /* $.fn.dataTable.ext.order['especial-fila'] = function(settings, col) {
                return this.api().column(col, {order: 'index'}).nodes().map(function(td, i) {
                    return $(td).text() === 'true' ? 1 : 0; // true = 1 (final), false = 0
                });
            }; */
            /* $('#TCambios').css('width', '100%'); */
            $('#TCambios_filter input, #TCambios_length select').addClass('form-control');
            $('#TCambios_filter').find('label').find('input').attr("name", "input3")
        }

        function ModalIntercambio(idItem, sede, item, NameItem, necesidad, semana, especie) {
            $("#ModalCambio").modal("show");
            $("#tituloCambio").text("Sede: "+ sede +", Item: "+ NameItem +", Necesidad: " + necesidad);
            $("#tituloCambio").attr("Sede", sede);
            $("#tituloCambio").attr("Item", NameItem);
            $("#tituloCambio").attr("Necesidad", necesidad);
            console.log("el id del item es: " + idItem + " y la sede es: " + sede + " el item es " + item + " su nombre es: " + NameItem + " y la necesidad es: " + necesidad + " y la especie es: " + especie);
            cont = 0;
            $("#TSugerencias").DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "autoWidth": true,
                "searching": false,
                "info": false,
                "order": [
                    [0, 'asc']
                ],
                "ajax": {
                url: "tablas/TablaSugerencias.php",
                data: {
                    datos: {
                        item: item,
                        semana: semana,
                        sede: sede,
                        necesidad: necesidad,
                        especie: especie
                    }
                },
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
            })
            $('#TSugerencias').css('width', '100%');
            $('#TSugerencias_filter input, #TSugerencias_length select').addClass('form-control');
            $('#TSugerencias_filter').find('label').find('input').attr("name", "input3")
            $('#TSugerencias').off('blur', "input");
            /* $("#TSugerencias").on("blur", "input" , function () {
                EnviarCambio($(this).attr("name"), item, $(this).val(), sede, semana, necesidad, $(this).attr("disponible"), especie);
            }); */
            $("#TCambiosRealizados").DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "autoWidth": true,
                "searching": false,
                "info": false,
                "order": [
                    [0, 'asc']
                ],
                "ajax": {
                    url: "tablas/TablaCambiosRealizados.php",
                    data: {
                        datos: {
                            item: item,
                            semana: semana,
                            sede: sede
                        }
                    },
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
            })
            $('#TCambiosRealizados').css('width', '100%');
            $('#TCambiosRealizados_filter input, #TCambiosRealizados_length select').addClass('form-control');
            $('#TCambiosRealizados_filter').find('label').find('input').attr("name", "input3")
        }

        function Enviar_a_Cambio(sedeOrigen, item, sedeDestino, semana, necesidad, disponible, especie) {
            EnviarCambio(sedeOrigen, item, $("input[name='"+ sedeOrigen + "']").val(), sedeDestino, semana, necesidad, disponible, especie);
        }

        let cont = 0;

        function EnviarCambio(sedeOrigen, item, cantidad, sedeDestino, semana, necesidad, disponible, especie) {
            console.log("Cambio de: " + sedeOrigen + " a " + sedeDestino + " del item: " + item + " de " + cantidad + " en la semana: " + semana + " con necesidad de " + necesidad + " y disponible de " + disponible);

            
            if (cantidad > 0) {
                cont += parseInt(cantidad);
                console.log("contador: " + cont);
                if (parseInt(cantidad) <= disponible) {
                    if (necesidad >= cont) {
                        if (necesidad >= parseInt(cantidad)) {
                            datos = {
                                sedeOrigen: sedeOrigen,
                                item: item,
                                cantidad: cantidad,
                                sedeDestino: sedeDestino,
                                semana: semana,
                                especie: especie
                            }

                            $.ajax({
                                type: "POST",
                                url: "controlador/controlador.php",
                                data: {
                                    enviarCambio: datos,
                                },
                                dataType: "json",
                                success: function (data) {
                                    console.log(data);
                                    var itemName = $("#tituloCambio").attr("Item");
                                    var necesidadAtr = $("#tituloCambio").attr("Necesidad");
                                    $("#tituloCambio").text("Sede: "+ sedeDestino +", Item: "+ itemName +", Necesidad: " + (necesidadAtr - parseInt(cantidad)));
                                    $("#tituloCambio").attr("Sede", sedeDestino);
                                    $("#tituloCambio").attr("Necesidad", necesidadAtr - parseInt(cantidad));
                                    $('#TSugerencias').DataTable().ajax.reload();
                                    $('#TCambiosRealizados').DataTable().ajax.reload();
                                    if ($.fn.dataTable.isDataTable('#TCambios')) {
                                        $('#TCambios').DataTable().ajax.reload();
                                    } 
                                    if ($.fn.dataTable.isDataTable('#TCambiosCerdo')) {
                                        $('#TCambiosCerdo').DataTable().ajax.reload();
                                    }
                                    Swal.fire({
                                        title: 'Sobrante Enviado correctamente',
                                        text: '',
                                        icon: 'success',
                                        timer: 800, 
                                        showConfirmButton: false
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: '¡Error!',
                                text: 'El valor ingresado supera la necesidad',
                                icon: 'error',
                                showConfirmButton: true
                            });
                        }
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            text: 'El valor ingresado supera la necesidad',
                            icon: 'error',
                            showConfirmButton: true
                        });
                        cont -= parseInt(cantidad);
                    }
                }else {
                    Swal.fire({
                        title: '¡Error!',
                        text: 'El valor ingresado supera la cantidad disponible',
                        icon: 'error',
                        showConfirmButton: true
                    });
                    cont -= parseInt(cantidad);
                }
            }else {
                Swal.fire({
                    title: '¡Error!',
                    text: 'El valor ingresado no es valido',
                    icon: 'error',
                    showConfirmButton: true
                });
            }
            
            
        }

        /* $("#CerrarModal").click(function (e) { 
            e.preventDefault();
            cont = 0;
            $('#TCambios').DataTable().ajax.reload();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    UltimoCambio: true
                },
                dataType: "json",
                success: function (data) {
                    $("#miTabla td").each(function() {
                        $(this).css({
                            "background-color": "",
                        });
                    });
                    NroItems = ["073617", "073618", "073619", "073620", "073624", "073625", "073626", "073627", "073628", "073631", "006411", "037508", "073633", "073621", "073622", "073623", "073629", "073630"];

                    sedesDestino = ["", "LA 5A", "", "LA 39", "", "PLAZA NORTE", "", "CIUDAD JARDIN", "", "CENTRO SUR", "", "PALMIRA", "", "FLORESTA", "", "FLORALIA", "", "GUADUALES", "", "BOGOTA LA 80", "", "BOGOTA CHIA"];

                    sedesOrigen = ["", "", "LA 5A", "", "LA 39", "", "PLAZA NORTE", "", "CIUDAD JARDIN", "", "CENTRO SUR", "", "PALMIRA", "", "FLORESTA", "", "FLORALIA", "", "GUADUALES", "", "BOGOTA LA 80", "", "BOGOTA CHIA"];

                    $('#TCambios tbody tr:eq(' + NroItems.indexOf(data[2]) +') td:eq(' + sedesDestino.indexOf(data[4]) +')').css("background-color", "green");
                    $('#TCambios tbody tr:eq(' + NroItems.indexOf(data[2]) +') td:eq(' + sedesOrigen.indexOf(data[1]) +')').css("background-color", "green");
                }
            });
        }); */

        $("#CerrarModal2").click(function (e) { 
            e.preventDefault();
            cont = 0;
            if ($.fn.dataTable.isDataTable('#TCambios')) {
                $('#TCambios').DataTable().ajax.reload();
            } 
            if ($.fn.dataTable.isDataTable('#TCambiosCerdo')) {
                $('#TCambiosCerdo').DataTable().ajax.reload();
            }
        });

        function BorrarCambio(id, enviado) {
            Swal.fire({
                title: '¿Esta seguro que desea eliminar este Cambio?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            BorrarCambio: id
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            var sede = $("#tituloCambio").attr("Sede");
                            var itemName = $("#tituloCambio").attr("Item");
                            var necesidadAtr = $("#tituloCambio").attr("Necesidad");
                            let suma = parseInt(necesidadAtr) + parseInt(enviado);
                            if (cont != 0) {
                                cont -= parseInt(enviado);
                            }
                            $("#tituloCambio").text("Sede: "+ sede +", Item: "+ itemName +", Necesidad: " + suma);
                            $("#tituloCambio").attr("Sede", sede);
                            $("#tituloCambio").attr("Necesidad", suma);
                            $('#TCambiosRealizados').DataTable().ajax.reload();
                            $('#TSugerencias').DataTable().ajax.reload();
                            if ($.fn.dataTable.isDataTable('#TCambios')) {
                                $('#TCambios').DataTable().ajax.reload();
                            } 
                            if ($.fn.dataTable.isDataTable('#TCambiosCerdo')) {
                                $('#TCambiosCerdo').DataTable().ajax.reload();
                            }
                            Swal.fire({
                                title: 'Cambio Eliminado correctamente',
                                text: '',
                                icon: 'success',
                                timer: 1000, 
                                showConfirmButton: false
                            });
                            /* let sede = $("#tituloCambio").attr("Sede");
                            let NameItem = $("#tituloCambio").attr("Item");
                            let necesidad = parseInt($("#tituloCambio").attr("Necesidad"));
                            $("#tituloCambio").text("Sede: "+ sede +", Item: "+ NameItem +", Necesidad: " + (necesidad + data.enviado)); */
                        }
                    });
                }
            });
        }

        function AbrirModalCambioCerdo(semana) {
            $("#semanaIDCerdo").val(semana);
            $("#TCambiosCerdo").DataTable({
                "responsive": false,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "autoWidth": true,
                "searching": false,
                "info": false,
                "columnDefs": [
                    {
                        "targets": [12], // Columna especial (índice 6)
                        "visible": false, // Ocultar la columna
                        "orderDataType": "especial-fila", // Tipo de ordenamiento personalizado
                        "type": "string"
                    },
                    {
                        "targets": [0, 1, 2, 3, , 4, 5, 6, 7, , 8, 9, 10, 11],
                        "orderable": false // No se puede ordenar
                    }
                ],
                "order": [[12, "asc"], [0, "asc"] ], // Ordenar por la columna especial (oculta)
                "ordering": true,
                "ajax": {
                url: "tablas/TablaIntercambioItemsCerdo.php",
                data: {
                    semana: semana
                },
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
            })
            /* $('#TCambios').css('width', '100%'); */
            $('#TCambiosCerdo_filter input, #TCambiosCerdo_length select').addClass('form-control');
            $('#TCambiosCerdo_filter').find('label').find('input').attr("name", "input3")
        }

        /* function ModalIntercambioCerdo(idItem, sede, item, NameItem, necesidad, semana) {
            
        } */

        /* $("#CerrarModalCerdo").click(function (e) { 
            e.preventDefault();
            cont = 0;
            $('#TCambios').DataTable().ajax.reload();
            $('#TCambiosCerdo').DataTable().ajax.reload();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    UltimoCambio: true
                },
                dataType: "json",
                success: function (data) {
                    $("#miTabla td").each(function() {
                        $(this).css({
                            "background-color": "",
                        });
                    });
                    NroItems = ["073612", "033700", "073613", "005800", "009251", "073559", "073614", "009270", "005819", "073615", "007844", "073616", "040959"];

                    sedesDestino = ["", "LA 5A", "", "LA 39", "", "PLAZA NORTE", "", "CIUDAD JARDIN", "", "CENTRO SUR", "", "PALMIRA", "", "FLORESTA", "", "FLORALIA", "", "GUADUALES", "", "BOGOTA LA 80", "", "BOGOTA CHIA"];

                    sedesOrigen = ["", "", "LA 5A", "", "LA 39", "", "PLAZA NORTE", "", "CIUDAD JARDIN", "", "CENTRO SUR", "", "PALMIRA", "", "FLORESTA", "", "FLORALIA", "", "GUADUALES", "", "BOGOTA LA 80", "", "BOGOTA CHIA"];

                    $('#TCambiosCerdo tbody tr:eq(' + NroItems.indexOf(data[2]) +') td:eq(' + sedesDestino.indexOf(data[4]) +')').css("background-color", "green");
                    $('#TCambiosCerdo tbody tr:eq(' + NroItems.indexOf(data[2]) +') td:eq(' + sedesOrigen.indexOf(data[1]) +')').css("background-color", "green");
                }
            });
        }); */

        $("#RealizarRES").click(function (e) { 
            e.preventDefault();
            $("#loading").show();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    intercambioRES: $("#semanaID").val()
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#loading").hide();
                    $("#TCambios").DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Cambios realizados correctamente',
                        text: '',
                        icon: 'success',
                        timer: 1000, 
                        showConfirmButton: false
                    });
                }
            });
        });

        $("#RevertirRES").click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    RevertirRES: $("#semanaID").val()
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#TCambios").DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Cambios revertidos correctamente',
                        text: '',
                        icon: 'success',
                        timer: 1000, 
                        showConfirmButton: false
                    });
                }
            });
        });

        $("#RealizarCERDO").click(function (e) { 
            e.preventDefault();
            $("#loading").show();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    intercambioCERDO: $("#semanaIDCerdo").val()
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#loading").hide();
                    $("#TCambiosCerdo").DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Cambios realizados correctamente',
                        text: '',
                        icon: 'success',
                        timer: 1000, 
                        showConfirmButton: false
                    });
                }
            });
        });

        $("#RevertirCERDO").click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    RevertirCERDO: $("#semanaIDCerdo").val()
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#TCambiosCerdo").DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Cambios revertidos correctamente',
                        text: '',
                        icon: 'success',
                        timer: 1000, 
                        showConfirmButton: false
                    });
                }
            });
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
</body>
</html>
