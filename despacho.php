<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
/* error_reporting(0); */
date_default_timezone_set("America/bogota");
$_SESSION["fechaInicio"] = date("G:i");
require_once('./modelo/funciones.php');
$listaResponsables = listaResponsables();
$listaConductores = listaConductores();
$listaPlacas = listaPlacas();
$listaOrigen = listaOrigen();
$listaDestinos = listaDestinos(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Planta de Desposte | Despacho</title>
	<?php include_once('encabezado.php'); ?> 
	<link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
	<style>
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
			<h1>Guia de transporte y destino de carne</h1>
		</div>

		<section class="section">
			<div class="row">
				<div class="col-lg-12">

					<div class="card">
						<div class="card-body">
							<h5 class="card-title">
								<center>
									<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
										title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA</button>
                                        <h5 class="mt-1" style="color: red;display: none" id="alertItem">La ultima guia no tiene items</h5>
								</center>
								<table id="jtable" class="table table-striped table-bordered table-hover datatable">
									<thead>
										<tr>
											<th>Consecutivo<br>Tipo</th>
											<th>Fecha<br>Lote</th>
											<th>Destino<br>Lote</th>
											<th>Conductor<br>Placa</th>
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
		<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

			<div class="modal-content">
					<div class="modal-header d-flex justify-content-between align-items-center" style="padding: 10px !important;">
						<h6 id="horaInicialGuia" class="float-start m-0"></h6>
						<h6 class="modal-title" id="titulo" style="font-weight: bold;">AGREGAR GUIA</h6>
						<button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="POST" id="criteriosForm">
							<div class="row">
								<div class="col-md-6 mb-1">
									<label for="fechaexp" class="form-label" style="font-weight: bold;">Fecha de Expedición</label>
									<div class="form-group label-floating" id="fechaExpDiv">
										<input type="date" class="form-control" autocomplete="off" id="fechaexp" name="fechaexp" placeholder="Ingrese fecha de expedición">
									</div>
									<div class="alert alert-danger" role="alert" id="fechaExpE" style="display: none;">
										<i class="bi bi-exclamation-triangle-fill"></i> Debes ingresar la fecha de expedición
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="producto" class="form-label" style="font-weight: bold;">Especie</label>
									<div class="form-group label-floating" id="telefonoDiv">
										<select class="form-control" id="producto" name="producto">
											<option value="">Seleccione la especie</option>
											<option value="CERDO">Porcino</option>
											<option value="RES">Bovino</option>
										</select>
									</div>
									<div class="alert alert-danger" role="alert" id="productoE" style="display: none;">
										<i class="bi bi-exclamation-triangle-fill"></i> Debes seleccionar la especie a despachar
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="consecutivog" class="form-label" style="font-weight: bold;">Lote</label>
									<div class="form-group label-floating" id="codigoAutoDiv">
										<input type="text" class="form-control" autocomplete="off" id="consecutivog" name="consecutivog" placeholder="Ingrese el lote">
										<input type="hidden" id="id_guia">
									</div>
									<div class="alert alert-danger" role="alert" id="consecutivogE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debes ingresar el lote a despachar
									</div>
								</div>

								<div class="col-md-6" id="labelCanales">
									<label for="canales" class="form-label" style="font-weight: bold;">Número de Canales</label>
									<div class="form-group label-floating" id="conseGuiaDiv">
										<input class="form-control" type="number" name="canales" autocomplete="off" id="canales" placeholder="Ingrese el número de canales">
									</div>
									<div class="alert alert-danger" role="alert" id="canalesE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debe ingresar la cantidad de canales
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="responsable" class="form-label" style="font-weight: bold;">Responsable de Despacho</label>
									<div class="form-group label-floating" id="conseGuiaDiv">
										<select class="form-control" id="responsable" name="responsable" disabled>
											<option value="">Seleccione el responsable de despacho</option>
											<?php foreach ($listaResponsables as $perm) : ?>
												<option value="<?php echo $perm['cedula'] ?>">
													<?php echo $perm['nombres'] . " - " . $perm['cedula'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debe seleccionar el responsable del despacho
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="destino" class="form-label" style="font-weight: bold;">Destino</label>
									<div class="form-group label-floating" id="conseGuiaDiv">
										<select class="form-control" id="destino" name="destino">
											<option value="">Seleccione el destino de despacho</option>
											<?php foreach ($listaDestinos as $perm) : ?>
												<option value="<?php echo $perm['id'] ?>">
													<?php echo $perm['empresa'] . " - " . $perm['sede'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="alert alert-danger" role="alert" id="destinoE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debe seleccionar el destino del despacho
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="conductor" class="form-label" style="font-weight: bold;">Nombre del Conductor</label>
									<div class="form-group label-floating" id="conseGuiaDiv">
										<select class="form-control" id="conductor" name="conductor">
											<option value="">Seleccione el conductor de despacho</option>
											<?php foreach ($listaConductores as $perm) : ?>
												<option value="<?php echo $perm['cedula'] ?>">
													<?php echo $perm['nombres'] . " - " . $perm['cedula'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="alert alert-danger" role="alert" id="conductorE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debe seleccionar el conductor
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="placa" class="form-label" style="font-weight: bold;">Placa del Vehículo</label>
									<div class="form-group label-floating" id="conseGuiaDiv">
										<select class="form-control" id="placa" name="placa">
											<option value="">Seleccione la placa del vehículo</option>
											<?php foreach ($listaPlacas as $perm) : ?>
												<option value="<?php echo $perm['placa'] ?>">
													<?php echo $perm['placa'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="alert alert-danger" role="alert" id="placaE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debe seleccionar la placa del vehículo
									</div>
								</div>

								<div class="col-md-6 mb-1">
									<label for="precinto" class="form-label" style="font-weight: bold;">Precinto</label>
									<div class="form-group label-floating" id="codigoAutoDiv">
										<input type="text" class="form-control" autocomplete="off" id="precinto" name="precinto" placeholder="Ingrese el precinto">
									</div>
									<div class="alert alert-danger" role="alert" id="precintoE" style="display: none">
										<i class="bi bi-exclamation-triangle-fill"></i> Debes ingresar el precinto
									</div>
								</div>

								<div class="col-md-12">
									<label for="observaciones" class="form-label" style="font-weight: bold;">Observaciones</label>
									<div class="form-group label-floating" id="observacionesDiv">
										<input type="text" class="form-control" autocomplete="off" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
									</div>
								</div>

							</div>
						</form>
						<div class="row mt-3">
							<div class="col-md-12" style="text-align:center">
								<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
								<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
								<button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
							</div>
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
						<input type="hidden" name="destinoID" id="destinoID">

						<div class="row">

							<div class="col-md-4 mb-2">
								<label for="valorCriterio" class="form-label fw-bold">Item</label>
								<div class="form-group">
									<!-- <input class="form-control" list="datalistValorCriterio" autocomplete="off" id="valorCriterio" placeholder="Busca el Item para agregarlo">
									<datalist id="datalistValorCriterio"></datalist> -->
									<select name="valorCriterio" id="valorCriterio" class="form-control select2">
										<option value="">Seleccione el item</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="itemE" style="display: none">
									Debe seleccionar el item
								</div>
							</div>

							<div class="col-md-3 mb-2">
								<label for="tipoMarinado" class="form-label fw-bold">Tipo de Proceso</label>
								<select name="tipoMarinado" id="tipoMarinado" class="form-select">
									<option value="">Seleccione el tipo de proceso</option>
									<option value="Marinado">Marinado</option>
									<option value="Sin Marinar">Sin Marinar</option>
								</select>
								<div class="alert alert-danger" role="alert" id="tipoMarinadoE" style="display: none">
									<i class="bi bi-exclamation-triangle-fill"></i> Debe seleccionar el tipo de proceso
								</div>
							</div>

							<div class="col-md-3 mb-2">
								<label for="lotes" class="form-label fw-bold">Lote</label>
								<div class="form-group">
									<input class="form-control" autocomplete="off" id="lotes" name="lotes" placeholder="Ingrese el Lote del Item">
									<input type="hidden" id="id_criterio" name="id_criterio">
								</div>
								<div class="alert alert-danger" role="alert" id="loteItemE" style="display: none">
									Debe ingresar el lote del item
								</div>
							</div>

							<div class="col-md-2 mb-2">
								<label for="temperatura" class="form-label fw-bold">Temperatura</label>
								<div class="form-group">
									<input class="form-control" type="number" name="temperatura" autocomplete="off" id="temperatura" placeholder="Selecciona la Temperatura del Item">
								</div>
							</div>

							<div class="col-md-4 mb-2">
								<label for="unidades" class="form-label fw-bold">Unidades</label>
								<div class="form-group">
									<input class="form-control" type="number" name="unidades" autocomplete="off" id="unidades" placeholder="Selecciona las Unidades del Item">
								</div>
							</div>

							<div class="col-md-3 mb-2">
								<label for="peso" class="form-label fw-bold">Peso</label>
								<div class="form-group">
									<input type="number" class="form-control" name="peso" autocomplete="off" id="peso" placeholder="Ingrese el Peso del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar el peso del item
								</div>
							</div>

							<div class="col-md-3 mb-2">
								<label for="cajas" class="form-label fw-bold">Canastillas</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="cajas" autocomplete="off" id="cajas" placeholder="Ingrese la cantidad de Canastillas del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="canastillasE" style="display: none">
									Debe ingresar la cantidad de canastillas del item
								</div>
							</div>
						</div>
					</form>
					<div class="d-flex justify-content-center gap-2">
						<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Agregar Item" id="btnNuevoCriterio">Guardar</button>
						<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Item" id="btnEditarCriterio" style="display: none;">Editar</button>
						<button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal">Cerrar</button>
					</div>
					<div>
						<table id="jtableCriterio" class="table table-striped table-bordered table-hover datatable w-100">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th>Descripción</th>
									<th>Lote</th>
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

	<?php require_once('scripts.php'); ?> 
	<script src="js/select2.min.js"></script>
	<script>
		$(document).ready(function() {
			var dataTable = $('#jtable').DataTable({
				"responsive": true,
				"processing": true,
				"serverSide": true,
				"bDestroy": true,
				"order": [
					[0, 'desc']
				],
				"ajax": {
					url: "tablas/tablaProveedores.php",
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

			$('#jtable').css('width', '100%');
			$('#jtable_filter input, #jtable_length select').addClass('form-control');

			bloquearGuia();

			$("#valorCriterio").select2({
				theme: "bootstrap4",
				width: "100%",
				placeholder: "Seleccione el item",
				allowClear: true,
				dropdownAutoWidth: true,
				dropdownParent: $("#modalCriterios"),
				language: {
					noResults: function() {
						return "No se encontraron resultados";
					}
				}
			});
		});

		function bloquearGuia() {
			$.ajax({
				type: "POST",
				url: "controlador/controlador.php",
				data: {
					bloq_guiaDC: "1"
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
			});
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
					const datosE = {
						id_item_proveedor: id_item_proveedor,
						proveedor: $('#id').val()
					};

					console.log('datos para eliminar: ', datosE);
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: 'controlador/controlador.php',
						data: {
							datosE: datosE
						},
						success: function(res) {
							if (res.status == "success") {
								$('#jtableCriterio').DataTable().ajax.reload();
								$('#jtable').DataTable().ajax.reload();
								buscarItems($('#id').val(), $('#tipo').val());
								bloquearGuia();
							}

							Swal.fire({
								title: res.message,
								icon: res.status,
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function(jqXHR, textStatus, errorThrown) {
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

		function abrirModal(consecutivoGuia, lote, status, tipo, destinoID) {
			$('#destinoID').val(destinoID);
			$('#titulo2').text('Consecutivo Guia: ' + consecutivoGuia);
			console.log('lote: ', lote);
			if (lote != '0') {
				$('#lotes').val(lote);
			}
			if ((status == 1) || (tipo == 0)) {
				$('#btnNuevoCriterio').css('display', 'initial');
				$('#btnEditarCriterio').css('display', 'none');
			} else {
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
			}
			$('#valorCriterio').val('').trigger('change.select2');
			$('#tipoMarinado').val('');
			$('#temperatura').val('');
			$('#unidades').val('');
			$('#cajas').val('');
			$('#peso').val('');
			$(".alert").css('display', 'none');
		}

		function buscarItems(id, tipo, totalCambios) {
			$('#itemE').css('display', 'none');
			$('#loteItemE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#pesoE').css('display', 'none');
			$('#id').val(id);
			$('#tipo').val(tipo);
			const datosItems = {
				id: id,
				tipo: tipo,
			};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					proveedor: datosItems
				},
				success: function(data) {
					$('#valorCriterio').empty();
					$('#valorCriterio').append("<option value=''>Seleccione el item</option>");
					for (i = 0; i < data.length; i++) {
						$('#valorCriterio').append("<option value='" + data[i]['item'] + "'>" + data[i]['descripcion'] + "</option>");
					}
					$('#valorCriterio').val('').trigger('change.select2');
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
					"targets": [9]
				}],
				"ajax": {
					url: "tablas/tablaCriterios.php",
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

		$("#valorCriterio").change(function (e) { 
			e.preventDefault();
			if ($('#valorCriterio').val() != '') {
				const destinoID = $('#destinoID').val();
				const item = $('#valorCriterio').val();
				const datos = {
					destinoID: destinoID,
					item: item
				};
				$.ajax({
					type: "POST",
					url: "controlador/controlador.php",
					data: {
						VerificarItem: datos
					},
					dataType: "json",
					success: function (response) {
						if (response.status == "success") {
							$('#tipoMarinado').val(response.proceso);
						} else {
							Swal.fire({
								position: "top-end",
								title: 'Item no encontrado',
								text: 'Error al traer el proceso',
								icon: 'error',
								timer: 1000,
								showConfirmButton: false,
								toast: true
							});
							$('#tipoMarinado').val('');
						}
					},
					error: function (response) {
						console.log(response);
					}
				});
			}
		});

		$('#btnNuevoCriterio').click(function() {
			validacionesCri = validacionesC();
			if (validacionesCri == "") {
				// Bloquear el botón
				$('#btnNuevoCriterio').prop('disabled', true);
				
				const datos = {
					item: $('#valorCriterio').val(),
					proceso: $('#tipoMarinado').val(),
					lote: $('#lotes').val(),
					temperatura: $('#temperatura').val(),
					unidades: $('#unidades').val(),
					cajas: $('#cajas').val(),
					peso: $('#peso').val(),
					proveedor: $('#id').val()
				};
				console.log('datos a enviar: ', datos);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datos: datos
					},
					success: function(data) {
						$('#valorCriterio').val('').trigger('change.select2');
						$('#tipoMarinado').val('');
						$('#temperatura').val('');
						$('#unidades').val('');
						$('#cajas').val('');
						$('#peso').val('');
						$('#jtableCriterio').DataTable().ajax.reload();
						buscarItems($('#id').val(), $('#tipo').val());
						Swal.fire({
							title: 'Item registrado satisfactoriamente',
							text: '',
							icon: 'success',
							timer: 1000,
							showConfirmButton: false
						});
						bloquearGuia();
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

		$('#crearProveedor').click(function() {
			var today = new Date();
			var day = String(today.getDate()).padStart(2, '0');
			var month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
			var year = today.getFullYear();
			var formattedDate = year + '-' + month + '-' + day;
			let guardarTiempoDespachoRes = 1;
			$.ajax({
				type: 'POST',
				url: 'controlador/controlador.php',
				data: {
					guardarTiempoDespachoRes
				},
				success: function(data) {
					console.log(data);
				}
			});
			$('#destinoID').val();
			$('#horaInicialGuia').text('');
			$('#titulo').text('AGREGAR GUIA').removeClass("me-5");
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
			$('#fechaexp').val(formattedDate);
			$('#consecutivog').val('');
			$('#fechaExpE').css('display', 'none');
			$('#productoE').css('display', 'none');
			$('#consecutivogE').css('display', 'none');
			$('#canalesE').css('display', 'none');
			$('#responsableE').css('display', 'none');
			$('#destinoE').css('display', 'none');
			$('#conductorE').css('display', 'none');
			$('#placaE').css('display', 'none');
			$('#precintoE').css('display', 'none');
			$('#tipoMarinadoE').css('display', 'none');
			<?php if ($_SESSION['usuario'] != 'ADMINISTRADOR') { ?>
				$('#responsable').val("<?= $_SESSION['usuario']; ?>");
			<?php } else { ?>
				$('#responsable').val('<?= $_SESSION['usuario']; ?>');
			<?php } ?> 
			$('#canales').val('');
			$('#destino').val('');
			$('#conductor').val('');
			$('#placa').val('');
			$('#producto').val('');
			$('#precinto').val('');
			$('#observaciones').val('');
		});

		$('#btnNuevoProveedor').click(function() {
			let validacionesF = validaciones();

			if (validacionesF == "") {
				// Bloquear el botón
				$('#btnNuevoProveedor').prop('disabled', true);
				
				const datosGuia = {
					fechaexp: $('#fechaexp').val(),
					consecutivog: $('#consecutivog').val(),
					responsable: $('#responsable').val(),
					destino: $('#destino').val(),
					conductor: $('#conductor').val(),
					placa: $('#placa').val(),
					producto: $('#producto').val(),
					canales: $('#canales').val(),
					precinto: $('#precinto').val(),
					observaciones: $('#observaciones').val(),
				};

				console.log('datos a guardar:', datosGuia);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosGuia: datosGuia
					},
					success: function(response) {
						if (response.status === "success") {
							bloquearGuia();
							$('#jtable').DataTable().ajax.reload();
							$('#fechaexp').val('');
							$('#consecutivog').val('');
							$('#responsable').val('');
							$('#destino').val('');
							$('#conductor').val('');
							$('#placa').val('');
							$('#producto').val('');
							$('#precinto').val('');
							$('#observaciones').val('');
							$("#modalNuevoProveedor").modal('hide');
							Swal.fire({
								title: 'Nuevo despacho registrado satisfactoriamente',
								text: '',
								icon: 'success',
								timer: 1000,
								showConfirmButton: false
							});
							// Desbloquear el botón después del éxito
							$('#btnNuevoProveedor').prop('disabled', false);
						} else {
							Swal.fire({
								title: 'Error',
								text: response.message,
								icon: 'error',
								timer: 1000,
								showConfirmButton: false
							});
							// Desbloquear el botón en caso de error en la respuesta
							$('#btnNuevoProveedor').prop('disabled', false);
						}
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

		async function buscarGuia(idGuia, tipo) {
			$('#id_guia').val(idGuia);
			$('#titulo').text('EDITAR GUIA #' + idGuia).addClass("me-5");
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			if (tipo == 'POLLO') {
				$('#consecutivog').prop('disabled', true).val('0');
			} else {
				$('#consecutivog').prop('disabled', false);
			}
			console.log('consulta: ', idGuia);
			

			$.ajax({
				type: "POST",
				url: "controlador/controlador.php",
				data: {
					TraerConductores: {
						idGuia: idGuia,
						modulo: "despacho"
					}
				},
				dataType: "json",
				success: function (response) {
					if (response.status == "success") {
						var conductores = response.conductores;
						$("#conductor").empty();
						$("#conductor").append('<option value="">Seleccione un conductor</option>');
						conductores.forEach(function (conductor) {
							$("#conductor").append('<option value="' + conductor.cedula + '">' + conductor.nombres + ' - ' + conductor.cedula + '</option>');
						});
					} else {
						Swal.fire({
							title: 'Error',
							text: "Error al traer los conductores 1",
							icon: 'error',
							timer: 1000,
							showConfirmButton: false
						});
					}
				}
			});

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idGuia: idGuia
				},
				success: function(data) {
					console.log(data);
					$('#horaInicialGuia').text('HI: ' + data[0].hora_inicial);
					$('#fechaexp').val(data[0].fechaexp);
					$('#consecutivog').val(data[0].consecutivog);
					$('#responsable').val(data[0].responsable);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#destino').val(data[0].destino);
					$('#canales').val(data[0].canales);
					$('#producto').val(data[0].tipo);
					$('#precinto').val(data[0].precinto);
					$('#observacion').val(data[0].observacion);
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
					idcriterio: idcriterio
				},
				success: function(data) {
					console.log('valores recibidos: ', data);
					$('#valorCriterio').val(data[0].item).trigger('change.select2');
					$('#tipoMarinado').val(data[0].proceso);
					$('#lote').val(data[0].lote);
					$('#temperatura').val(data[0].temperatura);
					$('#unidades').val(data[0].unidades);
					$('#cajas').val(data[0].cajas);
					$('#peso').val(data[0].peso);
				}
			});
		}

		function bloquearEdicion(idBloquear) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idBloquear: idBloquear
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
					idDesbloquear: idDesbloquear
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
				
				infoEdit = {
					"id_guia": $('#id_guia').val(),
					"fechaexp": $('#fechaexp').val(),
					"consecutivog": $('#consecutivog').val(),
					"producto": $('#producto').val(),
					"canales": $('#canales').val(),
					"responsable": $('#responsable').val(),
					"destino": $('#destino').val(),
					"conductor": $('#conductor').val(),
					"placa": $('#placa').val(),
					"precinto": $('#precinto').val(),
					"observaciones": $('#observaciones').val(),
				};
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoEdit
					},
					success: function(data) {
						$("#modalNuevoProveedor").modal('hide');
						$('#jtable').DataTable().ajax.reload();
						Swal.fire({
							title: 'Despacho modificado satisfactoriamente',
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
							text: 'Hubo un problema al editar el despacho. Inténtelo de nuevo.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
					}
				});
			}
		});

		function validaciones() {
			$('#fechaExpE').css('display', 'none');
			$('#productoE').css('display', 'none');
			$('#consecutivogE').css('display', 'none');
			$('#canalesE').css('display', 'none');
			$('#responsableE').css('display', 'none');
			$('#destinoE').css('display', 'none');
			$('#conductorE').css('display', 'none');
			$('#placaE').css('display', 'none');
			$('#precintoE').css('display', 'none');
			$('#tipoMarinadoE').css('display', 'none');

			if ($("#fechaexp").val() == null || $("#fechaexp").val() == "") {
				$('#fechaExpE').css('display', 'block');
				$('#fechaexp').focus();
				return 'R';
			}
			if ($("#producto").val() == null || $("#producto").val() == "") {
				$('#productoE').css('display', 'block');
				$('#producto').focus();
				return 'R';
			}

			if ($("#consecutivog").val() == null || $("#consecutivog").val() == "") {
				$('#consecutivogE').css('display', 'block');
				$('#consecutivog').focus();
				return 'R';
			}
			if ($("#canales").val() == null || $("#canales").val() == "") {
				$('#canalesE').css('display', 'block');
				$('#canales').focus();
				return 'R';
			}
			if ($("#responsable").val() == null || $("#responsable").val() == "") {
				$('#responsableE').css('display', 'block');
				$('#responsable').focus();
				return 'R';
			}
			if ($("#destino").val() == null || $("#destino").val() == "") {
				$('#destinoE').css('display', 'block');
				$('#destino').focus();
				return 'R';
			}
			if ($("#conductor").val() == null || $("#conductor").val() == "") {
				$('#conductorE').css('display', 'block');
				$('#conductor').focus();
				return 'R';
			}
			if ($("#placa").val() == null || $("#placa").val() == "") {
				$('#placaE').css('display', 'block');
				$('#placa').focus();
				return 'R';
			}
			if ($("#precinto").val() == null || $("#precinto").val() == "") {
				$('#precintoE').css('display', 'block');
				$('#precinto').focus();
				return 'R';
			}
			return "";
		}

		$('#btnEditarCriterio').click(function() {
			validacionesCri = validacionesC();
			if (validacionesCri == "") {
				// Bloquear el botón
				$('#btnEditarCriterio').prop('disabled', true);
				
				infoCriEdit = {
					"id_criterio": $('#id_criterio').val(),
					"item": $('#valorCriterio').val(),
					"proceso": $('#tipoMarinado').val(),
					"lote": $('#lotes').val(),
					"temperatura": $('#temperatura').val(),
					"unidades": $('#unidades').val(),
					"cajas": $('#cajas').val(),
					"peso": $('#peso').val(),
				};
				console.log('datos enviados:', infoCriEdit);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoCriEdit
					},
					success: function(res) {
						if (res.status == "success") {
							$('#btnNuevoCriterio').css('display', 'initial');
							$('#btnEditarCriterio').css('display', 'none');
							$('#valorCriterio').val('').trigger('change.select2');
							$('#tipoMarinado').val('');
							$('#lote').val('');
							$('#temperatura').val('');
							$('#unidades').val('');
							$('#cajas').val('');
							$('#peso').val('');
							$('#jtableCriterio').DataTable().ajax.reload();
							Swal.fire({
								title: 'Item modificado satisfactoriamente',
								text: '',
								icon: 'success',
								timer: 1000,
								showConfirmButton: false
							});
							// Desbloquear el botón después del éxito
							$('#btnEditarCriterio').prop('disabled', false);
						} else {
							// Desbloquear el botón en caso de error en la respuesta
							$('#btnEditarCriterio').prop('disabled', false);
							Swal.fire({
								icon: "error",
								title: res.message,
								timer: 1500,
								showConfirmButton: false
							});
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						// Desbloquear el botón en caso de error
						$('#btnEditarCriterio').prop('disabled', false);
						Swal.fire({
							icon: "error",
							title: "Error al editar el item",
							timer: 1500,
							showConfirmButton: false
						});
					}
				});
			}
		});

		function validacionesC() {
			$('#itemE').css('display', 'none');
			$('#loteItemE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#pesoE').css('display', 'none');

			if ($("#valorCriterio").val() == null || $("#valorCriterio").val() == "") {
				$('#itemE').css('display', 'block');
				return 'R';
			}

			if ($("#tipoMarinado").val() == null || $("#tipoMarinado").val() == "") {
				$('#tipoMarinadoE').css('display', 'block');
				return 'R';
			}

			if ($("#lotes").val() == null || $("#lotes").val() == "") {
				$('#loteItemE').css('display', 'block');
				return 'R';
			}
			if ($("#peso").val() == null || $("#peso").val() == "") {
				$('#pesoE').css('display', 'block');
				return 'R';
			}
			if ($("#cajas").val() == null || $("#cajas").val() == "") {
				$('#canastillasE').css('display', 'block');
				return 'R';
			}
			return "";
		}

		/* $('#producto').change(function() {
			var selectedValue = $(this).val();
			if (selectedValue === 'POLLO') {
				$('#consecutivog').prop('disabled', true).val('0'); 
				$('#labelCanales').contents().filter(function() {
					return this.nodeType === 3; 
				}).first().replaceWith('Nro de Pollos'); 
				$('#canales').attr('placeholder', 'Ingrese el Nro de Pollos'); // Cambia el placeholder del input
				$('#canalesE').text('Debe ingresar la cantidad de Pollos'); // Cambia el texto del mensaje de error
			} else {
				$('#consecutivog').prop('disabled', false).val(''); // Habilita el input y limpia el valor
				$('#labelCanales').contents().filter(function() {
					return this.nodeType === 3;
				}).first().replaceWith('Nro de Canales'); // Cambia el texto de vuelta a "Nro de Canales"
				$('#canales').attr('placeholder', 'Ingrese el Nro de Canales'); // Cambia el placeholder del input
				$('#canalesE').text('Debe ingresar la cantidad de Canales'); // Cambia el texto del mensaje de error
			} 
		});*/
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