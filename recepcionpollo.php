<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
//error_reporting(0);
$_SESSION['tipoR'] = 1;
date_default_timezone_set("America/bogota");
$_SESSION["fechaInicio"] = date("G:i");
require_once('./modelo/funciones.php');
$listaResponsables = listaResponsables();
$listaConductores = listaConductores_recepcion_pollo();
$listaPlacas = listaPlacas_recepcion_pollo();
$listaOrigen = listaProveedores();
$listaDestinos = listaDestinos();
$fecha_actual = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Planta de Desposte | Recepción</title>
	<?php include_once('encabezado.php'); ?>
	<style>
		.custom-margin {
			display: flex;
			align-items: center;
		}

		.custom-margin .form-group {
			display: flex;
			align-items: center;
			margin-left: 10px;
		}

		.custom-margin .form-control {
			width: 100px;
			/* O el tamaño que prefieras */
		}

		.text-left-custom {
			text-align: left !important;
		}

		#jtable th,
		#jtable td {
			width: 20%;
		}

		#TLotes th:nth-child(1), #TLotes td:nth-child(1) {
			width: 10%;
			center{
				margin-top: 10px;
			}
		}
		#TLotes th:nth-child(2), #TLotes td:nth-child(2) {
			width: 80%;
			center{
				margin-top: 10px;
			}
		}
		#TLotes th:nth-child(3), #TLotes td:nth-child(3) {
			width: 10%;
		}

		#jtable th {
			vertical-align: middle !important;
		}
	</style>
