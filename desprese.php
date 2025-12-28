<?php
    session_start();
    if (!isset($_SESSION["usuario"])) {
        header("Location: cerrarsesion.php");
        exit();
    }
    //error_reporting(0);
    date_default_timezone_set("America/bogota");
    /* $_SESSION["fechaInicio"] = date("G:i"); */
    /* $_SESSION["ojo"] = "N"; */
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
    <title>Planta de Desposte | Desprese</title>
    <?php include_once('encabezado.php');?>
    <style>
        #jtable th:nth-child(1), #jtable td:nth-child(1) {
            width: 3%;
        }

        #jtable th:nth-child(2), #jtable td:nth-child(2) {
            width: 26%;
        }
        #jtable th, #jtable td {
            width: 22%;
        }
        
        #jtable th:nth-child(5), #jtable td:nth-child(5) {
            width: 26%;
        }

        #TItems th:nth-child(1), #TItems td:nth-child(1) {
            width: 4%;
        }
        #TItems th:nth-child(2), #TItems td:nth-child(2) {
            width: 6%;
        }
        #TItems th:nth-child(3), #TItems td:nth-child(3) {
            width: 30%;
        }
        #TItems th, #TItems td {
            width: 12%;
        }
        #TItems th:nth-child(8), #TItems td:nth-child(8) {
            width: 11%;
            padding: 0rem;
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
            padding: 0.4rem;
        }
        #TPesos td:nth-child(9){
            padding: 0.4rem;
        }

        table td, table td p {
            vertical-align: middle !important;
        }

        table td p {
            margin-top: 0;
            margin-bottom: 0;
        }

        #TDataDesprese td {
            padding: 5px !important;
        }

        #TDataDesprese th:nth-child(1), #TDataDesprese td:nth-child(1) {
            width: 14%;
        }

        #TDataDesprese th:nth-child(7), #TDataDesprese td:nth-child(7) {
            width: 8%;
        }

        .dataTables_wrapper .dataTables_filter label {
            width: auto !important;
            margin-bottom: 8px !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 5px;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            width: auto !important;
            max-width: none !important;
            margin-bottom: 0px;
        }

        table[name="TablasCF"] th, table[name="TablasCF"] td {
            min-width: 149px !important;
            max-width: 150px !important;
        }

        #TablaEncabezadoCF tbody tr td:nth-child(5), #TablaEncabezadoCF tbody tr td:nth-child(6), #TablaEncabezadoCF tbody tr td:nth-child(7) {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-warning-rgb),var(--bs-bg-opacity)) !important;
        }

        #TablaConPE_CF tbody tr:not(:last-child) td:nth-child(8) {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-warning-rgb),var(--bs-bg-opacity)) !important;
        }
    </style>
