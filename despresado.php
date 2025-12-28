<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
//error_reporting(0);
date_default_timezone_set("America/bogota");
$_SESSION["fechaInicio"] = date("G:i");
include("modelo/funciones.php");
include("config.php");
$destinos = listaDestinos();
$proveedoresPollo = listaProveedores();
$proveedoresHielo = listaProveedoresHielo();
$proveedoresEmpaque = listaProveedoresEmpaque();
$fabricantesEmpaque = listaFabricantesEmpaque();
$proveedoresSalmuera = listaProveedoresSalmuera();
$items = itemsDesprese();
$listaResponsables = listaResponsables();
$salmueras = listaSalmuera();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Planta de Desposte | Despresado</title>
    <?php include_once('encabezado.php');?>
    <style>
        #jtable th:nth-child(1), #jtable td:nth-child(1) {
            width: 3%;
            center{
                margin-top: 11px;
            }
        }
        #jtable th:nth-child(2), #jtable td:nth-child(2) {
            width: 26%;
            /* center{
                margin-top: 11px;
            } */
        }
        #jtable th, #jtable td {
            width: 22%;
            center{
                margin-top: 4px;
            }

        }
        #jtable th:nth-child(5), #jtable td:nth-child(4) {
            width: 26%;
        }
        #TItems th:nth-child(1), #TItems td:nth-child(1) {
            width: 4%;
        }
        #TItems th:nth-child(2), #TItems td:nth-child(2) {
            width: 6%;
        }
        #TItems th:nth-child(3), #TItems td:nth-child(3) {
            width: 10%;
        }
        #TItems th:nth-child(4), #TItems td:nth-child(4) {
            width: 25%;
        }
        #TItems th:nth-child(9), #TItems td:nth-child(9) {
            width: 11%;
        }
        #TItemsMejora th:nth-child(1), #TItemsMejora td:nth-child(1) {
            width: 3%;
        }
        #TItemsMejora th:nth-child(2), #TItemsMejora td:nth-child(2) {
            width: 6%;
        }
        #TItemsMejora th:nth-child(3), #TItemsMejora td:nth-child(3) {
            width: 30%;
        }
        #TItemsMejora th:nth-child(4), #TItemsMejora td:nth-child(4) {
            width: 18%;
        }
        #TItemsMejora th, #TItemsMejora td {
            width: 8%;
        }
        #TItemsMejora th:nth-child(9), #TItemsMejora td:nth-child(9) {
            width: 11%;
        }
        #TPesos td{
            padding: 0rem;
            center{
                margin-top: 11px;
            }
        }
        #TPesos td:nth-child(7){
            padding: 0rem;
            center{
                margin-top: 0px;
            }
        }

        table td, table th {
            vertical-align: middle !important;
        }
        #TDataDespresado td:nth-child(1) {
            width: 12% !important;
        }
        #TDataDespresado td:nth-child(2) {
            width: 20% !important;
        }

        table[name="TablasCF"] th, table[name="TablasCF"] td {
            min-width: 149px !important;
            max-width: 150px !important;
        }

        #TablaHeaderLQ tbody tr td:nth-child(5), #TablaHeaderLQ tbody tr td:nth-child(6), #TablaHeaderLQ tbody tr td:nth-child(7) {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-warning-rgb),var(--bs-bg-opacity)) !important;
        }

        #TablaConPE_LQ tbody tr:not(:last-child) td:nth-child(8) {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-warning-rgb),var(--bs-bg-opacity)) !important;
        }
    </style>
