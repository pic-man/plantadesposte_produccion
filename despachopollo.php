<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
error_reporting(0);
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$listaResponsables = listaResponsables();
$listaConductores = listaConductores();
$listaPlacas = listaPlacas();
$listaOrigen = listaOrigen();
$listaDestinos = listaDestinos();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Planta de Desposte | Despacho</title>
	<?php include_once('encabezado.php');?> 
	<link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
</head>
<body>
	<?php include_once('menu.php');?>
	<?php include_once('menuizquierda.php');?> 

	<main id="main" class="main">

		<div class="pagetitle">
		<h1>Guia de transporte y destino de pollo</h1>
		</div>

		<section class="section">
		<div class="row">
			<div class="col-lg-12">

			<div class="card">
				<div class="card-body">
				<h5 class="card-title">
					<center>
					<?php if ($_SESSION["usuario"] != "1106781852") : ?>
						<button href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
						data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR GUIA</button>
					<?php endif; ?>
					<h5 class="mt-1" style="color: red;display: none" id="alertItem">La ultima guia no tiene items</h5>
					</center>
				<table id="jtable" class="table table-striped table-bordered table-hover datatable">
				<thead>
						<tr>
							<th>Consecutivo<br>Tipo</th>
							<th>Fecha<br>Pollos</th>
							<th>Destino<br>&nbsp;</th>
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
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header d-flex justify-content-between align-items-center">
					<h6 id="horaInicialGuia" class="float-start m-0"></h6>
					<h6 class="modal-title fw-bold" id="titulo">AGREGAR GUIA</h6>
					<button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">
							<input type="hidden" id="id_guia">

							<div class="col-md-6 mb-2">
								<label for="fechaexp" class="form-label fw-bold">Fecha de Expedicion</label>
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fechaexp" name="fechaexp" placeholder="Ingrese fecha Expedicion" >
								</div>
								<div class="alert alert-danger" role="alert" id="fechaExpE" style="display: none">
									Debes ingresar fecha de expedicion
								</div>
							</div>

							<div class="col-md-6 mb-2" id="labelCanales">
								<label for="canales" class="form-label fw-bold">Nro de Pollos</label>
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="number" name="canales" autocomplete="off" id="canales" placeholder="Ingrese el Nro de Pollos">
								</div>
								<div class="alert alert-danger" role="alert" id="canalesE" style="display: none">
									Debe ingresar la cantidad de Canales
								</div>
							</div>

							<div class="col-md-6 mb-2" style="display: none;">
								<label for="responsable" class="form-label fw-bold">Responsable Despacho</label>
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="responsable" name="responsable" disabled>
										<option value="">Seleccione Responsable de Despacho</option>
										<?php foreach ($listaResponsables as $perm) : ?>
											<option value="<?php echo $perm['cedula'] ?>">
												<?php echo $perm['nombres'] . " - " . $perm['cedula'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
									Debe seleccinar el eesponsable del despacho
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label for="destino" class="form-label fw-bold">Destino</label>
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

							<div class="col-md-6 mb-2">
								<label for="guias" class="form-label fw-bold">Guias</label>
								<div class="d-flex align-items-center gap-2">
									<select name="tipoGuia" id="tipoGuia" class="form-select">
										<option value="">Seleccione el Tipo de Guia</option>
										<option value="DESPRESE">Desprese</option>
										<option value="DESPRESADO">Despresado</option>
										<option value="ASADERO">Asadero</option>
									</select>
									<select name="guias" id="guias" class="form-select select2">
										<option value="">Seleccione una Guia</option>
									</select>
									<input type="text" name="guiaSecundaria" id="guiaSecundaria" class="form-control" style="display: none;" placeholder="Ingrese la Guia Secundaria">
									<i class="bi bi-tag fs-3 text-warning" title="Guia secundaria" id="guiaSecundariaIcon" style="cursor: pointer;"></i>
								</div>
								<div class="alert alert-danger" role="alert" id="guiasE" style="display: none">
									Debe seleccionar la guia
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label for="conductor" class="form-label fw-bold">Nombre del Conductor</label>
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

							<div class="col-md-6 mb-2">
								<label for="placa" class="form-label fw-bold">Placa Vehiculo</label>
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

							<div class="col-md-6 mb-2">
								<label for="precinto" class="form-label fw-bold">Precinto</label>
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="precinto" name="precinto" placeholder="Ingrese Precinto">
								</div>
								<div class="alert alert-danger" role="alert" id="precintoE" style="display: none">
									Debes ingresar el precinto
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label for="observaciones" class="form-label fw-bold">Observaciones</label>
								<div class="form-group label-floating" id="observacionesDiv">
									<input type="text" class="form-control" autocomplete="off" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
								</div>
							</div>
						</div>
					</form>
					<div class="row mt-2">
						<div class="col-md-12 d-flex justify-content-center gap-2">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor" style="display:none">Editar</button>
							<button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalCriterios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" 	aria-hidden="true" style="background-color: #00000042 !important;">
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
							<div class="col-md-4 mb-2">
								<label for="item" class="form-label fw-bold">Item</label>
								<div class="form-group">
									<select name="item" id="item" class="form-control">
										<option value="">Seleccione el Item</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="itemE" style="display: none">
									Debe seleccionar el item
								</div>
							</div>
							<div class="col-md-4 mb-2">
								<label for="lotes" class="form-label fw-bold">Lote</label>
								<div class="form-group">
									<input class="form-control" autocomplete="off" id="lotes" name="lotes" placeholder="Ingrese el Lote del Item">
									<input type="hidden" id="id_criterio" name="id_criterio">
								</div>
								<div class="alert alert-danger" role="alert" id="loteItemE" style="display: none">
									Debe ingresar el lote del item
								</div>
							</div>
							<div class="col-md-4 mb-2">
								<label for="temperatura" class="form-label fw-bold">Temperatura</label>
								<div class="form-group">
									<input class="form-control" type="number" name="temperatura" autocomplete="off" id="temperatura" placeholder="Selecciona la Temperatura del Item">
								</div>
							</div>
							<div class="col-md-4 mb-2">
								<label for="unidades" class="form-label fw-bold">Unidades</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="unidades" autocomplete="off" id="unidades" placeholder="Selecciona las Unidades del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="unidadesE" style="display: none">
									Debe ingresar las unidades del item
								</div>
							</div>
							<div class="col-md-4 mb-2">
								<label for="peso" class="form-label fw-bold">Peso</label>
								<div class="form-group">
									<input type="number" class="form-control" name="peso" autocomplete="off" id="peso" placeholder="Ingrese el Peso del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar el peso del item
								</div>
							</div>
							<div class="col-md-2 mb-2">
								<label for="cajas" class="form-label fw-bold">Canastillas</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="cajas" autocomplete="off" id="cajas" placeholder="Ingrese la cantidad de Canastillas del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="canastillasE" style="display: none">
									Debe ingresar la cantidad de canastillas del item
								</div>
							</div>
							<div class="col-md-2 mb-2">
								<label for="cajasbase" class="form-label fw-bold">Canastillas Base</label>
								<div class="form-group">
									<input class="form-control" type="number" min="1" name="cajasbase" autocomplete="off" id="cajasbase" placeholder="Ingrese la cantidad de Canastillas Base del Item">
								</div>
								<div class="alert alert-danger" role="alert" id="canastillasbaseE" style="display: none">
									Debe ingresar la cantidad de canastillas base del item
								</div>
							</div>
						</div>
					</form>
					<div class="text-center mb-3">
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
				"serverSide": false,
				"bDestroy": true,
				/* "order": [
					[0, 'desc']
				], */
				"ordering": false,
				"columnDefs": [
					{
						"targets": 4,
						visible: <?php echo $_SESSION["usuario"] == "1106781852" ? "false" : "true"; ?>
					}
				],
				"ajax": {
					url: "tablas/tablaProveedoresP.php",
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
			
			$('#jtable').on('draw.dt', function() {
				setTimeout(initTableTooltips, 100);
			});

			/* $("#guias").select2({
				theme: "bootstrap4",
				width: "100%",
				placeholder: "Seleccione la Guia",
				allowClear: true,
				dropdownParent: $("#modalNuevoProveedor"),
				language: {
					noResults: function() {
						return "No se encontraron resultados";
					}
				},
			}); */
    	});

		function bloquearGuia() {
			$.ajax({
				type: "POST",
				url: "controlador/controlador.php",
				data: {
					bloq_guiaDP: "1"
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

		function initTableTooltips() {
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('#jtable [data-bs-toggle="tooltip"]'));
			tooltipTriggerList.forEach(function (tooltipTriggerEl) {
				
				var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
				if (existingTooltip) {
					existingTooltip.dispose();
				}
				
				new bootstrap.Tooltip(tooltipTriggerEl, {
					placement: 'auto',
					container: 'body',
					boundary: 'window'
				});
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
					const datosEP = {
						id_item_proveedor: id_item_proveedor,
						proveedor: $('#id').val()
					};

					console.log('datos para eliminar: ',datosEP);
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: 'controlador/controlador.php',
						data: {
							datosEP: datosEP
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

		function abrirModal(consecutivoGuia, lote, status, tipo) {
			$('#titulo2').text('Consecutivo Guia: ' + consecutivoGuia);
			console.log('lote: ', lote);
			if(lote != '0'){
				$('#lotes').val(lote);
			}
			if((status==1) || (tipo == 0)){
				$('#btnNuevoCriterio').css('display', 'initial');
				$('#btnEditarCriterio').css('display', 'none');
			}else{
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
			}
			$('#item').val('');
			$('#temperatura').val('');
			$('#unidades').val('');
			$('#cajas').val('');
			$('#peso').val('');
		}

		function buscarItems(id, tipo, tipo_pollo, totalCambios) {
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
					proveedorP: datosItems
				},
				success: function(data) {
					$('#item').empty();
					$('#item').append("<option value=''>Seleccione el Item</option>");
					for (i = 0; i < data.length; i++) {
						$('#item').append("<option value='" + data[i]['item'] + "'>" + data[i]['descripcion'] + "</option>");
					}
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
					url: "tablas/tablaCriteriosP.php",
					type: "post",
					data: {
						proveedor: id,
						tipo_pollo: tipo_pollo
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

		$('#btnNuevoCriterio').click(function() {
			console.log('item: ',$('#item').val());
			validacionesCri = validacionesC();
			if (validacionesCri == ""){
				$('#btnNuevoCriterio').prop('disabled', true);
				const datos = {
					item: $('#item').val(),
					lote: $('#lotes').val(),
					temperatura: $('#temperatura').val(),
					unidades: $('#unidades').val(),
					cajas: $('#cajas').val(),
					peso: $('#peso').val(),
					cajasbase: $('#cajasbase').val(),
					proveedor: $('#id').val()
				};
				console.log('datos a enviar: ', datos);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						datosP: datos
					},
					success: function(data) {
						$('#item').val('');
						$('#temperatura').val('');
						$('#unidades').val('');
						$('#cajas').val('');
						$('#cajasbase').val('');
						$('#peso').val('');
						$('#jtableCriterio').DataTable().ajax.reload();
						$('#jtable').DataTable().ajax.reload();
						buscarItems($('#id').val(), $('#tipo').val());
						Swal.fire({
        					title: 'Item registrado satisfactoriamente',
        					text: '',
        					icon: 'success',
        					timer: 1000, 
    						showConfirmButton: false
      					});

						bloquearGuia();
						$('#btnNuevoCriterio').prop('disabled', false);
					},
					error: function() {
						$('#btnNuevoCriterio').prop('disabled', false);
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
        
			$('#horaInicialGuia').text('');
			$('#titulo').text('AGREGAR GUIA').removeClass("me-5");
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
        	$('#fechaexp').val(formattedDate);
			$('#consecutivog').val('');
			$('#responsable').val("<?= $_SESSION['usuario'];?>");
			$('#canales').val('');
			$('#destino').val('');
			$('#conductor').val('');
			$('#placa').val('');
			$('#producto').val('');
			$('#precinto').val('');
			$('#observaciones').val('');
			$('#guias').val('');
			$('#guiaSecundaria').val('');
			$('#tipoGuia').val('');
			$("#tipoGuia").removeClass("is-invalid");
			$(".alert").hide();
			$("#guiaSecundaria").hide();
			$("#guiaSecundariaIcon").removeClass("bi-tag-fill");
			$("#guiaSecundariaIcon").addClass("bi-tag");
			$("#guiaSecundaria").val('');
			$('#fechaExpE').css('display', 'none');
			$('#productoE').css('display', 'none');
			$('#consecutivogE').css('display', 'none');
			$('#canalesE').css('display', 'none');
			$('#responsableE').css('display', 'none');
			$('#destinoE').css('display', 'none');
			$('#conductorE').css('display', 'none');
			$('#placaE').css('display', 'none');
			$('#precintoE').css('display', 'none');
		});

		$("#tipoGuia, #destino").change(function (e) { 
			e.preventDefault();
			$("#destino").removeClass("is-invalid");
			$("#tipoGuia").removeClass("is-invalid");

			if ($("#destino").val() == "") {
				$("#destinoE").css("display", "block");
				$("#destino").addClass("is-invalid");
				$("#destino").focus();
				return;
			}

			if ($("#tipoGuia").val() == "") {
				/* $("#tipoGuia").addClass("is-invalid"); */
				$("#guias").empty();
				$("#guias").append("<option value=''>Seleccione una Guia</option>");
				return;
			}

			if ($("#tipoGuia").val() == "ASADERO") {
				$("#guias").empty();
				$("#guias").append("<option value=''>Seleccione una Guia</option>");
				return;
			}		
				
			$.ajax({
				type: "POST",
				url: "controlador/controlador.php",
				data: {
					TraerGuias: $("#destino").val(),
					tipoGuia: $("#tipoGuia").val()
				},
				dataType: "json",
				success: function (response) {
					if (response.status === "success") {
						$("#guias").empty();
						$("#guias").append("<option value=''>Seleccione una Guia</option>");
						var guias = response.guias;
						Object.keys(guias).forEach(guiaKey => {
							$("#guias").append("<option value='" + guias[guiaKey]["id"] + "'>" + guias[guiaKey]["id"] + "</option>");
						});
						$("#guias").select2({
							theme: "bootstrap4",
							width: "100%",
							placeholder: "Seleccione la Guia",
							allowClear: true,
							dropdownAutoWidth: true,
							dropdownParent: $("#modalNuevoProveedor"),
							language: {
								noResults: function() {
									return "No se encontraron resultados";
								}
							}
						});
					} else {
						Swal.fire({
							icon: "error",
							title: "¡Error!",
							text: "Error al obtener las guias",
							timer: 1300,
							showConfirmButton: false
						});
					}
				},
				error: function (xhr, status, error) {
					Swal.fire({
						icon: "error",
						title: "¡Error!",
						text: "Error al obtener las guias",
						timer: 1300,
						showConfirmButton: false
					});
				}
			});
		});

		$('#btnNuevoProveedor').click(function() {
			let validacionesF = validaciones();

			if (validacionesF == "") {
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
					tipoGuia: $('#tipoGuia').val(),
					guiaDesprese: $('#guias').val(),
					guiaSecundaria: $('#guiaSecundaria').val()
				};

				console.log('datos a guardar:', datosGuia);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: { datosGuiaP: datosGuia },
					success: function(response) {
						if (response.status === "success") {
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
							bloquearGuia();
						} else {
							Swal.fire({
								title: 'Error',
								text: response.message,
								icon: 'error',
								timer: 1000, 
								showConfirmButton: false
							});
						}
						$('#btnNuevoProveedor').prop('disabled', false);
					},
					error: function(xhr, status, error) {
						Swal.fire({
							title: 'Error',
							text: 'Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.',
							icon: 'error',
							confirmButtonText: 'OK'
						});
						console.error('Error:', error);
						$('#btnNuevoProveedor').prop('disabled', false);
					}
				});
			}
		});

		function buscarGuia(idGuia,tipo) {
			$('#id_guia').val(idGuia);
			$(".alert").hide();
			$("#guiaSecundaria").hide();
			$("#guiaSecundariaIcon").removeClass("bi-tag-fill");
			$("#guiaSecundariaIcon").addClass("bi-tag");
			$("#guiaSecundaria").val('');
			console.log('id guia: ',idGuia);
			$('#titulo').text('EDITAR GUIA #' + idGuia).addClass("me-5");
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			if(tipo == 'POLLO'){
				$('#consecutivog').prop('disabled', true).val('0');
			}else{
				$('#consecutivog').prop('disabled', false);
			}
			console.log('consulta: ', idGuia);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idGuiaP: idGuia
				},
				success: async function(data) {
					console.log('data: ',data);

					// Llenar formulario con datos de la guía PRIMERO
					$('#fechaexp').val(data[0].fechaexp);
					$('#consecutivog').val(data[0].consecutivog);
					$('#responsable').val(data[0].responsable);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#destino').val(data[0].destino);
					$('#canales').val(data[0].canales);
					$('#producto').val(data[0].tipo);
					$('#precinto').val(data[0].precinto);
					$('#observaciones').val(data[0].observaciones);
					$('#tipoGuia').val(data[0].tipoGuia);

					$('#horaInicialGuia').text('HI: ' + data[0].hora_inicial);

					// Cargar lotes de producción de forma asíncrona DESPUÉS
					await new Promise((resolve, reject) => {
						$.ajax({
							type: "POST",
							url: "controlador/controlador.php",
							data: {
								TraerGuias: data[0].destino,
								tipoGuia: data[0].tipoGuia
							},
							dataType: "json",
							success: function (response) {
								if (response.status === "success") {
									$("#guias").empty();
									$("#guias").append("<option value=''>Seleccione una Guia</option>");
									var guias = response.guias;
									console.log('Opciones disponibles en guias:', guias);
									Object.keys(guias).forEach(guiaKey => {
										$("#guias").append("<option value='" + guias[guiaKey]["id"] + "'>" + guias[guiaKey]["id"] + "</option>");
									});
									console.log('Opciones cargadas en select #guias:', $('#guias option').map(function() { return $(this).val(); }).get());
									
									// Inicializar Select2
									$("#guias").select2({
										theme: "bootstrap4",
										width: "100%",
										placeholder: "Seleccione la Guia",
										allowClear: true,
										dropdownAutoWidth: true,
										dropdownParent: $("#modalNuevoProveedor"),
										language: {
											noResults: function() {
												return "No se encontraron resultados";
											}
										}
									});
									
									// Establecer valores DESPUÉS de inicializar Select2
									setTimeout(() => {
										// Procesar guiaDesprese
										if (data[0].guiaDesprese == 0 || data[0].guiaDesprese == null) {
											data[0].guiaDesprese = "";
										}
										
										// Procesar guiaSecundaria
										if (data[0].guiaSecundaria == 0 || data[0].guiaSecundaria == null) {
											data[0].guiaSecundaria = "";
										}
										
										console.log('guiaDesprese value:', data[0].guiaDesprese);
										console.log('guiaSecundaria value:', data[0].guiaSecundaria);
										
										// Establecer valor de guiaDesprese en el select #guias
										if (data[0].guiaDesprese && data[0].guiaDesprese !== "") {
											console.log('Intentando establecer valor:', data[0].guiaDesprese);
											console.log('Valor actual del select antes:', $('#guias').val());
											
											// Verificar si la opción existe
											var optionExists = $('#guias option[value="' + data[0].guiaDesprese + '"]').length > 0;
											console.log('¿La opción existe?', optionExists);
											
											if (optionExists) {
												$('#guias').val(data[0].guiaDesprese).trigger('change.select2');
												console.log('Valor establecido exitosamente en #guias:', data[0].guiaDesprese);
												console.log('Valor actual del select después:', $('#guias').val());
											} else {
												console.log('ERROR: La opción con valor', data[0].guiaDesprese, 'no existe en el select');
												// Crear la opción si no existe
												$('#guias').append('<option value="' + data[0].guiaDesprese + '">' + data[0].guiaDesprese + '</option>');
												$('#guias').val(data[0].guiaDesprese).trigger('change.select2');
												console.log('Opción creada y valor establecido:', data[0].guiaDesprese);
											}
										}
										
										// Establecer valor de guiaSecundaria si está disponible
										if (data[0].guiaSecundaria && data[0].guiaSecundaria !== "") {
											$('#guiaSecundaria').val(data[0].guiaSecundaria);
											$("#guiaSecundaria").show(100);
											$("#guiaSecundariaIcon").removeClass("bi-tag");
											$("#guiaSecundariaIcon").addClass("bi-tag-fill");
											console.log('Valor establecido en #guiaSecundaria:', data[0].guiaSecundaria);
										}
									}, 100);
									
									resolve(true);
								} else {
									Swal.fire({
										icon: "error",
										title: "¡Error!",
										text: "Error al obtener las guias",
										timer: 1300,
										showConfirmButton: false
									});
									reject(new Error("Error al obtener las guias"));
								}
							},
							error: function (xhr, status, error) {
								Swal.fire({
									icon: "error",
									title: "¡Error!",
									text: "Error al obtener las guias",
									timer: 1300,
									showConfirmButton: false
								});
								reject(new Error("Error al obtener las guias"));
							}
						});
					});
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
					idcriterioP: idcriterio
				},
				success: function(data) {
					console.log('data: ',data);
					$('#item').val(data[0].item);
					$('#lotes').val(data[0].lote);
					$('#temperatura').val(data[0].temperatura);
					$('#unidades').val(data[0].unidades);
					$('#cajas').val(data[0].cajas);
					$('#cajasbase').val(data[0].base);
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
					idBloquearP: idBloquear
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
				}
			});
		}

		$('#btnEditProveedor').click(function() {
			validacionesF = validaciones();
			if (validacionesF == "") {
				$('#btnEditProveedor').prop('disabled', true);
				infoEditP = {
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
					"tipoGuia": $('#tipoGuia').val(),
					"guiaDesprese": $('#guias').val(),
					"guiaSecundaria": $('#guiaSecundaria').val()
				};
				console.log('datos enviados: ',infoEditP);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoEditP
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
						$('#btnEditProveedor').prop('disabled', false);
					},
					error: function() {
						$('#btnEditProveedor').prop('disabled', false);
					}
				});
			}
		});

		function validaciones() {
			$(".alert").hide();
			$('#fechaExpE').css('display', 'none');
			$('#canalesE').css('display', 'none');
			$('#responsableE').css('display', 'none');
			$('#destinoE').css('display', 'none');
			$('#conductorE').css('display', 'none');
			$('#placaE').css('display', 'none');
			$('#precintoE').css('display', 'none');

			if ($("#fechaexp").val() == null || $("#fechaexp").val() == "") {
				$('#fechaExpE').css('display', 'block');
				return 'R';
			}

			if ($("#canales").val() == null || $("#canales").val() == "") {
				$('#canalesE').css('display', 'block');
				return 'R';
			}

			if ($("#responsable").val() == null || $("#responsable").val() == "") {
				$('#responsableE').css('display', 'block');
				return 'R';
			}

			if ($("#destino").val() == null || $("#destino").val() == "") {
				$('#destinoE').css('display', 'block');
				return 'R';
			}

			// Campos tipoGuia, guias y guiaSecundaria ya no son obligatorios
			// Se mantienen las validaciones de consistencia pero no son requeridos
			if ($("#tipoGuia").val() == "") {
				$("#guiasE").text("Debe seleccionar un tipo de guia");
				$('#guiasE').css('display', 'block');
				return 'R';
			}

			if ($("#tipoGuia").val() != "ASADERO") {
				if ($("#guias").val() == "") {
					$("#guiasE").text("Debe seleccionar una guia");
					$('#guiasE').css('display', 'block');
					return 'R';
				}
			}

			// guiaSecundaria ya no es obligatorio, solo se valida si está visible
			if ($("#guiaSecundaria").is(":visible")) {
				$('#guiaSecundaria').removeClass("is-invalid");
			}

			if ($("#conductor").val() == null || $("#conductor").val() == "") {
				$('#conductorE').css('display', 'block');
				return 'R';
			}

			if ($("#placa").val() == null || $("#placa").val() == "") {
				$('#placaE').css('display', 'block');
				return 'R';
			}

			if ($("#precinto").val() == null || $("#precinto").val() == "") {
				$('#precintoE').css('display', 'block');
				return 'R';
			}

			return "";
		}

		$('#btnEditarCriterio').click(function() {
			validacionesCri = validacionesC();
			if (validacionesCri == "") {
				$('#btnEditarCriterio').prop('disabled', true);
				infoCriEditP = {
					"id_criterio": $('#id_criterio').val(),
					"item": $('#item').val(),
					"lote": $('#lotes').val(),
					"temperatura": $('#temperatura').val(),
					"unidades": $('#unidades').val(),
					"cajas": $('#cajas').val(),
					"cajasbase": $('#cajasbase').val(),
					"peso": $('#peso').val(),
				};
				console.log('datos enviados:',infoCriEditP);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoCriEditP
					},
					success: function(res) {
						if (res.status == "success") {
							$('#btnNuevoCriterio').css('display', 'initial');
							$('#btnEditarCriterio').css('display', 'none');
							$('#item').val('');
							$('#lotes').val('');
							$('#temperatura').val('');
							$('#unidades').val('');
							$('#cajas').val('');
							$('#cajasbase').val('');
							$('#peso').val('');
							console.log('modificado');
							$('#jtableCriterio').DataTable().ajax.reload();
							$('#jtable').DataTable().ajax.reload();
						}
						
						Swal.fire({
        					title: res.message,
        					icon: res.status,
        					timer: 1500,
        					showConfirmButton: false
      					});
						$('#btnEditarCriterio').prop('disabled', false);
					},
					error: function(res) {
						Swal.fire({
							icon: "error",
							title: res.message,
							timer: 1500,
							showConfirmButton: false
						});
						$('#btnEditarCriterio').prop('disabled', false);
					}
				});
			}
		});
		
		function desbloquearEdicion(idDesbloquearP) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idDesbloquearP: idDesbloquearP
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
				}
			});
		}

		$("#guiaSecundariaIcon").click(function (e) { 
			e.preventDefault();
			if ($("#guiaSecundaria").is(":visible")) {
				$("#guiaSecundaria").hide(100);
				$("#guiaSecundariaIcon").removeClass("bi-tag-fill");
				$("#guiaSecundariaIcon").addClass("bi-tag");
			} else {
				$("#guiaSecundaria").show(100);
				$("#guiaSecundariaIcon").removeClass("bi-tag");
				$("#guiaSecundariaIcon").addClass("bi-tag-fill");
			}
		});

		function validacionesC() {
			$('#itemE').css('display', 'none');
			$('#loteItemE').css('display', 'none');
			$('#unidadesE').css('display', 'none');
			$('#canastillasE').css('display', 'none');
			$('#canastillasbaseE').css('display', 'none');
			$('#pesoE').css('display', 'none');
			item = $('#item').val();
			if ($("#item").val() == null || $("#item").val() == "") {
					$('#itemE').css('display', 'block');
					return 'R';	
			}
			if ($("#lotes").val() == null || $("#lotes").val() == "") {
				$('#loteItemE').css('display', 'block');
				return 'R';
			}

			var itemsCrudo = ["059758", "045401", "042788", "042789", "059756", "059757", "059755", "059762", "038188", "020163", "059760", "059761", "059759"];

			if (!itemsCrudo.includes(item)) {
				if ($("#unidades").val() == null || $("#unidades").val() == "") {
					$('#unidadesE').css('display', 'block');
					return 'R';
				}
			}

			if ($("#peso").val() == null || $("#peso").val() == "") {
				if((item != '050514')&&(item != '050515')&&(item != '050516')&&(item != '050517')&&(item != '051513')){
					$('#pesoE').css('display', 'block');
					return 'R';
				}	
			}
			if ($("#cajas").val() == null || $("#cajas").val() == "") {
				$('#canastillasE').css('display', 'block');
				return 'R';
			}

			if ($("#cajasbase").val() == null || $("#cajasbase").val() == "") {
				$('#canastillasbaseE').css('display', 'block');
				return 'R';
			}

			return "";
		}
	</script>
</body>
</html>