<?php
session_start();
error_reporting(0);
$_SESSION['tipoR'] = 1;
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$listaResponsables = listaResponsables();
$listaConductores = listaConductores_recepcion();
$listaPlacas = listaPlacas_recepcion();
$listaOrigen = listaOrigen();
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
	</style>
</head>

<body>
	<?php include_once('menu.php'); ?>
	<?php include_once('menuizquierda.php'); ?>
	<main id="main" class="main">

		<div class="pagetitle">
			<h1>Recepción de canales bovinas / porcinas</h1>
		</div>

		<section class="section">
			<div class="row">
				<div class="col-lg-12">

					<div class="card">
						<div class="card-body">
							<h5 class="card-title">
								<center>
									<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
										title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA DE RECEPCIÓN</a>
								</center>
								<table id="jtable" class="table table-striped table-bordered table-hover datatable">
									<thead>
										<tr>
											<th>Consecutivo<br>Tipo</th>
											<th>Fecha<br>Remisión</th>
											<th>Planta Beneficio<br>Destino</th>
											<th>Canales<br>Lote</th>
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
</body>

<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

		<div class="modal-content">
			<div class="modal-header" style="display: block;text-align: center;">
				<label style="text-align: center;font-weight: bold;" id="titulo">LOTE DE RECEPCIÓN</label>
				<a class="float-end" style="color:#000;" onclick="copiarDatos()"><i class="bi bi-clipboard-plus fs-2"></i></a>
			</div>
			<div class="modal-body">
				<form method="POST" id="criteriosForm">
					<div class="row">
						<div class="col-lg-3">
							Fecha de Recepción<span class="text-danger">*</span>
							<div class="form-group label-floating" id="fechaExpDiv">
								<input type="date" class="form-control" autocomplete="off" id="fecharec" value="<?php echo $fecha_actual; ?>" name="fecharec" placeholder="Ingrese fecha Recepción">
							</div>
							<div class="alert alert-danger" role="alert" id="fecharecE" style="display: none">
								Debes ingresar fecha de recepción
							</div>
						</div>
						<div class="col-lg-3">
							Fecha de Beneficio<span class="text-danger">*</span>
							<div class="form-group label-floating" id="fechaExpDiv">
								<input type="date" class="form-control" autocomplete="off" id="fechasac" name="fechasac" placeholder="Ingrese fecha Sacrificio">
							</div>
							<div class="alert alert-danger" role="alert" id="fechasacE" style="display: none">
								Debes ingresar fecha de beneficio
							</div>
						</div>

						<div class="col-lg-3">
							Especie<span class="text-danger">*</span>
							<div class="form-group label-floating" id="telefonoDiv">
								<select class="form-control" id="tipo" name="tipo">
									<option value="">Seleccione Especie</option>
									<option value="CERDO">CERDO</option>
									<option value="RES">RES</option>
								</select>
							</div>
							<div class="alert alert-danger" role="alert" id="tipoE" style="display: none">
								Debes seleccionar la especie a despachar
							</div>
						</div>

						<div class="col-lg-3">
							Nro Remisión
							<div class="form-group label-floating" id="codigoAutoDiv">
								<input type="text" class="form-control" autocomplete="off" id="remision" name="remision" placeholder="Ingrese Remisión">
								<input type="hidden" id="id_guia">
							</div>
							<div class="alert alert-danger" role="alert" id="remisionE" style="display: none">
								Debes ingresar el nro remisión
							</div>
						</div>

						<div class="col-lg-2">
							Canales<span class="text-danger">*</span>
							<div class="form-group label-floating" id="conseGuiaDiv">
								<input class="form-control" type="number" name="canales" autocomplete="off" id="canales" placeholder="Ingrese el Nro de Canales">
							</div>
							<div class="alert alert-danger" role="alert" id="canalesE" style="display: none">
								Debe ingresar la cantidad de Canales
							</div>
						</div>

						<div class="col-lg-2">
							Recibo<span class="text-danger">*</span>
							<select class="form-control" id="despacho" name="despacho">
								<option value="">Seleccione Orden de Recibo</option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
								<option value="E">E</option>
								<option value="F">F</option>
							</select>
							<div class="alert alert-danger" role="alert" id="despachoE" style="display: none">
								Debe seleccionar el orden de recibo
							</div>
						</div>

						<div class="col-lg-2">
							<center>Lote</center>
							<div class="form-group label-floating" id="codigoAutoDiv">
								<input type="text" class="form-control" autocomplete="off" id="consecutivog" name="consecutivog" disabled>
								<input type="hidden" id="id_guia">
							</div>
							<div class="alert alert-danger" role="alert" id="consecutivogE" style="display: none">
								Debes ingresar el lote a despachar
							</div>
						</div>

						<div class="col-lg-6">
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

						<div class="col-md-6">
							Origen<span class="text-danger">*</span>
							<div class="form-group label-floating" id="conseGuiaDiv">
								<select class="form-control" id="beneficio" name="beneficio">
									<option value="">Seleccione Planta de Beneficio</option>
									<?php foreach ($listaOrigen as $perm) : ?>
										<option value="<?php echo $perm['id'] ?>">
											<?php echo $perm['nit'] . " - " . $perm['sede'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="alert alert-danger" role="alert" id="beneficioE" style="display: none">
								Debe seleccinar la planta de beneficio
							</div>
						</div>

						<div class="col-md-6">
							Destino<span class="text-danger">*</span>
							<div class="form-group label-floating" id="conseGuiaDiv">
								<select class="form-control" id="destino" name="destino">
									<option value="">Seleccione Destino de Despacho</option>
									<?php foreach ($listaDestinos as $perm) : ?>
										<option value="<?php echo $perm['id'] ?>">
											<?php echo $perm['empresa'] . " - " . $perm['sede'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="alert alert-danger" role="alert" id="destinoE" style="display: none">
								Debe seleccinar el destino del despacho
							</div>
						</div>

						<div class="col-11 col-md-5">
							Nombre del Conductor<span class="text-danger">*</span>
							<div class="form-group label-floating" id="conseGuiaDiv">
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
						<div class="col-1 col-md-1">
							<!-- <a style="z-index: 0;color:#000; margin-right:10px" data-bs-toggle="modal" data-bs-target="#modalNuevoConductor"><i class="bi bi-folder-plus fs-2 text-warning" style="margin-top: 29px;margin-left: -10px;"></i></a> -->
							<a style="z-index: 0;color:#000; margin-right:10px" id="abrirSegundaModal">
								<i class="bi bi-folder-plus fs-2 text-warning" style="margin-top: 29px;margin-left: -10px;"></i>
							</a>
						</div>
						<div class="col-11 col-md-5">
							Placa Vehiculo<span class="text-danger">*</span>
							<div class="form-group label-floating" id="conseGuiaDiv">
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
						<div class="col-1 col-md-1">
							<!-- <a style="z-index: 0;color:#000; margin-right:10px" data-bs-toggle="modal" data-bs-target="#modalNuevaPlaca"><i class="bi bi-folder-plus fs-2 text-warning" style="margin-top: 29px;margin-left: -10px;"></i></a> -->
							<a style="z-index: 0;color:#000; margin-right:10px" id="abrirTerceraModal">
								<i class="bi bi-folder-plus fs-2 text-warning" style="margin-top: 29px;margin-left: -10px;"></i>
							</a>
						</div>
						<div class="col-md-12 mt-3">
							<center>
								<label style="text-align: center;font-weight: bold;" id="titulo">INFORMACIÓN GUÍAS DE TRANSPORTE Y CERTIFCADO DE CALIDAD</label>
							</center>
						</div>
						<div class="col-md-3">
							Lote Beneficio
							<div class="form-group label-floating" id="codigoAutoDiv">
								<input type="text" class="form-control" autocomplete="off" id="lotep" name="lotep" placeholder="Ingrese Lote Planta">
							</div>
						</div>
						<div class="col-md-3">
							N° Guía de ICA<span class="text-danger">*</span>
							<div class="form-group label-floating" id="codigoAutoDiv">
								<input type="text" class="form-control" autocomplete="off" id="ica" name="ica" placeholder="Ingrese Nro Guia de ICA">
							</div>
							<div class="alert alert-danger" role="alert" id="icaE" style="display: none">
								Debes ingresar el n° guía de ICA
							</div>
						</div>
						<div class="col-md-3" style="font-size: 13px">
							Guía de Transp. Carne en C<span class="text-danger">*</span>
							<div class="form-group label-floating" id="codigoAutoDiv">
								<input type="text" class="form-control" autocomplete="off" id="guiat" name="guiat" placeholder="Ingrese Guia de Transporte">
							</div>
							<div class="alert alert-danger" role="alert" id="guiatE" style="display: none">
								Debes ingresar el n° Guía de transporte de carne en canal
							</div>
						</div>
						<div class="col-md-3">
							Certificado de Calidad<span class="text-danger">*</span>
							<div class="form-group label-floating" id="codigoAutoDiv">
								<select class="form-control" id="certificadoc" name="certificadoc">
									<option value="">Tiene Certificado de Calidad</option>
									<option value="SI">SI</option>
									<option value="NO">NO</option>
								</select>
							</div>
							<div class="alert alert-danger" role="alert" id="certificadocE" style="display: none">
								Debes indicar si tiene certificado de calidad
							</div>
						</div>
						<div class="col-md-12 mt-3">
							<center>
								<label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO PRÁCTICAS HIGIÉNICAS PERSONAL MANIPULADOR</label>
							</center>
						</div>
						<div class="col-md-6 custom-margin">
							<input type="checkbox" class="form-check-input" id="cph1" name="cph1" value="1">&nbsp;
							<label for="cph1">Usa cofia y tapabocas</label>
						</div>

						<div class="col-md-6 custom-margin">
							<input type="checkbox" class="form-check-input" id="cph2" name="cph2" value="1">&nbsp;
							<label for="cph2">Capa limpia y en buen estado</label>
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
							<label for="chv2">¿Vehículo limpio y ausente de plagas?</label>
						</div>

						<div class="col-md-6 custom-margin">
							<input type="checkbox" class="form-check-input" id="chv3" name="chv3" value="1">&nbsp;
							<label for="chv3" style="font-size: 14px">¿Transporta sustancias químicas con las canales?</label>
						</div>

						<div class="col-md-6 custom-margin">
							<input type="checkbox" class="form-check-input" id="chv4" name="chv4" value="1">&nbsp;
							<label for="chv4">¿Transporta canales de una sola especie?</label>
						</div>

						<div class="col-md-12 mt-3">
							<center>
								<label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO CARACTERISTICAS ORGANOLÉPTICAS E HIGIÉNICO SANITARIAS DE LAS CANALES</label>
							</center>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh1" name="ccoh1" value="1">&nbsp;
							<label id="ccoh1t" for="ccoh1">Olor</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh2" name="ccoh2" value="1">&nbsp;
							<label id="ccoh2t" for="ccoh2">Color</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh3" name="ccoh3" value="1">&nbsp;
							<label id="ccoh3t" for="ccoh3">Sin Pelo</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh4" name="ccoh4" value="1">&nbsp;
							<label id="ccoh4t" for="ccoh4">Sin Contenido Ruminal</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh5" name="ccoh5" value="1">&nbsp;
							<label id="ccoh5t" for="ccoh5">Sin Materia Fecal</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh6" name="ccoh6" value="1">&nbsp;
							<label id="ccoh6t" for="ccoh6">Sin Medula Espinal</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh7" name="ccoh7" value="1">&nbsp;
							<label id="ccoh7t" for="ccoh7">Sin Nuches</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh8" name="ccoh8" value="1">&nbsp;
							<label id="ccoh8t" for="ccoh8">Sin Garrapatas</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh9" name="ccoh9" value="1">&nbsp;
							<label id="ccoh9t" for="ccoh9">Sin coágulos ni hematomas</label>
						</div>

						<div class="col-md-4 custom-margin">
							<input type="checkbox" class="form-check-input" id="ccoh10" name="ccoh10" value="1">&nbsp;
							<label id="ccoh10t" for="ccoh10">Sin leche</label>
						</div>

					</div>
					<div class="col-md-12">
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
					<div id="registrosPesos">
						<div class="row">
							<div class="col-md-3" id="turnoDiv">
								Turno
								<div class="form-group label-floating" id="turnoDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="number" name="turno" autocomplete="off" id="turno" placeholder="Ingrese el Turno">
								</div>
								<div class="alert alert-danger" role="alert" id="turnoE" style="display: none">
									Debe ingresar el turno
								</div>
							</div>
							<div class="col-md-3" id="parteDiv">
								Parte
								<div class="form-group label-floating" id="selectparteDiv">
									<select class="form-control" id="parte" name="parte">
										<option value="">Seleccione Parte</option>
										<option value="ESTOMAGO">ESTOMAGO</option>
										<option value="PIERNA">PIERNA</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="parteE" style="display: none">
									Debes seleccionar la parte a registrar
								</div>
							</div>
							<div class="col-md-3" id="pesoDiv">
								Peso
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="peso" min="1" max="100" autocomplete="off" id="peso" placeholder="Ingrese el Peso de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar el peso de la parte
								</div>
							</div>
							<div class="col-md-3" id="temperaturaDiv">
								Temperatura
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="temperaturap" autocomplete="off" id="temperaturap" placeholder="Ingrese la temperatura de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar la temperatura de la parte
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								Observación
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="text" name="observcionPeso" autocomplete="off" id="observacionPeso" placeholder="Ingrese Observación">
								</div>
							</div>
							<div class="col-md-3">
								Fotografía
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="file" name="foto" autocomplete="off" id="foto" placeholder="Seleccione Archivo">
								</div>
							</div>
							<!-- <div class="col-md-3">
								<div id="photo-preview" width="30px" height="30px"></div>
							</div> -->
						</div>

					</div>
					<div id="edicionPesos" style="display: none;">
						<div class="row">
							<div class="col-md-2">
								Turno
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="number" name="turnoEdicion" autocomplete="off" id="turnoEdicion" placeholder="Ingrese el Turno" disabled>
									<input type="hidden" name="id_recepcion_pesos" autocomplete="off" id="id_recepcion_pesos">
								</div>
								<div class="alert alert-danger" role="alert" id="turnoE" style="display: none">
									Debe ingresar el turno
								</div>
							</div>

							<div class="col-md-2">
								Peso Estomago 1
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" min="1" max="999" name="estomago1Edicion" autocomplete="off" id="estomago1Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Estomago 2
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" min="1" max="999" name="estomago2Edicion" autocomplete="off" id="estomago2Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Pierna 1
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" min="1" max="999" name="pierna1Edicion" autocomplete="off" id="pierna1Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Pierna 2
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" min="1" max="999" name="pierna2Edicion" autocomplete="off" id="pierna2Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Temperatura
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="temperaturaEdicion" autocomplete="off" id="temperaturaEdicion" placeholder="Ingrese la temperatura de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar la temperatura de la parte
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								Observación
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="text" name="observcionPeso" autocomplete="off" id="observacionPeso" placeholder="Ingrese Observación">
								</div>
							</div>
							<div class="col-md-3">
								Fotografía
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="file" name="foto" autocomplete="off" id="foto" placeholder="Seleccione Archivo">
								</div>
							</div>
							<div class="col-md-3">
								<div id="photo-preview"></div>
							</div>
						</div>

					</div>
				</form>
				<div class="text-center mt-3 mb-3">
					<!-- botones res -->
					<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Agregar Item" id="btnNuevoCriterio">Guardar</button>
					<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Item" id="btnEditarCriterio" style="display: none;">Editar</button>
					<button class="btn btn-warning" rel="tooltip" data-placement="bottom" title="Cancelar Edicion" id="btnCancelarEdicion" style="display: none;">Cancelar</button>

					<!-- botones cerdo -->
					<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Agregar Item" id="btnNuevoCriterioCerdo">Guardar</button>
					<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Item" id="btnEditarCriterioCerdo" style="display: none;">Editar</button>
					<button class="btn btn-warning" rel="tooltip" data-placement="bottom" title="Cancelar Edicion" id="btnCancelarEdicionCerdo" style="display: none;">Cancelar</button>

					<button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal" id="btnCerrarCriterios">Cerrar</button>
				</div>
				<div class="table-responsive">
					<table id="jtableCriterio" class="table table-striped table-bordered table-hover datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Turno</th>
								<th id="pesoHeader">Peso</th>
								<!-- <th>Estomago 2</th>
							<th>Piernas 1</th>
							<th>Piernas 2</th> -->
								<th>Total</th>
								<th>Temperatura</th>
								<th>Status</th>
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

<?php require_once('scripts.php'); ?>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

<script>
	document.getElementById('abrirSegundaModal').addEventListener('click', function(event) {
		event.preventDefault(); // Evita que el enlace realice su acción predeterminada
		var segundaModal = new bootstrap.Modal(document.getElementById('modalNuevoConductor'), {
			backdrop: false // Permite interacción con la primera modal
		});
		segundaModal.show();
	});

	document.getElementById('abrirTerceraModal').addEventListener('click', function(event) {
		event.preventDefault(); // Evita que el enlace realice su acción predeterminada
		var terceraModal = new bootstrap.Modal(document.getElementById('modalNuevaPlaca'), {
			backdrop: false // Permite interacción con la primera modal
		});
		terceraModal.show();
	});
</script>

<script>
	$(document).ready(function() {
		var dataTable = $('#jtable').dataTable({
			"responsive": true,
			"processing": true,
			"serverSide": true,
			"bDestroy": true,
			"order": [
				[0, 'desc']
			],
			"ajax": {
				url: "tablas/tablaRecepciones.php",
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
			const files = event.target.files;
			const preview = $('#photo-preview');
			const formData = new FormData();
			const userId = $('#user-id').val();

			preview.empty();

			for (let i = 0; i < files.length; i++) {
				const file = files[i];
				const img = $('<img>').attr('src', URL.createObjectURL(file));
				preview.append(img);
				formData.append('photos[]', file);
			}

			formData.append('user-id', userId);

			$.ajax({
				url: 'controlador/upload.php',
				method: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					const data = JSON.parse(response);
					/*  if (data.status === "success") {
					     alert('Fotos guardadas exitosamente');
					 } else {
					     alert('Error al guardar las fotos: ' + data.message);
					 } */
				},
				error: function() {
					alert('Error al guardar las fotos');
				}
			});
		});

		$('#tipo').change(function() {
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
		});

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

		function generarCodigo() {
			const tipo = $('#tipo').val();
			const despacho = $('#despacho').val();
			if (tipo && despacho) {
				const tipoCodigo = tipo === 'CERDO' ? '2' : '1';
				const fecha = new Date();
				const year = fecha.getFullYear();
				const ultimosDosDigitos = year.toString().slice(-2);
				const semana = ("0" + fecha.getWeek()).slice(-2);
				const diaSemana = fecha.getDay();
				const codigo = tipoCodigo + ultimosDosDigitos + semana + diaSemana + despacho;
				$('#consecutivog').val(codigo);
			} else {
				$('#consecutivog').val('');
			}
		}

		$('#tipo, #despacho').change(generarCodigo);

	});

	/* function eliminarItem(id_item_proveedor) {
		const datosEPeso = {
			id_item_proveedor: id_item_proveedor
		};
		console.log('datos para eliminar: ',datosEPeso);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: 'controlador/controlador.php',
			data: {
				datosEPeso: datosEPeso
			},
			success: function(data) {
				$('#jtableCriterio').DataTable().ajax.reload();
			}
		});
	} */

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
				const datosEPeso = {
					id_item_proveedor: id_item_proveedor
				};

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosEPeso: datosEPeso
					},
					success: function(data) {
						Swal.fire({
							title: 'Eliminado',
							text: 'El elemento ha sido eliminado con éxito.',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});
						$('#jtableCriterio').DataTable().ajax.reload();
						$('#jtable').DataTable().ajax.reload();
					}
				});
			}
		});
	}


	function abrirModal(consecutivoRecepcion, status, tipo, raza, canales) {
		$('#titulo2').text('Consecutivo Recepcion: ' + consecutivoRecepcion + ' Especie: ' + raza);
		console.log('raza: ', raza);

		$('#turnoE').css('display', 'none');
		$('#parteE').css('display', 'none');
		$('#pesoE').css('display', 'none');

		if (raza == 'RES') {

			$('#btnNuevoCriterioCerdo').css('display', 'none');
			$('#btnEditarCriterioCerdo').css('display', 'none');

			$('#parteDiv').css('display', 'initial');
			$("#turnoDiv").removeClass("col-md-4");
			$("#pesoDiv").removeClass("col-md-4");
			$("#temperaturaDiv").removeClass("col-md-4");
			$("#turnoDiv").addClass("col-md-3");
			$("#pesoDiv").addClass("col-md-3");
			$("#temperaturaDiv").addClass("col-md-3");

			if ((status == 1) || (tipo == 0)) {
				$('#btnNuevoCriterio').css('display', 'initial');
				$('#btnEditarCriterio').css('display', 'none');
			} else {
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
			}
		} else {
			$('#btnNuevoCriterio').css('display', 'none');
			$('#btnEditarCriterio').css('display', 'none');

			$('#parteDiv').css('display', 'none');
			$("#turnoDiv").removeClass("col-md-3");
			$("#pesoDiv").removeClass("col-md-3");
			$("#temperaturaDiv").removeClass("col-md-3");
			$("#turnoDiv").addClass("col-md-4");
			$("#pesoDiv").addClass("col-md-4");
			$("#temperaturaDiv").addClass("col-md-4");

			if ((status == 1) || (tipo == 0)) {
				$('#btnNuevoCriterioCerdo').css('display', 'initial');
				$('#btnEditarCriterioCerdo').css('display', 'none');
			} else {
				$('#btnNuevoCriterioCerdo').css('display', 'none');
				$('#btnEditarCriterioCerdo').css('display', 'none');
			}
		}

		$('#turno').val('');
		$('#parte').val('');
		$('#peso').val('');
	}

	function buscarItems(id_recepcion, raza) {
		//$('#jtableCriterio thead tr').empty();
		//$('#jtableCriterio tbody').empty();
		ruta = "tablas/tablaPesos.php";
		if (raza == 'CERDO') {
			ruta = "tablas/tablaPesosCerdo.php";
			$('#pesoHeader').text('Peso');
			//$('#jtableCriterio').DataTable().destroy();
			//$('#jtableCriterio thead tr').empty();
			//var newHeaders = ['#', 'Turno', 'Peso', 'Temperatura', 'Hora', 'Acción'];
		} else {
			$('#pesoHeader').text('E1 - E2 - P1 - P2');
			ruta = "tablas/tablaPesos.php";
			//$('#jtableCriterio').DataTable().destroy();
			//$('#jtableCriterio thead tr').empty();
			//var newHeaders = ['#', 'Turno', 'Estomago 1', 'Estomago 2', 'Pierna 1', 'Pierna 2', 'Total', 'Temperatura', 'Hora', 'Acción'];
		}

		/* newHeaders.forEach(function(header) {
            	$('#jtableCriterio thead tr').append('<th>' + header + '</th>');
        	}); */

		$('#id').val(id_recepcion);
		$('#jtableCriterio').dataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"processing": true,
			"serverSide": true,
			"bDestroy": true,
			"order": [
				[0, 'desc']
			],
			"columnDefs": [{
				"orderable": false,
				"targets": [1]
			}],
			"ajax": {
				url: ruta,
				type: "post",
				data: {
					recepcion: id_recepcion
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
	}


	$('#btnCancelarEdicion').click(function() {
		$('#btnNuevoCriterio').css('display', 'initial');
		$('#btnEditarCriterio').css('display', 'none');
		$('#btnCancelarEdicion').css('display', 'none');
		$('#registrosPesos').css('display', '');
		$('#edicionPesos').css('display', 'none');
	});

	$('#btnCancelarEdicionCerdo').click(function() {
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
		validacionesCri = validacionesC('RES');
		if (validacionesCri == "") {
			const datosPeso = {
				turno: $('#turno').val(),
				parte: $('#parte').val(),
				peso: $('#peso').val(),
				temperaturap: $('#temperaturap').val(),
				recepcion: $('#id').val()
			};
			console.log('datos a registrar: ', datosPeso);

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosPeso: datosPeso
				},
				success: function(data) {
					console.log('respuesta:', data);
					if (data.status === 'success') {
						$('#valorCriterio').val('');
						/* $('#turno').val('');
						$('#parte').val(''); */
						$('#peso').val('');
						$('#observacionPeso').val('');
						$('#foto').val('');
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
		var month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
		var year = today.getFullYear();
		var formattedDate = year + '-' + month + '-' + day;
		let guardarTiempoRecepcionRes = 1;
		$.ajax({
			type: 'POST',
			url: 'controlador/controlador.php',
			data: {
				guardarTiempoRecepcionRes
			},
			success: function(data) {
				console.log(data);
			}
		});
		$('#fecharec').val(formattedDate);
		$('#fechasac').val('');
		$('#tipo').val('');
		$('#remision').val('');
		$('#canales').val('');
		$('#consecutivog').val('');
		$('#responsable').val(<?php echo $_SESSION['usuario']; ?>);
		$('#beneficio').val('');
		$('#destino').val('');
		$('#conductor').val('');
		$('#placa').val('');
		$('#ica').val('');
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
		$('#ica').css('border', '');
		$('#certificadoc').css('border', '');

		$('#id_guia').val('');
		$('#titulo').text('Registro de Recepción');
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

		if (validacionesF == "") {
			console.log('validaciones: ', validacionesF);
			const datosRecepcion = {
				fecharec: $('#fecharec').val(),
				fechasac: $('#fechasac').val(),
				tipo: $('#tipo').val(),
				remision: $('#remision').val(),
				canales: $('#canales').val(),
				despacho: $('#despacho').val(),
				consecutivog: $('#consecutivog').val(),
				responsable: $('#responsable').val(),
				beneficio: $('#beneficio').val(),
				destino: $('#destino').val(),
				conductor: $('#conductor').val(),
				placa: $('#placa').val(),
				lotep: $('#lotep').val(),
				ica: $('#ica').val(),
				guiat: $('#guiat').val(),
				certificadoc: $('#certificadoc').val(),
				chv1: $('#chv1').val(),
				cph1: $('#cph1').is(':checked') ? 1 : 0,
				cph2: $('#cph2').is(':checked') ? 1 : 0,
				cph3: $('#cph3').is(':checked') ? 1 : 0,
				cph4: $('#cph4').is(':checked') ? 1 : 0,
				cph5: $('#cph5').is(':checked') ? 1 : 0,
				chv2: $('#chv2').is(':checked') ? 1 : 0,
				chv3: $('#chv3').is(':checked') ? 1 : 0,
				chv4: $('#chv4').is(':checked') ? 1 : 0,
				ccoh1: $('#ccoh1').is(':checked') ? 1 : 0,
				ccoh2: $('#ccoh2').is(':checked') ? 1 : 0,
				ccoh3: $('#ccoh3').is(':checked') ? 1 : 0,
				ccoh4: $('#ccoh4').is(':checked') ? 1 : 0,
				ccoh5: $('#ccoh5').is(':checked') ? 1 : 0,
				ccoh6: $('#ccoh6').is(':checked') ? 1 : 0,
				ccoh7: $('#ccoh7').is(':checked') ? 1 : 0,
				ccoh8: $('#ccoh8').is(':checked') ? 1 : 0,
				ccoh9: $('#ccoh9').is(':checked') ? 1 : 0,
				ccoh10: $('#ccoh10').is(':checked') ? 1 : 0,
				observaciones: $('#observaciones').val()
			};
			console.log('datos a guardar:', datosRecepcion);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosRecepcion: datosRecepcion
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
					$('#fecharec').val('');
					$('#fechasac').val('');
					$('#tipo').val('');
					$('#remision').val('');
					$('#canales').val('');
					$('#consecutivog').val('');
					$('#responsable').val('');
					$('#beneficio').val('');
					$('#destino').val('');
					$('#conductor').val('');
					$('#placa').val('');
					$('#ica').val('');
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

					$('#remision').css('border', '');
					$('#destino').css('border', '');
					$('#canales').css('border', '');
					$('#ica').css('border', '');
					$('#certificadoc').css('border', '');

					$("#modalNuevoProveedor").modal('hide');
					Swal.fire({
						title: 'Nueva recepción registrada satisfactoriamente',
						text: '',
						icon: 'success',
						timer: 1000,
						showConfirmButton: false
					});
				}
			});
		}
	});

	$('#btnNuevoConductor').click(function() {
		validacionesCo = validacionesCon();

		if (validacionesCo == "" && $("#cedulaExiste").val() == "") {
			const datosConductor = {
				cedula: $('#cedula').val(),
				nombresConductor: $('#nombresConductor').val()
			};
			console.log('datos a guardar:', datosConductor);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosConductor: datosConductor
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
				}
			});
		}
	});

	$('#btnNuevaPlaca').click(function() {
		validacionesPl = validacionesPla();

		if (validacionesPl == "") {
			console.log('validaciones: ', validacionesPl);
			const datosPlacaR = {
				placa: $('#placav').val()
			};
			console.log('datos a guardar:', datosPlacaR);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosPlacaR: datosPlacaR
				},

				success: function(data) {
					console.log('data: ', data);

					if (data.length < 5) {
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
					}
				}
			});
		}
	});

	function buscarGuia(idRecepcion) {
		$('#id_guia').val(idRecepcion);
		$('#titulo').text('Editar Guia');
		$('#btnNuevoProveedor').css('display', 'none');
		$('#btnEditProveedor').css('display', 'initial');
		console.log('consulta: ', idRecepcion);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: 'controlador/controlador.php',
			data: {
				idRecepcion: idRecepcion
			},
			success: function(data) {
				console.log('datos: ', data);
				$('#fecharec').val(data[0].fecharec);
				$('#fechasac').val(data[0].fechasac);
				$('#tipo').val(data[0].tipo);
				$('#remision').val(data[0].remision);
				$('#canales').val(data[0].canales);
				$('#despacho').val(data[0].recibo);
				$('#consecutivog').val(data[0].consecutivog);
				$('#responsable').val(data[0].responsable);
				$('#beneficio').val(data[0].beneficio);
				$('#destino').val(data[0].destino);
				$('#conductor').val(data[0].conductor);
				$('#placa').val(data[0].placa);
				$('#lotep').val(data[0].lotep);
				$('#ica').val(data[0].ica);
				$('#guiat').val(data[0].guiat);
				$('#certificadoc').val(data[0].certificadoc);
				$('#remision').css('border', '');
				$('#destino').css('border', '');
				$('#canales').css('border', '');
				$('#ica').css('border', '');
				$('#certificadoc').css('border', '');

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

				if (data[0].tipo === 'CERDO') {
					$('#ccoh1t').text('Olor');
					$('#ccoh2t').text('Color');
					$('#ccoh3t').text('Sin Pelo');
					$('#ccoh4t').text('Contenido estomacal');
					$('#ccoh5t').text('Sin Materia Fecal');
					$('#ccoh6t').text('Sin coágulos ni hematomas');
					$('#ccoh7t').text('Sin Riñones');
					$('#ccoh8t').text('Sin Leche');
					$('#ccoh9t').text('Sin Fracturas');
					$('#ccoh10t').text('Sin Dermatitis');
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
				}
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
				$('#consecutivog').val(data[0].consecutivog);
				$('#responsable').val(data[0].responsable);
				$('#beneficio').val(data[0].beneficio);
				$('#conductor').val(data[0].conductor);
				$('#placa').val(data[0].placa);
				$('#guiat').val(data[0].guiat);

				$('#remision').css('border', '2px solid red');
				$('#destino').css('border', '2px solid red');
				$('#canales').css('border', '2px solid red');
				$('#ica').css('border', '2px solid red');
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
		$('#btnCancelarEdicion').css('display', 'initial');
		$('#registrosPesos').css('display', 'none');
		$('#edicionPesos').css('display', '');
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
				$('#turnoEdicion').val(data[0].turno);
				$('#estomago1Edicion').val(data[0].estomago1);
				$('#estomago2Edicion').val(data[0].estomago2);
				$('#pierna1Edicion').val(data[0].piernas1);
				$('#pierna2Edicion').val(data[0].piernas2);
				$('#temperaturaEdicion').val(data[0].temperatura);
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
				idBloquearR: idBloquear
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
			console.log('validaciones: ', validacionesF);
			datosEdit = {
				"id_guia": $('#id_guia').val(),
				"fecharec": $('#fecharec').val(),
				"fechasac": $('#fechasac').val(),
				"tipo": $('#tipo').val(),
				"remision": $('#remision').val(),
				"canales": $('#canales').val(),
				"despacho": $('#despacho').val(),
				"consecutivog": $('#consecutivog').val(),
				"responsable": $('#responsable').val(),
				"beneficio": $('#beneficio').val(),
				"destino": $('#destino').val(),
				"conductor": $('#conductor').val(),
				"placa": $('#placa').val(),
				"lotep": $('#lotep').val(),
				"ica": $('#ica').val(),
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
				"observaciones": $('#observaciones').val()
			};
			console.log('datos a actualizar:', datosEdit);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosEdit: datosEdit
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
					$('#fecharec').val('');
					$('#fechasac').val('');
					$('#tipo').val('');
					$('#remision').val('');
					$('#canales').val('');
					$('#consecutivog').val('');
					$('#responsable').val('');
					$('#beneficio').val('');
					$('#destino').val('');
					$('#conductor').val('');
					$('#placa').val('');
					$('#ica').val('');
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
				}
			});
		}
	});

	function validaciones() {
		$('#fecharecE').css('display', 'none');
		$('#fechasacE').css('display', 'none');
		$('#tipoE').css('display', 'none');
		$('#despachoE').css('display', 'none');
		$('#canalesE').css('display', 'none');
		$('#consecutivogE').css('display', 'none');
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
			return 'R';
		}
		if ($("#fechasac").val() == null || $("#fechasac").val() == "") {
			$('#fechasacE').css('display', 'block');
			return 'R';
		}

		let fecharec = new Date($("#fecharec").val());
		let fechasac = new Date($("#fechasac").val());

		if (fechasac >= fecharec) {
			$('#fechasacE').css('display', 'block').text('La fecha de beneficio no puede ser mayor o igual que la fecha de recepción');
			return 'R';
		}

		if ($("#tipo").val() == null || $("#tipo").val() == "") {
			$('#tipoE').css('display', 'block');
			return 'R';
		}
		if ($("#canales").val() == null || $("#canales").val() == "") {
			$('#canalesE').css('display', 'block');
			return 'R';
		}
		if ($("#despacho").val() == null || $("#despacho").val() == "") {
			$('#despachoE').css('display', 'block');
			return 'R';
		}
		if ($("#consecutivog").val() == null || $("#consecutivog").val() == "") {
			$('#consecutivogE').css('display', 'block');
			return 'R';
		}
		if ($("#responsable").val() == null || $("#responsable").val() == "") {
			$('#responsableE').css('display', 'block');
			return 'R';
		}
		if ($("#beneficio").val() == null || $("#beneficio").val() == "") {
			$('#beneficioE').css('display', 'block');
			return 'R';
		}
		if ($("#destino").val() == null || $("#destino").val() == "") {
			$('#destinoE').css('display', 'block');
			return 'R';
		}
		if ($("#conductor").val() == null || $("#conductor").val() == "") {
			$('#conductorE').css('display', 'block');
			return 'R';
		}
		if ($("#placa").val() == null || $("#placa").val() == "") {
			$('#placaE').css('display', 'block');
			return 'R';
		}
		if ($("#ica").val() == null || $("#ica").val() == "") {
			$('#icaE').css('display', 'block');
			return 'R';
		}
		if ($("#guiat").val() == null || $("#guiat").val() == "") {
			$('#guiatE').css('display', 'block');
			return 'R';
		}
		if ($("#certificadoc").val() == null || $("#certificadoc").val() == "") {
			$('#certificadocE').css('display', 'block');
			return 'R';
		}
		if ($("#chv1").val() == null || $("#chv1").val() == "") {
			$('#chv1E').css('display', 'block');
			return 'R';
		}
		return "";
	}

	$('#btnEditarCriterio').click(function() {
		validacionesCri = validacionesC();
		//if (validacionesCri == "") {
		if (1 == 1) {
			infoPesoEdit = {
				"id_recepcion_pesos": $('#id_recepcion_pesos').val(),
				"estomago1Edicion": $('#estomago1Edicion').val(),
				"estomago2Edicion": $('#estomago2Edicion').val(),
				"pierna1Edicion": $('#pierna1Edicion').val(),
				"pierna2Edicion": $('#pierna2Edicion').val(),
				"temperaturaEdicion": $('#temperaturaEdicion').val(),
			};
			console.log('datos enviados:', infoPesoEdit);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					infoPesoEdit
				},
				success: function(data) {
					/* $('#btnNuevoCriterio').css('display', 'initial');
					$('#btnEditarCriterio').css('display', 'none');*/
					$('#turnoE').css('display', 'none');
					$('#btnNuevoCriterio').css('display', 'initial');
					$('#btnEditarCriterio').css('display', 'none');
					$('#btnCancelarEdicion').css('display', 'none');
					$('#registrosPesos').css('display', '');
					$('#edicionPesos').css('display', 'none');
					$('#estomago1Edicion').val('');
					$('#estomago2Edicion').val('');
					$('#pierna1Edicion').val('');
					$('#pierna2Edicion').val('');
					$('#turnoE').val('');
					$('#temperaturaEdicion').val('');
					$('#jtableCriterio').DataTable().ajax.reload();
					$('#jtable').DataTable().ajax.reload();
					Swal.fire({
						title: 'Turno modificado satisfactoriamente',
						text: '',
						icon: 'success',
						timer: 1000,
						showConfirmButton: false
					});
				}
			});
		}
	});

	$('#btnEditarCriterioCerdo').click(function() {
		validacionesCri = validacionesC();
		//if (validacionesCri == "") {
		if (1 == 1) {
			infoPesoEditCerdo = {
				"id_recepcion_pesos": $('#id_recepcion_pesos').val(),
				"peso": $('#peso').val(),
				"temperaturap": $('#temperaturap').val(),
			};
			console.log('datos enviado cerdos:', infoPesoEditCerdo);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					infoPesoEditCerdo
				},
				success: function(data) {
					/* $('#btnNuevoCriterio').css('display', 'initial');
					$('#btnEditarCriterio').css('display', 'none');*/
					$('#turnoE').css('display', 'none');
					$('#btnNuevoCriterioCerdo').css('display', 'initial');
					$('#btnEditarCriterioCerdo').css('display', 'none');
					$('#btnCancelarEdicionCerdo').css('display', 'none');
					$('#registrosPesos').css('display', '');
					$('#turnoE').val('');
					$('#turno').prop('disabled', false);
					$('#btnNuevoCriterioCerdo').css('display', 'initial');
					$('#registrosPesosCerdo').css('display', '');
					$('#edicionPesosCerdo').css('display', 'none');

					$('#turno').val('');
					$('#peso').val('');
					$('#temperaturap').val('');
					$('#jtableCriterio').DataTable().ajax.reload();
					$('#jtable').DataTable().ajax.reload();
					Swal.fire({
						title: 'Turno modificado satisfactoriamente',
						text: '',
						icon: 'success',
						timer: 1000,
						showConfirmButton: false
					});
				}
			});
		}
	});

	$("#cedula").on('change', function() {
		console.log('validando cedula');
		var cedula = $("#cedula").val();
		if (!($("#cedula").val() == null || $("#cedula").val() == "")) {
			console.log("Datos enviados a controlador:", cedula);
			$.ajax({
				type: 'POST',
				url: 'controlador/controlador.php',
				data: {
					cedula
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

	function validacionesC(raza) {
		$('#turnoE').css('display', 'none');
		$('#parteE').css('display', 'none');
		$('#pesoE').css('display', 'none');

		if ($("#turno").val() == null || $("#turno").val() == "") {
			$('#turnoE').css('display', 'block');
			return 'R';
		}
		if (raza != 'CERDO') {
			if ($("#parte").val() == null || $("#parte").val() == "") {
				$('#parteE').css('display', 'block');
				return 'R';
			}
		}
		if ($("#peso").val() == null || $("#peso").val() == "") {
			$('#pesoE').css('display', 'block');
			return 'R';
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
</script>

</html>