</head>
<body>
    <?php include_once('menu.php'); ?> 
    <?php include_once('menuizquierda.php');?> 

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Desprese</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-4 offset-md-4 d-flex justify-content-center">
                                    <a href="#" class="btn btn-warning" style="margin: 0px !important; font-weight: 600 !important;" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA</a>                                    
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
                                        <th>Pollos Enteros<br>Pesos</th>
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

    <div class="modal fade" id="modalNuevoProveedor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h6 id="horaInicialDesprese" class="float-start m-0"></h6>
                    <h6 style="margin-bottom: 0px;font-weight: bold;" id="titulo">AGREGAR DESPRESE</h6>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">

                        <input type="hidden" id="id">
                        <input type="hidden" id="statusEdit">
                        
                        <div class="col-md-4 mb-2">
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

                        <div class="col-md-4 mb-2">
                            <label for="fechaRegistro" class="form-label">Fecha de Registro</label>
                            <input type="date" id="fechaRegistro" class="form-control" disabled>
                            <div class="alert alert-danger" role="alert" id="fechaRegistroError" style="display: none" value="<?= date("Y-m-d") ?>">
                                Debe ingresar la fecha de registro
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
                            <i class="bi bi-plus-square fs-3 me-3 text-warning float-start" style="cursor: pointer; margin-top: 35px;" id="AgregarLote"></i>
                        </div>

                        <div class="col-md-6 mb-2" style="display: none;">
                            <label for="responsable" class="form-label">Responsable</label>
                            <select class="form-select" id="responsable" name="responsable" disabled>
                                <?php foreach ($listaResponsables as $perm) : ?>
                                    <option value="<?= $perm['cedula'] ?>">
                                        <?= $perm['cedula'] . " - " . $perm['nombres'] ?></option>
                                <?php endforeach ?>
                            </select>
							<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
								Debe seleccinar el responsable
							</div>
						</div>

                        <!-- <div class="col-md-12 mt-2" style="text-align: center;">
                            <button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
                        </div> -->

                        <h6 style="font-size: large;font-weight: bold;" name="DivProveedores">Proveedores</h6>

                        <div class="col-md-11 mb-2">
                            <div class="row">
                                <div class="col-md-4 mb-2" name="DivProveedores">
                                    <label for="tipoP" class="form-label">Tipo de Pollo</label>
                                    <select name="tipoP" id="tipoP" class="form-select" disabled>
                                        <option value="0">Seleccione un tipo</option>
                                        <option value="BLANCO">BLANCO</option>
                                        <option value="CAMPO">CAMPO</option>
                                        <option value="ASADERO">ASADERO</option>
                                    </select>
                                    <div class="alert alert-danger" role="alert" id="tipoPError" style="display: none">
                                        Debe seleccinar el tipo de pollo
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2" name="DivProveedores">
                                    <label for="proveedorP" class="form-label">Proveedor</label>
                                    <input type="text" name="proveedorP" id="proveedorP" class="form-control" disabled placeholder="Ingrese el proveedor">
                                    <input type="hidden" name="proveedor" id="proveedor">
                                    <div class="alert alert-danger" role="alert" id="proveedorPError" style="display: none">
                                        Debe ingresar un proveedor
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2" name="DivProveedores">
                                    <label for="fechaBeneficio" class="form-label">Fecha de Beneficio</label>
                                    <input type="date" name="fechaBeneficio" id="fechaBeneficio" class="form-control" disabled>
                                    <div class="alert alert-danger" role="alert" id="fechaBeneficioError" style="display: none">
                                        Debe ingresar la fecha de beneficio
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
                                    <label for="unidadesDisponiblesD" class="form-label">Unidades Disponibles</label>
                                    <input type="number" name="unidadesDisponiblesD" id="unidadesDisponiblesD" class="form-control" disabled>
                                    <div class="alert alert-danger" role="alert" id="unidadesDisponiblesDError" style="display: none">
                                        No hay unidades disponibles
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2" name="DivProveedores">
                                    <label for="kilosDisponiblesD" class="form-label">Kilos Disponibles</label>
                                    <input type="number" name="kilosDisponiblesD" id="kilosDisponiblesD" class="form-control" disabled>
                                    <div class="alert alert-danger" role="alert" id="kilosDisponiblesDError" style="display: none">
                                        No hay kilos disponibles
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1 mb-2" name="DivProveedores">
                            <div class="row" style="height: 100%;">
                                <div class="col-md-12 d-flex align-items-center">
                                    <i class="bi bi-plus-square fs-3 text-warning cursor-pointer" id="AgregarDataDesprese"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-1" id="DivDataDesprese" style="display: none;">
                            <table id="TDataDesprese" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Lote</th>
                                        <th>Proveedor</th>
                                        <th>Fecha Beneficio</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Unidades Disponibles</th>
                                        <th>Kilos Disponibles</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <h6 style="font-size: large;font-weight: bold;display: none;" name="DivPollo" class="mt-1">Pollos</h6>

                        <input type="hidden" name="idPesos" id="idPesos">
                        
                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="TipoPollo" class="form-label">Tipo de pollo</label>
                            <select name="TipoPollo" id="TipoPollo" class="form-select">
                                <option value="0">Seleccione un tipo de pollo</option>
                                <option value="DESPRESE">POLLO DESPRESE</option>
                                <option value="ENTERO">POLLO ENTERO</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="TipoPolloError" style="display: none">
                                Debe ingresar un tipo de pollo
                            </div>
                        </div>

                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="loteP" class="form-label">Lote</label>
                            <select name="loteP" id="loteP" class="form-select">
                                <option value="0">Seleccione un lote</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="lotePError" style="display: none">
                                Debe de ingresar el lote
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

                        <div class="col-md-2 mb-2" style="display: none" name="DivPollo">
                            <label for="nroPollos" class="form-label">N° Pollos</label>
                            <input type="number" name="nroPollos" id="nroPollos" class="form-control" placeholder="Ingrese la cantidad de pollos">
                            <div class="alert alert-danger" role="alert" id="nroPollosError" style="display: none">
                                Debe ingresar la cantidad de pollos
                            </div>
                        </div>

                        <div class="col-md-12 mb-2 mt-2" style="text-align: center;">
                            <button class="btn btn-primary" style="display: none;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="GuardarPollos">Guardar Pollo</button>
                            <button class="btn btn-primary" style="display: none;" rel="tooltip" data-placement="bottom" id="EditarPesos">Editar Pollo</button>
                        </div>

                        <div class="table-responsive mt-3" id="DivTabla" style="display: none;">
                            <table id="TPesos" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Lote</th>
                                        <th>N° Pollos</th>
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
					<div class="row mt-1">
						<div class="col-md-12 mb-1" style="text-align:center">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
							<button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="modalItems" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1051;background-color: #00000042 !important;">
        <div class="modal-dialog  modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="tituloI">Consecutivo Guia: 1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">

                        <input type="hidden" id="idGuia">
                        
                        <div class="col-md-3 mb-2">
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

                        <div class="col-md-3 mb-2">
                            <label for="kilos" class="form-label">Kilos</label>
                            <input type="number" name="kilos" id="kilos" class="form-control" autocomplete="off" placeholder="Ingrese los kilos">
                            <div class="alert alert-danger" role="alert" id="kilosError" style="display: none">
                                Debe ingresar los kilos
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="cajas" class="form-label">Cajas</label>
                            <input type="number" name="cajas" id="cajas" class="form-control" autocomplete="off" placeholder="Ingrese las cajas">
                            <div class="alert alert-danger" role="alert" id="cajasError" style="display: none">
                                Debe ingresar las cajas
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="canastillaBase" class="form-label">Cajas Base</label>
                            <input type="number" name="canastillaBase" id="canastillaBase" class="form-control" autocomplete="off" placeholder="Ingrese las canastillas base">
                            <div class="alert alert-danger" role="alert" id="canastillaBaseError" style="display: none">
                                Debe ingresar las cajas base
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-3 text-center">
                            <button type="button" class="btn btn-primary float-start" style="visibility: hidden;">Ejecutar Pesos Estandar</button>
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" id="AggItem">Guardar</button>
							<button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" id="EditItem">Editar</button>
							<button class="btn btn-danger" style="margin-right: 1rem !important;" id="CerrarModalDesprese">Cerrar</button>
                            <button type="button" class="btn btn-success" id="PesosEstandar">Ejecutar Pesos Estandar</button>
                            <button type="button" class="btn btn-danger" id="RevertPesosEstandar" style="display: none;">Revertir Pesos Estandar</button>
                        </div>

                    </div>					
                    <div class="table-responsive">
                        <table id="TItems" class="table table-striped table-bordered table-hover datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
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
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important">
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
							<button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" id="EditItemMejora">Editar</button>
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

                        <div class="col-md-12">
                            <table id="TInfoGuia" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Tipo Pollo</th>
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
                                <?php foreach ($proveedoresHielo as $proveedor): ?> 
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach; ?> 
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
                                <?php foreach ($proveedoresEmpaque as $proveedor): ?> 
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach; ?> 
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
                                <?php foreach ($fabricantesEmpaque as $fabricante): ?> 
                                    <option value="<?= $fabricante[0] ?>"><?= $fabricante[2] ?></option>
                                <?php endforeach; ?> 
                            </select>
                            <div class="alert alert-danger" role="alert" id="fabricanteEError" style="display: none">
                                Debe ingresar un fabricante
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="proveedorS" class="form-label">Proveedor Salmuera</label>
                            <select name="proveedorS" id="proveedorS" class="form-select">
                                <option value="0">Seleccione un proveedor</option>
                                <?php foreach ($proveedoresSalmuera as $proveedor): ?> 
                                    <option value="<?= $proveedor[0] ?>"><?= $proveedor[2] ?></option>
                                <?php endforeach; ?> 
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
                                <option value="">Seleccione una concentración</option>
                                <?php foreach ($salmueras as $salmuera): ?> 
                                    <option value="<?= $salmuera["id"] ?>"><?= $salmuera["concentracion"] ?></option>
                                <?php endforeach; ?> 
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
                                    <button class="btn btn-primary" style="display:none; margin: 0px !important;" rel="tooltip" data-placement="bottom" title="Editar Guia" id="EditarGuia">Editar</button>
                                    <button class="btn btn-primary" style="margin: 0px !important;" rel="tooltip" data-placement="bottom" title="Editar Guia" id="GuardarGuia">Guardar</button>
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
                                <?php foreach ($items as $item): ?> 
                                    <option value="<?= $item[0] ?>"><?= $item[1] ?></option>
                                <?php endforeach; ?> 
                            </select>
                            <div class="alert alert-danger" role="alert" id="itemMejoraError" style="display: none">
                                Debe ingresar un item
                            </div>
                        </div>

                        <div class="col-md-6 mb-2" style="display: none;">
                            <label for="responsableM" class="form-label">Responsable</label>
                            <select class="form-select" id="responsableM" name="responsableM" disabled>
                                <?php foreach ($listaResponsables as $perm): ?> 
                                    <option value="<?= $perm['cedula'] ?>">
                                        <?= $perm['cedula'] . " - " . $perm['nombres'] ?></option>
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

                        <div class="col-md-12 mt-3 mb-3" name="CamposMejora" style="display: none;">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-primary" style="margin: 0px !important;" rel="tooltip" data-placement="bottom" id="AgregarMejora">Guardar</button>
                                <button class="btn btn-primary" style="margin: 0px !important; display:none" rel="tooltip" data-placement="bottom" id="EditMejora">Editar</button>
                                <button class="btn btn-danger" style="margin: 0px !important;" id="CerrarModalMejoras">Cerrar</button>
                            </div>
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
                        <div class="col-md-12">
                            <table id="TablaEncabezadoCF" class="table table-striped table-bordered table-hover datatable" name="TablasCF">
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
                            <table id="TablaConPE_CF" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
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
                            <table id="TablaTotalCF" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th colspan="2">Total</th>
                                    </tr>
                                </thead>
                            </table>
                            <table id="TablaTotalPorcentajeM" class="table table-striped table-bordered table-hover datatable" name="TablasCF" style="width: auto !important;">
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

    <?php require_once('scripts.php'); ?> 

    <script>
        $(document).ready(function() {
            $('#jtable').DataTable({
                "responsive": true,
                "processing": true,
                "bDestroy": true,
                "pageLength": 10,
                "deferRender": true,
                "order": [
                    [0, 'DESC']
                ],
                "ajax": {
                    url: "tablas/TablaDesprese.php",
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

        function vaciarCamposGuia(){
            $("#sede").prop("selectedIndex", 0);
            $("#fechaRegistro").val("<?= date("Y-m-d") ?>");
            $("#responsable").val("<?= $_SESSION["usuario"] ?>");
            $("#lotePollo").val("");
            $("#tipoP").val("0");
            $("#proveedorP").val("");
            $("#proveedor").val("");
            $("#fechaBeneficio").val("");
            $("#fechaPollo").val("");
            $("#unidadesDisponiblesD").val("");
            $("#kilosDisponiblesD").val("");
            
            $("#sedeError").css("display", "none");
            $("#fechaRegistroError").css("display", "none");
            $("#responsableE").css("display", "none");
            $("#lotePolloError").css("display", "none");
            $("#tipoPError").css("display", "none");
            $("#proveedorPError").css("display", "none");
            $("#fechaBeneficioError").css("display", "none");
            $("#fechaPolloError").css("display", "none");
            $("#unidadesDisponiblesDError").hide();
            $("#kilosDisponiblesDError").hide();
        }

        $("#crearProveedor").click(function() {   
            $('#horaInicialDesprese').text('');
            $('#titulo').text('AGREGAR DESPRESE').removeClass("me-5");
            $("#statusEdit").val("");
            habilitarModuloPollo = true;
            validarGuia = true;
            $("#sede").removeAttr("disabled");
            $("#fechaRegistro").attr("disabled", "true");
            $("#responsable").removeAttr("disabled");
            vaciarCamposGuia();
            $("[name='DivProveedores']").hide();
            $("#DivDataDesprese").hide();
            $("[name='DivPollo']").css("display", "none");
            $("#DivTabla").css("display", "none");
            $('#btnNuevoProveedor').css('display', 'initial');
            /* $('#btnEditProveedor').css('display', 'none'); */
            $("#GuardarPollos").css("display", "none");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    fechaInicio: true
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                }
            });
        });

        async function validaciones() {
            $("#sedeError").css("display", "none");
            $("#tipoPError").css("display", "none");
            $("#fechaRegistroError").css("display", "none");
            $("#proveedorPError").css("display", "none");
            $("#lotePolloError").css("display", "none");
            $("#fechaBeneficioError").css("display", "none");
            $("#fechaPolloError").css("display", "none");
            $("#proveedorPEntError").css("display", "none");
            $("#lotePolloEntError").css("display", "none");
            $("#fechaBeneficioEntError").css("display", "none");
            $("#fechaPolloEntError").css("display", "none");
            $("#responsableE").css("display", "none");

            let errores = false;

            if ($("#sede").val() == "0" || $("#sede").val() == null) {
                $("#sedeError").css("display", "block");
                errores = true;
            }
            /* if ($("#tipoP").val() == "" || $("#tipoP").val() == "0") {
                $("#tipoPError").css("display", "block");
                errores = true;
            } */
            if ($("#fechaRegistro").val() == "" || $("#fechaRegistro").val() == null) {
                $("#fechaRegistroError").css("display", "block");
                errores = true;
            }
            /* let grupo1 = {
                proveedorP: $("#proveedorP").val(),
                lotePollo: $("#lotePollo").val(),
                fechaBeneficio: $("#fechaBeneficio").val(),
                fechaPollo: $("#fechaPollo").val()
            };

            let grupo2 = {
                proveedorPEnt: $("#proveedorPEnt").val(),
                lotePolloEnt: $("#lotePolloEnt").val(),
                fechaBeneficioEnt: $("#fechaBeneficioEnt").val(),
                fechaPolloEnt: $("#fechaPolloEnt").val()
            };

            let grupo1Lleno = Object.values(grupo1).some(val => val !== "" && val !== "0" && val !== null);
            let grupo1Incompleto = Object.values(grupo1).some(val => val === "" || val === "0" || val === null);

            let grupo2Lleno = Object.values(grupo2).some(val => val !== "" && val !== "0" && val !== null);
            let grupo2Incompleto = Object.values(grupo2).some(val => val === "" || val === "0" || val === null);

            if (grupo1Lleno) {
                if (grupo1Lleno && grupo1Incompleto) {
                    Object.keys(grupo1).forEach(campo => {
                        if (grupo1[campo] === "" || grupo1[campo] === "0" || grupo1[campo] === null) {
                            $(`#${campo}Error`).css("display", "block");
                        }
                    });
                    errores = true;
                }
                if ($("#lotePollo").val() != "") {
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
                        $("#lotePolloError").text("Debe ingresar un lote");
                        $("#lotePolloError").css("display", "none");
                    }
                }
            }else {
                if (!grupo2Lleno) {
                    Object.keys(grupo1).forEach(campo => {
                        if (grupo1[campo] === "" || grupo1[campo] === "0" || grupo1[campo] === null) {
                            $(`#${campo}Error`).css("display", "block");
                        }
                    });
                    errores = true;    
                }
            }

            if (grupo2Lleno && grupo2Incompleto) {
                Object.keys(grupo2).forEach(campo => {
                    if (grupo2[campo] === "" || grupo2[campo] === "0" || grupo2[campo] === null) {
                        $(`#${campo}Error`).css("display", "block");
                    }
                });
                errores = true;
            }

            if ($("#lotePolloEnt").val() != "") {
                const resultado = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            validarLotePlanta: $("#lotePolloEnt").val()
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
                    $("#lotePolloEntError").text("Lote no existe");
                    $("#lotePolloEntError").css("display", "block");
                    return "R";
                } else {
                    $("#lotePolloEntError").text("Debe ingresar un lote");
                    $("#lotePolloEntError").css("display", "none");
                }
            } */

            return errores ? 'R' : '';
        }

        $('#btnNuevoProveedor').click(async function() {
            var validacion = await validaciones();
            console.log('validacion: ', validacion);
            if (validacion == "") {
                $('#btnNuevoProveedor').prop('disabled', true);
                datos = {
                    sede: $("#sede").val(),
                    /* tipoP: $("#tipoP").val(), */
                    fechaRegistro: $("#fechaRegistro").val(),
                    /* proveedorP: $("#proveedorP").val(),
                    lotePollo: $("#lotePollo").val(),
                    fechaBeneficio: $("#fechaBeneficio").val(),
                    fechaPollo: $("#fechaPollo").val(),
                    proveedorPEnt: $("#proveedorPEnt").val(),
                    lotePolloEnt: $("#lotePolloEnt").val(),
                    fechaBeneficioEnt: $("#fechaBeneficioEnt").val(),
                    fechaPolloEnt: $("#fechaPolloEnt").val(), */
                    responsable: $("#responsable").val(),
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        guiaDesprese: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#jtable").DataTable().ajax.reload();
                        $("#id").val(data.id);
                        $("#sede").attr("disabled", "true");
                        $("#fechaRegistro").attr("disabled", "true");
                        $("#responsable").attr("disabled", "true");
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

        function guardarGuia() {
            return new Promise(async (resolve, reject) => {
                var validacion = await validaciones();
                if (validacion == "") {
                    datos = {
                        sede: $("#sede").val(),
                        fechaRegistro: $("#fechaRegistro").val(),
                        responsable: $("#responsable").val(),
                    }
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            guiaDesprese: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            $("#jtable").DataTable().ajax.reload();
                            $("#id").val(data.id);
                            $("#sede").attr("disabled", "true");
                            $("#fechaRegistro").attr("disabled", "true");
                            $("#responsable").attr("disabled", "true");
                            $('#btnNuevoProveedor').css('display', 'none');
                            iniciarTablaData();
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

        function iniciarTablaData(){
            $("#TDataDesprese").DataTable({
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
                    url: "tablas/TablaDataDesprese.php",
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
            $('#TDataDesprese').css('width', '100%');
            $('#TDataDesprese_filter input, #TDataDesprese_length select').addClass('form-control');
            $('#TDataDesprese_filter').find('label').find('input').attr("name", "input")
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
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        validarLotePlantaDatos: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log('data: ', data);
                        if (data.hasOwnProperty('status') && data.status == "error") {
                            $("#lotePolloError").text("Lote invalido");
                            $("#lotePolloError").css("display", "block");
                            $("#fechaBeneficio").val("");
                            $("#fechaPollo").val("");
                            $("#lotePollo").val("");
                        } else if (data.hasOwnProperty('status') && data.status == "errorTipo") {
                            $("#lotePolloError").text(data.message);
                            $("#lotePolloError").css("display", "block");
                            $("#fechaBeneficio").val("");
                            $("#fechaPollo").val("");
                            $("#lotePollo").val("");
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
                                    console.log('campo');
                                    var datos = data.data;
                                    $("div[name='DivProveedores'], h6[name='DivProveedores']").show();
                                    vaciarCamposData();
                                    $("#lotePolloError").css("display", "none");
                                    $("#lotePolloError").text("Debe ingresar un lote");
                                    $("#tipoP").val("CAMPO");
                                    $("#fechaBeneficio").val(datos.CAMPO.fecha_beneficio);
                                    $("#fechaPollo").val(datos.CAMPO.fechasac);
                                    $("#kilosDisponiblesD").val(datos.CAMPO.peso_real);
                                    $("#unidadesDisponiblesD").val(datos.CAMPO.unidades);
                                    $("#proveedorP").val(datos.CAMPO.proveedorText);
                                    $("#proveedor").val(datos.CAMPO.proveedor);
                                } else if (result.isDenied) {
                                    console.log('blanco');
                                    var datos = data.data;
                                    $("div[name='DivProveedores'], h6[name='DivProveedores']").show();
                                    vaciarCamposData();
                                    $("#lotePolloError").css("display", "none");
                                    $("#lotePolloError").text("Debe ingresar un lote");
                                    $("#tipoP").val("BLANCO");
                                    $("#fechaBeneficio").val(datos.BLANCO.fecha_beneficio);
                                    $("#fechaPollo").val(datos.BLANCO.fechasac);
                                    $("#kilosDisponiblesD").val(datos.BLANCO.peso_real);
                                    $("#unidadesDisponiblesD").val(datos.BLANCO.unidades);
                                    $("#proveedorP").val(datos.BLANCO.proveedorText);
                                    $("#proveedor").val(datos.BLANCO.proveedor);
                                }
                            });
                        } else {
                            var data = data.data;
                            $("div[name='DivProveedores'], h6[name='DivProveedores']").show();
                            vaciarCamposData();
                            $("#lotePolloError").css("display", "none");
                            $("#lotePolloError").text("Debe ingresar un lote");
                            if (data.tipo != "BLANCO_DESPRESE" && data.tipo != "CAMPO_DESPRESE") {
                                $("#tipoP").val(data.tipo);
                                $("#fechaBeneficio").val(data.fecha_beneficio);
                                $("#fechaPollo").val(data.fechasac);
                                $("#kilosDisponiblesD").val(data.peso_real);
                                $("#unidadesDisponiblesD").val(data.unidades);
                                $("#proveedorP").val(data.proveedorText);
                                $("#proveedor").val(data.proveedor);
                            } else if (data.tipo == "CAMPO_DESPRESE" || data.tipo == "BLANCO_DESPRESE") {
                                Swal.fire({
                                    title: "¡Atención!",
                                    text: "El tipo de pollo es invalido",
                                    icon: "warning",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        }
                    }
                });
            } else {
                $("#lotePolloError").show();
                return false;
            }
        });

        function vaciarCamposData() {
            $("#tipoPError").hide();
            $("#lotePolloError").hide();
            $("#proveedorPError").hide();
            $("#fechaBeneficioError").hide();
            $("#fechaPolloError").hide();
            $("#kilosDisponiblesDError").hide();
            $("#unidadesDisponiblesDError").hide();

            $("#tipoP").val("0");
            $("#proveedorP").val("");
            $("#proveedorP").val("");
            $("#fechaBeneficio").val("");
            $("#fechaPollo").val("");
            $("#kilosDisponiblesD").val("");
            $("#unidadesDisponiblesD").val("");
        }

        async function validacionDatosDesprese() {
            $("#tipoPError").hide();
            $("#lotePolloError").hide();
            $("#proveedorPError").hide();
            $("#fechaBeneficioError").hide();
            $("#fechaPolloError").hide();
            $("#kilosDisponiblesDError").hide();
            $("#unidadesDisponiblesDError").hide();

            if ($("#tipoP").val() == "0") {
                $("#tipoPError").show();
                return false;
            } else {
                datos = {
                    tipo: $("#tipoP").val(),
                    idGuia: $("#id").val()
                }
                var validacion = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            validarTipoPollo: datos
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
                                    title: "Error",
                                    text: data.message,
                                    icon: "error",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (err) {
                            Swal.fire({
                                title: "Error",
                                text: 'Error al validar el tipo de pollo',
                                icon: "error",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                });

                if (validacion.status == "errorTipo" || validacion.status == "error") {
                    return false;
                }
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

            if ($("#unidadesDisponiblesD").val() == "" || $("#unidadesDisponiblesD").val() == null) {
                $("#unidadesDisponiblesDError").show();
                return false;
            }

            if ($("#kilosDisponiblesD").val() == "" || $("#kilosDisponiblesD").val() == null) {
                $("#kilosDisponiblesDError").show();
                return false;
            }

            return true;
        }

        var habilitarModuloPollo = true;

        $("#AgregarDataDesprese").click(async function (e) { 
            e.preventDefault();
            if (await validacionDatosDesprese()) {
                datos = {
                    lote: $("#lotePollo").val(),
                    tipoP: $("#tipoP").val(),
                    proveedor: $("#proveedor").val(),
                    fechaBeneficio: $("#fechaBeneficio").val(),
                    fechaVencimiento: $("#fechaPollo").val(),
                    kilosDisponibles: $("#kilosDisponiblesD").val(),
                    unidadesDisponibles: $("#unidadesDisponiblesD").val(),
                    guia: $("#id").val()
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        agregarDataDesprese: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.hasOwnProperty('status') && data.status == "errorLote") {
                            Swal.fire({
                                title: data.message,
                                text: "",
                                icon: "error",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                            $("#lotePollo").val("");
                            vaciarCamposData();
                        } else if (data.status == "success") {
                            $("#DivDataDesprese").show();
                            $("#TDataDesprese").DataTable().ajax.reload();
                            Swal.fire({
                                title: "Datos agregados correctamente",
                                text: '',
                                icon: "success",
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
                                        trerLotes: $("#id").val()
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
                                            title: "Error",
                                            text: 'Error al traer los lotes',
                                            icon: "error",
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
                                title: "Error",
                                text: data.message,
                                icon: "error",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (err) {
                        Swal.fire({
                            title: "Error",
                            text: 'Error al agregar los datos',
                            icon: "error",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                });
            }
        });

        function EliminarRegistro(id, lote, guia) {
            Swal.fire({
                title: "¿Estás seguro?",
                html: '¡No podrás revertir esto!<br>¡Se eliminarán los pesos del pollo!',
                icon: "warning",
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
                            eliminarRegistro: datos
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == "success") {
                                $("#TDataDesprese").DataTable().ajax.reload();
                                $("#TPesos").DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: data.message,
                                    icon: "error",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (err) {
                            Swal.fire({
                                title: "Error",
                                text: 'Error al eliminar el registro',
                                icon: "error",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function vaciarCamposPollo(){
            $("#TipoPolloError").css("display", "none");
            $("#lotePError").css("display", "none");
            $("#kilosPError").css("display", "none");
            $("#cajasPError").css("display", "none");
            $("#cajasBasePError").css("display", "none");
            $("#nroPollosError").css("display", "none");

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
                    trerLotes: $("#id").val()
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
                        title: "Error",
                        text: 'Error al traer los lotes',
                        icon: "error",
                        timer: 1300, 
                        showConfirmButton: false
                    });
                }
            });
            $("#EditarPesos").hide();
            $("#GuardarPollos").css("display", "initial");
            $("#DivTabla").css("display", "block");
            $("#TPesos").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": true,
                "order": [[ 0, "desc" ]],
                "ordering": totalCambios > 0 && <?= $_SESSION["tipo"] ?> == 0 && <?= $_SESSION["registrosCambios"] ?> == 1 ? false : true,
                "ajax": {
                    url: "tablas/TablaPesosPollo.php",
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
            $('#TPesos').css('width', '100%');
            $('#TPesos_filter input, #TPesos_length select').addClass('form-control');
            $('#TPesos_filter').find('label').find('input').attr("name", "input3");
        }

        function validacionesPollo() {
            $("#TipoPolloError").css("display", "none");
            $("#lotePError").css("display", "none");
            $("#kilosPError").css("display", "none");
            $("#cajasPError").css("display", "none");
            $("#cajasBasePError").css("display", "none");
            $("#nroPollosError").css("display", "none");

            if ($("#TipoPollo").val() == "" || $("#TipoPollo").val() == "0") {
                $("#TipoPolloError").css("display", "block");
                return "L";
            }

            if ($("#loteP").val() == "" || $("#loteP").val() == "0") {
                $("#lotePError").css("display", "block");
                return "L";
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

            if ($("#nroPollos").val() == "" || $("#nroPollos").val() == 0 || $("#nroPollos").val() < 0) {
                $("#nroPollosError").css("display", "block");
                return "L";
            }

            return "";
        }

        $("#GuardarPollos").click(function (e) { 
            e.preventDefault();
            if (validacionesPollo() == "") {
                $("#GuardarPollos").prop('disabled', true);
                datos = {
                    guia: $("#id").val(),
                    TipoPollo: $("#TipoPollo").val(),
                    loteP: $("#loteP").val(),
                    kilosP: $("#kilosP").val(),
                    cajasP: $("#cajasP").val(),
                    cajasBaseP: $("#cajasBaseP").val(),
                    nroPollos: $("#nroPollos").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        GuardarPollo: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.hasOwnProperty('status') && data.status == "errorTipo") {
                            Swal.fire({
                                title: "¡Atención!",
                                text: data.message,
                                icon: "warning",
                            });
                            $("#GuardarPollos").prop('disabled', false);
                        } else if (data.status == "success") {
                            $("#loteP").val("0");
                            $("#kilosP").val("");
                            $("#cajasP").val("");
                            $("#cajasBaseP").val("");
                            $("#nroPollos").val("");
                            $('#TPesos').DataTable().ajax.reload();
                            $('#jtable').DataTable().ajax.reload();
                            $('#TDataDesprese').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Pesos Registrados Correctamente",
                                text: '',
                                icon: "success",
                                timer: 600, 
                                showConfirmButton: false
                            });
                            $("#GuardarPollos").prop('disabled', false);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: data.message,
                                icon: "error",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                            $("#GuardarPollos").prop('disabled', false);
                        }
                    },
                    error: function (err) {
                        Swal.fire({
                            title: "Error",
                            text: 'Error al registrar los pesos',
                            icon: "error",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#GuardarPollos").prop('disabled', false);
                    }
                });
            }
        });

        function editPesosPollo(id) {
            $("#idPesos").val(id);
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    traerPesos: id
                },
                dataType: "json",
                success: function (data) {
                    $("#TipoPollo").val(data[0][0]);
                    $("#kilosP").val(data[0][1]);
                    $("#cajasP").val(data[0][2]);
                    $("#cajasBaseP").val(data[0][3]);
                    $("#nroPollos").val(data[0][4]);
                    $("#loteP").val(data[0][5]);
                    $("#GuardarPollos").css("display", "none");
                    $("#EditarPesos").css("display", "initial");
                }
            });
        }

        $("#EditarPesos").click(function (e) { 
            e.preventDefault();
            if (validacionesPollo() == "") {
                $("#EditarPesos").prop('disabled', true);
                datos = {
                    id: $("#idPesos").val(),
                    tipoPollo: $("#TipoPollo").val(),
                    loteP: $("#loteP").val(),
                    kilosP: $("#kilosP").val(),
                    cajasP: $("#cajasP").val(),
                    cajasBaseP: $("#cajasBaseP").val(),
                    nroPollos: $("#nroPollos").val(),
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        EditPesos: datos
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.status == "success") {
                            $("#GuardarPollos").css("display", "initial");
                            $("#EditarPesos").css("display", "none");
                            $('#TPesos').DataTable().ajax.reload();
                            $('#TDataDesprese').DataTable().ajax.reload();
                            $("#loteP").val("0");
                            $("#kilosP").val("");
                            $("#cajasP").val("");
                            $("#cajasBaseP").val("");
                            $("#nroPollos").val("");
                        }
                        
                        Swal.fire({
                            icon: res.status,
                            title: res.message,
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $("#EditarPesos").prop('disabled', false);
                    },
                    error: function(res) {
						Swal.fire({
							icon: "error",
							title: "Error al modificar el peso",
							timer: 1500,
							showConfirmButton: false
						});
						$("#EditarPesos").prop('disabled', false);
					}
                });
            }
        });

        function borrarPeso(id) {
            Swal.fire({
                title: "¿Esta seguro que desea eliminar este Item?",
                icon: "warning",
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
                            deletePeso: id
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#TPesos').DataTable().ajax.reload();
                            $('#TDataDesprese').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Pesos Eliminados correctamente",
                                text: '',
                                icon: "success",
                                timer: 1000, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function buscar_guia(id, totalCambios) {
            $('#id').val(id);
            $('#titulo').text('EDITAR DESPRESE #' + id).addClass("me-5");
            $("#statusEdit").val("1");
            habilitarModuloPollo = false;
            validarGuia = false;
            $("#sede").removeAttr("disabled");
            vaciarCamposGuia();
            /* if ("<?= $_SESSION["usuario"] ?>" == "ADMINISTRADOR") {
                $("#fechaBeneficio").removeAttr("disabled");
                $("#fechaBeneficioEnt").removeAttr("disabled");
                $("#fechaPollo").removeAttr("disabled");
                $("#fechaPolloEnt").removeAttr("disabled");
            } */
            $("div[name='DivProveedores'], h6[name='DivProveedores']").show();
            $("#DivDataDesprese").show();
            iniciarTablaData();
            moduloPollo(totalCambios);
            $('#btnNuevoProveedor').css('display', 'none');
            /* $('#btnEditProveedor').css('display', 'initial'); */
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    idDesprese: $("#id").val()
                },
                success: function(data) {
                    $('#horaInicialDesprese').text('HI: ' + data.hora_inicio);
                    $("#sede").val(data.destino);
                    $("#fechaRegistro").val(data.fecha_registro);
                    $("#responsable").val(data.responsable);
                }
            });
        }

        $('#sede').change(async function() {
            var validacion = await validaciones();
            if ($("#statusEdit").val() == "1") {
                if (validacion == "") {
                    datos = {
                        sede: $("#sede").val(),
                        responsable: $("#responsable").val(),
                        idDesprese: $("#id").val(),
                    }
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: 'controlador/controlador.php',
                        data: {
                            editGuia: datos
                        },
                        success: function(data) {
                            if (data.status == "success") {
                                $('#jtable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: "Guia modificada correctamente",
                                    text: '',
                                    icon: "success",
                                    timer: 1300, 
                                    showConfirmButton: false
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
                        error: function (data) {
                            Swal.fire({
                                title: "Error",
                                text: "Error al editar la guia",
                                icon: "error",
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            }
        });

        function abrirModal(id, sede, totalCambios) {
            $("#tituloI").text("Formato de Desprese # " + id + " - Sede: " + sede);
            $("#idGuia").val(id);
            $("#item").prop("selectedIndex", 0);
            $("#kilos").val("");
            $("#cajas").val("");
            $("#canastillaBase").val("");
            $("#itemError").css("display", "none");
            $("#kilosError").css("display", "none");
            $("#cajasError").css("display", "none");
            $("#canastillaBaseError").css("display", "none");
            $("#EditItem").css("display", "none");
            $("#AggItem").css("display", "initial");

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    verificarDesprese: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data["status"] == "success") {
                        $("#item").attr("disabled", true);
                        $("#kilos").attr("disabled", true);
                        $("#cajas").attr("disabled", true);
                        $("#canastillaBase").attr("disabled", true);
                    } else {
                        $("#item").removeAttr("disabled");
                        $("#kilos").removeAttr("disabled");
                        $("#cajas").removeAttr("disabled");
                        $("#canastillaBase").removeAttr("disabled");
                    }
                }
            });

            $("#TItems").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": false,
                "order": [[ 0, "desc" ]],
                "ordering": false,
                "ajax": {
                    url: "tablas/TablaDespreseItems.php",
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
            });

            $('#TItems').css('width', '100%');
            $('#TItems_filter input, #TItems_length select').addClass('form-control');
            $('#TItems_filter').find('label').find('input').attr("name", "input2");

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    idItemsD: id
                },
                dataType: "json",
                success: function (data) {
                    data.push("0");
                    var items = <?= json_encode($items); ?>;
                    items.unshift(["0", "Seleccione un item"])
                    $("#item").empty();
                    items.forEach(item => {
                        $("#item").append("<option value="+item[0]+">"+item[1]+"</option>");
                    });
                    $("#item option").each(function() {
                        var valorOpcion = $(this).val();
                        if (!data.includes(valorOpcion)) {
                            $(this).remove();
                        }
                    });
                }
            });

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    verificarPesosEstandar: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data["status"] == "success") {
                        $("#PesosEstandar").css("display", "none");
                        if ("<?= $_SESSION["usuario"] ?>" == "ADMINISTRADOR") {
                            $("#RevertPesosEstandar").css("display", "initial");
                        } else {
                            $("#RevertPesosEstandar").css("display", "initial");
                            /* $("#RevertPesosEstandar").css("visibility", "hidden"); */
                        }
                    }else {
                        $("#PesosEstandar").css("display", "initial");
                        $("#RevertPesosEstandar").css("display", "none");
                    }
                }
            });
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
                    canastillaBase: $("#canastillaBase").val()
                }

                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        despreseItem: datos
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
                            title: "Item registrado correctamente",
                            text: '',
                            icon: "success",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarPesosDesprese: $("#idGuia").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                console.log(data);
                                setTimeout(() => {
                                    if (data["status"] == "errorM") {
                                    Swal.fire({
                                        title: "¡Aviso!",
                                        text: 'El peso del desprese no puede ser superior al peso inicial',
                                        icon: "error",
                                        confirmButtonText: 'Aceptar'
                                    });
                                    } else if (data["status"] == "errorA") {
                                        Swal.fire({
                                            title: "¡Aviso!",
                                            html: 'Desviación fuera de Rango Peso inicial vs Peso Desprese de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                            icon: "error",
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

        $("#CerrarModalDesprese").click(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: "¿Desea cerrar la ventana de Desprese?",
                icon: "warning",
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

        function editDesprese(idItem, descripcion) {
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
            $("#EditItemMejora").css("display", "initial");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    idItemD: idItem
                },
                dataType: "json",
                success: function (data) {
                    $("#kilosDesprese").val(data[0][0]);
                    $("#cajasDesprese").val(data[0][1]);
                    $("#canastillaBaseDesprese").val(data[0][2]);
                }
            });
        }

        function validacionesEditMejora() {
            $("#kilosDespreseError").css("display", "none");
            $("#cajasDespreseError").css("display", "none");
            
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

        $("#EditItemMejora").click(function (e) { 
            e.preventDefault();
            if (validacionesEditMejora() == "") {
                $("#EditItemMejora").prop('disabled', true);
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
                        editItem: datos
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.status == "success") {
                            $("#modalEdit").modal('hide');
                            $('#TItems').DataTable().ajax.reload();
                            $.ajax({
                                type: "POST",
                                url: "controlador/controlador.php",
                                data: {
                                    verificarPesosDesprese: $("#idGuia").val()
                                },
                                dataType: "json",
                                success: function (data) {
                                    console.log(data);
                                    setTimeout(() => {
                                        if (data["status"] == "errorM") {
                                            Swal.fire({
                                                title: "¡Aviso!",
                                                text: 'El peso del desprese no puede ser superior al peso inicial',
                                                icon: "error",
                                                confirmButtonText: 'Aceptar'
                                            });
                                        } else if (data["status"] == "errorA") {
                                            Swal.fire({
                                                title: "¡Aviso!",
                                                html: 'Desviación fuera de Rango Peso inicial vs Peso Desprese de un <span style="color: red;">-' + data["porcentaje"].toFixed(2) + '%</span>',
                                                icon: "error",
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
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#EditItemMejora").prop('disabled', false);
                    },
                    error: function (res) {
                        Swal.fire({
                            icon: "error",
                            title: "Error al actualizar el item",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $("#EditItemMejora").prop('disabled', false);
                    }
                });
            }
        });

        function borrarItem(idItem,descripcion) {
            Swal.fire({
                title: "¿Esta seguro que desea eliminar este Item?",
                icon: "warning",
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
                            deleteItem: idItem
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#TItems').DataTable().ajax.reload();
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Item Eliminado correctamente",
                                text: '',
                                icon: "success",
                                timer: 1300, 
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function iniciarTablaInfoGuia(guia) {
            $("#TInfoGuia").DataTable({
                "responsive": true,
                "processing": false,
                "bDestroy": true,
                "paging": false,
                "info": false,
                "searching": false,
                "ajax": {
                    url: "tablas/TablaInfoGuia.php",
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
            $('#TInfoGuia').css('width', '100%');
            $('#TInfoGuia_filter input, #TInfoGuia_length select').addClass('form-control');
            $('#TInfoGuia_filter').find('label').find('input').attr("name", "input2");
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
                    url: "tablas/TablaRegistrosImpresion.php",
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

        var ojo = "";

        function abrirModalMejora(idGuia, sede, verificar, totalCambios) {
            $("#modalMejoras").modal("show");
            $("#TituloMejora").text("Agregar Mejoras Guia: " + idGuia + " - Sede: " + sede);
            $("#idMejora").val(idGuia);
            if (verificar == "false") {
                modal_temp(idGuia);
            }
            iniciarTablaInfoGuia(idGuia);
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    verificarCampos: idGuia
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data["status"] == "error") {
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
                    }else {
                        $("#proveedorH").val(data.items.proveedor_hielo);
                        $("#loteHielo").val(data.items.lote_hielo);
                        $("#fechaHielo").val(data.items.fecha_venci_hielo);
                        $("#proveedorE").val(data.items.proveedor_empaque);
                        $("#loteEmpaque").val(data.items.lote_empaque);
                        $("#fabricanteE").val(data.items.fabricante_empaque);
                        $("#proveedorS").val(data.items.proveedor_salmuera);
                        $("#loteSalmuera").val(data.items.lote_salmuera);
                        $("#concentracion").val(data.items.concentracion);
                        $("#fechaSalmuera").val(data.items.fecha_venci_salmuera);
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        $("div[name=CamposMejora]").css("display", "block");
                        $("#DivImprimirEtiquetas").css("display", "flex");
                        iniciarTablaRegistrosImpresion(idGuia);
                    }
                }
            });
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
                    verifiSelect: idGuia
                },
                dataType: "json",
                success: async function (data) {
                    var itemsDB = ["0"];
                    data.forEach(function(valor) {
                        itemsDB.push(valor[0]);
                    });
                    $("#itemMejora").empty();
                    $("#itemImp").empty();
                    await $("#item option").each(function() {
                        var valorOpcion = $(this).val();
                        var texto = $(this).text();
                        $("#itemMejora").append("<option value="+valorOpcion+">"+texto+"</option>");
                        $("#itemImp").append("<option value="+valorOpcion+">"+texto+"</option>");
                    });
                    await $("#itemMejora option").each(function() {
                        var valorOpcion = $(this).val();
                        if (!itemsDB.includes(valorOpcion)) {
                            $(this).remove();
                        }
                    });
                    await $("#itemImp option").each(function() {
                        var valorOpcion = $(this).val();
                        if (!itemsDB.includes(valorOpcion)) {
                            $(this).remove();
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "controlador/controlador.php",
                        data: {
                            verifiPollo: idGuia
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data == "BLANCO") {
                                $("#itemMejora").append("<option value='059759'>POLLO BLANCO MERCAMIO MARINADO</option>");
                                $("#itemImp").append("<option value='059759'>POLLO BLANCO MERCAMIO MARINADO</option>");
                            }else if(data == "CAMPO"){
                                $("#itemMejora").append("<option value='059755'>POLLO CAMPO MERCAMIO MARINADO</option>");
                                $("#itemImp").append("<option value='059755'>POLLO CAMPO MERCAMIO MARINADO</option>");
                            }
                        }
                    });
                }
            });

            $("#TItemsMejora").DataTable({
                "responsive": true,
                "processing": false,
                "serverSide": true,
                "bDestroy": true,
                "paging": false,
                "order": [
                    [0, 'desc']
                ],
                "ordering": totalCambios > 0 && <?= $_SESSION["tipo"] ?> == 0 && <?= $_SESSION["registrosCambios"] ?> == 1 ? false : true,
                "ajax": {
                    url: "tablas/TablaMejoraItems.php",
                    type: "post",
                    data: {
                        id: idGuia
                    },
                    dataSrc: function(json) {
                        if (json.ojo) {
                            $.ajax({
                                type: "POST",
                                url: "controlador/actualizarSesion.php",
                                data: {
                                    accion: "actualizarOjo",
                                    valor: json.ojo
                                },
                                success: function(response) {
                                    ojo = json.ojo;
                                }
                            });
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
                        CamposGuia: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        $("div[name=CamposMejora]").css("display", "block");
                        $("#DivImprimirEtiquetas").css("display", "flex");
                        Swal.fire({
                            title: "Campos agregados a la guia correctamente",
                            text: '',
                            icon: "success",
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
                        CamposGuia: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#EditarGuia").css("display", "initial");
                        $("#GuardarGuia").css("display", "none");
                        Swal.fire({
                            title: "Campos actualizados correctamente",
                            text: '',
                            icon: "success",
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
                        AggMejora: datos
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
                            title: "Mejora agregada correctamente",
                            text: '',
                            icon: "success",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "controlador/controlador.php",
                            data: {
                                verificarItems: $("#idMejora").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                setTimeout(() => {
                                    if (data["status"] == "success") {
                                        modal_temp($("#idMejora").val());
                                        /* Swal.fire({
                                            title: "¡Aviso!",
                                            text: 'Ingrese la Temperatura.',
                                            icon: "info",
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
                title: "¿Desea cerrar la ventana de Mejoras?",
                icon: "warning",
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
                            verificarItems: $("#idMejora").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data["status"] == "success") {
                                modal_temp($("#idMejora").val());
                                /* Swal.fire({
                                    title: "¡Aviso!",
                                    text: 'Ingrese la Temperatura.',
                                    icon: "info",
                                    confirmButtonText: 'Aceptar'
                                }); */
                            }
                        }
                    });
                    if (ojo == "R") {
                        ojo = "N";
                        window.open("controlador/imprimirDespreseOjo.php?id=" + $("#idMejora").val(), "_blank");
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
                    traerMejora: idItem
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
                        editarMejora: datos
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res["status"] == "success") {
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
                        }
                        
                        Swal.fire({
                            icon: res.status,
                            title: res.message,
                            timer: 1500, 
                            showConfirmButton: false
                        });
                        $("#EditMejora").prop('disabled', false);
                    },
                    error: function (res) {
                        Swal.fire({
                            icon: "error",
                            title: "Error al actualizar la mejora",
                            timer: 1500, 
                            showConfirmButton: false
                        });
                        $("#EditMejora").prop('disabled', false);
                    }
                });
            } else {
                console.log("No se puede editar");
            }
        });

        function borrarMejora(idItem) {
            Swal.fire({
                title: "¿Esta seguro que desea eliminar esta Mejora?",
                icon: "warning",
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
                            deleteMejora: idItem
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#TItemsMejora').DataTable().ajax.reload();
                            $('#jtable').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Item Eliminado correctamente",
                                text: '',
                                icon: "success",
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
                        generarCodigo: datos
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
                        if (item == "POLLO CAMPO MERCAMIO MARINADO" || item == "POLLO BLANCO MERCAMIO MARINADO") {
                            var codigo = especie + year + semana + dia + siglas + sede;
                        }else if(semanaM == ""){
                            var codigo = especie + year + semana + diaM + dia +  siglas + sede;
                        }else{
                            var codigo = especie + year + semanaM + diaM + semana + dia + siglas + sede;
                        }
                        
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
                    idTemp: id
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
                        AggTemp: datos,
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#ModalTemp").modal("hide");
                        Swal.fire({
                            title: "Temperaturas agregadas correctamente",
                            text: '',
                            icon: "success",
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
                    bloquear_desprese: id
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
                    desbloquear_desprese: id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#jtable').DataTable().ajax.reload();
                }
            });
        }

        $("#PesosEstandar").click(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: "¿Esta seguro que desea realizar ejecutar los Pesos Estandar?",
                icon: "warning",
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
                            PesosEstandar: $("#idGuia").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            if (data["status"] == "success") {
                                $('#TItems').DataTable().ajax.reload();
                                $('#jtable').DataTable().ajax.reload();
                                $("#PesosEstandar").css("display", "none");
                                if ("<?= $_SESSION["usuario"] ?>" == "ADMINISTRADOR") {
                                    $("#RevertPesosEstandar").css("display", "initial");
                                } else {
                                    $("#RevertPesosEstandar").css("display", "initial");
                                    /* $("#RevertPesosEstandar").css("visibility", "hidden"); */
                                }
                                $("#item").attr("disabled", true);
                                $("#kilos").attr("disabled", true);
                                $("#cajas").attr("disabled", true);
                                $("#canastillaBase").attr("disabled", true);
                                Swal.fire({
                                    title: "Pesos Estandar ejecutados correctamente",
                                    text: '',
                                    icon: "success",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }else {
                                Swal.fire({
                                    title: data["message"],
                                    text: "",
                                    icon: "error",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        });

        $("#RevertPesosEstandar").click(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: "¿Esta seguro que desea revertir los Pesos Estandar?",
                icon: "warning",
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
                            RevertPesosEstandar: $("#idGuia").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            if (data["status"] == "success") {
                                $('#TItems').DataTable().ajax.reload();
                                $('#jtable').DataTable().ajax.reload();
                                $("#PesosEstandar").css("display", "initial");
                                $("#RevertPesosEstandar").css("display", "none");
                                Swal.fire({
                                    title: "Pesos Estandar revertidos correctamente",
                                    text: '',
                                    icon: "success",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "controlador/controlador.php",
                                    data: {
                                        verificarDesprese: $("#idGuia").val()
                                    },
                                    dataType: "json",
                                    success: function (data) {
                                        console.log(data);
                                        if (data["status"] == "success") {
                                            $("#item").attr("disabled", true);
                                            $("#kilos").attr("disabled", true);
                                            $("#cajas").attr("disabled", true);
                                            $("#canastillaBase").attr("disabled", true);
                                        } else {
                                            $("#item").removeAttr("disabled");
                                            $("#kilos").removeAttr("disabled");
                                            $("#cajas").removeAttr("disabled");
                                            $("#canastillaBase").removeAttr("disabled");
                                            $("#PesosEstandar").css("display", "initial");
                                        }
                                    }
                                });
                            }else {
                                Swal.fire({
                                    title: "Error al revertir los pesos estandar",
                                    text: data["message"],
                                    icon: "error",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        });

        function actualizarItemStatus(id, status) {
            datos = {
                id: id,
                status: status
            }
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    itemStatus: datos
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#TItemsMejora").DataTable().ajax.reload();
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
                        generarCodigo: datos
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
                        if (item == "POLLO CAMPO MERCAMIO MARINADO" || item == "POLLO BLANCO MERCAMIO MARINADO") {
                            var codigo = especie + year + semana + dia + siglas + sede;
                        }else if(semanaM == ""){
                            var codigo = especie + year + semana + diaM + dia +  siglas + sede;
                        }else{
                            var codigo = especie + year + semanaM + diaM + semana + dia + siglas + sede;
                        }
                        
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

            datos = {
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
                    guardarEtiquetas: datos
                },
                dataType: "json",
                success: function (data) {
                    if (data["status"] == "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Etiquetas guardadas correctamente",
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
                            icon: "error",
                            title: "Error al guardar las etiquetas",
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                    $("#guardarEtiquetas").prop('disabled', false);
                },
                error: function (data) {
                    console.log(data);
                    $("#guardarEtiquetas").prop('disabled', false);
                }
            });
        });

        function eliminarEtiqueta(id) {
            Swal.fire({
                title: "¿Estás seguro de querer eliminar esta etiqueta?",
                icon: "warning",
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
                            eliminarEtiqueta: id
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data["status"] == "success") {
                                Swal.fire({
                                    title: "Etiqueta eliminada correctamente",
                                    icon: "success",
                                    timer: 1300, 
                                    showConfirmButton: false
                                });
                                $("#TRegistrosImpresion").DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: "Error al eliminar la etiqueta",
                                    icon: "error",
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
            const form = $('<form action="controlador/imprimirItem.php" method="post" target="_blank"></form>');
            form.append('<input type="hidden" name="guia" value=\'' + $("#idMejora").val() + '\'>');
            $('body').append(form);
            form.submit();
            form.remove();
            $("#TRegistrosImpresion").DataTable().ajax.reload();
        });

        var totalKilosDespresados = 0;
        var totalUnidadesDespresadas = 0;
        var totalPorcentajePart = 0;
        var totalPrecioVenta = 0;
        var totalCostoTotal = 0;

        function AbrirModalCF(guia, sede, fecha, polloDesprese, polloEntero) {
            $("#ModalCF").modal("show");
            $("#TituloCF").html("LIQUIDACION GUIA DE DESPRESE Y MEJORAMIENTO #" + guia + " - SEDE: " + sede + " - FECHA GUIA: " + fecha + "<br> Desprese: " + polloDesprese[1] + " - " + parseFloat(polloDesprese[0]).toFixed(2) + "kg / Entero: " + polloEntero[1] + " - " + parseFloat(polloEntero[0]).toFixed(2) + "kg");
            $("#idGuia").val(guia);
            $("#TablaEncabezadoCF").DataTable({
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
                    url: "tablas/TablaEncabezadoCF.php",
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
            $("#TablaConPE_CF").DataTable({
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
                    url: "tablas/TablaConPE_CF.php",
                    data: {
                        guia: guia
                    },
                    dataSrc: function (data) {
                        totalKilosDespresados = data.totalKilosDespresados;
                        totalUnidadesDespresadas = data.totalUnidadesDespresadas;
                        totalPorcentajePart = data.totalPorcentajePart;
                        totalPrecioVenta = data.totalPrecioVenta;
                        totalCostoTotal = data.totalCostoTotal;
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

            $("#TablaSinPE_CF").DataTable({
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
            });
            IniciarTablaTotalCF();
            $("#TablaTotalCF").DataTable({
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
                    url: "tablas/TablaTotalCF.php",
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
            $("#TablaTotalPorcentajeM").DataTable({
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
                    url: "tablas/TablaTotalPorcentajeM.php",
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

        function IniciarTablaTotalCF() {
            setTimeout(function () {
                $("#TablaTotal").DataTable({
                    bDestroy: true,
                    processing: true,
                    ordering: false,
                    paging: false,
                    searching: false,
                    info: false,
                    autoWidth: false,
                    data: [
                        [
                            "",
                            "GRAN TOTAL DESPRESADO",
                            totalKilosDespresados,
                            "",
                            totalUnidadesDespresadas,
                            totalPorcentajePart + "%",
                            "",
                            "",
                            totalPrecioVenta,
                            totalCostoTotal,
                            ""
                        ]
                    ],
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
            }, 500);
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
                    InsertarDatosCF : {
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
                        $("#TablaEncabezadoCF").DataTable().ajax.reload();
                        $("#TablaConPE_CF").DataTable().ajax.reload();
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
            window.open("controlador/imprimirLiquidacion.php?guia=" + guia, "_blank");
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
                                ValidarGuiaPlano: guia
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
                                ValidarGuiaPlanoDestino: guia,
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
                planosInput.name = 'crearPlanos';
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
                planosInput.name = 'crearPlanosMejoramiento';
                planosInput.value = JSON.stringify({planos: planos});
                form.appendChild(planosInput);
                
                // Agregar el formulario al DOM y enviarlo
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            } else {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'controlador/imprimirLiquidacion.php';
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
                            title: "¡Aviso!",
                            text: 'Ingrese la Temperatura de la Sede ' + data["sede"],
                            icon: "info",
                            confirmButtonText: 'Aceptar'
                        });
                    }else {
                        Swal.fire({
                            title: "¡Aviso!",
                            text: data["message"],
                            icon: "info",
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