</head>
<body>
	<?php include_once('menu.php'); ?>
	<?php include_once('menuizquierda.php'); ?>
	<main id="main" class="main">

		<div class="pagetitle">
			<h1>Recepción de aves</h1>
		</div>

		<section class="section">
			<div class="row">
				<div class="col-md-12">

					<div class="card">
						<div class="card-body">
							<h5 class="card-title">
								<center>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
										title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA DE RECEPCIÓN DE AVES</button>
                                        <h5 class="mt-1" style="color: red;display: none" id="alertItem">La ultima guia no tiene items</h5>
								</center>
								<table id="jtable" class="table table-striped table-bordered table-hover datatable">
									<thead>
										<tr>
											<th>idOculto</th>
											<th>Consecutivo<br>Especie - Tipo</th>
											<th>Fecha</th>
											<th>Proveedor<br>Empresa Destino</th>
											<th>Pollos</th>
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

	<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

			<div class="modal-content">
				<div class="modal-header d-flex justify-content-between align-items-center">
					<h6 id="horaInicialGuia" class="float-start m-0"></h6>
					<h6 class="modal-title fw-bold" id="titulo">LOTE DE RECEPCIÓN</h6>
					<button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">
							<div class="col-md-3">
								Fecha de Recepción<span class="text-danger">*</span>
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fecharec" value="<?php echo $fecha_actual; ?>" name="fecharec" placeholder="Ingrese fecha Recepción">
								</div>
								<div class="alert alert-danger" role="alert" id="fecharecE" style="display: none">
									Debes ingresar fecha de recepción
								</div>
							</div>
							
							<div class="col-md-3">
								Fecha de Beneficio<span class="text-danger">*</span>
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fechaben" name="fechaben" placeholder="Ingrese fecha Beneficio">
								</div>
								<div class="alert alert-danger" role="alert" id="fechabenE" style="display: none">
									Debes ingresar fecha de Beneficio
								</div>
							</div>
							
							<div class="col-md-3">
								Fecha de Vencimiento<span class="text-danger">*</span>
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fechasac" name="fechasac" placeholder="Ingrese fecha Vencimiento">
								</div>
								<div class="alert alert-danger" role="alert" id="fechasacE" style="display: none">
									Debes ingresar fecha de vencimiento
								</div>
							</div>
							<div class="col-md-3">
								Especie
								<div class="form-group label-floating" id="telefonoDiv">
									<select class="form-control" id="especie" name="especie">
										<option value="">Seleccione Especie</option>
										<option value="POLLO">POLLO</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="especieE" style="display: none">
									Debes seleccionar la especie a recibir
								</div>
							</div>
							<div class="col-md-3">
								Tipo
								<div class="form-group label-floating" id="telefonoDiv">
									<select class="form-control" id="tipo" name="tipo">
										<option value="">Seleccione Tipo</option>
										<option value="BLANCO">BLANCO</option>
										<option value="CAMPO">CAMPO</option>
										<option value="ASADERO">ASADERO</option>
										<option value="BLANCO_DESPRESE">BLANCO DESPRESADO</option>
										<option value="CAMPO_DESPRESE">CAMPO DESPRESADO</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="tipoE" style="display: none">
									Debes seleccionar el tipo de item a recibir
								</div>
							</div>

							<div class="col-md-3">
								Cantidad a recibir
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="number" name="canales" autocomplete="off" id="canales" placeholder="Ingrese el Nro de Pollos">
								</div>
								<div class="alert alert-danger" role="alert" id="canalesE" style="display: none">
									Debe ingresar la cantidad a recibir
								</div>
							</div>

							<div class="col-md-6">
								Proveedor
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="beneficio" name="beneficio">
										<option value="">Seleccione Proveedor</option>
										<?php foreach ($listaOrigen as $perm) : ?>
											<option value="<?php echo $perm['id'] ?>">
												<?php echo $perm['sede'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="beneficioE" style="display: none">
									Debe seleccinar el proveedor
								</div>
							</div>

							<div class="col-md-3">
								Orden Recibo
								<select name="recibo" id="recibo" class="form-control">
									<option value="">Seleccione Recibo</option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
									<option value="E">E</option>
									<option value="F">F</option>
								</select>
								<div class="alert alert-danger" role="alert" id="reciboE" style="display: none">
									Debe de ingresar el recibo
								</div>
							</div>

							<div class="col-md-2">
								Lote Recepción
								<div class="form-group label-floating">
									<input class="form-control" type="text" style="width: 130%;" name="lotePlanta" autocomplete="off" id="lotePlanta" disabled placeholder="Ingrese el lote de la planta">
								</div>
								<div class="alert alert-danger" role="alert" id="lotePlantaE" style="display: none">
									Debe ingresar el lote de la planta
								</div>
							</div>

							<div class="col-md-1">
								<a style=" z-index: 0;color:#000;" id="AbrirModalLotes">
									<i class="bi bi-folder-plus fs-2 text-warning ms-5"></i>
								</a>
							</div>

							<div class="col-md-3">
								Destino
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="destino" name="destino">
										<option value="">Seleccione la Empresa Destino</option>
										<option value="MERCAMIO">MERCAMIO</option>
										<option value="MERCATODO">MERCATODO</option>
										<option value="MERKMIOS">MERKMIOS</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="destinoE" style="display: none">
									Debe seleccinar la Empresa Destino
								</div>
							</div>

							<div class="col-md-3">
								Responsable Recepción<span class="text-danger">*</span>
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="responsable" name="responsable" disabled>
										<?php foreach ($listaResponsables as $perm) : ?>
											<option value="<?php echo $perm['cedula'] ?>">
												<?php echo $perm['cedula'] . " - " . $perm['nombres'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
									Debe seleccinar el eesponsable del despacho
								</div>
							</div>

							<div class="col-md-12 mt-3">
								<center>
									<label style="text-align: center;font-weight: bold;" id="titulo">INFORMACIÓN GUÍAS DE TRANSPORTE Y CERTIFICADO DE CALIDAD</label>
								</center>
							</div>

							<div class="col-md-3">
								Nombre del Conductor<span class="text-danger">*</span>
								<div class="form-group label-floating" id="conseGuiaDiv" style="width: 120%;">
									<select class="form-control" id="conductor" name="conductor">
										<option value="">Seleccione el Conductor de Despacho</option>
										<?php foreach ($listaConductores as $perm) : ?>
											<option value="<?php echo $perm['cedula'] ?>">
												<?php echo $perm['nombres'] . " - " . $perm['cedula'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="conductorE" style="display: none">
									Debe seleccinar el conductor
								</div>
							</div>
							<div class="col-md-1">
								<a style="z-index: 0;color:#000;" id="abrirSegundaModal">
									<i class="bi bi-folder-plus fs-2 text-warning ms-5"></i>
								</a>
							</div>
							<div class="col-md-2">
								Placa Vehiculo<span class="text-danger">*</span>
								<div class="form-group label-floating" id="conseGuiaDiv" style="width: 125%;">
									<select class="form-control" id="placa" name="placa">
										<option value="">Seleccione la Placa del Vehiculo</option>
										<?php foreach ($listaPlacas as $perm) : ?>
											<option value="<?php echo $perm['placa'] ?>">
												<?php echo $perm['placa'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="placaE" style="display: none">
									Debe seleccinar la placa del vehiculo
								</div>
							</div>
							<div class="col-md-1">
								<!-- <a style="z-index: 0;color:#000; margin-right:10px" data-bs-toggle="modal" data-bs-target="#modalNuevaPlaca"><i class="bi bi-folder-plus fs-2 text-warning" style="margin-top: 29px;margin-left: -10px;"></i></a> -->
								<a style="z-index: 0;color:#000;" id="abrirTerceraModal">
									<i class="bi bi-folder-plus fs-2 text-warning ms-5"></i>
								</a>
							</div>

							<div class="col-md-2">
								<center>Guia Transporte</center>
								<div class="form-group label-floating" id="codigoAutoDiv">
									<select class="form-control" id="guiat" name="guiat">
										<option value="">Tiene Guia de Transporte</option>
										<option value="SI">SI CUMPLE</option>
										<option value="NO">NO CUMPLE</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="guiatE" style="display: none">
									Debes ingresar la guia de transporte
								</div>
							</div>

							<div class="col-md-3">
								<center>Certificado de Calidad</center>
								<div class="form-group label-floating" id="codigoAutoDiv">
									<select class="form-control" id="certificadoc" name="certificadoc">
										<option value="">Tiene Certificado de Calidad</option>
										<option value="SI">SI</option>
										<option value="NO">NO</option>
									</select>
									<input type="hidden" id="id_guia">
								</div>
								<div class="alert alert-danger" role="alert" id="certificadocE" style="display: none">
									Debes indicar si tiene certificado de calidad
								</div>
							</div>

							<div class="col-md-12 mt-3">
								<center>
									<label style="text-align: center;font-weight: bold;" id="titulo">VERIFICACIÓN CONDICIONES PERSONAL MANIPULADOR</label>
								</center>
							</div>
							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph1" name="cph1" value="1">&nbsp;
								<label for="cph1">Usa cofia y tapabocas</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph2" name="cph2" value="1">&nbsp;
								<label for="cph2">Dotacion limpia y en buen estado</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph3" name="cph3" value="1">&nbsp;
								<label for="cph3">Botas limpias y en buen estado</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph4" name="cph4" value="1">&nbsp;
								<label for="cph4">Uñas cortas limpias y sin esmalte</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph5" name="cph5" value="1">&nbsp;
								<label for="cph5">No usa accesorios</label>
							</div>

							<div class="col-md-12 mt-3">
								<center>
									<label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO CONDICIONES HIGIÉNICO SANITARIAS DEL VEHÍCULO DE TRANSPORTE</label>
								</center>
							</div>

							<div class="col-md-6 custom-margin">
								Temperatura del Vehiculo<span class="text-danger">*</span>
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="number" name="chv1" autocomplete="off" id="chv1" placeholder="Ingrese Temperatura">
								</div>
								<div class="alert alert-danger" role="alert" id="chv1E" style="display:none">
									Debe ingresar la Temperatura del Vehiculo
								</div>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv2" name="chv2" value="1">&nbsp;
								<label for="chv2">Limpio</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv3" name="chv3" value="1">&nbsp;
								<label for="chv3">Ausente de Plagas</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv4" name="chv4" value="1">&nbsp;
								<label for="chv4">Ausente de Olores Fuertes</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv5" name="chv5" value="1">&nbsp;
								<label for="chv5">Uso de Estibas</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv6" name="chv6" value="1">&nbsp;
								<label for="chv6" style="font-size: 14px">No se Transportan Sustancias Peligrosas</label>
							</div>

							<div class="col-md-12 mt-3">
								<center>
									<label style="text-align: center;font-weight: bold;" id="titulo">VERIFICACIÓN CONDICIONES PRODUCTO</label>
								</center>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh1" name="ccoh1" value="1">&nbsp;
								<label id="ccoh1t" for="ccoh1">Olor</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh2" name="ccoh2" value="1">&nbsp;
								<label id="ccoh2t" for="ccoh2">Color</label>
							</div>

							<div class="col-md-12 mt-3">
								<center>
									<label style="text-align: center;font-weight: bold;" id="titulo">VERIFICACIÓN CONDICIONES MATERIAL DE EMPAQUE</label>
								</center>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh3" name="ccoh3" value="1">&nbsp;
								<label id="ccoh3t" for="ccoh3">Limpio</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh4" name="ccoh4" value="1">&nbsp;
								<label id="ccoh4t" for="ccoh4">En Buen Estado</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh5" name="ccoh5" value="1">&nbsp;
								<label id="ccoh5t" for="ccoh5">Sin Olores Fuertes o Desagradables</label>
							</div>
						</div>
						<div class="col-md-12 mt-3">
							Observaciones
							<div class="form-group label-floating" id="observacionesDiv">
								<input type="text" class="form-control" autocomplete="off" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
							</div>
						</div>

				</div>
				</form>
				<div class="row">
					<div class="col-md-12" style="text-align:center; margin-bottom:10px">
						<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
						<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
						<button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalFechas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header text-center">
					<h5 class="modal-title" id="tituloC" style="font-weight: bold;">Imprimir Reporte por Rango de Fechas</h5>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">
							<div class="col-md-6">
								<label for="fechainicial">Fecha Inicial</label>
								<input type="date" class="form-control" autocomplete="off" id="fechainicial" name="fechainicial" placeholder="Ingrese fecha Inicial">
							</div>
							
							<div class="col-md-6">
								<label for="fechafinal">Fecha Final</label>
								<input type="date" class="form-control" autocomplete="off" id="fechafinal" name="fechafinal" placeholder="Ingrese fecha Final">
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12 text-center mt-3 mb-3">
								<button type="button" class="btn btn-primary" rel="tooltip" data-placement="bottom" id="btnImprimir">Imprimir</button>
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalNuevoConductor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">

			<div class="modal-content">
				<div class="modal-header" style="display: block;text-align: center;">
					<label style="text-align: center;font-weight: bold;" id="tituloC">Nuevo Conductor</label>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">
							<div class="col-12 col-md-6">
								Cedula
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="cedula" name="cedula" placeholder="Ingrese Cedula">
									<input type="hidden" id="id_guia">
									<input type="hidden" id="cedulaExiste">
								</div>
								<div class="alert alert-danger" role="alert" id="cedulaE" style="display: none">
									Debes ingresar la cedula del conductor
								</div>
							</div>

							<div class="col-12 col-md-6">
								Nombres y Apellidos
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="nombresConductor" name="nombresConductor" placeholder="Ingrese los Nombres y Apellidos" onkeyup="javascript:this.value=this.value.toUpperCase();">
									<input type="hidden" id="id_guia">
								</div>
								<div class="alert alert-danger" role="alert" id="nombresConductorE" style="display: none">
									Debes ingresar los nombres y apellidos del conductor
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12" style="text-align:center; margin-bottom:10px; margin-top:10px">
							<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Conductor" id="btnNuevoConductor">Guardar</button>
							<button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalNuevaPlaca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable">

			<div class="modal-content">
				<div class="modal-header" style="display: block;text-align: center;">
					<label style="text-align: center;font-weight: bold;" id="tituloC">Nueva Placa</label>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">
							<div class="col-12">
								Placa Vehiculo
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="placav" name="placav" placeholder="Ingrese Placa">
								</div>
								<div class="alert alert-danger" role="alert" id="placavE" style="display: none">
									Debes ingresar la placa del vehiculo
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12" style="text-align:center; margin-bottom:10px; margin-top:10px">
							<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Placa" id="btnNuevaPlaca">Guardar</button>
							<button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalCriterios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-dialog modal-fullscreen modal-dialog-scrollable">

			<div class="modal-content">
				<div class="modal-header" style="display: block;text-align: center;">
					<h5 class="modal-title" id="titulo2" style="font-weight: bold; text-align: center;"></h5>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<input type="hidden" name="id" id="id">
						<input type="hidden" name="tipo" id="tipo">
						<div class="row">
							<div class="col-md-3 mb-3">
								<label for="valorCriterio">Item</label>
								<div class="form-group">
									<!-- <input class="form-control" list="datalistValorCriterio" autocomplete="off" id="valorCriterio" placeholder="Busca el Item para agregarlo"> -->
									<select name="valorCriterio" id="valorCriterio" class="form-control">
										<option value="">Seleccione un item</option>
									</select>
									<input type="hidden" id="id_criterio" name="id_criterio">
									<!-- <datalist id="datalistValorCriterio"></datalist> -->
								</div>
								<div class="alert alert-danger" role="alert" id="itemE" style="display: none">
									Debe seleccionar el item
								</div>
							</div>
							<div class="col-md-3 mb-3" id="divPollos">
								<label for="base">Pollos por Canastilla</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="pollosxcanastilla" autocomplete="off" id="pollosxcanastilla" placeholder="Ingrese la cantidad de Pollos por Canastilla">
								</div>
								<div class="alert alert-danger" role="alert" id="pollosxcanastillaE" style="display: none">
									Debe ingresar la cantidad de pollos por canastilla
								</div>
							</div>
							<div class="col-md-2 mb-3">
								<label for="cajas">Canastillas</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="cajas" autocomplete="off" id="cajas" placeholder="Ingrese la cantidad de Canastillas del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="canastillasE" style="display: none">
									Debe ingresar la cantidad de canastillas del item
								</div>
							</div>
							<div class="col-md-2 mb-3">
								<label for="base">Canastillas Base</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="base" autocomplete="off" id="base" placeholder="Ingrese la cantidad de Canastillas Base">
								</div>
								<div class="alert alert-danger" role="alert" id="baseE" style="display: none">
									Debe ingresar la cantidad de canastillas base
								</div>
							</div>
							
							<div class="col-md-1 mb-3">
								<label for="peso">Peso</label>
								<div class="form-group">
									<input type="number" class="form-control" name="peso" autocomplete="off" id="peso" placeholder="Ingrese el Peso del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar el peso del item
								</div>
							</div>
							
							<div class="col-md-1 mb-3">
								<label for="temperatura">Temperatura</label>
								<div class="form-group">
									<input class="form-control" type="number" name="temperatura" autocomplete="off" id="temperatura" placeholder="Selecciona la Temperatura del Item">
								</div>
							</div>
							
							<div class="col-md-2 mb-3">
								<label for="cajas">Peso Canastilla</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="pesocanastilla" autocomplete="off" id="pesocanastilla" disabled>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								Observación
								<div class="form-group label-floating">
									<input class="form-control" type="text" name="observacionPollo" autocomplete="off" id="observacionPollo" placeholder="Ingrese Observación">
								</div>
							</div>
							<div class="col-md-4 mb-3">
								Fotografía
								<div class="form-group label-floating">
									<input class="form-control" type="file" name="foto" autocomplete="off" id="foto" placeholder="Seleccione Archivo">
								</div>
								<div class="form-group label-floating">
									<input class="form-control" type="hidden" name="fotoForm" autocomplete="off" id="fotoForm">
									<input type="hidden" name="borrarfoto" id="borrarfoto">
								</div>
							</div>
						</div>
					</form>
					<div class="text-center mb-3 mt-2">
						<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Agregar Item" id="btnNuevoCriterio">Guardar</button>
						<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Item" id="btnEditarCriterio" style="display: none;">Editar</button>
						<button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal">Cerrar</button>
					</div>
					<div class="table-responsive">
						<table id="jtableCriterio" class="table table-striped table-bordered table-hover datatable">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th>Descripción</th>
									<th>Temperatura</th>
									<th>Und</th>
									<th>Canastillas</th>
									<th>Peso</th>
									<th>Hora</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody id="tbodyCriterio"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalNovedades" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:10001;">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Novedades</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<!-- Aquí se mostrará el contenido dinámico -->
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalLotes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

			<div class="modal-content">
				<div class="modal-header" style="display: block;text-align: center;">
					<h5 class="modal-title" style="font-weight: bold; text-align: center;">Lotes</h5>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<input type="hidden" name="idProveedor" id="idProveedor">
						<div class="row">

							<div class="col-md-2"></div>

							<div class="col-md-8 mb-3">
								<label for="lote" class="form-label">Lote Proveedor</label>
								<input type="text" class="form-control" id="lote" name="lote" required autocomplete="off" placeholder="Ingrese el lote">
								<div class="alert alert-danger" role="alert" id="loteE" style="display: none">
									Debe agregar el lote del Proveedor
								</div>
							</div>

							<div class="col-md-2"></div>

						</div>

						<div class="text-center mb-3 mt-2">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" type="submit" title="Agregar Item" id="GuardarLote">Guardar</button>
							<button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</form>
					
					<div class="col-md-12">
						<table id="TLotes" class="table table-striped table-bordered table-hover datatable">
							<thead>
								<tr>
									<th>#</th>
									<th>Lote</th>
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

	<?php require_once('scripts.php'); ?>
	<script>
		document.getElementById('abrirSegundaModal').addEventListener('click', function(event) {
			event.preventDefault();
			var segundaModal = new bootstrap.Modal(document.getElementById('modalNuevoConductor'), {
				backdrop: false
			});
			segundaModal.show();
		});

		document.getElementById('abrirTerceraModal').addEventListener('click', function(event) {
			event.preventDefault();
			var terceraModal = new bootstrap.Modal(document.getElementById('modalNuevaPlaca'), {
				backdrop: false
			});
			terceraModal.show();
		});
	</script>
	<script>
		$(document).ready(function() {
			var dataTable = $('#jtable').dataTable({
				"responsive": true,
				"processing": true,
				"bDestroy": true,
				"columnDefs": [
					{
						"targets": [0],
						"visible": false
					},
					{
						"targets": [1, 2, 3, 4, 5],
						"orderable": false
					}
				],
				"order": [
					[0, 'desc']
				],
				"ajax": {
					url: "tablas/tablaRecepcionesPollo.php",
					type: "post"
				},
				"language": {
					"searchPlaceholder": "Ingrese caracter",
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
					},
				},
			});
			$('#jtable td:nth-child(5)').addClass('text-left-custom');
			$('#jtable').css('width', '100%');
			$('#jtable_filter input, #jtable_length select').addClass('form-control');

			$('#foto').on('change', function(event) {
				if ($("#borrarfoto").val() == "" || $("#borrarfoto").val() == null) {
					const files = event.target.files;
					const formData = new FormData();

					let fileNames = [];

					for (let i = 0; i < files.length; i++) {
						const file = files[i];
						const uniqueId = Date.now() + '_' + Math.floor(Math.random() * 1000000);
						const fileExtension = file.name.split('.').pop();
						const newFileName = `${uniqueId}.${fileExtension}`;

						fileNames.push(newFileName);

						const renamedFile = new File([file], newFileName, { type: file.type });

						const img = $('<img>').attr('src', URL.createObjectURL(renamedFile)).css({ width: '100px', margin: '10px' }).attr("id", "mifoto");
						formData.append('photos[]', renamedFile);
					}

					$('#fotoForm').val(fileNames.join(','));

					$.ajax({
						url: 'controlador/uploadpollo.php',
						method: 'POST',
						data: formData,
						contentType: false,
						processData: false,
						success: function(response) {
						},
					error: function() {
						//alert('Error al guardar las fotos');
					}
					});
				}else{
					$.ajax({
					type: "POST",
					url: "controlador/controlador.php",
					data: {
						borrarfoto: $("#borrarfoto").val()
					},
					dataType: "json",
					success: function (data) {
						console.log(data);
						$("#borrarfoto").val("");
						const files = event.target.files;
						const formData = new FormData();

						let fileNames = [];

						for (let i = 0; i < files.length; i++) {
							const file = files[i];
							const uniqueId = Date.now() + '_' + Math.floor(Math.random() * 1000000);
							const fileExtension = file.name.split('.').pop();
							const newFileName = `${uniqueId}.${fileExtension}`;

							fileNames.push(newFileName);

							const renamedFile = new File([file], newFileName, { type: file.type });

							const img = $('<img>').attr('src', URL.createObjectURL(renamedFile)).css({ width: '100px', margin: '10px' }).attr("id", "mifoto");
							formData.append('photos[]', renamedFile);
						}

						$('#fotoForm').val(fileNames.join(','));

						$.ajax({
							url: 'controlador/uploadpollo.php',
							method: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							success: function(response) {
							},
						error: function() {
							//alert('Error al guardar las fotos');
						}
						});
					}
					});
				}
			});

			/* $('#tipo').change(function() {
				var selectedValue = $(this).val();
				if (selectedValue === 'CERDO') {
					$('#ccoh1t').text('Olor');
					$('#ccoh2t').text('Color');
					$('#ccoh3t').text('Sin Pelo');
					$('#ccoh4t').text('Contenido estomacal');
					$('#ccoh5t').text('Sin Materia Fecal');
					$('#ccoh6t').text('Sin coágulos ni hematomas');
					$('#ccoh7t').text('Sin Riñones');
					$('#ccoh8t').text('Sin Leche');
					$('#ccoh9t').text('Sin Fracturas');
					$('#dermatitis-div').css('display', 'block');
					$('#ccoh1').prop('checked', false);
					$('#ccoh2').prop('checked', false);
					$('#ccoh3').prop('checked', false);
					$('#ccoh4').prop('checked', false);
					$('#ccoh5').prop('checked', false);
					$('#ccoh6').prop('checked', false);
					$('#ccoh7').prop('checked', false);
					$('#ccoh8').prop('checked', false);
					$('#ccoh9').prop('checked', false);
					$('#ccoh10').prop('checked', false);
				} else {
					$('#ccoh1t').text('Olor');
					$('#ccoh2t').text('Color');
					$('#ccoh3t').text('Sin Pelo');
					$('#ccoh4t').text('Sin Contenido Ruminal');
					$('#ccoh5t').text('Sin Materia Fecal');
					$('#ccoh6t').text('Sin Medula Espinal');
					$('#ccoh7t').text('Sin Nuches');
					$('#ccoh8t').text('Sin Garrapatas');
					$('#ccoh9t').text('Sin coágulos ni hematomas');
					$('#ccoh10t').text('Sin leche');
					$('#ccoh1').prop('checked', false);
					$('#ccoh2').prop('checked', false);
					$('#ccoh3').prop('checked', false);
					$('#ccoh4').prop('checked', false);
					$('#ccoh5').prop('checked', false);
					$('#ccoh6').prop('checked', false);
					$('#ccoh7').prop('checked', false);
					$('#ccoh8').prop('checked', false);
					$('#ccoh9').prop('checked', false);
					$('#ccoh10').prop('checked', false);
				}
			}); */

			Date.prototype.getWeek = function() {
				const onejan = new Date(this.getFullYear(), 0, 1);
				const millisInDay = 86400000;
				let dayOfYear = ((this - onejan) / millisInDay) + onejan.getDay();
				const currentDayOfWeek = this.getDay();
				if (currentDayOfWeek === 6) {
					dayOfYear -= 1;
				}
				return Math.ceil((dayOfYear + 1) / 7);
			};

			bloquearRecepcion();
		});

		function bloquearRecepcion() {
			/* $.ajax({
				type: "POST",
				url: "controlador/controlador.php",
				data: {
					bloq_guia: "1"
				},
				dataType: "json",
				success: function (data) {
					if (data == "no hay lote") {
						$("#crearProveedor").attr("disabled", "true");
						$("#alertItem").css("display", "block");
					} else {
						$("#crearProveedor").removeAttr("disabled");
						$("#alertItem").css("display", "none");
					}
				}
			}); */
		}

		function eliminarItem(id_item_proveedor) {
			Swal.fire({
				title: '¿Esta seguro que desea eliminar este registro?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Sí, eliminar',
				cancelButtonText: 'Cancelar'
			}).then((result) => {
				if (result.isConfirmed) {
					const datosEPolloR = {
						id_item_proveedor: id_item_proveedor,
						proveedor: $('#id').val()
					};

					console.log('datos para eliminar: ', datosEPolloR);
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: 'controlador/controlador.php',
						data: {
							datosEPolloR: datosEPolloR
						},
						success: function(res) {
							if (res.status == "success") {
								$('#jtableCriterio').DataTable().ajax.reload();
								$('#jtable').DataTable().ajax.reload();
								buscarItems($('#id').val(), $('#tipo').val());
								bloquearRecepcion();
							}
							
							Swal.fire({
								title: res.message,
								icon: res.status,
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function(res) {
							Swal.fire({
								icon: "error",
								title: "Error al eliminar el item",
								timer: 1500,
								showConfirmButton: false
							});
						}
					});
				}
			});
		}

		function abrirModal(consecutivoRecepcion, proveedor, status, pc, canales, tipo) {
			console.log('Valor recibido para pollosxcanatilla:', pc);

			$('#titulo2').text('Consecutivo Recepcion: ' + consecutivoRecepcion + ' Proveedor: ' + proveedor);

			$('#itemE').css('display', 'none');
			$('#baseE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#pollosxcanastillaE').css('display', 'none');
			$('#pesoE').css('display', 'none');

			$('#pesocanastilla').val(pc);

			$('#btnNuevoCriterio').css('display', 'none');
			$('#btnEditarCriterio').css('display', 'none');

			$("#pesoDiv").removeClass("col-md-3");
			$("#temperaturaDiv").removeClass("col-md-3");
			$("#pesoDiv").addClass("col-md-4");
			$("#temperaturaDiv").addClass("col-md-4");

			if ((status == 1) || (tipo == 0)) {
				$('#btnNuevoCriterio').css('display', 'initial');
				$('#btnEditarCriterio').css('display', 'none');
			} else {
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
			}
			$('#valorCriterio').val('');
			$('#pollosxcanastilla').val('');
			$('#base').val('');
			$('#temperatura').val('');
			$('#cajas').val('');
			$('#peso').val('');
			$("#observacionPollo").val("");
			$("#foto").val("");
			$("#fotoForm").val("");
			$("#borrarfoto").val("");
		}

		function buscarItems(id, tipo, totalCambios) {
			/* $('#itemE').css('display', 'none');
			$('#loteItemE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#pesoE').css('display', 'none'); */
			$('#id').val(id);
			$('#tipo').val('tipo');
			const datosItems = {
				id: id,
				tipo: 'pollo',
			};
			console.log('datos items: ', datosItems);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					proveedorP: datosItems
				},
				success: function(data) {
					$("#valorCriterio").empty();
					$("#valorCriterio").append("<option value=''>Seleccione un item</option>");
					/* for (i = 0; i < data.length; i++) {
						$('#datalistValorCriterio').append("<option data-value='" + data[i]['item'] + "' value='" + data[i]['descripcion'] + "'>" + data[i]['codigo'] + "</option>");
					} */
					if (tipo == 'BLANCO') {
						$("#valorCriterio").append("<option value='059759'>POLLO BLANCO</option>");
						$("#divPollos").show();
					} else if (tipo == 'CAMPO') {
						$("#valorCriterio").append("<option value='059755'>POLLO CAMPO</option>");
						$("#divPollos").show();
					} else if (tipo == "ASADERO") {
						$("#valorCriterio").append("<option value='074567'>POLLO BLANCO - ASADERO</option>");
						$("#divPollos").show();
					} else if (tipo == "BLANCO_DESPRESE") {
						$("#valorCriterio").append("<option value='059762'>ALAS BLANCA MERCAMIO MARINADAS</option>");
						$("#valorCriterio").append("<option value='059760'>PECHUGA BLANCA MERCAMIO MARINADA</option>");
						$("#valorCriterio").append("<option value='059761'>PERNIL BLANCO MERCAMIO MARINADO</option>");
						$("#divPollos").hide();
					} else {
						$("#valorCriterio").append("<option value='059758'>ALAS CAMPO MERCAMIO MARINADAS</option>");
						$("#valorCriterio").append("<option value='059756'>PECHUGA CAMPO MERCAMIO MARINADA</option>");
						$("#valorCriterio").append("<option value='059757'>PERNIL CAMPO MERCAMIO MARINADO</option>");
						$("#divPollos").hide();
					}
					//$('#valorCriterio').val('');
				}
			});

			$('#jtableCriterio').dataTable({
				"paging": true,
				"lengthChange": true,
				"searching": true,
				"info": true,
				"autoWidth": false,
				"processing": true,
				"serverSide": true,
				"bDestroy": true,
				"order": [
					[0, 'desc']
				],
				"ordering": totalCambios > 0 && <?= $_SESSION["tipo"] ?> == 0 && <?= $_SESSION["registrosCambios"] ?> == 1 ? false : true,
				"columnDefs": [{
					"orderable": false,
					"targets": [8]
				}],
				"ajax": {
					url: "tablas/tablaCriteriosP2.php",
					type: "post",
					data: {
						proveedor: id
					}
				},
				"language": {
					"searchPlaceholder": "Ingrese caracter",
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
					},
				},
				"dom": '<"row"<"col-md-6"l><"col-md-6"f>><"row"<"col-md-12"tr>><"row"<"col-md-6"i><"col-md-6"p>>',
			});

			// Estilo personalizado para alinear y espaciar los botones de paginación
			$('#jtableCriterio_paginate').addClass('text-md-right mt-3').css('float', 'center');
			$('#jtableCriterio_paginate .paginate_button').addClass('ml-1');
		};

		$('#btnCancelarEdicion').click(function() {
			$('#btnNuevoCriterio').css('display', 'initial');
			$('#btnEditarCriterio').css('display', 'none');
			$('#btnCancelarEdicion').css('display', 'none');
			$('#registrosPesos').css('display', '');
			$('#edicionPesos').css('display', 'none');
		});

		/* $('#btnCancelarEdicionCerdo').click(function() {
			$('#turno').prop('disabled', false);
			$('#btnNuevoCriterioCerdo').css('display', 'initial');
			$('#btnEditarCriterioCerdo').css('display', 'none');
			$('#btnCancelarEdicionCerdo').css('display', 'none');
			$('#registrosPesosCerdo').css('display', '');
			$('#edicionPesosCerdo').css('display', 'none');

			$('#turno').val('');
			$('#peso').val('');
			$('#temperaturap').val('');
		}); */

		$('#btnCerrarCriterios').click(function() {
			$('#turno').prop('disabled', false);
			$('#btnNuevoCriterioCerdo').css('display', 'initial');
			$('#btnEditarCriterioCerdo').css('display', 'none');
			$('#btnCancelarEdicionCerdo').css('display', 'none');
			$('#registrosPesosCerdo').css('display', '');
			$('#edicionPesosCerdo').css('display', 'none');

			$('#turno').val('');
			$('#peso').val('');
			$('#temperaturap').val('');
		});

		$('#btnNuevoCriterio').click(function() {
			console.log('item: ', $('#valorCriterio').val());
			let producto;
			let unidades;
			let cajas;

			cajas = $('#cajas').val();
			producto = $('#valorCriterio').val();
			pollosxcanastilla = $('#pollosxcanastilla').val();
			
			unidades = cajas * pollosxcanastilla;

			validacionesCri = validacionesC();
			if (validacionesCri == "") {
				// Bloquear el botón
				$('#btnNuevoCriterio').prop('disabled', true);
				
				const datos = {
					item: $('#valorCriterio').val(),
					temperatura: $('#temperatura').val(),
					unidades: unidades,
					cajas: $('#cajas').val(),
					base: $('#base').val(),
					peso: $('#peso').val(),
					proveedor: $('#id').val(),
					pesocanastilla: $('#pesocanastilla').val(),
					observacionPollo: $("#observacionPollo").val(),
					fotoForm: $("#fotoForm").val()
				};
				console.log('datos a enviar: ', datos);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosItemRecepcionPollo: datos
					},
					success: function(data) {
						//$('#valorCriterio').val('');
						$('#temperatura').val('');
						$('#unidades').val('');
						$('#cajas').val('');
						$('#base').val('');
						$('#peso').val('');
						$("#observacionPollo").val("");
						$("#foto").val("");
						$("#fotoForm").val("");
						$('#jtableCriterio').DataTable().ajax.reload();
						$('#jtable').DataTable().ajax.reload();
						//buscarItems($('#id').val(), $('#tipo').val());
						Swal.fire({
							title: 'Item registrado satisfactoriamente',
							text: '',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});
						bloquearRecepcion();
						// Desbloquear el botón después del éxito
						$('#btnNuevoCriterio').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#btnNuevoCriterio').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema al guardar el item. Inténtelo de nuevo.',
							icon: 'error',
							timer: 2000,
							showConfirmButton: false
						});
					}
				});
			}
		});

		$('#btnNuevoCriterioCerdo').click(function() {
			validacionesCri = validacionesC('CERDO');
			if (validacionesCri == "") {
				const datosPesoCerdo = {
					turno: $('#turno').val(),
					parte: $('#parte').val(),
					peso: $('#peso').val(),
					observacionPeso: $('#observacionPeso').val(),
					foto: $('#foto').val(),
					temperaturap: $('#temperaturap').val(),
					recepcion: $('#id').val()
				};
				console.log('datos a registrar: ', datosPesoCerdo);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosPesoCerdo: datosPesoCerdo
					},
					success: function(data) {
						console.log('respuesta:', data);
						if (data.status === 'success') {
							$('#valorCriterio').val('');
							$('#turno').val('');
							$('#parte').val('');
							$('#peso').val('');
							$('#temperaturap').val('');
							$('#jtableCriterio').DataTable().ajax.reload();
							$('#jtable').DataTable().ajax.reload();
							Swal.fire({
								title: 'Peso registrado',
								text: data.message,
								icon: 'success',
								timer: 1000,
								showConfirmButton: false
							});
							bloquearRecepcion();
						} else if (data.status === 'warning') {
							Swal.fire({
								title: 'Advertencia',
								text: data.message,
								icon: 'warning',
								confirmButtonText: 'OK'
							});
						} else if (data.status === 'error') {
							Swal.fire({
								title: 'Error',
								text: data.message,
								icon: 'error',
								confirmButtonText: 'OK'
							});
						} else {
							Swal.fire({
								title: 'Error',
								text: 'Respuesta del servidor no válida',
								icon: 'error',
								confirmButtonText: 'OK'
							});
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error:', textStatus, errorThrown);

						Swal.fire({
							title: 'Error',
							text: 'Ocurrió un error inesperado: ' + textStatus,
							icon: 'error',
							confirmButtonText: 'OK'
						});
					}
				});
			}
		});

		$('#crearProveedor').click(function() {
			var today = new Date();
			var day = String(today.getDate()).padStart(2, '0');
			var month = String(today.getMonth() + 1).padStart(2, '0');
			var year = today.getFullYear();
			var formattedDate = year + '-' + month + '-' + day;
			let guardarTiempoRecepcionPollo = 1;
			$.ajax({
				type: 'POST',
				url: 'controlador/controlador.php',
				data: {
					guardarTiempoRecepcionPollo
				},
				success: function(data) {
					console.log(data);
				}
			});
			$('#fecharec').val(formattedDate);
			$('#fechasac').val('');
			$('#tipo').val('');
			$('#canales').val('');
			$("#recibo").val("");
			$("#lotePlanta").val("");
			$("#lotePlanta").attr("disabled", true);
			$("#AbrirModalLotes").css("display", "none");
			$('#responsable').val("<?php echo $_SESSION['usuario']; ?>");
			$('#beneficio').val('');
			$("#fechaben").val("");
			$('#destino').val('');
			$('#conductor').val('');
			$('#placa').val('');
			$('#guiat').val('');
			$('#certificadoc').val('');
			$('#cph1').val('');
			$('#cph1').prop('checked', false);
			$('#cph2').val('');
			$('#cph2').prop('checked', false);
			$('#cph3').val('');
			$('#cph3').prop('checked', false);
			$('#cph4').val('');
			$('#cph4').prop('checked', false);
			$('#cph5').val('');
			$('#cph5').prop('checked', false);
			$('#chv1').val('');
			$('#chv1').prop('checked', false);
			$('#chv2').val('');
			$('#chv2').prop('checked', false);
			$('#chv3').val('');
			$('#chv3').prop('checked', false);
			$('#chv4').val('');
			$('#chv4').prop('checked', false);
			$('#chv5').val('');
			$('#chv5').prop('checked', false);
			$('#chv6').val('');
			$('#chv6').prop('checked', false);
			$('#ccoh1').val('');
			$('#ccoh1').prop('checked', false);
			$('#ccoh2').val('');
			$('#ccoh2').prop('checked', false);
			$('#ccoh3').val('');
			$('#ccoh3').prop('checked', false);
			$('#ccoh4').val('');
			$('#ccoh4').prop('checked', false);
			$('#ccoh5').val('');
			$('#ccoh5').prop('checked', false);
			$('#ccoh6').val('');
			$('#ccoh6').prop('checked', false);
			$('#ccoh7').val('');
			$('#ccoh7').prop('checked', false);
			$('#ccoh8').val('');
			$('#ccoh8').prop('checked', false);
			$('#ccoh9').val('');
			$('#ccoh9').prop('checked', false);
			$('#ccoh10').val('');
			$('#ccoh10').prop('checked', false);
			$('#despacho').val('');
			$('#lotep').val('');
			$('#observaciones').val('');

			$('#remision').css('border', '');
			$('#destino').css('border', '');
			$('#canales').css('border', '');
			$("#reciboE").css("display", "none");
			$('#ica').css('border', '');
			$('#certificadoc').css('border', '');
			

			$('#id_guia').val('');
			$('#titulo').text('Registro de Recepción').removeClass("me-5");
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
		});

		$('#abrirSegundaModal').click(function() {

			$('#cedula').val('');
			$('#nombresConductor').val('');

			$('#cedulaE').css('display', 'none');
			$('#nombresConductorE').css('display', 'none');
		});

		$('#abrirTerceraModal').click(function() {
			$('#placav').val('');
			$('#placavE').css('display', 'none');
		});

		$('#btnNuevoProveedor').click(function() {
			validacionesF = validaciones();

			console.log('validaciones: ', validacionesF);
			if (validacionesF == "") {
				// Bloquear el botón
				$('#btnNuevoProveedor').prop('disabled', true);
				
				const datosRecepcionPollo = {
					fecharec: $('#fecharec').val(),
					fechasac: $('#fechasac').val(),
					especie: $('#especie').val(),
					tipo: $('#tipo').val(),
					canales: $('#canales').val(),
					despacho: $('#despacho').val(),
					responsable: $('#responsable').val(),
					beneficio: $('#beneficio').val(),
					destino: $('#destino').val(),
					conductor: $('#conductor').val(),
					placa: $('#placa').val(),
					guiat: $('#guiat').val(),
					chv1: $('#chv1').val(),
					cph1: $('#cph1').is(':checked') ? 1 : 0,
					cph2: $('#cph2').is(':checked') ? 1 : 0,
					cph3: $('#cph3').is(':checked') ? 1 : 0,
					cph4: $('#cph4').is(':checked') ? 1 : 0,
					cph5: $('#cph5').is(':checked') ? 1 : 0,
					chv2: $('#chv2').is(':checked') ? 1 : 0,
					chv3: $('#chv3').is(':checked') ? 1 : 0,
					chv4: $('#chv4').is(':checked') ? 1 : 0,
					chv5: $('#chv5').is(':checked') ? 1 : 0,
					chv6: $('#chv6').is(':checked') ? 1 : 0,
					ccoh1: $('#ccoh1').is(':checked') ? 1 : 0,
					ccoh2: $('#ccoh2').is(':checked') ? 1 : 0,
					ccoh3: $('#ccoh3').is(':checked') ? 1 : 0,
					ccoh4: $('#ccoh4').is(':checked') ? 1 : 0,
					ccoh5: $('#ccoh5').is(':checked') ? 1 : 0,
					observaciones: $('#observaciones').val(),
					lotePlanta: $('#lotePlanta').val(),
					fechaBeneficio: $('#fechaben').val(),
					certificadoc: $('#certificadoc').val()
				};
				console.log('datos a guardar recepcion pollo:', datosRecepcionPollo);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosRecepcionPollo: datosRecepcionPollo
					},
					success: function(data) {
						$('#jtable').DataTable().ajax.reload();
						bloquearRecepcion();
						$('#fecharec').val('');
						$('#fechasac').val('');
						$('#canales').val('');
						$('#responsable').val('');
						$('#beneficio').val('');
						$('#destino').val('');
						$('#conductor').val('');
						$('#placa').val('');
						$('#cph1').val('');
						$('#cph2').val('');
						$('#cph3').val('');
						$('#cph4').val('');
						$('#cph5').val('');
						$('#chv1').val('');
						$('#chv2').val('');
						$('#chv3').val('');
						$('#chv4').val('');
						$('#chv5').val('');
						$('#chv6').val('');
						$('#ccoh1').val('');
						$('#ccoh2').val('');
						$('#ccoh3').val('');
						$('#ccoh4').val('');
						$('#ccoh5').val('');
						$('#despacho').val('');
						$('#observaciones').val('');

						$('#destino').css('border', '');
						$('#canales').css('border', '');

						$("#modalNuevoProveedor").modal('hide');
						Swal.fire({
							title: 'Nueva recepción registrada satisfactoriamente',
							text: '',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});
						// Desbloquear el botón después del éxito
						$('#btnNuevoProveedor').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#btnNuevoProveedor').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
					}
				});
			}
		});

		$('#btnNuevoConductor').click(function() {
			validacionesCo = validacionesCon();

			if (validacionesCo == "" && $("#cedulaExiste").val() == "") {
				// Bloquear el botón
				$('#btnNuevoConductor').prop('disabled', true);
				
				const datosConductorPollo = {
					cedula: $('#cedula').val(),
					nombresConductor: $('#nombresConductor').val()
				};
				console.log('datos a guardar:', datosConductorPollo);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosConductorPollo: datosConductorPollo
					},
					success: function(data) {
						console.log('data: ', data);
						$("#modalNuevoConductor").modal('hide');
						Swal.fire({
							title: 'Nuevo conductor registrado satisfactoriamente',
							text: '',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});

						let selectConductor = $('#conductor');
						selectConductor.empty();
						selectConductor.append('<option value="">Seleccione el Conductor de Recepcion</option>');

						let listaConductores = JSON.parse(data);

						listaConductores.forEach(function(perm) {
							selectConductor.append('<option value="' + perm.cedula + '">' + perm.cedula + " - " + perm.nombres + '</option>');
						});
						$('#conductor').val($('#cedula').val());
						// Desbloquear el botón después del éxito
						$('#btnNuevoConductor').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#btnNuevoConductor').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema al guardar el conductor. Inténtelo de nuevo.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
					}
				});
			}
		});

		$('#btnNuevaPlaca').click(function() {
			validacionesPl = validacionesPla();

			if (validacionesPl == "") {
				// Bloquear el botón
				$('#btnNuevaPlaca').prop('disabled', true);
				
				console.log('validaciones: ', validacionesPl);
				const datosPlacaRPollo = {
					placa: $('#placav').val()
				};
				console.log('datos a guardar:', datosPlacaRPollo);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosPlacaRPollo: datosPlacaRPollo
					},

					success: function(data) {
						console.log('data: ', data);

						if (data.length < 5) {
							// Desbloquear el botón si la placa ya existe
							$('#btnNuevaPlaca').prop('disabled', false);
							$('#placavE').css('display', 'block').text('La placa ya esta registrada en el sistema');
						} else {
							$('#placavE').css('display', 'none');
							$("#modalNuevaPlaca").modal('hide');
							Swal.fire({
								title: 'Nueva placa registrada satisfactoriamente',
								text: '',
								icon: 'success',
								timer: 1000,
								showConfirmButton: false
							});

							let selectPlaca = $('#placa');
							selectPlaca.empty();
							selectPlaca.append('<option value="">Seleccione la Placa del Vehiculo</option>');

							let listaPlaca = JSON.parse(data);

							listaPlaca.forEach(function(perm) {
								selectPlaca.append('<option value="' + perm.placa + '">' + perm.placa + '</option>');
							});
							$('#placa').val($('#placav').val());
							// Desbloquear el botón después del éxito
							$('#btnNuevaPlaca').prop('disabled', false);
						}
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#btnNuevaPlaca').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema al guardar la placa. Inténtelo de nuevo.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
					}
				});
			}
		});

		function buscarGuia(idRecepcionPollo) {
			$('#id_guia').val(idRecepcionPollo);
			$('#titulo').text('Editar Guia Recepcion' + " #" + idRecepcionPollo).addClass("me-5");
			$("#recibo").val("");
			$("#AbrirModalLotes").css("display", "block");
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			if ("<?= $_SESSION["usuario"] ?>" == "ADMINISTRADOR") {
				$("#lotePlanta").removeAttr("disabled");
			}else {
				$("#lotePlanta").attr("disabled", true);
			}
			console.log('consulta: ', idRecepcionPollo);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idRecepcionPollo: idRecepcionPollo
				},
				success: function(data) {
					console.log('datos: ', data);
					$('#fecharec').val(data[0].fecharec);
					$('#fechasac').val(data[0].fechasac);
					$('#especie').val(data[0].especie);
					$('#tipo').val(data[0].tipo);
					$('#canales').val(data[0].canales);
					$('#despacho').val(data[0].recibo);
					$('#responsable').val(data[0].responsable);
					$('#beneficio').val(data[0].beneficio);
					$('#destino').val(data[0].destino);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#guiat').val(data[0].guiat);
					$('#certificadoc').val(data[0].certificadoc);
					$('#destino').css('border', '');
					$('#canales').css('border', '');
					$('#certificadoc').css('border', '');
					$("#fechaben").val(data[0].fecha_beneficio);

					$('#horaInicialGuia').text('HI: ' + data[0].hora_inicial);

					if (data[0].cph1 == 1) {
						$('#cph1').prop('checked', true);
					} else {
						$('#cph1').prop('checked', false);
					}

					if (data[0].cph2 == 1) {
						$('#cph2').prop('checked', true);
					} else {
						$('#cph2').prop('checked', false);
					}

					if (data[0].cph3 == 1) {
						$('#cph3').prop('checked', true);
					} else {
						$('#cph3').prop('checked', false);
					}

					if (data[0].cph4 == 1) {
						$('#cph4').prop('checked', true);
					} else {
						$('#cph4').prop('checked', false);
					}

					if (data[0].cph5 == 1) {
						$('#cph5').prop('checked', true);
					} else {
						$('#cph5').prop('checked', false);
					}

					$('#chv1').val(data[0].chv1);

					if (data[0].chv2 == 1) {
						$('#chv2').prop('checked', true);
					} else {
						$('#chv2').prop('checked', false);
					}

					if (data[0].chv3 == 1) {
						$('#chv3').prop('checked', true);
					} else {
						$('#chv3').prop('checked', false);
					}

					if (data[0].chv4 == 1) {
						$('#chv4').prop('checked', true);
					} else {
						$('#chv4').prop('checked', false);
					}

					if (data[0].chv5 == 1) {
						$('#chv5').prop('checked', true);
					} else {
						$('#chv5').prop('checked', false);
					}

					if (data[0].chv6 == 1) {
						$('#chv6').prop('checked', true);
					} else {
						$('#chv6').prop('checked', false);
					}

					if (data[0].ccoh1 == 1) {
						$('#ccoh1').prop('checked', true);
					} else {
						$('#ccoh1').prop('checked', false);
					}

					if (data[0].ccoh2 == 1) {
						$('#ccoh2').prop('checked', true);
					} else {
						$('#ccoh2').prop('checked', false);
					}

					if (data[0].ccoh3 == 1) {
						$('#ccoh3').prop('checked', true);
					} else {
						$('#ccoh3').prop('checked', false);
					}

					if (data[0].ccoh4 == 1) {
						$('#ccoh4').prop('checked', true);
					} else {
						$('#ccoh4').prop('checked', false);
					}

					if (data[0].ccoh5 == 1) {
						$('#ccoh5').prop('checked', true);
					} else {
						$('#ccoh5').prop('checked', false);
					}

					if (data[0].ccoh6 == 1) {
						$('#ccoh6').prop('checked', true);
					} else {
						$('#ccoh6').prop('checked', false);
					}

					if (data[0].ccoh7 == 1) {
						$('#ccoh7').prop('checked', true);
					} else {
						$('#ccoh7').prop('checked', false);
					}

					if (data[0].ccoh8 == 1) {
						$('#ccoh8').prop('checked', true);
					} else {
						$('#ccoh8').prop('checked', false);
					}

					if (data[0].ccoh9 == 1) {
						$('#ccoh9').prop('checked', true);
					} else {
						$('#ccoh9').prop('checked', false);
					}

					if (data[0].ccoh10 == 1) {
						$('#ccoh10').prop('checked', true);
					} else {
						$('#ccoh10').prop('checked', false);
					}

					$('#observaciones').val(data[0].observaciones);
					$("#lotePlanta").val(data[0].lote_planta);
					var recibo = data[0].lote_planta.charAt(data[0].lote_planta.length - 1);
					$("#recibo").val(recibo);
				}
			});
		}

		function copiarDatos() {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					copiarDatos: 1
				},
				success: function(data) {
					console.log('id_recepcion: ', data[0].id_recepcion);
					console.log('responsable: ', data[0].responsable, ' - sesion:', <?php echo $usuarioresponsable; ?>);
					$('#fechasac').val(data[0].fechasac);
					$('#tipo').val(data[0].tipo);

					$('#despacho').val(data[0].recibo);
					$('#responsable').val(data[0].responsable);
					$('#beneficio').val(data[0].beneficio);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#guiat').val(data[0].guiat);

					$('#destino').css('border', '2px solid red');
					$('#canales').css('border', '2px solid red');
					$('#certificadoc').css('border', '2px solid red');

					if (data[0].cph1 == 1) {
						$('#cph1').prop('checked', true);
					} else {
						$('#cph1').prop('checked', false);
					}

					if (data[0].cph2 == 1) {
						$('#cph2').prop('checked', true);
					} else {
						$('#cph2').prop('checked', false);
					}

					if (data[0].cph3 == 1) {
						$('#cph3').prop('checked', true);
					} else {
						$('#cph3').prop('checked', false);
					}

					if (data[0].cph4 == 1) {
						$('#cph4').prop('checked', true);
					} else {
						$('#cph4').prop('checked', false);
					}

					if (data[0].cph5 == 1) {
						$('#cph5').prop('checked', true);
					} else {
						$('#cph5').prop('checked', false);
					}

					$('#chv1').val(data[0].chv1);

					if (data[0].chv2 == 1) {
						$('#chv2').prop('checked', true);
					} else {
						$('#chv2').prop('checked', false);
					}

					if (data[0].chv3 == 1) {
						$('#chv3').prop('checked', true);
					} else {
						$('#chv3').prop('checked', false);
					}

					if (data[0].chv4 == 1) {
						$('#chv4').prop('checked', true);
					} else {
						$('#chv4').prop('checked', false);
					}

					/* if(data[0].ccoh1 == 1){$('#ccoh1').prop('checked', true);}
					else{$('#ccoh1').prop('checked', false);}

					if(data[0].ccoh2 == 1){$('#ccoh2').prop('checked', true);}
					else{$('#ccoh2').prop('checked', false);}

					if(data[0].ccoh3 == 1){$('#ccoh3').prop('checked', true);}
					else{$('#ccoh3').prop('checked', false);}

					if(data[0].ccoh4 == 1){$('#ccoh4').prop('checked', true);}
					else{$('#ccoh4').prop('checked', false);}

					if(data[0].ccoh5 == 1){$('#ccoh5').prop('checked', true);}
					else{$('#ccoh5').prop('checked', false);}

					if(data[0].ccoh6 == 1){$('#ccoh6').prop('checked', true);}
					else{$('#ccoh6').prop('checked', false);}

					if(data[0].ccoh7 == 1){$('#ccoh7').prop('checked', true);}
					else{$('#ccoh7').prop('checked', false);}

					if(data[0].ccoh8 == 1){$('#ccoh8').prop('checked', true);}
					else{$('#ccoh8').prop('checked', false);}

					if(data[0].ccoh9 == 1){$('#ccoh9').prop('checked', true);}
					else{$('#ccoh9').prop('checked', false);}

					if(data[0].ccoh10 == 1){$('#ccoh10').prop('checked', true);}
					else{$('#ccoh10').prop('checked', false);} */

					$('#observaciones').val(data[0].observaciones);
				}
			});
		}

		function buscarCriterio(idcriterio) {
			$('#id_criterio').val(idcriterio);
			$('#titulo').text('Editar Registro');
			$('#btnNuevoCriterio').css('display', 'none');
			$('#btnEditarCriterio').css('display', 'initial');
			console.log('consulta: ', idcriterio);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idcriterioRecepcionPollo: idcriterio
				},
				success: function(data) {
					console.log('datos recibidos: ', data);
					$('#valorCriterio').val(data[0].item);
					$('#descripcionCriterio').val(data[0].descripcion);
					$('#temperatura').val(data[0].temperatura);
					$('#unidades').val(data[0].unidades);
					$('#cajas').val(data[0].cajas);
					$('#peso').val(data[0].peso);
					$('#base').val(data[0].base);
					$('#pollosxcanastilla').val(data[0].unidades/data[0].cajas);
					$("#observacionPollo").val(data[0].observacion);
					$("#borrarfoto").val(data[0].foto);
					
				}
			});
		}

		function buscarCriterioCerdo(idcriterio) {
			$('#id_criterio').val(idcriterio);
			$('#titulo').text('Editar Registro');
			$('#btnNuevoCriterioCerdo').css('display', 'none');
			$('#btnEditarCriterioCerdo').css('display', 'initial');
			$('#btnCancelarEdicionCerdo').css('display', 'initial');
			console.log('consulta: ', idcriterio);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idPeso: idcriterio
				},
				success: function(data) {
					console.log('datos recibidos: ', data);
					$('#id_recepcion_pesos').val(data[0].id_recepcion_pesos);
					$('#turno').val(data[0].turno);
					$('#turno').prop('disabled', true);
					$('#peso').val(data[0].estomago1);
					$('#temperaturap').val(data[0].temperatura);
				}
			});
		}

		function bloquearEdicion(idBloquear) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idBloquearRPollo: idBloquear
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
				}
			});
		}

		function desbloquearEdicion(idDesbloquear) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idDesbloquearR: idDesbloquear
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
				}
			});
		}

		$('#btnEditProveedor').click(function() {
			validacionesF = validaciones();
			if (validacionesF == "") {
				// Bloquear el botón
				$('#btnEditProveedor').prop('disabled', true);
				
				console.log('validaciones: ', validacionesF);
				datosEditPollo = {
					"id_guia": $('#id_guia').val(),
					"fecharec": $('#fecharec').val(),
					"fechasac": $('#fechasac').val(),
					"especie": $('#especie').val(),
					"tipo": $('#tipo').val(),
					"remision": $('#remision').val(),
					"canales": $('#canales').val(),
					"despacho": $('#despacho').val(),
					"responsable": $('#responsable').val(),
					"beneficio": $('#beneficio').val(),
					"destino": $('#destino').val(),
					"conductor": $('#conductor').val(),
					"placa": $('#placa').val(),
					"guiat": $('#guiat').val(),
					"certificadoc": $('#certificadoc').val(),
					"chv1": $('#chv1').val(),
					"cph1": $('#cph1').is(':checked') ? 1 : 0,
					"cph2": $('#cph2').is(':checked') ? 1 : 0,
					"cph3": $('#cph3').is(':checked') ? 1 : 0,
					"cph4": $('#cph4').is(':checked') ? 1 : 0,
					"cph5": $('#cph5').is(':checked') ? 1 : 0,
					"chv2": $('#chv2').is(':checked') ? 1 : 0,
					"chv3": $('#chv3').is(':checked') ? 1 : 0,
					"chv4": $('#chv4').is(':checked') ? 1 : 0,
					"chv5": $('#chv5').is(':checked') ? 1 : 0,
					"chv6": $('#chv6').is(':checked') ? 1 : 0,
					"ccoh1": $('#ccoh1').is(':checked') ? 1 : 0,
					"ccoh2": $('#ccoh2').is(':checked') ? 1 : 0,
					"ccoh3": $('#ccoh3').is(':checked') ? 1 : 0,
					"ccoh4": $('#ccoh4').is(':checked') ? 1 : 0,
					"ccoh5": $('#ccoh5').is(':checked') ? 1 : 0,
					"ccoh6": $('#ccoh6').is(':checked') ? 1 : 0,
					"ccoh7": $('#ccoh7').is(':checked') ? 1 : 0,
					"ccoh8": $('#ccoh8').is(':checked') ? 1 : 0,
					"ccoh9": $('#ccoh9').is(':checked') ? 1 : 0,
					"ccoh10": $('#ccoh10').is(':checked') ? 1 : 0,
					"observaciones": $('#observaciones').val(),
					"lotePlanta" : $('#lotePlanta').val(),
					"fechaBeneficio": $('#fechaben').val(),
				};
				console.log('datos a actualizar:', datosEditPollo);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosEditPollo: datosEditPollo
					},
					success: function(data) {
						$('#jtable').DataTable().ajax.reload();
						$('#fecharec').val('');
						$('#fechasac').val('');
						$('#tipo').val('');
						$('#remision').val('');
						$('#canales').val('');
						$('#lotePlanta').val('');
						$('#responsable').val('');
						$('#beneficio').val('');
						$('#destino').val('');
						$('#conductor').val('');
						$('#placa').val('');
						$('#guiat').val('');
						$('#certificadoc').val('');
						$('#cph1').val('');
						$('#cph2').val('');
						$('#cph3').val('');
						$('#cph4').val('');
						$('#cph5').val('');
						$('#chv1').val('');
						$('#chv2').val('');
						$('#chv3').val('');
						$('#chv4').val('');
						$('#ccoh1').val('');
						$('#ccoh2').val('');
						$('#ccoh3').val('');
						$('#ccoh4').val('');
						$('#ccoh5').val('');
						$('#ccoh6').val('');
						$('#ccoh7').val('');
						$('#ccoh8').val('');
						$('#ccoh9').val('');
						$('#despacho').val('');
						$('#lotep').val('');
						$('#observaciones').val('');
						$("#modalNuevoProveedor").modal('hide');
						Swal.fire({
							title: 'Recepción editada satisfactoriamente',
							text: '',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});
						// Desbloquear el botón después del éxito
						$('#btnEditProveedor').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#btnEditProveedor').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema al editar la recepción. Inténtelo de nuevo.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
					}
				});
			}
		});

		function validaciones() {
			$('#fecharecE').css('display', 'none');
			$('#fechasacE').css('display', 'none');
			$('#especieE').css('display', 'none');
			$('#tipoE').css('display', 'none');
			$('#reciboE').css('display', 'none');
			$('#lotePlantaE').css('display', 'none');
			$('#despachoE').css('display', 'none');
			$('#canalesE').css('display', 'none');
			$('#responsableE').css('display', 'none');
			$('#beneficioE').css('display', 'none');
			$('#destinoE').css('display', 'none');
			$('#conductorE').css('display', 'none');
			$('#placaE').css('display', 'none');
			$('#icaE').css('display', 'none');
			$('#guiatE').css('display', 'none');
			$('#certificadocE').css('display', 'none');
			$('#chv1E').css('display', 'none');

			if ($("#fecharec").val() == null || $("#fecharec").val() == "") {
				$('#fecharecE').css('display', 'block');
				return 'R1';
			}
			if ($("#fechasac").val() == null || $("#fechasac").val() == "") {
				$('#fechasacE').css('display', 'block');
				return 'R2';
			}

			let fecharec = new Date($("#fecharec").val());
			let fechasac = new Date($("#fechasac").val());

			if (fechasac <= fecharec) {
				$('#fechasacE').css('display', 'block').text('La fecha de vencimiento no puede ser menor o igual que la fecha de recepción');
				return 'R3';
			}
			if ($("#especie").val() == null || $("#especie").val() == "") {
				$('#especieE').css('display', 'block');
				return 'R4';
			}
			if ($("#tipo").val() == null || $("#tipo").val() == "") {
				$('#tipoE').css('display', 'block');
				return 'R5';
			}
			if ($("#canales").val() == null || $("#canales").val() == "") {
				$('#canalesE').css('display', 'block');
				return 'R7';
			}

			if ($("#recibo").val() == null || $("#recibo").val() == "") {
				$('#reciboE').css('display', 'block');
				return 'R7';
			}

			if ($("#fechaben").val() == null || $("#fechaben").val() == "") {
				$("#fechabenE").css("display", "block");
			}

			if ($("#lotePlanta").val() == null || $("#lotePlanta").val() == "") {
				$('#lotePlantaE').css('display', 'block');
				return 'R7';
			}
			
			if ($("#conductor").val() == null || $("#conductor").val() == "") {
				$('#conductorE').css('display', 'block');
				return 'R10';
			}
			if ($("#placa").val() == null || $("#placa").val() == "") {
				$('#placaE').css('display', 'block');
				return 'R11';
			}
			if ($("#beneficio").val() == null || $("#beneficio").val() == "") {
				$('#beneficioE').css('display', 'block');
				return 'R6';
			}
			if ($("#destino").val() == null || $("#destino").val() == "") {
				$('#destinoE').css('display', 'block');
				return 'R6';
			}
			if ($("#guiat").val() == null || $("#guiat").val() == "") {
				$('#guiatE').css('display', 'block');
				return 'R';
			}
			if ($("#certificadoc").val() == null || $("#certificadoc").val() == "") {
				$('#certificadocE').css('display', 'block');
				return 'R';
			}
			if ($("#responsable").val() == null || $("#responsable").val() == "") {
				$('#responsableE').css('display', 'block');
				return 'R9';
			}
			if ($("#chv1").val() == null || $("#chv1").val() == "") {
				$('#chv1E').css('display', 'block');
				return 'R12';
			}
			return "";
		}

		$('#btnEditarCriterio').click(function() {

			let producto;
			let unidades;
			let cajas;

			cajas = $('#cajas').val();
			producto = $('#valorCriterio').val();
			pollosxcanastilla = $('#pollosxcanastilla').val();
			unidades = cajas * pollosxcanastilla;

			validacionesCri = validacionesC();
			if (validacionesCri == "") {
				// Bloquear el botón
				$('#btnEditarCriterio').prop('disabled', true);
				
				infoCriEditPolloR = {
					"id_criterio": $('#id_criterio').val(),
					"item": $('#valorCriterio').val(),
					"temperatura": $('#temperatura').val(),
					"pollosxcanastilla": $('#pollosxcanastilla').val(),
					"cajas": $('#cajas').val(),
					"unidades": $('#pollosxcanastilla').val() * $('#cajas').val(),
					"peso": $('#peso').val(),
					"base": $('#base').val(),
					"pesocanastilla": $('#pesocanastilla').val(),
					"observacion": $('#observacionPollo').val(),
					"fotoForm": $("#fotoForm").val()

				};
				console.log('datos enviados:', infoCriEditPolloR);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoCriEditPolloR
					},
					success: function(res) {
						if (res.status == "success") {
							$('#btnNuevoCriterio').css('display', 'initial');
							$('#btnEditarCriterio').css('display', 'none');
							$('#valorCriterio').val('');
							$('#temperatura').val('');
							$('#unidades').val('');
							$('#pollosxcanastilla').val('');
							$('#cajas').val('');
							$('#peso').val('');
							$('#base').val('');
							$("#observacionPollo").val("");
							$("#foto").val("");
							$("#fotoForm").val("");
							$("#borrarfoto").val("");
							$('#jtableCriterio').DataTable().ajax.reload();
							$('#jtable').DataTable().ajax.reload();
							// Desbloquear el botón después del éxito
							$('#btnEditarCriterio').prop('disabled', false);
						} else {
							// Desbloquear el botón en caso de error en la respuesta
							$('#btnEditarCriterio').prop('disabled', false);
						}
						
						Swal.fire({
							icon: res.status,
							title: res.message,
							timer: 1500,
							showConfirmButton: false
						});
					},
					error: function(res) {
						// Desbloquear el botón en caso de error
						$('#btnEditarCriterio').prop('disabled', false);
						Swal.fire({
							icon: "error",
							title: "Error al modificar el item",
							timer: 1500,
							showConfirmButton: false
						});
					}
				});
			}
		});

		$("#cedula").on('change', function() {
			console.log('validando cedula');
			var cedulaPollo = $("#cedula").val();
			if (!($("#cedula").val() == null || $("#cedula").val() == "")) {
				console.log("Datos enviados a controlador:", cedula);
				$.ajax({
					type: 'POST',
					url: 'controlador/controlador.php',
					data: {
						cedulaPollo
					},
					success: function(data) {
						if (data.length < 5) {
							/* Swal.fire({
								title: 'Advertencia',
								text: 'La cedula ingresada ya esta registrada en el sistema',
								icon: 'warning',
								confirmButtonText: 'OK'
							}); */

							$('#cedulaExiste').val(cedula);
							$('#cedulaE').css('display', 'block').text('La cedula ya esta registrada en el sistema');
						} else {
							$('#cedulaExiste').val('');
							$('#cedulaE').css('display', 'none');
						}
					}
				});
			}
		});

		function validacionesC() {
			$('#itemE').css('display', 'none');
			$('#baseE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#pollosxcanastillaE').css('display', 'none');
			$('#pesoE').css('display', 'none');
			item = $('#valorCriterio').val();
			if ($("#valorCriterio").val() == null || $("#valorCriterio").val() == "") {
				$('#itemE').css('display', 'block');
				return 'R';
			}

			if ($("#cajas").val() == null || $("#cajas").val() == "") {
				$('#canastillasE').css('display', 'block');
				return 'R';
			} else if ($("#valorCriterio").val() == 'POLLO ENTERO APANADO') {
				var cajasVal = parseInt($("#cajas").val(), 10);
				if (cajasVal % 2 != 0) {
					$('#canastillasE').text('El valor de cajas de POLLO APANADO no puede ser impar').css('display', 'block');
					return 'R';
				}
			}

			if ($("#valorCriterio").val() == "074567" || $("#valorCriterio").val() == "059755" || $("#valorCriterio").val() == "059759") {
				if ($("#pollosxcanastilla").val() == "" || $("#pollosxcanastilla").val() == null) {
					$("#pollosxcanastillaE").css("display", "block");
					return 'R';
				}
			}
			
			if ($("#base").val() == null || $("#base").val() == "") {
				$('#baseE').css('display', 'block');
				return 'R';
			}
			if ($("#peso").val() == null || $("#peso").val() == "") {
				if ((item != '050514') && (item != '050515') && (item != '050516') && (item != '050517') && (item != '050513')) {
					$('#pesoE').css('display', 'block');
					return 'R';
				}
			}
			
			
			return "";
		}

		function validacionesCon() {

			$('#cedulaE').css('display', 'none');
			$('#nombresConductorE').css('display', 'none');

			if ($("#cedula").val() == null || $("#cedula").val() == "" || $("#cedulaExiste").val() != "") {
				$('#cedulaE').css('display', 'block');
				return 'R';
			}
			if ($("#nombresConductor").val() == null || $("#nombresConductor").val() == "") {
				$('#nombresConductorE').css('display', 'block');
				return 'R';
			}
			return "";
		}

		function validacionesPla() {

			$('#placavE').css('display', 'none');

			if ($("#placav").val() == null || $("#placav").val() == "") {
				$('#placavE').css('display', 'block');
				return 'R';
			}
			return "";
		}

		$('#btnImprimir').click(function(e) {
			e.preventDefault(); 

			const fechaInicial = $('#fechainicial').val();
			const fechaFinal = $('#fechafinal').val();

			let data = {};
			if (fechaInicial) data.fechainicial = fechaInicial;
			if (fechaFinal) data.fechafinal = fechaFinal;

			$.ajax({
				url: 'controlador/recepcionpollopdf.php',
				method: 'POST',
				data: data,
				success: function(response) {
					window.open('controlador/imprimiringresoPollopdf.php?' + $.param(data), '_blank');
				},
				error: function(error) {
					console.error("Error:", error);
					alert("Hubo un problema al generar el reporte.");
				}
			});
		});

		function mostrarObservacion(contenido) {
			console.log("Contenido recibido:", contenido);
			$('#modalNovedades .modal-body').text(contenido);

			const modalElement = $('#modalNovedades')[0];
			if (modalElement) {
				var modalNovedades = new bootstrap.Modal(modalElement, {
					backdrop: false
				});
				modalNovedades.show();
			} else {
				console.error("El elemento #modalNovedades no existe en el DOM.");
			}
		}

		function mostrarFoto(contenido) {
			console.log("Contenido recibido:", contenido);

			const imageHTML = `
				<div style="text-align: center;">
					<img src="controlador/recepcion pollo/${contenido}" alt="Foto" style="max-width: 100%; height: auto; border-radius: 8px;" />
				</div>
			`;

			$('#modalNovedades .modal-body').html(imageHTML);

			const modalElement = $('#modalNovedades')[0];
			if (modalElement) {
				const modalNovedades = new bootstrap.Modal(modalElement, {
					backdrop: false
				});
				modalNovedades.show();
			} else {
				console.error("El elemento #modalNovedades no existe en el DOM.");
			}
		}

		function generarCodigo() {
			var proveedor = $("#beneficio").val();
			if (proveedor != "" && $("#recibo").val() != "") {
				const especie = 3
				const fecha = <?= date("Y") ?>;
				const year = fecha.toString().slice(-2);
				let semana = <?= date("W") ?>;
				let dia = <?= date("w") ?>;

				datos = {
					proveedor: proveedor,
					fecha: "<?= date("Y-m-d") ?>"
				}

				$.ajax({
					type: "POST",
					url: "controlador/controlador.php",
					data: {
						generarCodigoLote: datos,
					},
					dataType: "json",
					success: function (data) {
						var codigo = data["proveedor"] + especie + year + semana + dia + $("#recibo").val();
						$("#lotePlanta").val(codigo);
					}
				});
			}else {
				$("#lotePlanta").val("");
			}
		}

		$("#beneficio, #recibo").change(generarCodigo);

		$("#AbrirModalLotes").click(function (e) { 
			e.preventDefault();
			$("#lote").val("");
			$("#loteE").css("display", "none");
			$("#modalLotes").modal("show");

			$('#TLotes').dataTable({
				"responsive": true,
				"processing": true,
				"serverSide": false,
				"bDestroy": true,
				"info": false,
				"paging": false,
				"searching": false,
				"order": [
					[0, 'desc']
				],
				"ajax": {
					url: "tablas/tablaGuiaLotes.php",
					type: "post",
					data: {
						guia: $("#id_guia").val()
					}
				},
				"language": {
					"searchPlaceholder": "Ingrese caracter",
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
					},
				},
			});
			$('#TLotes').css('width', '100%');
			$('#TLotes_filter input, #TLotes_length select').addClass('form-control');
		});

		function borrarLote(id) {
			Swal.fire({
				title: '¿Esta seguro que desea eliminar este lote?',
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
							deleteLote: id
						},
						dataType: "json",
						success: function (data) {
							$('#TLotes').DataTable().ajax.reload();
							$('#jtable').DataTable().ajax.reload();
							Swal.fire({
								title: 'Lote Eliminado correctamente',
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

		$("#GuardarLote").click(function (e) {
			e.preventDefault();
			if ($("#lote").val() != "") {
				// Bloquear el botón
				$('#GuardarLote').prop('disabled', true);
				
				datos = {
					lote: $("#lote").val(),
					idGuia: $("#id_guia").val()
				}

				$.ajax({
					type: "POST",
					url: "controlador/controlador.php",
					data: {
						guardarLote: datos
					},
					dataType: "json",
					success: function (data) {
						console.log(data);
						$('#TLotes').DataTable().ajax.reload();
						$('#jtable').DataTable().ajax.reload();
						$("#lote").val("");
						Swal.fire({
							title: 'Lote agregado correctamente',
							text: '',
							icon: 'success',
							timer: 1300, 
							showConfirmButton: false
						});
						// Desbloquear el botón después del éxito
						$('#GuardarLote').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						// Desbloquear el botón en caso de error
						$('#GuardarLote').prop('disabled', false);
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema al guardar el lote. Inténtelo de nuevo.',
							icon: 'error',
							timer: 2000,
							showConfirmButton: false
						});
						console.error('Error:', error);
					}
				});
			} else {
				$("#loteE").css("display", "block");
			}
		});

		function MostrarAlerta() {
			Swal.fire({
				title: '¡AVISO!',
				text: 'Ingresar los Lotes del Proveedor en la Guia.',
				icon: 'info',
				showConfirmButton: true,
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