</head>
<body>
    <?php include_once('menu.php');?>
    
    <?php include_once('menuizquierda.php');?> 
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Despresado</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <center>
                                
                            </center>
                            <div class="row mt-2">
                                <div class="col-md-4 offset-md-4 d-flex justify-content-center">
                                    <a href="#" class="btn btn-warning" style="font-weight: 600 !important; margin: initial !important;" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA</a>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-primary" style="font-weight: 600 !important;" id="crearPlanosBtn">Crear Planos</button>
                                </div>
                            </div>
                            <h5 class="card-title">
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>id<br>&nbsp;</th>
                                        <th>Destino<br>Fecha</th>
                                        <th>Pollos Desprese<br>Pesos</th>
                                        <th>Acción<br>&nbsp;</th>
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
        <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h6 id="horaInicialDespresado" class="float-start m-0"></h6>
                    <h6 style="margin-bottom: 0px;font-weight: bold;" id="titulo">AGREGAR DESPRESADO</h6>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">

                        <input type="hidden" id="id">
                        <input type="hidden" id="statusEdit">
                        
                        <div class="col-md-3 mb-2">
                            <label for="sede" class="form-label">Destino</label>
                            <select name="sede" id="sede" class="form-select">
                                <option value="0">Seleccione un destino</option>
                                 <?php foreach ($destinos as $destino):?>
                                    <option value="<?= $destino["id"] ?>"><?= $destino["empresa"]. " - " .$destino["sede"] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="sedeError" style="display: none">
                                Debe ingresar el destino
                            </div>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label for="fechaRegistro" class="form-label">Fecha de Registro</label>
                            <input type="date" id="fechaRegistro" class="form-control" disabled>
                            <div class="alert alert-danger" role="alert" id="fechaRegistroError" style="display: none" value="<?= date("Y-m-d") ?>">
                                Debe ingresar la fecha de registro
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="cantidadPollo" class="form-label">Cantidad de Pollos</label>
                            <input type="number" name="cantidadPollo" id="cantidadPollo" class="form-control" placeholder="Ingrese la cantidad de pollos">
                            <div class="alert alert-danger" role="alert" id="cantidadPolloError" style="display: none">
                                Debe ingresar la cantidad de pollos
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="lotePollo" class="form-label">Lote Recepción</label>
                            <input type="text" name="lotePollo" id="lotePollo" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                            <input type="hidden" name="existe" id="existe">
                            <div class="alert alert-danger" role="alert" id="lotePolloError" style="display: none">
                                Debe ingresar un lote
                            </div>
                        </div>

                        <div class="col-md-1 mb-2">
                            <i class="bi bi-plus-square fs-3 text-warning float-start" style="cursor: pointer; margin-top: 35px;" id="AgregarLote"></i>
                        </div>

                        <div class="col-md-6 mb-2" style="display: none;">
                            <label for="responsable" class="form-label">Responsable</label>
                            <select class="form-select" id="responsable" name="responsable" disabled>
                                <?php foreach ($listaResponsables as $perm) : ?>
                                    <option value="<?php echo $perm['cedula'] ?>">
                                        <?php echo $perm['cedula'] . " - " . $perm['nombres'] ?></option>
                                <?php endforeach ?>
                            </select>
							<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
								Debe seleccinar el responsable
							</div>
						</div>

                        <div class="col-md-12 mt-2 d-flex justify-content-center">
                            <button class="btn btn-primary" style="display: none;" rel="tooltip" data-placement="bottom" id="btnEditProveedor">Editar</button>
                        </div>

                        <h6 style="font-size: large;font-weight: bold;" class="mt-3" name="DivProveedores">Proveedores</h6>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <label for="tipoP" class="form-label">Tipo de Pollo</label>
                            <select name="tipoP" id="tipoP" class="form-select" disabled>
                                <option value="0">Seleccione un tipo</option>
                                <option value="BLANCO">BLANCO</option>
                                <option value="CAMPO">CAMPO</option>
                                <option value="BLANCO_DESPRESE">BLANCO DESPRESE</option>
                                <option value="CAMPO_DESPRESE">CAMPO DESPRESE</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="tipoPError" style="display: none">
								Debe seleccinar el tipo de pollo
							</div>
                        </div>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <label for="proveedorP" class="form-label">Proveedor</label>
                            <!--<select name="proveedorP" id="proveedorP" class="form-select">
                                <option value="0">Seleccione un proveedor</option>
                                <?php /* foreach ($proveedoresPollo as $proveedor): */ ?>
                                <?php /* endforeach; */ ?>
                            </select> -->
                            <input type="text" name="proveedorP" id="proveedorP" class="form-control" disabled placeholder="Ingrese el proveedor">
                            <input type="hidden" name="proveedor" id="proveedor">
                            <div class="alert alert-danger" role="alert" id="proveedorPError" style="display: none">
                                Debe ingresar un proveedor
                            </div>
                        </div>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <label for="fechaBeneficio" class="form-label">Fecha Beneficio</label>
                            <input type="date" name="fechaBeneficio" id="fechaBeneficio" class="form-control" disabled>
                            <div class="alert alert-danger" role="alert" id="fechaBeneficioError" style="display: none">
                                Debe ingresar la fecha beneficio
                            </div>
                        </div>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <label for="fechaPollo" class="form-label">Fecha Vencimiento Proveedor</label>
                            <input type="date" name="fechaPollo" id="fechaPollo" disabled class="form-control">
                            <div class="alert alert-danger" role="alert" id="fechaPolloError" style="display: none">
                                Debe ingresar la fecha de vencimiento
                            </div>
                        </div>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <label for="unidades" class="form-label">Unidades</label>
                            <input type="number" name="unidades" id="unidades" class="form-control" disabled placeholder="Ingrese las unidades">
                            <div class="alert alert-danger" role="alert" id="unidadesError" style="display: none">
                                Debe ingresar las unidades
                            </div>
                        </div>

                        <div class="col-md-4 mb-2" name="DivProveedores">
                            <div class="row">
                                <div class="col-12 d-flex align-items-top mb-2" style="gap: 0.6rem;">
                                    <div class="flex-grow-1">
                                        <label for="kilosDisponiblesD" class="form-label">Kilos Disponibles</label>
                                        <input type="number" name="kilosDisponiblesD" id="kilosDisponiblesD" class="form-control" disabled placeholder="Ingrese los kilos disponibles">
                                        <div class="alert alert-danger" role="alert" id="kilosDisponiblesDError" style="display: none">
                                            No hay kilos disponibles
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-top" style="padding: 0rem !important;">
                                        <i class="bi bi-plus-square fs-3 text-warning float-start" style="cursor: pointer; margin-top: 36px;" id="AgregarDataDespresado"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-1" id="DivDataDesprese" style="display: none;">
                            <table id="TDataDespresado" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Item</th>
                                        <th>Lote</th>
                                        <th>Proveedor</th>
                                        <th>Fecha Beneficio</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Unidades</th>
                                        <th>Kilos Disponibles</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <h6 style="font-size: large;font-weight: bold;display: none;" name="DivPollo" class="mt-3">Items</h6>

                        <input type="hidden" name="idPesos" id="idPesos">

                        <div class="col-md-3 mb-2" style="display: none" name="DivPollo">
                            <label for="item" class="form-label">Item</label>
                            <select name="item" id="item" class="form-select">
                                <option value="0">Seleccione un item</option>
                                <?php foreach ($items as $item):?>
                                    <option value="<?= $item[0] ?>"><?= $item[1] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="itemError" style="display: none">
                                Debe ingresar un item
                            </div>
                        </div>

                        <div class="col-md-3 mb-2" style="display: none" name="DivPollo">
                            <label for="loteP" class="form-label">Lote</label>
                            <select name="loteP" id="loteP" class="form-select">
                                <option value="0">Seleccione un lote</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="lotePError" style="display: none">
                                Debe ingresar un lote
                            </div>
                        </div>
                        
                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="kilosP" class="form-label">Kilos</label>
                            <input type="number" name="kilosP" id="kilosP" class="form-control" placeholder="Ingrese los kilos">
                            <div class="alert alert-danger" role="alert" id="kilosPError" style="display: none">
                                Debe de ingresar los kilos
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="cajasP" class="form-label">Cajas</label>
                            <input type="number" name="cajasP" id="cajasP" class="form-control" placeholder="Ingrese las cajas">
                            <div class="alert alert-danger" role="alert" id="cajasPError" style="display: none">
                                Debe de ingresar las cajas
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="cajasBaseP" class="form-label">Cajas Base</label>
                            <input type="number" name="cajasBaseP" id="cajasBaseP" class="form-control" placeholder="Ingrese las cajas base">
                            <div class="alert alert-danger" role="alert" id="cajasBasePError" style="display: none">
                                Debe de ingresar las cajas base
                            </div>
                        </div>

                        <div class="col-md-12 mb-2 mt-2" style="text-align: center;">
                            <button class="btn btn-primary" style="display: none;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="GuardarPollos">Guardar Item</button>
                            <button class="btn btn-primary" style="display: none;" rel="tooltip" data-placement="bottom" id="EditarPesos">Editar Item</button>
                        </div>

                        <div class="table-responsive mt-3" id="DivTabla" style="display: none;">
                        <table id="TItems" class="table table-striped table-bordered table-hover datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Lote</th>
                                    <th>Descripción</th>
                                    <th>Kilos</th>
                                    <th>Cajas</th>
                                    <th>Cajas Base</th>
                                    <th>Kilos Neto</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                        <!-- <div class="table-responsive mt-3" id="DivTabla" style="display: none;">
                            <table id="TPesos" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Kilos</th>
                                        <th>Cajas</th>
                                        <th>Cajas Base</th>
                                        <th>Kilos Neto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div> -->

                    </div>					
					<div class="row mt-4">
						<div class="col-md-12 mb-1" style="text-align:center">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
							
							<button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1100; background-color: #00000042 !important">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="tituloII">Agregar Mejora</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <h6 style="font-size: large;font-weight: bold;" class="mt-1 mb-2">Desprese</h6>

                        <input type="hidden" id="idItem">

                        <div class="col-md-4 mb-2">
                            <label for="kilosDesprese" class="form-label">Kilos</label>
                            <input type="number" name="kilosDesprese" id="kilosDesprese" class="form-control" autocomplete="off" placeholder="Ingrese los kilos">
                            <div class="alert alert-danger" role="alert" id="kilosDespreseError" style="display: none">
                                Debe ingresar los kilos
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="cajasDesprese" class="form-label">Cajas</label>
                            <input type="number" name="cajasDesprese" id="cajasDesprese" class="form-control" autocomplete="off" placeholder="Ingrese las cajas">
                            <div class="alert alert-danger" role="alert" id="cajasDespreseError" style="display: none">
                                Debe ingresar las cajas
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="canastillaBaseDesprese" class="form-label">Cajas Base</label>
                            <input type="number" name="canastillaBaseDesprese" id="canastillaBaseDesprese" class="form-control" autocomplete="off" placeholder="Ingrese las canastillas base">
                            <div class="alert alert-danger" role="alert" id="canastillaBaseDespreseError" style="display: none">
                                Debe ingresar las cajas base
                            </div>
                        </div>

                        <div class="col-md-12 mt-2 mb-1 text-center">
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" id="AggItemMejora">Guardar</button>
							<button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" id="EditItemDespresado">Editar</button>
							<button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="modalMejoras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog  modal-dialog-centered modal-fullscreen modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="TituloMejora">Agregar Mejoras Guia: 1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">

                        <input type="hidden" id="idMejora">
                        <input type="hidden" id="mejoraItem">

                        <div class="col-md-12 mb-2">
                            <table id="TDatosGuia" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Lote</th>
                                        <th>Proveedor</th>
                                        <th>Fecha Beneficio</th>
                                        <th>Fecha Vencimiento Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="proveedorH" class="form-label">Proveedor Proveedor Hielo</label>
                            <select name="proveedorH" id="proveedorH" class="form-select">
                                <option value="0">Seleccione un proveedor</option>
                                <?php foreach ($proveedoresHielo as $proveedor):?>
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="proveedorHError" style="display: none">
                                Debe ingresar un proveedor
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="loteHielo" class="form-label">Lote Proveedor Hielo</label>
                            <input type="text" name="loteHielo" id="loteHielo" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                            <div class="alert alert-danger" role="alert" id="loteHieloError" style="display: none">
                                Debe ingresar un lote
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="fechaHielo" class="form-label">Fecha de vencimiento Hielo</label>
                            <input type="date" name="fechaHielo" id="fechaHielo" class="form-control">
                            <div class="alert alert-danger" role="alert" id="fechaHieloError" style="display: none">
                                Debe ingresar la fecha de vencimiento
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="proveedorE" class="form-label">Proveedor Empaque</label>
                            <select name="proveedorE" id="proveedorE" class="form-select">
                                <option value="0">Seleccione un proveedor</option>
                                <?php foreach ($proveedoresEmpaque as $proveedor):?>
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="proveedorEError" style="display: none">
                                Debe ingresar un proveedor
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="loteEmpaque" class="form-label">Lote Proveedor Empaque</label>
                            <input type="text" name="loteEmpaque" id="loteEmpaque" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                            <div class="alert alert-danger" role="alert" id="loteEmpaqueError" style="display: none">
                                Debe ingresar un lote
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="fabricanteE" class="form-label">Fabricante Empaque</label>
                            <select name="fabricanteE" id="fabricanteE" class="form-select">
                                <option value="0">Seleccione un fabricante</option>
                                <?php foreach ($fabricantesEmpaque as $fabricante):?>
                                    <option value="<?= $fabricante[0] ?>"><?= $fabricante[2] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="fabricanteEError" style="display: none">
                                Debe ingresar un fabricante
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="proveedorS" class="form-label">Proveedor Salmuera</label>
                            <select name="proveedorS" id="proveedorS" class="form-select">
                                <option value="0">Seleccione un proveedor</option>
                                <?php foreach ($proveedoresSalmuera as $proveedor):?>
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="proveedorSError" style="display: none">
                                Debe ingresar un proveedor
                            </div>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label for="loteSalmuera" class="form-label">Lote Salmuera</label>
                            <input type="text" name="loteSalmuera" id="loteSalmuera" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                            <div class="alert alert-danger" role="alert" id="loteSalmueraError" style="display: none">
                                Debe ingresar un lote
                            </div>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label for="concentracion" class="form-label">Concentración</label>
                            <select name="concentracion" id="concentracion" class="form-select">
                                <option value="0">Seleccione la concentración</option>
                                <?php foreach ($salmueras as $salmuera):?>
                                    <option value="<?= $salmuera["id"] ?>"><?= $salmuera["concentracion"] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="concentracionError" style="display: none">
                                ingrese la concentracion
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="fechaSalmuera" class="form-label">Fecha de vencimiento Salmuera</label>
                            <input type="date" name="fechaSalmuera" id="fechaSalmuera" class="form-control">
                            <div class="alert alert-danger" role="alert" id="fechaSalmueraError" style="display: none">
                                Debe ingresar la fecha de vencimiento
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-3" style="text-align: center;">
                            <div class="row">
                                <div class="col-md-4 offset-md-4 d-flex justify-content-center gap-2">
                                    <button class="btn btn-primary m-0" style="display: none; margin: 0px !important;" rel="tooltip" data-placement="bottom" title="Editar Guia" id="EditarGuia">Editar</button>
                                    <button class="btn btn-primary m-0" style="margin: 0px !important;" rel="tooltip" data-placement="bottom" title="Editar Guia" id="GuardarGuia">Guardar</button>
                                </div>

                                <div class="col-md-4 justify-content-end gap-2 align-items-center" id="DivImprimirEtiquetas" style="display: none;">
                                    <button id="ImprimirEtiquetas" class="btn btn-primary" style="margin: 0px !important;" rel="tooltip" data-placement="bottom"><i class="bi bi-printer me-1"></i> Imprimir Etiquetas</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2" name="CamposMejora" style="display: none;">
                            <label for="itemMejora" class="form-label">Item</label>
                            <select name="itemMejora" id="itemMejora" class="form-select">
                                <option value="0">Seleccione un item</option>
                                <?php foreach ($items as $item):?>
                                    <option value="<?= $item[0] ?>"><?= $item[1] ?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="itemMejoraError" style="display: none">
                                Debe ingresar un item
                            </div>
                        </div>

                        <div class="col-md-6 mb-2" style="display: none;">
                            <label for="responsableM" class="form-label">Responsable</label>
                            <select class="form-select" id="responsableM" name="responsableM" disabled>
                                <?php foreach ($listaResponsables as $perm) : ?>
                                    <option value="<?php echo $perm['cedula'] ?>">
                                        <?php echo $perm['cedula'] . " - " . $perm['nombres'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="responsableME" style="display: none">
                                Debe seleccinar el responsable
                            </div>
                        </div>

                        <div class="col-md-3 mb-2" name="CamposMejora" style="display: none;">
                            <label for="lote" class="form-label">Lote Producto Terminado</label>
                            <input type="text" class="form-control" id="lote" autocomplete="off" disabled name="lote">
                            <div class="alert alert-danger" role="alert" id="loteError" style="display: none">
                                Debe de ingresar el lote
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" name="CamposMejora" style="display: none;">
                            <label for="kilosMejora" class="form-label">Kilos</label>
                            <input type="number" name="kilosMejora" id="kilosMejora" class="form-control" autocomplete="off" placeholder="Ingrese los kilos">
                            <div class="alert alert-danger" role="alert" id="kilosMejoraError" style="display: none">
                                Debe ingresar los kilos
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" name="CamposMejora" style="display: none;">
                            <label for="cajasMejora" class="form-label">Cajas</label>
                            <input type="number" name="cajasMejora" id="cajasMejora" class="form-control" autocomplete="off" placeholder="Ingrese las cajas">
                            <div class="alert alert-danger" role="alert" id="cajasMejoraError" style="display: none">
                                Debe ingresar las cajas
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" name="CamposMejora" style="display: none;">
                            <label for="cajasBaseMejora" class="form-label">Cajas Base</label>
                            <input type="number" name="cajasBaseMejora" id="cajasBaseMejora" class="form-control" autocomplete="off" placeholder="Ingrese las cajas base">
                            <div class="alert alert-danger" role="alert" id="cajasBaseMejoraError" style="display: none">
                                Debe ingresar las cajas base
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-3 text-center" name="CamposMejora" style="display: none;">
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" id="AgregarMejora">Guardar</button>
                            <button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" id="EditMejora">Editar</button>
                            <button class="btn btn-danger" style="margin-right: 1rem !important;" id="CerrarModalMejoras">Cerrar</button>
                        </div>

                        <div class="table-responsive" name="CamposMejora" style="display: none;">
                            <table id="TItemsMejora" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Descripción</th>
                                        <th>Lote Produccion</th>
                                        <th>Kilos</th>
                                        <th>Cajas</th>
                                        <th>Cajas Base</th>
                                        <th>Kilos Neto</th>
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
		</div>
	</div>

    <div class="modal fade" id="ModalTemp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="TituloTemp">Agregar Temperatura Salmuera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" name="idTemp" id="idTemp">

                        <div class="col-md-6 mb-3">
                            <label for="Temp1" class="form-label">Temperatura 1</label>
                            <input type="number" class="form-control" id="Temp1" autocomplete="off" name="Temp1">
                            <div class="alert alert-danger" role="alert" id="Temp1Error" style="display: none">
                                Debe de ingresar la temperatura 1
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Hora1" class="form-label">Hora 1</label>
                            <input type="text" class="form-control" id="Hora1" name="Hora1" autocomplete="off" disabled value="<?= date("g:i A") ?>">
                            <div class="alert alert-danger" role="alert" id="Hora1Error" style="display: none">
                                Debe de ingresar la Hora
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Temp2" class="form-label">Temperatura 2</label>
                            <input type="number" class="form-control" id="Temp2" name="Temp2" autocomplete="off">
                            <div class="alert alert-danger" role="alert" id="Temp2Error" style="display: none">
                                Debe de ingresar la temperatura 2
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Hora2" class="form-label">Hora 2</label>
                            <input type="text" class="form-control" id="Hora2" name="Hora2" autocomplete="off" disabled value="<?= date("g:i A") ?>">
                            <div class="alert alert-danger" role="alert" id="Hora2Error" style="display: none">
                                Debe de ingresar la Hora
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Temp3" class="form-label">Temperatura 3</label>
                            <input type="number" class="form-control" id="Temp3" name="Temp3" autocomplete="off">
                            <div class="alert alert-danger" role="alert" id="Temp3Error" style="display: none">
                                Debe de ingresar la temperatura 3
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Hora3" class="form-label">Hora 3</label>
                            <input type="text" class="form-control" id="Hora3" name="Hora3" autocomplete="off" disabled value="<?= date("g:i A") ?>">
                            <div class="alert alert-danger" role="alert" id="Hora3Error" style="display: none">
                                Debe de ingresar la Hora
                            </div>
                        </div>

                        <div class="col-md-12 mt-1 mb-1 text-center">
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" id="AgregarTemp">Guardar</button>
							<button class="btn btn-danger" style="margin-right: 1rem !important;" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalImprimirEtiquetas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalImprimirEtiquetasLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h1 class="modal-title fs-5" id="TituloImprimir">Imprimir Etiquetas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="FormImprimirEtiquetas" style="margin: 0px !important;">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label for="itemImp" class="form-label fw-bold">Item</label>
                                <select name="itemImp" id="itemImp" class="form-select">
                                    <option value="">Seleccione un Item</option>
                                </select>
                                <div class="alert alert-danger" role="alert" id="itemImpError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe seleccionar un item
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="loteImp" class="form-label fw-bold">Lote</label>
                                <input type="text" name="loteImp" id="loteImp" class="form-control" autocomplete="off" placeholder="Ingrese el lote">
                                <div class="alert alert-danger" role="alert" id="loteImpError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe seleccionar un lote
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="cantidadImp" class="form-label fw-bold">Cantidad Cajas</label>
                                <input type="number" name="cantidadImp" id="cantidadImp" class="form-control" autocomplete="off" placeholder="Ingrese la cantidad">
                                <div class="alert alert-danger" role="alert" id="cantidadImpError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe ingresar la cantidad
                                </div>
                            </div>

                            <div class="col-md-12 mt-1 d-flex justify-content-center gap-2">
                                <button type="submit" class="btn btn-primary" id="guardarEtiquetas">Guardar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <table id="TRegistrosImpresion" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Lote</th>
                                        <th>Cantidad</th>
                                        <th>Status</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                            <button class="btn btn-primary" id="imprimirEtiquetasPDF">Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCF" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="ModalCFLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h1 class="modal-title fs-5" id="TituloCF">&nbsp;</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" name="idGuia" id="idGuia">

                        <div class="col-md-12">
                            <table id="TablaHeaderLQ" class="table table-striped table-bordered table-hover datatable" name="TablasCF">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Descripción Item</th>
                                        <th>Kilos a Procesar</th>
                                        <th>Vr. Promedio Kilo a Despostar</th>
                                        <th>Costo Factura Pollo</th>
                                        <th>Costo Promedio Salmuera</th>
                                        <th>% Salmuera</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table id="TablaConPE_LQ" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th>Codigo Item</th>
                                        <th>Descripción</th>
                                        <th>Peso en Kilos Piezas Despresadas</th>
                                        <th>Kilos Despacho</th>
                                        <th>No. Piezas Despresadas</th>
                                        <th>% part</th>
                                        <th>COSTO X KL</th>
                                        <th>Precio Venta Unitario</th>
                                        <th>Total Precio Venta</th>
                                        <th>Costo Total</th>
                                        <th>Lote</th>
                                        <th>Kilos Mejorados</th>
                                        <th>Kilos Rendimiento Proceso</th>
                                        <th>Kilos Salmuera</th>
                                        <th>Costo Kilos Salmuera</th>
                                        <th>% Mejoramiento</th>
                                        <th>% Merma</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table id="TablaSinPE_CF" class="table table-striped table-bordered table-hover datatable" name="TablasCF">
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table id="TablaTotal" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
                            </table>
                        </div>

                        <div class="col-md-12 d-inline-flex gap-2">
                            <table id="TablaTotalLQ" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th colspan="2">Total</th>
                                    </tr>
                                </thead>
                            </table>
                            <table id="TablaTotalPorcentajeM_LQ" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th colspan="2">Total % Mejoramiento</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="col-md-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-success" id="imprimirCF"><i class="bi bi-printer me-2"></i>Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalDatosCF" tabindex="-1" aria-labelledby="ModalDatosCFLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formDatosCF" style="margin-bottom: 0px !important;">
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="accionCF" id="accionCF">
                            <input type="hidden" name="itemCF" id="itemCF">

                            <div class="col-md-12">
                                <label for="campoDatoCF" class="form-label fw-bold">&nbsp;</label>
                                <input type="text" id="campoDatoCF" class="form-control" placeholder="Ingrese un valor" required pattern="^[0-9]+(\.[0-9]+)?$">
                                <div class="alert alert-danger" role="alert" id="campoDatoCFError" style="display: none;">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Ingrese un valor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCrearPlano" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalCrearPlanoLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" style="visibility: hidden;"></button>
                    <h1 class="modal-title fs-5">Crear Plano</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="FormCrearPlano" style="margin: 0px !important;">
                        <div class="row mb-3">

                            <input type="hidden" name="destinoPlano" id="destinoPlano">
                            <input type="hidden" name="tipoPolloPlano" id="tipoPolloPlano">

                            <div class="col-md-12 mb-2">
                                <label for="guiaPlano" class="form-label fw-bold">Guia</label>
                                <input type="text" name="guiaPlano" id="guiaPlano" class="form-control" placeholder="Ingrese la guia" required pattern="^[0-9]+$">
                                <div class="alert alert-danger" role="alert" id="guiaPlanoError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe ingresar la guia
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="COPlano" class="form-label fw-bold">C.O</label>
                                <input type="text" name="COPlano" id="COPlano" class="form-control" autocomplete="off" placeholder="Ingrese el C.O" required>
                                <div class="alert alert-danger" role="alert" id="COPlanoError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe ingresar el C.O
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="bodegaPlano" class="form-label fw-bold">Bodega</label>
                                <input type="number" name="bodegaPlano" id="bodegaPlano" class="form-control" autocomplete="off" placeholder="Ingrese la bodega" required>
                                <div class="alert alert-danger" role="alert" id="bodegaPlanoError" style="display: none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Debe ingresar la bodega
                                </div>
                            </div>

                            <div class="col-md-12 mt-1 d-flex justify-content-center gap-2">
                                <button type="submit" class="btn btn-primary" id="guardarGuiaPlano">Guardar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-1">
                        <div class="col-md-12">
                            <table id="TPlanos" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Guia</th>
                                        <th>Sede</th>
                                        <th>C.O</th>
                                        <th>Bodega</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-end">
                            <div>
                                <select name="tipoPlano" id="tipoPlano" class="form-select">
                                    <option value="">Seleccione el tipo de plano</option>
                                    <option value="Desprese">Desprese</option>
                                    <option value="Mejoramiento">Mejoramiento</option>
                                    <option value="Excel">Excel</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" id="crearPlano">Crear Plano</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('scripts.php');?>

    <script>
        $(document).ready(function() {
            $('#jtable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": false,
                "bDestroy": true,
                "order": [
                    [0, 'DESC']
                ],
                "ajax": {
                    url: "tablas/TablaDespresado.php",
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

        $("#crearProveedor").click(function() {        
            habilitarModuloPollo = true;
            validarGuia = true;
            $('#horaInicialDespresado').text('');
            $('#titulo').text('AGREGAR DESPRESADO').removeClass("me-5");
            $("#sede").removeAttr("disabled");
            $("#tipoP").removeAttr("disabled");
            $("#fechaRegistro").attr("disabled", "true");
            $("#cantidadPollo").removeAttr("disabled");
            $("#proveedorP").removeAttr("disabled");
            $("#lotePollo").removeAttr("disabled");
            $("#tipoP").attr("disabled", "true");
            $("#fechaPollo").attr("disabled", "true");
            $("#proveedorP").attr("disabled", "true");
            $("#fechaBeneficio").attr("disabled", "true");
            $("#unidades").attr("disabled", "true");
            $("#responsable").removeAttr("disabled");
            $("#sede").prop("selectedIndex", 0);
            $("#tipoP").prop("selectedIndex", 0);
            $("#fechaRegistro").val("<?= date("Y-m-d") ?>");
            $("#cantidadPollo").val("");
            $("#proveedorP").val("");
            $("#lotePollo").val("");
            $("#kilosDisponiblesD").val("");
            $("#existe").val("");
            $("#fechaBeneficio").val("");
            $("#fechaPollo").val("");
            $("#unidades").val("");
            $("#responsable").val("<?= $_SESSION["usuario"] ?>");
            $("#item").prop("selectedIndex", 0);
            $("#kilosP").val("");
            $("#cajasP").val("");
            $("#cajasBaseP").val("");
            $("#loteP").val("0");
            $(".alert").hide();
            $("#DivDataDesprese").hide();
            $("[name='DivProveedores']").hide();
            $("[name='DivPollo']").css("display", "none");
            $("#DivTabla").css("display", "none");
            $('#btnNuevoProveedor').css('display', 'initial');
            $('#btnEditProveedor').css('display', 'none');
            $("#GuardarPollos").css("display", "none");
        });
    
        async function validaciones() {
            $(".alert").hide();

            if ($("#sede").val() == "0" || $("#sede").val() == null) {
                $("#sedeError").css("display", "block");
                return "R";
            }

            /* if ($("#tipoP").val() == "" || $("#tipoP").val() == "0") {
                $("#tipoPError").css("display", "block");
                return "R";
            } */

            if ($("#fechaRegistro").val() == "" || $("#fechaRegistro").val() == null) {
                $("#fechaRegistroError").css("display", "block");
                return "R";
            }

            if ($("#cantidadPollo").val() == "" || $("#cantidadPollo").val() == 0 || $("#cantidadPollo").val() < 0 || $("#cantidadPollo").val() == null) {
                $("#cantidadPolloError").css("display", "block");
                return "R";
            }

            /* if ($("#proveedorP").val() == "0" || $("#proveedorP").val() == "") {
                $("#proveedorPError").css("display", "block");
                return "R";
            } */

            /* if ($("#lotePollo").val() == "") {
                $("#lotePolloError").text("Debe ingresar un lote");
                $("#lotePolloError").css("display", "block");
                return "R";
            } else {
                // 🔁 usamos await con una promesa que envuelve al $.ajax
                const resultado = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            validarLotePlanta: $("#lotePollo").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            resolve(data);
                        },
                        error: function (err) {
                            reject(err);
                        }
                    });
                });

                if (resultado == "0") {
                    $("#lotePolloError").text("Lote no existe");
                    $("#lotePolloError").css("display", "block");
                    return "R";
                } else {
                    $("#lotePolloError").css("display", "none");
                }
            } */

            /* if ($("#fechaBeneficio").val() == "" || $("#fechaBeneficio").val() == "0") {
                $("#fechaBeneficioError").css("display", "block");
                return "R";
            }

            if ($("#fechaPollo").val() == "" || $("#fechaPollo").val() == "0") {
                $("#fechaPolloError").css("display", "block");
                return "R";
            } */

            return "";
        }

        $('#btnNuevoProveedor').click(async function () { // <-- cambio aquí: función async
            var validacion = await validaciones(); // <-- cambio aquí: usamos await
            console.log('validacion: ', validacion);

            if (validacion == "") {
                $('#btnNuevoProveedor').prop('disabled', true);
                datos = {
                    sede: $("#sede").val(),
                    fechaRegistro: $("#fechaRegistro").val(),
                    cantidadPollo: $("#cantidadPollo").val(),
                    responsable: $("#responsable").val(),
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        guiaDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#jtable").DataTable().ajax.reload();
                        $("#id").val(data.id);
                        $("#sede").attr("disabled", "true");
                        $("#fechaRegistro").attr("disabled", "true");
                        $("#cantidadPollo").attr("disabled", "true");
                        $('#btnNuevoProveedor').css('display', 'none');
                        iniciarTablaData();
                        validarGuia = false;
                        $('#btnNuevoProveedor').prop('disabled', false);
                    },
                    error: function() {
                        $('#btnNuevoProveedor').prop('disabled', false);
                    }
                });
            }
        });

        function iniciarTablaData(){
            $("#TDataDespresado").DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "info": false,
                "paging": false,
                "searching": false,
                "order": [
                    [0, 'DESC']
                ],
                "ajax": {
                    url: "tablas/TablaDataDespresado.php",
                    type: "post",
                    data: {
                        guia: $("#id").val()
                    },
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
            $('#TDataDespresado').css('width', '100%');
            $('#TDataDespresado_filter input, #TDataDespresado_length select').addClass('form-control');
            $('#TDataDespresado_filter').find('label').find('input').attr("name", "input")
        }

        function guardarGuia() {
            return new Promise(async (resolve, reject) => {
                var validacion = await validaciones();
                if (validacion == "") {
                    datos = {
                        sede: $("#sede").val(),
                        fechaRegistro: $("#fechaRegistro").val(),
                        cantidadPollo: $("#cantidadPollo").val(),
                        responsable: $("#responsable").val(),
                    }
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            guiaDespresado: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            $("#jtable").DataTable().ajax.reload();
                            $("#id").val(data.id);
                            $("#sede").attr("disabled", "true");
                            $("#fechaRegistro").attr("disabled", "true");
                            $("#cantidadPollo").attr("disabled", "true");
                            $('#btnNuevoProveedor').css('display', 'none');
                            iniciarTablaData();
                            validarGuia = false;
                            resolve(true);

                        },
                        error: function(error) {
                            reject(error);
                        }
                    });
                } else {
                    reject('Validation failed');
                }
            });
        }

        function vaciarCamposData() {
            $(".alert").hide();

            $("#tipoP").val("0");
            $("#proveedorP").val("");
            $("#proveedor").val("");
            $("#fechaBeneficio").val("");
            $("#fechaPollo").val("");
            $("#unidades").val("");
            $("#kilosDisponiblesD").val("");
        }

        var validarGuia = true;

        $("#AgregarLote").click(async function (e) { 
            e.preventDefault();
            if (validarGuia) {
                validarGuia = false;
                var validacionGuia = await validaciones();
                if (validacionGuia == "") {
                    var agregarGuia = await guardarGuia();
                    if (agregarGuia == true) {
                        console.log('se agrego la guia');
                    } else {
                        console.log('no se agrego la guia');
                        $("#lotePollo").val("");
                        return false;
                    }
                } else {
                    validarGuia = true;
                    $("#lotePollo").val("");
                    return false;
                }
            }

            datos = {
                lote: $("#lotePollo").val(),
                guia: $("#id").val()
            }

            if ($("#lotePollo").val() != "") {
                vaciarCamposData();
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        validarLotePlantaDatosDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log('data: ', data);
                        if (data.hasOwnProperty('status') && data.status == "error") {
                            /* $("#lotePolloError").text("Lote no existe");
                            $("#lotePolloError").css("display", "block"); */
                            $("#fechaBeneficio").val("");
                            $("#fechaPollo").val("");
                            $("#lotePollo").val("");
                            $("#unidades").val("");
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }else if (data.hasOwnProperty('status') && data.status == "errorTipo") {
                            Swal.fire({
                                title: '¡Atención!',
                                text: data.message,
                                icon: 'warning',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        } else if (data.hasOwnProperty('status') && data.status == "errorTipos") {
                            Swal.fire({
                                title: "¡Atención!",
                                text: "EL lote tiene diferentes tipos, ¿Cual desea agregar?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6", // azul para confirmar
                                denyButtonColor: "#ffc107", // amarillo para el otro
                                cancelButtonColor: "#d33", // rojo para cancelar
                                confirmButtonText: "CAMPO",
                                denyButtonText: "BLANCO",
                                showDenyButton: true,
                                cancelButtonText: "Cancelar"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("[name='DivProveedores']").show();
                                    $("#lotePolloError").css("display", "none");
                                    $("#lotePolloError").text("Debe ingresar un lote");
                                    var datos = data.data;
                                    $("#tipoP").val(datos.CAMPO.tipo);
                                    $("#fechaBeneficio").val(datos.CAMPO.fecha_beneficio);
                                    $("#fechaPollo").val(datos.CAMPO.fechasac);
                                    $("#kilosDisponiblesD").val(datos.CAMPO.peso_real);
                                    $("#unidades").val(datos.CAMPO.unidades);
                                    $("#proveedorP").val(datos.CAMPO.proveedorText);
                                    $("#proveedor").val(datos.CAMPO.proveedor);
                                } else if (result.isDenied) {
                                    $("[name='DivProveedores']").show();
                                    $("#lotePolloError").css("display", "none");
                                    $("#lotePolloError").text("Debe ingresar un lote");
                                    var datos = data.data;
                                    $("#tipoP").val(datos.BLANCO.tipo);
                                    $("#fechaBeneficio").val(datos.BLANCO.fecha_beneficio);
                                    $("#fechaPollo").val(datos.BLANCO.fechasac);
                                    $("#kilosDisponiblesD").val(datos.BLANCO.peso_real);
                                    $("#unidades").val(datos.BLANCO.unidades);
                                    $("#proveedorP").val(datos.BLANCO.proveedorText);
                                    $("#proveedor").val(datos.BLANCO.proveedor);
                                }
                            });
                        } else {
                            $("[name='DivProveedores']").show();
                            $("#lotePolloError").css("display", "none");
                            $("#lotePolloError").text("Debe ingresar un lote");
                            $("#tipoP").val(data.tipo);
                            $("#fechaBeneficio").val(data.fecha_beneficio);
                            $("#fechaPollo").val(data.fechasac);
                            $("#kilosDisponiblesD").val(data.peso_real);
                            $("#unidades").val(data.unidades);
                            $("#proveedorP").val(data.proveedorText);
                            $("#proveedor").val(data.proveedor);
                        }
                    }
                });
            } else {
                $("#lotePolloError").show();
                return false;
            }
        });

        async function validacionDatosDespresado() {
            $(".alert").hide();

            if ($("#tipoP").val() == "0") {
                $("#tipoPError").show();
                return false;
            } else {
                /* datos = {
                    tipo: $("#tipoP").val(),
                    idGuia: $("#id").val()
                }
                var validacion = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            validarTipoPolloDespresado: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == "errorTipo") {
                                $("#tipoPError").text(data.message);
                                $("#tipoPError").show();
                                resolve(data);
                            } else if (data.status == "success") {
                                $("#tipoPError").text("Debe seleccinar el tipo de pollo");
                                $("#tipoPError").hide();
                                resolve(data);
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (err) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al validar el tipo de pollo',
                                icon: 'error',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                });

                if (validacion.status == "errorTipo" || validacion.status == "error") {
                    return false;
                } */
            }

            if ($("#lotePollo").val() == "") {
                $("#lotePolloError").show();
                return false;
            }

            if ($("#proveedorP").val() == "0" || $("#proveedorP").val() == "") {
                $("#proveedorPError").show();
                return false;
            }

            if ($("#fechaBeneficio").val() == "" || $("#fechaBeneficio").val() == null) {
                $("#fechaBeneficioError").show();
                return false;
            }

            if ($("#fechaPollo").val() == "" || $("#fechaPollo").val() == null) {
                $("#fechaPolloError").show();
                return false;
            }

            if ($("#unidades").val() == "" || $("#unidades").val() == null) {
                $("#unidadesError").show();
                return false;
            }

            if ($("#kilosDisponiblesD").val() == "" || $("#kilosDisponiblesD").val() == null) {
                $("#kilosDisponiblesDError").show();
                return false;
            }

            return true;
        }

        var habilitarModuloPollo = true;

        $("#AgregarDataDespresado").click(async function (e) { 
            e.preventDefault();
            if (await validacionDatosDespresado()) {
                datos = {
                    lote: $("#lotePollo").val(),
                    tipoP: $("#tipoP").val(),
                    proveedor: $("#proveedor").val(),
                    fechaBeneficio: $("#fechaBeneficio").val(),
                    fechaVencimiento: $("#fechaPollo").val(),
                    unidades: $("#unidades").val(),
                    guia: $("#id").val()
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        agregarDataDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.hasOwnProperty('status') && data.status == "errorLote") {
                            Swal.fire({
                                title: data.message,
                                text: "",
                                icon: 'error',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                            $("#lotePollo").val("");
                            vaciarCamposData();
                        } else if (data.hasOwnProperty('status') && data.status == "errorTipo") {
                            Swal.fire({
                                title: '¡Atención!',
                                text: data.message,
                                icon: 'warning',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                            $("#lotePollo").val("");
                            vaciarCamposData();
                        } else if (data.status == "success") {
                            $("#DivDataDesprese").show();
                            $("#TDataDespresado").DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Datos agregados correctamente',
                                text: '',
                                icon: 'success',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                            if (habilitarModuloPollo) {
                                moduloPollo();
                                habilitarModuloPollo = false;
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "controlador/controlador.php",
                                    data: {
                                        trerLotesDespresado: $("#id").val()
                                    },
                                    dataType: "json",
                                    success: function (data) {
                                        if (data.hasOwnProperty('status') && data.status == "error") {
                                            Swal.fire({
                                                title: "Error",
                                                text: data.message,
                                                icon: "error",
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        } else {
                                            $("#loteP").empty();
                                            $("#loteP").append('<option value="0">Seleccione un Lote</option>');
                                            for (let i = 0; i < data.length; i++) {
                                                $("#loteP").append('<option value="'+ data[i] +'">'+ data[i] +'</option>');
                                            }
                                        }
                                    },
                                    error: function (err) {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Error al traer los lotes',
                                            icon: 'error',
                                            timer: 1300, 
                                            showConfirmButton: false
                                        });
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "controlador/controlador.php",
                                    data: {
                                        TraerItemsDespresado: $("#id").val()
                                    },
                                    dataType: "json",
                                    success: function (data) {
                                        if (data.status == "success") {
                                            var items = data.data;
                                            items.unshift({"item":"0", "descripcion":"Seleccione un item"});
                                            $("#item").empty();
                                            items.forEach(item => {
                                                $("#item").append("<option value="+item.item+">"+item.descripcion+"</option>");
                                            });
                                        } else {
                                            $("#item").empty();
                                            $("#item").append('<option value="0">Seleccione un item</option>');
                                            Swal.fire({
                                                title: "Error",
                                                text: data.message,
                                                icon: "error",
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        }
                                    },
                                    error: function (err) {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Error al traer los items',
                                            icon: 'error',
                                            timer: 1300, 
                                            showConfirmButton: false
                                        });
                                    }
                                });
                            }
                            $("#lotePollo").val("");
                            vaciarCamposData();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (err) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al agregar los datos',
                            icon: 'error',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function EliminarRegistro(id, lote, guia) {
            Swal.fire({
                title: '¿Estás seguro?',
                html: '¡No podrás revertir esto!<br>¡Se eliminarán los pesos del pollo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    datos = {
                        id: id,
                        lote: lote,
                        guia: guia
                    }

                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            eliminarRegistroDespresado: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == "success") {
                                $("#TDataDespresado").DataTable().ajax.reload();
                                $("#TPesos").DataTable().ajax.reload();
                                $("#TItems").DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (err) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al eliminar el registro',
                                icon: 'error',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function vaciarCamposPollo(){
            $(".alert").hide();

            $("#TipoPollo").val("0");
            $("#loteP").val("0");
            $("#kilosP").val("");
            $("#cajasP").val("");
            $("#cajasBaseP").val("");
            $("#nroPollos").val(""); 
        }

        function moduloPollo(totalCambios) {
            $("[name='DivPollo']").css("display", "block"); 
            vaciarCamposPollo();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    trerLotesDespresado: $("#id").val()
                },
                dataType: "json",
                success: function (data) {
                    if (data.hasOwnProperty('status') && data.status == "error") {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        $("#loteP").empty();
                        $("#loteP").append('<option value="0">Seleccione un Lote</option>');
                        for (let i = 0; i < data.length; i++) {
                            $("#loteP").append('<option value="'+ data[i] +'">'+ data[i] +'</option>');
                        }
                    }
                },
                error: function (err) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al traer los lotes',
                        icon: 'error',
                        timer: 1300, 
                        showConfirmButton: false
                    });
                }
            });
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    TraerItemsDespresado: $("#id").val()
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        var items = data.data;
                        items.unshift({"item":"0", "descripcion":"Seleccione un item"});
                        $("#item").empty();
                        items.forEach(item => {
                            $("#item").append("<option value="+item.item+">"+item.descripcion+"</option>");
                        });
                    } else if (data.status == "errorRegistros") {
                        $("#item").empty();
                        $("#item").append('<option value="0">Seleccione un item</option>');
                        Swal.fire({
                            position: "top-end",
                            title: "No hay Lote para buscar los items",
                            icon: "warning",
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (err) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al traer los items',
                        icon: 'error',
                        timer: 1300, 
                        showConfirmButton: false
                    });
                }
            });
            $("#EditarPesos").hide();
            $("#GuardarPollos").css("display", "initial");
            $("#DivTabla").css("display", "block");
            $("#TItems").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": false,
                "order": [[ 0, "desc" ]],
                "ordering": totalCambios > 0 && <?= $_SESSION["tipo"] ?> == 0 && <?= $_SESSION["registrosCambios"] ?> == 1 ? false : true,
                "ajax": {
                    url: "tablas/TablaDespresadoItems.php",
                    type: "post",
                    data: {
                        id: $("#id").val()
                    }
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
            $('#TItems').css('width', '100%');
            $('#TItems_filter input, #TItems_length select').addClass('form-control');
            $('#TItems_filter').find('label').find('input').attr("name", "input3");
        }

        async function validacionesPollo() {
            $(".alert").hide();

            if ($("#item").val() == "" || $("#item").val() == "0") {
                $("#itemError").css("display", "block");
                return "L";
            }

            if ($("#loteP").val() == "" || $("#loteP").val() == "0") {
                $("#lotePError").css("display", "block");
                return "L";
            } else {
                var validacion = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            validarLoteDespresado: {
                                lote: $("#loteP").val(),
                                guia: $("#id").val(),
                                item: $("#item").val()
                            }
                        },
                        dataType: "json",
                        success: function (data) {
                            resolve(data);
                        },
                        error: function (err) {
                            reject(err);
                        }
                    });
                });

                if (validacion.status == "error") {
                    $("#lotePError").text(validacion.message);
                    $("#lotePError").show();
                    return "L";
                } else {
                    $("#lotePError").hide();
                    $("#lotePError").text("Debe ingresar un lote");
                }
            }

            if ($("#kilosP").val() == "" || $("#kilosP").val() == 0 || $("#kilosP").val() < 0) {
                $("#kilosPError").css("display", "block");
                return "L";
            }

            if ($("#cajasP").val() == "" || $("#cajasP").val() == 0 || $("#cajasP").val() < 0) {
                $("#cajasPError").css("display", "block");
                return "L";
            }

            if ($("#cajasBaseP").val() == "" || $("#cajasBaseP").val() == 0 || $("#cajasBaseP").val() < 0) {
                $("#cajasBasePError").css("display", "block");
                return "L";
            }

            return "";
        }

        $("#GuardarPollos").click(async function (e) { 
            e.preventDefault();
            if (await validacionesPollo() == "") {
                $("#GuardarPollos").prop('disabled', true);
                datos = {
                    guia: $("#id").val(),
                    lote: $("#loteP").val(),
                    item: $("#item").val(),
                    kilos: $("#kilosP").val(),
                    cajas: $("#cajasP").val(),
                    canastillaBase: $("#cajasBaseP").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        despresadoItem: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#item").prop("selectedIndex", 0);
                        $("#kilosP").val("");
                        $("#loteP").val("0");
                        $("#cajasP").val("");
                        $("#cajasBaseP").val("");
                        $('#TItems').DataTable().ajax.reload();
                        $('#jtable').DataTable().ajax.reload();
                        $('#TDataDespresado').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Item registrado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarPesosDespresado: $("#id").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                console.log(data);
                                setTimeout(() => {
                                    if (data["status"] == "errorM") {
                                    Swal.fire({
                                        title: '¡Aviso!',
                                        text: 'El peso del despresado no puede ser superior al peso inicial',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                    } else if (data["status"] == "errorA") {
                                        Swal.fire({
                                            title: '¡Aviso!',
                                            html: 'Desviación fuera de Rango Peso inicial vs Peso Despresado de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar'
                                        });
                                    }
                                }, 1300);
                            }
                        });
                        $("#GuardarPollos").prop('disabled', false);
                    },
                    error: function() {
                        $("#GuardarPollos").prop('disabled', false);
                    }
                });
            }
        });

        function buscar_guia(id, totalCambios) {
            $('#id').val(id);
            habilitarModuloPollo = false;
            validarGuia = false;
            $("#statusEdit").val("1");
            $('#titulo').text('EDITAR DESPRESADO #' + id).addClass("me-5");
            $("#sede").removeAttr("disabled");
            $("#cantidadPollo").removeAttr("disabled");
            $("#tipoP").prop("selectedIndex", 0);
            $("#proveedorP").val("");
            $("#proveedor").val("");
            $("#lotePollo").val("");
            $("#fechaBeneficio").val("");
            $("#unidades").val("");
            $("#cantidadPollo").val("");
            $("#fechaPollo").val("");
            $("#loteP").val("0");
            $("#kilosDisponiblesD").val("");
            $("#responsable").val("<?= $_SESSION["usuario"] ?>");
            $("#item").prop("selectedIndex", 0);
            $("#kilosP").val("");
            $("#cajasP").val("");
            $("#cajasBaseP").val("");
            $(".alert").hide();
            $("[name='DivProveedores']").show();
            $("[name='DivPollo']").show();
            $("#DivDataDesprese").show();
            iniciarTablaData();
            moduloPollo(totalCambios);
            $("#GuardarPollos").show();
            $("#EditarPesos").hide();
            $("#DivTabla").show();
            $('#btnNuevoProveedor').hide();
            $('#btnEditProveedor').show();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    idDespreseDespresado: $("#id").val()
                },
                success: function(data) {
                    $("#sede").val(data[0][0]);
                    /* $("#proveedorP").val(data[0][1]);
                    $("#lotePollo").val(data[0][2]);
                    $("#fechaPollo").val(data[0][3]); */
                    $("#responsable").val(data[0][4]);
                    /* $("#fechaBeneficio").val(data[0][5]);
                    $("#tipoP").val(data[0][6]); */
                    $("#fechaRegistro").val(data[0][7]);
                    $("#cantidadPollo").val(data[0][8]);

                    $('#horaInicialDespresado').text('HI: ' + data[0][9]);
                }
            });
            /* $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    TraerItemsDespresado: id
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        var items = data.data;
                        items.unshift({"item":"0", "descripcion":"Seleccione un item"});
                        $("#item").empty();
                        items.forEach(item => {
                            $("#item").append("<option value="+item.item+">"+item.descripcion+"</option>");
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (err) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al traer los items',
                        icon: 'error',
                        timer: 1300, 
                        showConfirmButton: false
                    });
                }
            }); */
        }

        function editDesprese(id, descripcion) {
            $("#idPesos").val(id);
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    idItemDespresado: id
                },
                dataType: "json",
                success: function (data) {
                    $("#kilosP").val(data[0][0]);
                    $("#cajasP").val(data[0][1]);
                    $("#cajasBaseP").val(data[0][2]);
                    $("#item").val(data[0][3]);
                    $("#loteP").val(data[0][4]);
                    $("#GuardarPollos").css("display", "none");
                    $("#EditarPesos").css("display", "initial");
                }
            });
        }

        $("#EditarPesos").click(async function (e) { 
            e.preventDefault();
            if (await validacionesPollo() == "") {
                $("#EditarPesos").prop('disabled', true);
                datos = {
                    id_item: $("#idPesos").val(),
                    item: $("#item").val(),
                    kilosDesprese: $("#kilosP").val(),
                    cajasDesprese: $("#cajasP").val(),
                    canastillaBase: $("#cajasBaseP").val(),
                    lote: $("#loteP").val()
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        editItemDespresado: datos
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.status == "success") {
                            $("#GuardarPollos").css("display", "initial");
                            $("#EditarPesos").css("display", "none");
                            $("#jtable").DataTable().ajax.reload();
                            $('#TItems').DataTable().ajax.reload();
                            $('#TDataDespresado').DataTable().ajax.reload();
                            $("#item").prop("selectedIndex", 0);
                            $("#kilosP").val("");
                            $("#cajasP").val("");
                            $("#cajasBaseP").val("");
                            $("#loteP").val("0");
                            $.ajax({
                                type: "POST",
                                url: "controlador/controlador.php",
                                data: {
                                    verificarPesosDespresado: $("#id").val()
                                },
                                dataType: "json",
                                success: function (data) {
                                    console.log(data);
                                    setTimeout(() => {
                                        if (data["status"] == "errorM") {
                                            Swal.fire({
                                            title: '¡Aviso!',
                                            text: 'El peso del despresado no puede ser superior al peso inicial',
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar'
                                        });
                                        } else if (data["status"] == "errorA") {
                                            Swal.fire({
                                                title: '¡Aviso!',
                                                html: 'Desviación fuera de Rango Peso inicial vs Peso despresado de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                                icon: 'error',
                                                confirmButtonText: 'Aceptar'
                                            });
                                        }
                                    }, 1300);
                                }
                            });
                        }
                        
                        Swal.fire({
                            icon: res.status,
                            title: res.message,
                            timer: 1000, 
                            showConfirmButton: false
                        });
                        $("#EditarPesos").prop('disabled', false);
                    },
                    error: function (res) {
                        Swal.fire({
                            icon: "error",
                            title: "Error al actualizar el item",
                            timer: 1000, 
                            showConfirmButton: false
                        });
                        $("#EditarPesos").prop('disabled', false);
                    }
                });
            }
        });

        function borrarItem(id) {
            Swal.fire({
                title: '¿Esta seguro que desea eliminar este Item?',
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
                            deleteItemDespresado: id
                        },
                        dataType: "json",
                        success: function (res) {
                            if (res.status == "success") {
                                $('#TItems').DataTable().ajax.reload();
                                $('#jtable').DataTable().ajax.reload();
                                $('#TDataDespresado').DataTable().ajax.reload();
                            }

                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        },
                        error: function (res) {
                            Swal.fire({
                                icon: "error",
                                title: "Error al eliminar el item",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        $('#btnEditProveedor').click(async function() {
            var validacion = await validaciones(); // <-- cambio aquí: usamos await
            console.log('validacion: ', validacion);
            if (validacion == "") {
                $('#btnEditProveedor').prop('disabled', true);
                datos = {
                    sede: $("#sede").val(),
                    fechaRegistro: $("#fechaRegistro").val(),
                    cantidadPollo: $("#cantidadPollo").val(),
                    idDesprese: $("#id").val(),
                    responsable: $("#responsable").val()
                }
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        editGuiaDespresado: datos
                    },
                    success: function(data) {
                        $('#jtable').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Guia modificada correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $('#btnEditProveedor').prop('disabled', false);
                    },
                    error: function() {
                        $('#btnEditProveedor').prop('disabled', false);
                    }
                });
            }
        });

        function abrirModal(id, sede) {
            $("#tituloI").text("Formato de Despresado # " + id + " - Sede: " + sede);
            $("#idGuia").val(id);
            $("#item").prop("selectedIndex", 0);
            $("#kilos").val("");
            $("#cajas").val("");
            $("#canastillaBase").val("");
            $("#loteP").val("0");
            $("#kilosDisponiblesD").val("");
            $("#itemError").css("display", "none");
            $("#kilosError").css("display", "none");
            $("#cajasError").css("display", "none");
            $("#canastillaBaseError").css("display", "none");
            $("#EditItem").css("display", "none");
            $("#AggItem").css("display", "initial");
            $("#TItems").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": false,
                "ajax": {
                    url: "tablas/TablaDespresadoItems.php",
                    type: "post",
                    data: {
                        id: id
                    }
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
            $('#TItems').css('width', '100%');
            $('#TItems_filter input, #TItems_length select').addClass('form-control');
            $('#TItems_filter').find('label').find('input').attr("name", "input2");
        }

        function validacionesItems() {
            $("#itemError").css("display", "none");
            $("#kilosError").css("display", "none");
            $("#cajasError").css("display", "none");
            $("#canastillaBaseError").css("display", "none");

            if ($("#item").val() == "0" || $("#item").val() == null) {
                $("#itemError").css("display", "block");
                return "L"
            }

            if ($("#kilos").val() == "" || $("#kilos").val() == "0") {
                $("#kilosError").css("display", "block");
                return "L"
            }

            if ($("#cajas").val() == "" || $("#cajas").val() == "0") {
                $("#cajasError").css("display", "block");
                return "L"
            }

            if ($("#canastillaBase").val() == "" || $("#canastillaBase").val() == "0") {
                $("#canastillaBaseError").css("display", "block");
                return "L"
            }

            return "";
        }

        $("#AggItem").click(function (e) { 
            e.preventDefault();
            if (validacionesItems() == "") {
                $("#AggItem").prop('disabled', true);
                datos = {
                    guia: $("#idGuia").val(),
                    item: $("#item").val(),
                    kilos: $("#kilos").val(),
                    cajas: $("#cajas").val(),
                    canastillaBase: $("#canastillaBase").val(),
                    idGuia: $("#idGuia").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        despresadoItem: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#item").prop("selectedIndex", 0);
                        $("#kilos").val("");
                        $("#cajas").val("");
                        $("#canastillaBase").val("");
                        $("#TItems").DataTable().ajax.reload();
                        $("#jtable").DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Item registrado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarPesosDespresado: $("#idGuia").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                console.log(data);
                                setTimeout(() => {
                                    if (data["status"] == "errorM") {
                                    Swal.fire({
                                        title: '¡Aviso!',
                                        text: 'El peso del despresado no puede ser superior al peso inicial',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                    } else if (data["status"] == "errorA") {
                                        Swal.fire({
                                            title: '¡Aviso!',
                                            html: 'Desviación fuera de Rango Peso inicial vs Peso Despresado de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar'
                                        });
                                    }
                                }, 1300);
                            }
                        });
                        $("#AggItem").prop('disabled', false);
                    },
                    error: function() {
                        $("#AggItem").prop('disabled', false);
                    }
                });
            }
        });

        $("#CerrarModalDespresado").click(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: '¿Desea cerrar la ventana del Despresado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#modalItems").modal("hide");
                }
            });
        });

        /* function editDesprese(idItem, descripcion) {
            $("#modalEdit").modal("show");
            $("#tituloII").text("Editar Item: " + idItem + " - " + descripcion);
            $("#idItem").val(idItem);
            $("#kilosDesprese").val("");
            $("#cajasDesprese").val("");
            $("#canastillaBaseDesprese").val("");
            $("#kilosDespreseError").css("display", "none");
            $("#cajasDespreseError").css("display", "none");
            $("#canastillaBaseDespreseError").css("display", "none");
            $("#AggItemMejora").css("display", "none");
            $("#EditItemDespresado").css("display", "initial");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    idItemDespresado: idItem
                },
                dataType: "json",
                success: function (data) {
                    $("#kilosDesprese").val(data[0][0]);
                    $("#cajasDesprese").val(data[0][1]);
                    $("#canastillaBaseDesprese").val(data[0][2]);
                }
            });
        } */

        function validacionesEditDespresado() {
            $("#kilosDespreseError").css("display", "none");
            $("#cajasDespreseError").css("display", "none");
            $("#canastillaBaseDespreseError").css("display", "none");
            
            if ($("#kilosDesprese").val() == "" || $("#kilosDesprese").val() == 0) {
                $("#kilosDespreseError").css("display", "block");
                return "L";
            }

            if ($("#cajasDesprese").val() == "" || $("#cajasDesprese").val() == 0) {
                $("#cajasDespreseError").css("display", "block");
                return "L";
            }

            if ($("#canastillaBaseDesprese").val() == "" || $("#canastillaBaseDesprese").val() == 0) {
                $("#canastillaBaseDespreseError").css("display", "block");
                return "L";
            }

            return "";
        }

        $("#EditItemDespresado").click(function (e) { 
            e.preventDefault();
            if (validacionesEditDespresado() == "") {
                $("#EditItemDespresado").prop('disabled', true);
                datos = {
                    kilosDesprese: $("#kilosDesprese").val(),
                    cajasDesprese: $("#cajasDesprese").val(),
                    canastillaBase: $("#canastillaBaseDesprese").val(),
                    id_item: $("#idItem").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        editItemDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#modalEdit").modal('hide');
                        $('#TItems').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Item Actualizado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarPesosDespresado: $("#idGuia").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                console.log(data);
                                setTimeout(() => {
                                    if (data["status"] == "errorM") {
                                        Swal.fire({
                                        title: '¡Aviso!',
                                        text: 'El peso del despresado no puede ser superior al peso inicial',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                    } else if (data["status"] == "errorA") {
                                        Swal.fire({
                                            title: '¡Aviso!',
                                            html: 'Desviación fuera de Rango Peso inicial vs Peso despresado de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar'
                                        });
                                    }
                                }, 1300);
                            }
                        });
                        $("#EditItemDespresado").prop('disabled', false);
                    },
                    error: function() {
                        $("#EditItemDespresado").prop('disabled', false);
                    }
                });
            }
        });

        /* function borrarItem(idItem,descripcion) {
            Swal.fire({
                title: '¿Esta seguro que desea eliminar este Item?',
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
                            deleteItemDespresado: idItem
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#TItems').DataTable().ajax.reload();
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Item Eliminado correctamente',
                                text: '',
                                icon: 'success',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        } */

        function iniciarTablaDatosGuia(guia) {
            $("#TDatosGuia").DataTable({
                "responsive": true,
                "bDestroy": true,
                "info": false,
                "searching": false,
                "paging": false,
                "pageLength": 10,
                "lengthChange": false,
                "ajax": {
                    url: "tablas/TablaDatosGuia.php",
                    type: "post",
                    data: {
                        guia: guia
                    },
                    dataSrc: function(json) {
                        return json.data;
                    }
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
            $('#TDatosGuia').css('width', '100%');
            $('#TDatosGuia_filter input, #TDatosGuia_length select').addClass('form-control');
            $('#TDatosGuia_filter').find('label').find('input').attr("name", "input")
        }

        function iniciarTablaRegistrosImpresion(guia) {
            $("#TRegistrosImpresion").DataTable().destroy();
            $("#TRegistrosImpresion").DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "paging": false,
                "info": false,
                "searching": false,
                "ajax": {
                    url: "tablas/TablaRegistrosImpresionDespresado.php",
                    type: "post",
                    data: {
                        guia: guia
                    },
                    dataSrc: function(json) {
                        return json.data;
                    }
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
            $('#TRegistrosImpresion').css('width', '100%');
            $('#TRegistrosImpresion_filter input, #TRegistrosImpresion_length select').addClass('form-control');
            $('#TRegistrosImpresion_filter').find('label').find('input').attr("name", "input2");
        }

        var ojo = "N";

        function abrirModalMejora(idGuia, sede, verificar) {
            $("#modalMejoras").modal("show");
            $("#TituloMejora").text("Agregar Mejoras Guia: " + idGuia + " - Sede: " + sede);
            $("#idMejora").val(idGuia);
            if (verificar == "false") {
                modal_temp(idGuia);
            }
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    verificarCamposDespresado: idGuia
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.status == "error") {
                        $("#proveedorH").prop("selectedIndex", 0);
                        $("#loteHielo").val("");
                        $("#fechaHielo").val("");
                        $("#proveedorE").prop("selectedIndex", 0);
                        $("#loteEmpaque").val("");
                        $("#fabricanteE").prop("selectedIndex", 0);
                        $("#proveedorS").prop("selectedIndex", 0);
                        $("#loteSalmuera").val("");
                        $("#concentracion").val("");
                        $("#fechaSalmuera").val("");
                        $("#proveedorHError").css("display", "none");
                        $("#loteHieloError").css("display", "none");
                        $("#fechaHieloError").css("display", "none");
                        $("#proveedorEError").css("display", "none");
                        $("#loteEmpaqueError").css("display", "none");
                        $("#fabricanteEError").css("display", "none");
                        $("#proveedorSError").css("display", "none");
                        $("#loteSalmueraError").css("display", "none");
                        $("#concentracionError").css("display", "none");
                        $("#fechaSalmueraError").css("display", "none");                    
                        $("#EditarGuia").css("display", "none");
                        $("#GuardarGuia").css("display", "initial");
                        $("div[name=CamposMejora]").css("display", "none")
                        $("#DivImprimirEtiquetas").css("display", "none");
                    } else {
                        $("#proveedorH").val(data.items[0].proveedor_hielo);
                        $("#loteHielo").val(data.items[0].lote_hielo);
                        $("#fechaHielo").val(data.items[0].fecha_venci_hielo);
                        $("#proveedorE").val(data.items[0].proveedor_empaque);
                        $("#loteEmpaque").val(data.items[0].lote_empaque);
                        $("#fabricanteE").val(data.items[0].fabricante_empaque);
                        $("#proveedorS").val(data.items[0].proveedor_salmuera);
                        $("#loteSalmuera").val(data.items[0].lote_salmuera);
                        $("#concentracion").val(data.items[0].concentracion);
                        $("#fechaSalmuera").val(data.items[0].fecha_venci_salmuera);
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        $("div[name=CamposMejora]").css("display", "block");
                        $("#DivImprimirEtiquetas").css("display", "flex");
                        iniciarTablaRegistrosImpresion(idGuia);
                    }
                }
            });
            iniciarTablaDatosGuia(idGuia);
            $("#itemMejora").prop("selectedIndex", 0);
            $("#kilosMejora").val("");
            $("#cajasMejora").val("");
            $("#responsableM").val("<?= $_SESSION["usuario"] ?>");
            $("#lote").val("");
            $("#lote").attr("disabled", "true");
            $("#cajasBaseMejora").val("");
            $("#itemMejoraError").css("display", "none");
            $("#kilosMejoraError").css("display", "none");
            $("#cajasMejoraError").css("display", "none");
            $("#responsableME").css("display", "none");
            $("#loteError").css("display", "none");
            $("#cajasBaseMejoraError").css("display", "none");
            $("#EditMejora").css("display", "none");
            $("#AgregarMejora").css("display", "initial");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    verifiSelectDespresado: idGuia
                },
                dataType: "json",
                success: function (data) {
                    var itemsDB = ["0"];
                    data.forEach(function(valor) {
                        itemsDB.push(valor[0]);
                    });
                    $("#itemMejora").empty();
                    $("#itemImp").empty();
                    $("#item option").each(function() {
                        var valorOpcion = $(this).val();
                        var texto = $(this).text();
                        $("#itemMejora").append("<option value="+valorOpcion+">"+texto+"</option>");
                        $("#itemImp").append("<option value="+valorOpcion+">"+texto+"</option>");
                    });
                    $("#itemMejora option").each(function() {
                        var valorOpcion = $(this).val();
                        if (!itemsDB.includes(valorOpcion)) {
                            $(this).remove();
                        }
                    });
                    $("#itemImp option").each(function() {
                        var valorOpcion = $(this).val();
                        if (!itemsDB.includes(valorOpcion)) {
                            $(this).remove();
                        }
                    });
                    /* $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            verifiPollo: idGuia
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data == "BLANCO") {
                                $("#itemMejora").append("<option value='059759'>POLLO BLANCO MERCAMIO MARINADO</option>");
                            }else if(data == "CAMPO"){
                                $("#itemMejora").append("<option value='059755'>POLLO CAMPO MERCAMIO MARINADO</option>");
                            }
                        }
                    }); */
                }
            });

            $("#TItemsMejora").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": false,
                "ajax": {
                    url: "tablas/TablaMejoraItemsDespresado.php",
                    type: "post",
                    data: {
                        id: idGuia
                    },
                    dataSrc: function(json) {
                        if (json.ojo) {
                            ojo = json.ojo;
                        }
                        return json.data;
                    }
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
            $('#TItemsMejora').css('width', '100%');
            $('#TItemsMejora_filter input, #TItemsMejora_length select').addClass('form-control');
            $('#TItemsMejora_filter').find('label').find('input').attr("name", "input2");
            setInterval(() => {
                modal_temp(idGuia)
            }, 1800000);
        }

        function validarCamposGuia() {
            $("#proveedorHError").css("display", "none");
            $("#loteHieloError").css("display", "none");
            $("#fechaHieloError").css("display", "none");
            $("#proveedorEError").css("display", "none");
            $("#loteEmpaqueError").css("display", "none");
            $("#fabricanteEError").css("display", "none");
            $("#proveedorSError").css("display", "none");
            $("#loteSalmueraError").css("display", "none");
            $("#concentracionError").css("display", "none");
            $("#fechaSalmueraError").css("display", "none");

            if ($("#proveedorH").val() == "0" || $("#proveedorH").val() == null) {
                $('#proveedorHError').css('display', 'block');
                return 'R';
            }           
            
            if ($("#loteHielo").val() == "" || $("#loteHielo").val() == null) {
                $('#loteHieloError').css('display', 'block');
                return 'R';
            }

            if ($("#fechaHielo").val() == "" || $("#fechaHielo").val() == null) {
                $('#fechaHieloError').css('display', 'block');
                return 'R';
            }  

            if ($("#proveedorE").val() == "0" || $("#proveedorE").val() == null) {
                $('#proveedorEError').css('display', 'block');
                return 'R';
            }

            if ($("#loteEmpaque").val() == "" || $("#loteEmpaque").val() == null) {
                $('#loteEmpaqueError').css('display', 'block');
                return 'R';
            }

            if ($("#fabricanteE").val() == "0" || $("#fabricanteE").val() == null) {
                $('#fabricanteEError').css('display', 'block');
                return 'R';
            }

            if ($("#proveedorS").val() == "0" || $("#proveedorS").val() == null) {
                $('#proveedorSError').css('display', 'block');
                return 'R';
            }  

            if ($("#loteSalmuera").val() == "" || $("#loteSalmuera").val() == null) {
                $('#loteSalmueraError').css('display', 'block');
                return 'R';
            }

            if ($("#concentracion").val() == "" || $("#concentracion").val() < 1 || $("#concentracion").val() > 100) {
                $('#concentracionError').css('display', 'block');
                return 'R';
            }

            if ($("#fechaSalmuera").val() == "" || $("#fechaSalmuera").val() == null) {
                $('#fechaSalmueraError').css('display', 'block');
                return 'R';
            }

            return "";
        }

        $("#GuardarGuia").click(function (e) { 
            e.preventDefault();
            if (validarCamposGuia() == "") {
                $("#GuardarGuia").prop('disabled', true);
                datos = {
                    proveedorH: $("#proveedorH").val(),
                    loteHielo: $("#loteHielo").val(),
                    fechaHielo: $("#fechaHielo").val(),
                    proveedorE: $("#proveedorE").val(),
                    loteEmpaque: $("#loteEmpaque").val(),
                    fabricanteEmpaque: $("#fabricanteE").val(),
                    proveedorS: $("#proveedorS").val(),
                    loteSalmuera: $("#loteSalmuera").val(),
                    fechaSalmuera: $("#fechaSalmuera").val(),
                    concentracion: $("#concentracion").val(),
                    idGuia: $("#idMejora").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        CamposGuiaDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        $("div[name=CamposMejora]").css("display", "block");
                        $("#DivImprimirEtiquetas").css("display", "flex");
                        Swal.fire({
                            title: 'Campos agregados a la guia correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#GuardarGuia").prop('disabled', false);
                    },
                    error: function() {
                        $("#GuardarGuia").prop('disabled', false);
                    }
                });
            }
        });

        $("#EditarGuia").click(function (e) { 
            e.preventDefault();
            if (validarCamposGuia() == "") {
                $("#EditarGuia").prop('disabled', true);
                datos = {
                    proveedorH: $("#proveedorH").val(),
                    loteHielo: $("#loteHielo").val(),
                    fechaHielo: $("#fechaHielo").val(),
                    proveedorE: $("#proveedorE").val(),
                    loteEmpaque: $("#loteEmpaque").val(),
                    fabricanteEmpaque: $("#fabricanteE").val(),
                    proveedorS: $("#proveedorS").val(),
                    loteSalmuera: $("#loteSalmuera").val(),
                    fechaSalmuera: $("#fechaSalmuera").val(),
                    concentracion: $("#concentracion").val(),
                    idGuia: $("#idMejora").val()
                }
                
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        CamposGuiaDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        Swal.fire({
                            title: 'Campos actualizados correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#EditarGuia").prop('disabled', false);
                    },
                    error: function() {
                        $("#EditarGuia").prop('disabled', false);
                    }
                });
            }
        });

        function validacionesMejora() {
            $("#itemMejoraError").css("display", "none");
            $("#kilosMejoraError").css("display", "none");
            $("#cajasMejoraError").css("display", "none");
            $("#loteError").css("display", "none");
            $("#cajasBaseMejoraError").css("display", "none");

            if ($("#itemMejora").val() == "0" || $("#itemMejora").val() == "") {
                $("#itemMejoraError").css("display", "block");
                return "L";
            }

            if ($("#responsableM").val() == "" || $("#responsableM").val() == null) {
                $("#responsableME").css("display", "block");
                return "L";
            }

            if ($("#lote").val() == "" || $("#lote").val() == null) {
                $("#loteError").css("display", "block");
                return "L";
            }
            
            if ($("#kilosMejora").val() == "" || $("#kilosMejora").val() == 0) {
                $("#kilosMejoraError").css("display", "block");
                return "L";
            }

            if ($("#cajasMejora").val() == "" || $("#cajasMejora").val() == 0) {
                $("#cajasMejoraError").css("display", "block");
                return "L";
            }

            if ($("#cajasBaseMejora").val() == "" || $("#cajasBaseMejora").val() == 0) {
                $("#cajasBaseMejoraError").css("display", "block");
                return "L";
            }

            return "";
        }

        $("#AgregarMejora").click(function (e) { 
            e.preventDefault();
            if (validacionesMejora() == "") {
                $("#AgregarMejora").prop('disabled', true);
                datos = {
                    idGuia: $("#idMejora").val(),
                    item: $("#itemMejora").val(),
                    kilos_mejora: $("#kilosMejora").val(),
                    cajas_mejora: $("#cajasMejora").val(),
                    responsable: $("#responsableM").val(),
                    lote: $("#lote").val(),
                    cajas_base: $("#cajasBaseMejora").val(),
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        AggMejoraDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#itemMejora").prop("selectedIndex", 0);
                        $("#kilosMejora").val("");
                        $("#cajasMejora").val("");
                        $("#lote").val("");
                        $("#cajasBaseMejora").val("");
                        $('#TItemsMejora').DataTable().ajax.reload();
                        $('#jtable').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Mejora agregada correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarItemsDespresado: $("#idMejora").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                setTimeout(() => {
                                    if (data["status"] == "success") {
                                        modal_temp($("#idMejora").val());
                                        /* Swal.fire({
                                            title: '¡Aviso!',
                                            text: 'Ingrese la Temperatura.',
                                            icon: 'info',
                                            confirmButtonText: 'Aceptar'
                                        }); */
                                    }
                                }, 1300);
                            }
                        });
                        $("#AgregarMejora").prop('disabled', false);
                    },
                    error: function() {
                        $("#AgregarMejora").prop('disabled', false);
                    }
                });
            }
        });

        $("#CerrarModalMejoras").click(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: '¿Desea cerrar la ventana de Mejoras?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#modalMejoras").modal("hide");
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            verificarItemsDespresado: $("#idMejora").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data["status"] == "success") {
                                modal_temp($("#idMejora").val());
                                /* Swal.fire({
                                    title: '¡Aviso!',
                                    text: 'Ingrese la Temperatura.',
                                    icon: 'info',
                                    confirmButtonText: 'Aceptar'
                                }); */
                            }
                        }
                    });

                    if (ojo == "R") {
                        ojo = "N";
                        window.open("controlador/imprimirDespresadoOjo.php?id=" + $("#idMejora").val(), "_blank");
                    }
                }
            });
        });

        function edit_mejora(idItem){
            if ("<?= $_SESSION["usuario"] ?>" == "ADMINISTRADOR") {
                $("#lote").removeAttr("disabled");
            } else {
                $("#lote").attr("disabled", "true");
            }
            
            $("#AgregarMejora").css("display", "none");
            $("#EditMejora").css("display", "initial");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    traerMejoraDespresado: idItem
                },
                dataType: "json",
                success: function (data) {
                    $("#itemMejora").val(data[0][0]);
                    $("#kilosMejora").val(data[0][1]);
                    $("#cajasMejora").val(data[0][2]);
                    $("#mejoraItem").val(data[0][3]);
                    $("#responsableM").val(data[0][4]);
                    $("#lote").val(data[0][5]);
                    $("#cajasBaseMejora").val(data[0][6]);
                }
            });
        }

        $("#EditMejora").click(function (e) { 
            e.preventDefault();
            if (validacionesMejora() == "") {
                $("#EditMejora").prop('disabled', true);
                datos = {
                    idItem: $("#mejoraItem").val(),
                    item: $("#itemMejora").val(),
                    kilos_mejora: $("#kilosMejora").val(),
                    cajas_mejora: $("#cajasMejora").val(),
                    responsable: $("#responsableM").val(),
                    lote: $("#lote").val(),
                    cajas_base: $("#cajasBaseMejora").val(),
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        editarMejoraDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#itemMejora").prop("selectedIndex", 0);
                        $("#kilosMejora").val("");
                        $("#cajasMejora").val("");
                        $("#responsableM").val("<?= $_SESSION["usuario"] ?>");
                        $("#lote").val("");
                        $("#cajasBaseMejora").val("");
                        $("#lote").attr("disabled", "true");
                        $("#EditMejora").css("display", "none");
                        $("#AgregarMejora").css("display", "initial");
                        $('#TItemsMejora').DataTable().ajax.reload();
                        $('#jtable').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Mejora Actualizado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#EditMejora").prop('disabled', false);
                    },
                    error: function() {
                        $("#EditMejora").prop('disabled', false);
                    }
                });
            }
        });

        function borrarMejora(idItem) {
            Swal.fire({
                title: '¿Esta seguro que desea eliminar esta Mejora?',
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
                            deleteMejoraDespresado: idItem
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#TItemsMejora').DataTable().ajax.reload();
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Item Eliminado correctamente',
                                text: '',
                                icon: 'success',
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function generarCodigo() {
            const item = $("#itemMejora option:selected").text();
            if (item) {
                const especie = 3
                const fecha =<?= date("Y") ?> ;
                const year = fecha.toString().slice(-2);
                let semana = <?= date("W") ?>;
                let dia = <?= date("w") ?>;
                let semanaM = "";
                let diaM = "";
                let siglas = "";

                datos = {
                    item: item,
                    idMejora: $("#idMejora").val()
                }
                
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        generarCodigoDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        let fecha = data[0][0].split("-");
                        console.log(fecha);
                        if (fecha[0] == "00" || fecha[0] == "0") {
                            fecha[0] = "7";
                        }
                        if (dia == 0) {
                            dia = "7";
                        }
                        if (semana != fecha[1]) {
                            semanaM = fecha[1];
                            diaM = fecha[0];
                        }else if(dia != fecha[0]){
                            diaM = fecha[0];
                        }else{
                            diaM = dia;
                        }
                        if (semana < 10) {
                            semana = "0" + semana;
                        }

                        siglas = data[0][1];
                        sede = data[0][2];

                        var codigo = especie + year + semana + dia + siglas + sede;


                        $('#lote').val(codigo);
                    }
                });
            } else {
                $('#lote').val('');
            }
        }

        $("#itemMejora").change(generarCodigo)

        function modal_temp(id) {
            $("#idTemp").val(id);
            $("#Temp1").val("");
            $("#Temp2").val("");
            $("#Temp3").val("");
            $("#Hora1").val("");
            $("#Hora2").val("");
            $("#Hora3").val("");
            $("#Temp1Error").css("display", "none");
            $("#Temp2Error").css("display", "none");
            $("#Temp3Error").css("display", "none");
            $("#Temp1").parent().css("display", "block");
            $("#Temp2").parent().css("display", "block");
            $("#Temp3").parent().css("display", "block");
            $("#Hora1").parent().css("display", "block");
            $("#Hora2").parent().css("display", "block");
            $("#Hora3").parent().css("display", "block");
            
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    idTempDespresado: id
                },
                dataType: "json",
                success: function (data) {
                    if (data[0][0] == "" && data[0][1] == "" && data[0][2] == "") {
                        $("#Temp2").parent().css("display", "none");
                        $("#Temp3").parent().css("display", "none");
                        $("#Hora2").parent().css("display", "none");
                        $("#Hora3").parent().css("display", "none");
                        $("#Hora1").val("");
                    }else {
                        $("#Hora1").val(data[0][3]);
                    } 
                    if(data[0][0] != "" && data[0][1] == "" && data[0][2] == ""){
                        $("#Temp3").parent().css("display", "none");
                        $("#Hora3").parent().css("display", "none");
                        $("#Hora2").val("");
                    }else {
                        $("#Hora2").val(data[0][4]);
                    }
                    if (data[0][0] != "" && data[0][1] != "" && data[0][2] == "") {
                        $("#Hora3").val("");
                    }
                    if (data[0][0] != "" && data[0][1] != "" && data[0][2] != "") {
                        $("#Hora1").val(data[0][3]);
                        $("#Hora2").val(data[0][4]);
                        $("#Hora3").val(data[0][5]);
                    }
                    $("#Temp1").val(data[0][0]);
                    $("#Temp2").val(data[0][1]);
                    $("#Temp3").val(data[0][2]);
                    $("#ModalTemp").modal("show");
                }
            });
        }

        function validacionesTemp() {
            $("#Temp1Error").css("display", "none");
            $("#Temp2Error").css("display", "none");
            $("#Temp3Error").css("display", "none");

            if ($("#Temp1").parent().css("display") == "block") {
                if ($("#Temp1").val() == "" || $("#Temp1").val() <= 0) {
                    $("#Temp1Error").css("display", "block");
                    return "l";
                }
            }

            if ($("#Temp2").parent().css("display") == "block") {
                if ($("#Temp2").val() == "" || $("#Temp2").val() <= 0) {
                    $("#Temp2Error").css("display", "block");
                    return "l";
                }
            }

            if ($("#Temp3").parent().css("display") == "block") {
                if ($("#Temp3").val() == "" || $("#Temp3").val() <= 0) {
                    $("#Temp3Error").css("display", "block");
                    return "l";
                }
            }
            
            return "";
        }

        $("#AgregarTemp").click(function (e) { 
            e.preventDefault();
            
            if (validacionesTemp() == "") {
                $("#AgregarTemp").prop('disabled', true);
                datos = {
                    id: $("#idTemp").val(),
                    temp1: $("#Temp1").val(),
                    temp2: $("#Temp2").val(),
                    temp3: $("#Temp3").val(),
                    hora1: $("#Hora1").val(),
                    hora2: $("#Hora2").val(),
                    hora3: $("#Hora3").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        AggTempDespresado: datos,
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#ModalTemp").modal("hide");
                        Swal.fire({
                            title: 'Temperaturas agregadas correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#AgregarTemp").prop('disabled', false);
                    },
                    error: function() {
                        $("#AgregarTemp").prop('disabled', false);
                    }
                });
            }
        });

        function bloquear_desprese(id) {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    bloquear_despresado: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#jtable').DataTable().ajax.reload();
                }
            });
        }

        function desbloquear_desprese(id) {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    desbloquear_despresado: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#jtable').DataTable().ajax.reload();
                }
            });
        }

        $("#ImprimirEtiquetas").click(function(e) {
            e.preventDefault();
            $("#ModalImprimirEtiquetas").modal("show");
            $("#loteImpError").hide();
            $("#itemImpError").hide();
            $("#cantidadImpError").hide();
            $("#loteImp").removeClass("is-valid is-invalid");
            $("#itemImp").removeClass("is-valid is-invalid");
            $("#cantidadImp").removeClass("is-valid is-invalid");
            $("#loteImp").val("");
            $("#itemImp").val("0");
            $("#cantidadImp").val("");
        });

        $("#itemImp").change(function (e) { 
            e.preventDefault();
            const item = $("#itemImp option:selected").text();
            if (item) {
                const especie = 3
                const fecha =<?= date("Y") ?> ;
                const year = fecha.toString().slice(-2);
                let semana = <?= date("W") ?>;
                let dia = <?= date("w") ?>;
                let semanaM = "";
                let diaM = "";
                let siglas = "";

                datos = {
                    item: item,
                    idMejora: $("#idMejora").val()
                }
                
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        generarCodigoDespresado: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        let fecha = data[0][0].split("-");
                        console.log(fecha);
                        if (fecha[0] == "00" || fecha[0] == "0") {
                            fecha[0] = "7";
                        }
                        if (dia == 0) {
                            dia = "7";
                        }
                        if (semana != fecha[1]) {
                            semanaM = fecha[1];
                            diaM = fecha[0];
                        }else if(dia != fecha[0]){
                            diaM = fecha[0];
                        }else{
                            diaM = dia;
                        }
                        if (semana < 10) {
                            semana = "0" + semana;
                        }

                        siglas = data[0][1];
                        sede = data[0][2];

                        var codigo = especie + year + semana + dia + siglas + sede;

                        
                        $('#loteImp').val(codigo);
                    }
                });
            } else {
                $('#loteImp').val('');
            }
        });

        $("#guardarEtiquetas").click(function(e) {
            e.preventDefault();
            $("#loteImpError").hide();
            $("#itemImpError").hide();
            $("#cantidadImpError").hide();
            $("#loteImp").removeClass("is-valid is-invalid");
            $("#itemImp").removeClass("is-valid is-invalid");
            $("#cantidadImp").removeClass("is-valid is-invalid");

            // Validar Item
            if ($("#itemImp").val() == "0") {
                $("#itemImpError").show();
                $("#itemImp").addClass("is-invalid");
                $("#itemImp").focus();
                return false;
            } else {
                $("#itemImp").addClass("is-valid");
            }

            // Validar Lote
            if ($("#loteImp").val() == "") {
                $("#loteImpError").show();
                $("#loteImp").addClass("is-invalid");
                $("#loteImp").focus();
                return false;
            } else {
                $("#loteImp").addClass("is-valid");
            }

            // Validar Cantidad
            let cantidad = $("#cantidadImp").val();
            if (
                cantidad == "" ||
                isNaN(cantidad) ||
                parseInt(cantidad) <= 0
            ) {
                $("#cantidadImpError").show();
                $("#cantidadImp").addClass("is-invalid");
                $("#cantidadImp").focus();
                $("#cantidadImpError").html('<i class="bi bi-exclamation-circle me-1"></i> Debe ingresar la cantidad');
                return false;
            } else {
                $("#cantidadImp").addClass("is-valid");
            }

            var datos = {
                lote: $("#loteImp").val(),
                item: $("#itemImp").val(),
                cantidad: $("#cantidadImp").val(),
                guia: $("#idMejora").val()
            }

            $("#guardarEtiquetas").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    guardarEtiquetasDespresado: datos
                },
                dataType: "json",
                success: function (response) {
                    if (response["status"] == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Etiquetas guardadas correctamente',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#loteImpError").hide();
                        $("#itemImpError").hide();
                        $("#cantidadImpError").hide();
                        $("#loteImp").removeClass("is-valid is-invalid");
                        $("#itemImp").removeClass("is-valid is-invalid");
                        $("#cantidadImp").removeClass("is-valid is-invalid");
                        $("#loteImp").val("");
                        $("#itemImp").val("");
                        $("#cantidadImp").val("");
                        $("#TRegistrosImpresion").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar las etiquetas',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                    $("#guardarEtiquetas").prop('disabled', false);
                },
                error: function (response) {
                    console.log(response);
                    $("#guardarEtiquetas").prop('disabled', false);
                }
            });
        });

        function eliminarEtiqueta(id) {
            Swal.fire({
                title: '¿Estás seguro de querer eliminar esta etiqueta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            eliminarEtiquetaDespresado: id
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data["status"] == "success") {
                                Swal.fire({
                                    title: 'Etiqueta eliminada correctamente',
                                    icon: 'success',
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                                $("#TRegistrosImpresion").DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error al eliminar la etiqueta',
                                    icon: 'error',
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            });
        }

        $("#imprimirEtiquetasPDF").click(function (e) { 
            e.preventDefault();
            const form = $('<form action="controlador/imprimirItemDespresado.php" method="post" target="_blank"></form>');
            form.append('<input type="hidden" name="guia" value=\'' + $("#idMejora").val() + '\'>');
            $('body').append(form);
            form.submit();
            form.remove();
            $("#TRegistrosImpresion").DataTable().ajax.reload();
        });

        function AbrirModalCF(guia, sede, fecha, polloDesprese) {
            $("#ModalCF").modal("show");
            $("#TituloCF").html("LIQUIDACION GUIA DE DESPRESADO Y MEJORAMIENTO #" + guia + " - SEDE: " + sede + " - FECHA GUIA: " + fecha + "<br> Desprese: " + parseFloat(polloDesprese).toFixed(2) + "kg");
            $("#idGuia").val(guia);
            $("#TablaHeaderLQ").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                columnDefs: [
                    {
                        targets: "_all",
                        width: "150px"
                    }
                ],
                ajax: {
                    type: "post",
                    url: "tablas/TablaHeaderLQ.php",
                    data: {
                        guia: guia
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
            $("#TablaConPE_LQ").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                columnDefs: [
                    {
                        targets: "_all",
                        width: "150px"
                    }
                ],
                ajax: {
                    type: "post",
                    url: "tablas/TablaConPE_LQ.php",
                    data: {
                        guia: guia
                    },
                    dataSrc: function (data) {
                        IniciarTablaTotalCF(data.tablaTotal);
                        return data.data;
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

            /* $("#TablaSinPE_CF").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                columns: [
                    { title: "Codigo Item", width: "150px" },
                    { title: "Descripción", width: "150px" },
                    { title: "Peso en Kilos Piezas Despresadas", width: "150px" },
                    { title: "Kilos Despacho", width: "150px" },
                    { title: "No. Piezas Despresadas", width: "150px" },
                    { title: "% part", width: "150px" },
                    { title: "COSTO X KL", width: "150px" },
                    { title: "Precio Venta Unitario", width: "150px" },
                    { title: "Total Precio Venta", width: "150px" },
                    { title: "Costo Total", width: "150px" },
                    { title: "Lote", width: "150px" }
                ],
                ajax: {
                    type: "post",
                    url: "tablas/TablaSinPE_CF.php",
                    data: {
                        guia: guia
                    }
                },
                initComplete: function () {
                    $("#TablaSinPE_CF thead").hide();
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
            }); */
            
            $("#TablaTotalLQ").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: "_all",
                        width: "150px"
                    }
                ],
                columns: [
                    { title: "Descripcion", width: "150px" },
                    { title: "Valor", width: "150px" },
                ],
                ajax: {
                    type: "post",
                    url: "tablas/TablaTotalLQ.php",
                    data: {
                        guia: guia
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
            $("#TablaTotalPorcentajeM_LQ").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: "_all",
                        width: "150px"
                    }
                ],
                columns: [
                    { title: "Descripcion", width: "150px" },
                    { title: "Porcentaje", width: "150px" },
                ],
                ajax: {
                    type: "post",
                    url: "tablas/TablaTotalPorcentajeM_LQ.php",
                    data: {
                        guia: guia
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
        }

        function IniciarTablaTotalCF(data) {
            $("#TablaTotal").DataTable({
                bDestroy: true,
                processing: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                autoWidth: false,
                data: data,
                columns: [
                    { title: "", width: "150px" },
                    { title: "GRAN TOTAL DESPRESADO", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" },
                    { title: "", width: "150px" }
                ],
                initComplete: function () {
                    $("#TablaTotal thead").hide();
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
        }

        function InsertDatosCF(guia, accion) {
            $("#ModalDatosCF").modal("show");
            $("#accionCF").val(accion);
            if (accion == "costoFactura") {
                $("label[for='campoDatoCF']").text("Costo Factura Pollo")
            } else if (accion == "costoPromedio") {
                $("label[for='campoDatoCF']").text("Costo Promedio Salmuera")
            } else {
                $("label[for='campoDatoCF']").text("% Salmuera")
            }
            $("#formDatosCF").trigger("reset");
            $("#formDatosCF .alert").hide();
        }

        function InsertVentaCF(item) {
            $("#ModalDatosCF").modal("show");
            $("#accionCF").val("venta");
            $("#itemCF").val(item);
            $("label[for='campoDatoCF']").text("Precio de Venta Unitario");
            $("#formDatosCF").trigger("reset");
            $("#formDatosCF .alert").hide();
        }

        $("#formDatosCF").submit(function (e) { 
            e.preventDefault();
            var idGuia = $("#idGuia").val();
            var accion = $("#accionCF").val();
            var campoDatoCF = $("#campoDatoCF").val();
            var item = $("#itemCF").val();

            if (campoDatoCF <= 0) {
                $("#campoDatoCF").addClass("is-invalid")
                $("#campoDatoCF").focus();
                $("#campoDatoCFError").show();
            }

            $("#campoDatoCF").removeClass("is-invalid")
            $("#campoDatoCFError").hide();

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    InsertarDatosCFDespresado : {
                        guia: idGuia,
                        accion: accion,
                        dato: campoDatoCF,
                        item: item
                    }
                },
                dataType: "json",
                success: function (response) {
                    if (response["status"] == "success") {
                        Swal.fire({
                            title: "Datos guardados correctamente",
                            icon: "success",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#ModalDatosCF").modal("hide");
                        $("#TablaHeaderLQ").DataTable().ajax.reload();
                        $("#TablaConPE_LQ").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Error al guardar los datos",
                            icon: "error",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                },
                error: function (data) {
                    Swal.fire({
                        title: "Error al guardar los datos",
                        icon: "error",
                        timer: 1300, 
                        showConfirmButton: false
                    });
                }
            });

        });

        $("#imprimirCF").click(function (e) { 
            e.preventDefault();
            var guia = $("#idGuia").val();
            window.open("controlador/imprimirLiquidacionDespresado.php?guia=" + guia, "_blank");
        });

        $("#crearPlanosBtn").click(function (e) { 
            e.preventDefault();
            $("#ModalCrearPlano").modal("show");
            $("#destinoPlano").val("");
            /* $("#FormCrearPlano").trigger("reset"); */
            $("#FormCrearPlano input").removeClass("is-invalid is-valid");
            $("#FormCrearPlano .alert").hide();
            $("#tipoPlano").val("");
            $("#tipoPlano").removeClass("is-invalid is-valid");
            if ($("#COPlano").val() == "") {
                $("#COPlano").prop("readonly", false);
            }
            if ($("#bodegaPlano").val() == "") {
                $("#bodegaPlano").prop("readonly", false);
            }
            $("#crearPlanoSalmuera").hide();
            $("#crearPlano").show();
            IniciarTablaGuiasPlano();
        });

        function IniciarTablaGuiasPlano() {
            $("#TPlanos").DataTable({
                bDestroy: true,
                processing: true,
                paging: false,
                info:false,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                pagingType: "simple",
                language: {
                    "searchPlaceholder": "Ingrese caracter",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "_MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": " _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "0 a 0 de 0 registros",
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
            $("#TPlanos").css('width', '100%');
            $("#TPlanos_filter input").addClass('form-control-sm');
            $("#TPlanos_length select").addClass('form-select-sm me-1 ms-0');
            $("#TPlanos_filter").find('label').find('input').attr("name", "input");
            $("#TPlanos_previous").css("padding", "0.3rem 0.7rem");
            $("#TPlanos_next").css("padding", "0.3rem 0.7rem");
        }

        $("#guardarGuiaPlano").click(async function (e) { 
            e.preventDefault();
            var guia = $("#guiaPlano").val();
            var coPlano = $("#COPlano").val();
            var bodega = $("#bodegaPlano").val();

            $("#FormCrearPlano .alert").hide();
            $("#FormCrearPlano input").removeClass("is-invalid is-valid");
            
            if (guia == "") {
                $("#guiaPlano").addClass("is-invalid")
                $("#guiaPlano").focus();
                $("#guiaPlanoError").html('<i class="bi bi-exclamation-circle me-1"></i> Debe ingresar la guia');
                $("#guiaPlanoError").show();
                return;
            }

            if (isNaN(guia)) {
                $("#guiaPlano").addClass("is-invalid")
                $("#guiaPlano").focus();
                $("#guiaPlanoError").html('<i class="bi bi-exclamation-circle me-1"></i> La guía debe ser un número');
                $("#guiaPlanoError").show();
                return;
            }

            var guiaExiste = false;
            $("#TPlanos").DataTable().rows().every(function() {
                var data = this.data();
                if (data[0] == guia) {
                    guiaExiste = true;
                    return false;
                }
            });

            if (guiaExiste) {
                $("#guiaPlano").addClass("is-invalid")
                $("#guiaPlano").focus();
                $("#guiaPlanoError").html('<i class="bi bi-exclamation-circle me-1"></i> La guía ya se encuentra agregada en la tabla');
                $("#guiaPlanoError").show();
                return;
            }

            if (coPlano == "") {
                $("#COPlano").addClass("is-invalid")
                $("#COPlano").focus();
                $("#COPlanoError").show();
                return;
            }

            if (bodega == "") {
                $("#bodegaPlano").addClass("is-invalid")
                $("#bodegaPlano").focus();
                $("#bodegaPlanoError").show();
                return;
            }

            var totalFilas = $("#TPlanos").DataTable().rows().count();

            var destinoStr = "";

            $("#guardarGuiaPlano").prop('disabled', true);
            if (totalFilas == 0) {
                try {
                    var validacion = await new Promise((resolve, reject) => {
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                ValidarGuiaPlanoDespresado: guia
                            },
                            dataType: "json",
                            success: function (response) {
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                reject({"status": "error", "message": "Error de conexión"});
                            }
                        });
                    });

                    if (validacion.status == "success") {
                        $("#destinoPlano").val(validacion.destino);
                        $("#tipoPolloPlano").val(validacion.tipo);
                        destinoStr = validacion.destinoStr;
                        $("#FormCrearPlano .alert").hide();
                        $("#FormCrearPlano input").removeClass("is-invalid is-valid");
                    } else {
                        $("#guiaPlano").val("");
                        $("#guiaPlano").addClass("is-invalid")
                        $("#guiaPlano").focus();
                        $("#guiaPlanoError").html('<i class="bi bi-exclamation-circle me-1"></i> ' + validacion.message);
                        $("#guiaPlanoError").show();
                        $("#guardarGuiaPlano").prop('disabled', false);
                        return;
                    }
                } catch (error) {
                    console.error('Error al procesar:', error);
                    Swal.fire({
                        title: "Error",
                        text: "Error al validar los datos",
                        icon: "error",
                        timer: 1300, 
                        showConfirmButton: false
                    });
                    $("#guardarGuiaPlano").prop('disabled', false);
                    return;
                }
            } else {
                try {
                    var validacion = await new Promise((resolve, reject) => {
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                ValidarGuiaPlanoDestinoDespresado: guia,
                                destino: $("#destinoPlano").val(),
                                tipoPollo: $("#tipoPolloPlano").val()
                            },
                            dataType: "json",
                            success: function (response) {
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                reject({"status": "error", "message": "Error de conexión"});
                            }
                        });
                    });

                    if (validacion.status == "success") {
                        destinoStr = validacion.destinoStr;
                        $("#FormCrearPlano .alert").hide();
                        $("#FormCrearPlano input").removeClass("is-invalid is-valid");
                    } else {
                        $("#guiaPlano").val("");
                        $("#guiaPlano").addClass("is-invalid")
                        $("#guiaPlano").focus();
                        $("#guiaPlanoError").html('<i class="bi bi-exclamation-circle me-1"></i> ' + validacion.message);
                        $("#guiaPlanoError").show();
                        $("#guardarGuiaPlano").prop('disabled', false);
                        return;
                    }
                } catch (error) {
                    console.error('Error al procesar:', error);
                    Swal.fire({
                        title: "Error",
                        text: "Error al validar los datos",
                        icon: "error",
                        timer: 1300, 
                        showConfirmButton: false
                    });
                    $("#guardarGuiaPlano").prop('disabled', false);
                    return;
                }
            }

            if (destinoStr == "") {
                Swal.fire({
                    title: "¡Atención!",
                    text: "Error al traer la sede",
                    icon: "warning",
                    timer: 1300, 
                    showConfirmButton: false
                });
                $("#guardarGuiaPlano").prop('disabled', false);
                return;
            }
            
            $("#TPlanos").DataTable().row.add([guia, destinoStr, coPlano, bodega, "<div class='d-flex justify-content-center'><i class='bi bi-trash fs-4 text-danger' onclick='EliminarPlano(this)' style='cursor: pointer;'></i></div>"]).draw();

            $("#FormCrearPlano .alert").hide();
            $("#FormCrearPlano input").removeClass("is-invalid is-valid");
            $("#guiaPlano").val("");
            $("#guiaPlano").focus();
            $("#COPlano").val(coPlano);
            $("#bodegaPlano").val(bodega);
            $("#COPlano").prop("readonly", true);
            $("#bodegaPlano").prop("readonly", true);
            $("#guardarGuiaPlano").prop('disabled', false);
        });

        function EliminarPlano(element) {
            var table = $("#TPlanos").DataTable();
            var row = $(element).closest('tr');
            table.row(row).remove().draw();
        }

        $("#crearPlano").click(function (e) {
            e.preventDefault();
            var totalFilas = $("#TPlanos").DataTable().rows().count();
            
            if (totalFilas == 0) {
                Swal.fire({
                    title: "¡Atención!",
                    text: "No hay planos para crear",
                    icon: "warning",
                    timer: 1300, 
                    showConfirmButton: false
                });
            }

            var tipoPlano = $("#tipoPlano").val();

            if (tipoPlano == "") {
                $("#tipoPlano").addClass("is-invalid")
                $("#tipoPlano").focus();
                return;
            } else {
                $("#tipoPlano").removeClass("is-invalid is-valid");
            }

            var planos = [];
            var table = $("#TPlanos").DataTable();
            
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                planos.push({
                    guia: data[0],
                    co: data[2],
                    bodega: data[3]
                });
            });
            
            /* $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    crearPlanos: planos
                },
                dataType: "json",
                success: function(response) {
                    if (response.success == "success") {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: "Planos creados correctamente",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $("#TPlanos").DataTable().clear().draw();
                        $("#ModalCrearPlano").modal('hide');
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: response.message || "Error al crear los planos",
                            icon: "error",
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Error de conexión",
                        icon: "error",
                        confirmButtonText: 'Aceptar'
                    });
                }
            }); */

            if (tipoPlano == "Desprese") {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'controlador/controlador.php';
                form.target = '_blank';
                
                var planosInput = document.createElement('input');
                planosInput.type = 'hidden';
                planosInput.name = 'crearPlanosDespresado';
                planosInput.value = JSON.stringify({planos: planos});
                form.appendChild(planosInput);
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            } else if (tipoPlano == "Mejoramiento") {
                // Crear un formulario temporal para enviar los datos
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'controlador/controlador.php';
                form.target = '_blank';
                
                // Agregar los datos de los planos
                var planosInput = document.createElement('input');
                planosInput.type = 'hidden';
                planosInput.name = 'crearPlanosMejoramientoDespresado';
                planosInput.value = JSON.stringify({planos: planos});
                form.appendChild(planosInput);
                
                // Agregar el formulario al DOM y enviarlo
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            } else {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'controlador/imprimirLiquidacionDespresado.php';
                form.target = '_blank';
                
                // Agregar los datos de los planos
                var planosInput = document.createElement('input');
                planosInput.type = 'hidden';
                planosInput.name = 'datosPlanoExcel';
                planosInput.value = JSON.stringify({planos: planos});
                form.appendChild(planosInput);
                
                // Agregar el formulario al DOM y enviarlo
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
            
            Swal.fire({
                title: "¡Éxito!",
                text: "El archivo plano se ha descargado",
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            });

            $("#FormCrearPlano").trigger("reset");
            $("#TPlanos").DataTable().clear().draw();
            $("#ModalCrearPlano").modal('hide');
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
    <script>
        function mostrarAlerta() {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    mostrarAlerta: true
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data["sede"] != "") {
                        Swal.fire({
                            title: '¡Aviso!',
                            text: 'Ingrese la Temperatura de la Sede ' + data["sede"],
                            icon: 'info',
                            confirmButtonText: 'Aceptar'
                        });
                    }else {
                        Swal.fire({
                            title: '¡Aviso!',
                            text: data["message"],
                            icon: 'info',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                    
                }
            });
        }
        setInterval(mostrarAlerta, 1800000); // 1800000
    </script>
</body>
</html>


